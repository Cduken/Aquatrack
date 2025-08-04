<?php

namespace Database\Factories;

use App\Models\Management;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ManagementFactory extends Factory
{
    protected $model = Management::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password
            'position' => $this->faker->randomElement([
                'Waterworks Officer',
                'Field Technician',
                'Billing Clerk',
                'Meter Reader',
                'Maintenance Worker',
                'Customer Service Assistant',
                'Administrative Aide',
                'IT Support',
            ]),
            'department' => $this->faker->randomElement([
                'Operations',
                'Maintenance',
                'Customer Service',
                'Billing & Collection',
                'Meter Reading',
                'Administration',
                'Technical / IT',
                'Management / Oversight',
            ]),
            'contact_number' => $this->faker->numerify('09#########'),
            'notes' => $this->faker->sentence(),
        ];
    }
}
