<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        $teams = ['LAL', 'GSW', 'BOS', 'MIA', 'CHI', 'NYK', 'LAC', 'PHX', 'MIL', 'DAL', 'DEN', 'TOR', 'PHI', 'ATL', 'CLE'];

        return [
            'game_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => $this->faker->dateTimeBetween('now', '+30 days'),
            'visitor_team' => $this->faker->randomElement($teams),
            'home_team' => $this->faker->randomElement($teams),
            'arena' => $this->faker->city() . ' Arena',
            'status' => 'scheduled',
            'visitor_score' => null,
            'home_score' => null,
            'simulated_at' => null,
        ];
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'visitor_score' => null,
            'home_score' => null,
            'simulated_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'visitor_score' => $this->faker->numberBetween(95, 125),
            'home_score' => $this->faker->numberBetween(95, 125),
            'simulated_at' => now(),
        ]);
    }
}
