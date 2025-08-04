<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'staff']);
        Role::create(['name' => 'customer']);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'account_number' => 'ADM-0001',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole('admin');

        // Create staff user
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@email.com',
            'account_number' => 'STF-0001',
            'password' => Hash::make('staff123'),
        ]);
        $staff->assignRole('staff');

        // Create customer user
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@email.com',
            'account_number' => 'CST-0001',
            'password' => Hash::make('customer123'),
        ]);
        $customer->assignRole('customer');
    }
}
