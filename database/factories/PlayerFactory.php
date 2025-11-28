<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'team' => $this->faker->randomElement(['LAL', 'GSW', 'BOS', 'MIA', 'CHI', 'NYK', 'LAC', 'PHX', 'MIL', 'DAL']),
            'position' => $this->faker->randomElement(['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F']),
            'salary' => $this->faker->numberBetween(3000, 12000),
            'ppg' => $this->faker->randomFloat(1, 5, 30),
            'rpg' => $this->faker->randomFloat(1, 2, 12),
            'apg' => $this->faker->randomFloat(1, 1, 10),
            'spg' => $this->faker->randomFloat(1, 0.5, 2.5),
            'bpg' => $this->faker->randomFloat(1, 0.2, 2.5),
            'topg' => $this->faker->randomFloat(1, 1, 4),
            'mpg' => $this->faker->randomFloat(1, 15, 38),
            'roster_status' => $this->faker->randomElement(['active', 'bench', 'inactive']),
            'roster_rank' => $this->faker->optional()->numberBetween(1, 100),
            'is_playing' => $this->faker->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'roster_status' => 'active',
            'is_playing' => true,
        ]);
    }

    public function bench(): static
    {
        return $this->state(fn (array $attributes) => [
            'roster_status' => 'bench',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'roster_status' => 'inactive',
            'is_playing' => false,
        ]);
    }
}
