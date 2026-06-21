<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_name' => fake()->unique()->company() . ' Team',
            'shift_start' => '08:00:00',
            'shift_end' => '16:00:00',
            'is_active' => true,
        ];
    }
}
