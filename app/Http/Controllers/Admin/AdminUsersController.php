<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->paginate($request->per_page ?? 10)
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'avatar_url' => $user->avatar_url,
                    'role' => $user->roles->first()?->name ?? 'none',
                ];
            });

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'sort', 'order', 'per_page'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => [
                'required',
                'string',
                'regex:/^(\+63\d{10}|09\d{9})$/',
                'max:13' // +639123456789 (13 chars)
            ],
            'account_number' => [
                'required',
                'string',
                'regex:/^\d{3}-\d{2}-\d{3}$/', // Keep formatted validation
                'unique:users'
            ],
            'role' => 'required|in:customer,staff,admin',
            'zone' => 'required|string',
            'barangay' => 'required|string',
        ]);


        $user = User::create([
            'name' => $validated['name'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'account_number' => $validated['account_number'],
            'zone' => $validated['zone'],
            'barangay' => $validated['barangay'],
            'password' => Hash::make('temporary_password'),
        ]);

        // Generate actual password
        $password = strtoupper(substr($validated['lastname'], 0, 3)) . '_' . str_pad($user->id, 4, '0', STR_PAD_LEFT);

        // Update with actual password
        $user->update([
            'password' => Hash::make($password)
        ]);

        // Assign role
        $user->assignRole($validated['role']);

        return redirect()->route('admin.users')->with([
            'success' => 'User created successfully',
            'generated_password' => $password
        ]);
    }
}
