<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        $users = User::role('customer')->get();

        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created user in storage.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'nullable|email|unique:users,email',
    //         'phone' => 'nullable|string|max:20',
    //         'account_number' => 'nullable|string|max:50',
    //         'address' => 'nullable|string|max:255',
    //         'date_installed' => 'nullable|date',
    //         'meter_brand' => 'nullable|string|max:100',
    //         'meter_serial_number' => 'nullable|string|max:100',
    //         'meter_size' => 'nullable|integer',
    //         'last_reading' => 'nullable|numeric',
    //         'last_consumption' => 'nullable|numeric',
    //         'last_amount_due' => 'nullable|numeric',
    //         'last_reading_date' => 'nullable|date',
    //         'consumption' => 'nullable|numeric',
    //         'amount_due' => 'nullable|numeric',
    //         'due_date' => 'nullable|date',
    //         'payment_status' => 'required|in:Paid,Pending,Overdue',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     $validated['password'] = Hash::make($validated['password']);

    //     User::create($validated);

    //     return redirect()->route('admin.users')->with('success', 'User created successfully.');
    // }
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'account_number' => 'required|string|max:50',
        'address' => 'nullable|string|max:255',
        'date_installed' => 'nullable|date',
        'meter_brand' => 'nullable|string|max:100',
        'meter_serial_number' => 'nullable|string|max:100',
        'meter_size' => 'nullable|integer',
        'last_reading' => 'nullable|numeric',
        'last_consumption' => 'nullable|numeric',
        'last_amount_due' => 'nullable|numeric',
        'last_reading_date' => 'nullable|date',
        'consumption' => 'nullable|numeric',
        'amount_due' => 'nullable|numeric',
        'due_date' => 'nullable|date',
        'payment_status' => 'required|in:Paid,Pending,Overdue',
    ]);

    // Generate password from name and account_number
    $namePart = strtoupper(substr($validated['name'], 0, 3));
    $accountPart = strtoupper(substr($validated['account_number'] ?? '', 0, 3));
    $generatedPassword = $namePart . $accountPart;

    // Hash the password
    $validated['password'] = Hash::make($generatedPassword);

        if (empty($validated['email'])) {
        $nameSlug = strtolower(str_replace(' ', '', explode(' ', $validated['name'])[0]));
        $validated['email'] = $nameSlug . '@email.com';
    }
    if (empty($validated['email'])) {
    $nameParts = explode(' ', preg_replace('/\s+/', ' ', trim($validated['name']))); // clean extra spaces
    $first = strtolower($nameParts[1] ?? $nameParts[0]); // assume second word is first name
    $last = strtolower($nameParts[0] ?? 'user'); // assume first word is last name

    $baseEmail = $last . $first . '@email.com';
    $email = $baseEmail;
    $counter = 1;

    // Ensure uniqueness
    while (User::where('email', $email)->exists()) {
        $email = $last . $first . $counter . '@email.com';
        $counter++;
    }

    $validated['email'] = $email;
}


    // Create user
    $user = User::create($validated);

    // Optionally assign role
    $user->assignRole('customer');

    return redirect()->route('admin.users')->with('success', 'User created successfully.');
}


    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'date_installed' => 'nullable|date',
            'meter_brand' => 'nullable|string|max:100',
            'meter_serial_number' => 'nullable|string|max:100',
            'meter_size' => 'nullable|integer',
            'last_reading' => 'nullable|numeric',
            'last_consumption' => 'nullable|numeric',
            'last_amount_due' => 'nullable|numeric',
            'last_reading_date' => 'nullable|date',
            'consumption' => 'nullable|numeric',
            'amount_due' => 'nullable|numeric',
            'due_date' => 'nullable|date',
            'payment_status' => 'required|in:Paid,Pending,Overdue',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
