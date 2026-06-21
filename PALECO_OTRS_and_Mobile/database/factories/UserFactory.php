<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // Default password for all seeded users
            'is_active' => fake()->boolean(85), // 85% chance to be active, 15% inactive
            'shift_start' => '08:00:00',
            'shift_end' => '17:00:00',
            // department_id and role will be handled by the seeder
        ];
    }
}