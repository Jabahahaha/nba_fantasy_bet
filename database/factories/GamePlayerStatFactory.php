<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GamePlayerStat;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class GamePlayerStatFactory extends Factory
{
    protected $model = GamePlayerStat::class;

    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'player_id' => Player::factory(),
            'points' => $this->faker->numberBetween(0, 45),
            'rebounds' => $this->faker->numberBetween(0, 15),
            'assists' => $this->faker->numberBetween(0, 12),
            'steals' => $this->faker->numberBetween(0, 5),
            'blocks' => $this->faker->numberBetween(0, 4),
            'turnovers' => $this->faker->numberBetween(0, 5),
            'minutes_played' => $this->faker->numberBetween(15, 40),
            'fpts' => $this->faker->randomFloat(2, 10, 65),
        ];
    }
}
