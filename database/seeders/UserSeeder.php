<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Alice Rivera',
                'email' => 'alice.rivera@example.com',
                'phone' => '09171234567',
                'account_number' => 'ACCT-001',
                'address' => '123 Mango St, Clarin',
                'date_installed' => '2023-01-15',
                'meter_brand' => 'AquaFlow',
                'meter_serial_number' => 'AF123456',
                'meter_size' => 15,
                'last_reading' => 120.50,
                'last_consumption' => 15.00,
                'last_amount_due' => 450.75,
                'last_reading_date' => '2023-07-01',
                'consumption' => 12.00,
                'amount_due' => 360.00,
                'due_date' => '2023-08-10',
                'payment_status' => 'Pending',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ben Cruz',
                'email' => 'ben.cruz@example.com',
                'phone' => '09181234567',
                'account_number' => 'ACCT-002',
                'address' => '456 Coconut Ave, Clarin',
                'date_installed' => '2022-11-20',
                'meter_brand' => 'HydroMeter',
                'meter_serial_number' => 'HM654321',
                'meter_size' => 20,
                'last_reading' => 98.75,
                'last_consumption' => 10.50,
                'last_amount_due' => 315.00,
                'last_reading_date' => '2023-07-01',
                'consumption' => 9.25,
                'amount_due' => 277.50,
                'due_date' => '2023-08-10',
                'payment_status' => 'Paid',
                'email_verified_at' => now(),
                'password' => Hash::make('securepass'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carla Santos',
                'email' => 'carla.santos@example.com',
                'phone' => '09191234567',
                'account_number' => 'ACCT-003',
                'address' => '789 Banana Blvd, Clarin',
                'date_installed' => '2023-03-05',
                'meter_brand' => 'PureFlow',
                'meter_serial_number' => 'PF789012',
                'meter_size' => 25,
                'last_reading' => 150.00,
                'last_consumption' => 20.00,
                'last_amount_due' => 600.00,
                'last_reading_date' => '2023-07-01',
                'consumption' => 22.50,
                'amount_due' => 675.00,
                'due_date' => '2023-08-10',
                'payment_status' => 'Overdue',
                'email_verified_at' => now(),
                'password' => Hash::make('mypassword'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $data) {
            $user = User::create($data);
            $user->assignRole('customer');
        }
    }
}
