<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $staffRole = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web'
        ]);

        $customerRole = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web'
        ]);

        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now()
            ]
        );
        $admin->syncRoles([$adminRole]);
        $admin->role = 'admin'; // Update the custom role column
        $admin->save();

        // Create or update staff user
        $staff = User::updateOrCreate(
            ['email' => 'staff@email.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('staff123'),
                'email_verified_at' => now()
            ]
        );
        $staff->syncRoles([$staffRole]);
        $staff->role = 'staff'; // Update the custom role column
        $staff->save();

        // Create or update customer user
        $customer = User::updateOrCreate(
            ['email' => 'customer@email.com'],
            [
                'name' => 'Concessioner',
                'password' => Hash::make('customer123'),
                'email_verified_at' => now()
            ]
        );
        $customer->syncRoles([$customerRole]);
        $customer->role = 'customer';
        $customer->save();
    }
}
