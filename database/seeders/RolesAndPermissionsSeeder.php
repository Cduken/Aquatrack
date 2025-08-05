<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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




        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole('admin');

        // Create staff user
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@email.com',
            'password' => Hash::make('staff123'),
        ]);
        $staff->assignRole('staff');

        // Create customer user
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@email.com',
            'password' => Hash::make('customer123'),
        ]);

        $customer2 = User::create([
            'name' => 'Customer2',
            'email' => 'customer2@email.com',
            'password' => Hash::make('customer123'),
        ]);

        $customer2->assignRole('customer');
        $customer->assignRole('customer');
    }
}
