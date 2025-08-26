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
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%");
                });
            })
            ->when($request->role, function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->when($request->filled('enabled'), function ($query) use ($request) {
                if ($request->enabled === '0' || $request->enabled === 0 || $request->enabled === false || $request->enabled === 'false') {
                    $query->where('enabled', false);
                } elseif ($request->enabled === '1' || $request->enabled === 1 || $request->enabled === true || $request->enabled === 'true') {
                    $query->where('enabled', true);
                }
            })
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->paginate($request->per_page ?? 10)
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'avatar_url' => $user->avatar_url,
                    'role' => $user->roles->first()?->name ?? 'none',
                    'zone' => $user->zone,
                    'barangay' => $user->barangay,
                    'date_installed' => $user->date_installed,
                    'brand' => $user->brand,
                    'serial_number' => $user->serial_number,
                    'size' => $user->size,
                    'enabled' => $user->enabled,
                ];
            });

        // Define zones for the frontend
        $zones = [
            "Zone 1" => ["Poblacion Sur"],
            "Zone 2" => ["Poblacion Centro"],
            "Zone 3" => ["Poblacion Centro"],
            "Zone 4" => ["Poblacion Norte"],
            "Zone 5" => ["Candajec", "Buangan"],
            "Zone 6" => ["Bonbon"],
            "Zone 7" => ["Bonbon"],
            "Zone 8" => ["Nahawan"],
            "Zone 9" => ["Caboy", "Villaflor", "Cantuyoc"],
            "Zone 10" => ["Bacani", "Mataub", "Comaang", "Tangaran"],
            "Zone 11" => ["Cantuyoc", "Nahawan"],
            "Zone 12" => ["Lajog", "Buacao"],
        ];

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'sort', 'order', 'per_page', 'enabled', 'action']),
            'zones' => $zones,
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
                'max:13'
            ],
            'account_number' => [
                'required',
                'string',
                'regex:/^\d{3}-\d{2}-\d{3}$/',
                'unique:users'
            ],
            'role' => 'required|in:customer,staff,admin',
            'zone' => 'required|string',
            'barangay' => 'required|string',
            'date_installed' => 'required|date',
            'brand' => 'required|string|max:255',
            'serial_number' => 'required|string|regex:/^\d{9}$/|unique:users',
            'size' => 'required|string|max:50',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'account_number' => $validated['account_number'],
            'zone' => $validated['zone'],
            'barangay' => $validated['barangay'],
            'date_installed' => $validated['date_installed'],
            'brand' => $validated['brand'],
            'serial_number' => $validated['serial_number'],
            'size' => $validated['size'],
            'password' => Hash::make('temporary_password'),
            'enabled' => true,
        ]);

        $password = strtoupper(substr($validated['lastname'], 0, 3)) . '_' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $user->update(['password' => Hash::make($password)]);
        $user->assignRole($validated['role']);

        return redirect()->route('admin.users')->with([
            'success' => 'User created successfully',
            'generated_password' => $password
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'enabled' => 'required|boolean',
        ]);

        $userIds = $validated['user_ids'];
        $enabled = $validated['enabled'];

        // Perform the update and check the result
        $updated = User::whereIn('id', $userIds)->update(['enabled' => $enabled]);

        if ($updated === 0) {
            return response()->json(['message' => 'No users were updated.'], 400);
        }

        // Re-fetch the full paginated data with the current filters
        $users = User::with('roles')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%")
                        ->orWhere('phone', 'like', "%{$request->search}%")
                        ->orWhere('lastname', 'like', "%{$request->search}%")
                        ->orWhere('serial_number', 'like', "%{$request->search}%");
                });
            })
            ->when($request->role, function ($query) use ($request) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            })
            ->when($request->filled('enabled'), function ($query) use ($request) {
                if (in_array($request->enabled, ['0', 'false', 0, false])) {
                    $query->where('enabled', false);
                } elseif (in_array($request->enabled, ['1', 'true', 1, true])) {
                    $query->where('enabled', true);
                }
            })
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->paginate($request->per_page ?? 10)
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'avatar_url' => $user->avatar_url,
                    'role' => $user->roles->first()?->name ?? 'none',
                    'zone' => $user->zone,
                    'barangay' => $user->barangay,
                    'date_installed' => $user->date_installed,
                    'brand' => $user->brand,
                    'serial_number' => $user->serial_number,
                    'size' => $user->size,
                    'enabled' => $user->enabled,
                ];
            });

        // Define zones for the frontend
        $zones = [
            "Zone 1" => ["Poblacion Sur"],
            "Zone 2" => ["Poblacion Centro"],
            "Zone 3" => ["Poblacion Centro"],
            "Zone 4" => ["Poblacion Norte"],
            "Zone 5" => ["Candajec", "Buangan"],
            "Zone 6" => ["Bonbon"],
            "Zone 7" => ["Bonbon"],
            "Zone 8" => ["Nahawan"],
            "Zone 9" => ["Caboy", "Villaflor", "Cantuyoc"],
            "Zone 10" => ["Bacani", "Mataub", "Comaang", "Tangaran"],
            "Zone 11" => ["Cantuyoc", "Nahawan"],
            "Zone 12" => ["Lajog", "Buacao"],
        ];

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'sort', 'order', 'per_page', 'enabled', 'action']),
            'zones' => $zones,
            'flash' => ['success' => 'User status updated successfully'],
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with([
            'success' => 'User deleted successfully'
        ]);
    }
}
