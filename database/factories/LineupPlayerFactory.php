<?php

namespace Database\Factories;

use App\Models\Lineup;
use App\Models\LineupPlayer;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class LineupPlayerFactory extends Factory
{
    protected $model = LineupPlayer::class;

    public function definition(): array
    {
        return [
            'lineup_id' => Lineup::factory(),
            'player_id' => Player::factory(),
            'roster_position' => $this->faker->randomElement(['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F', 'UTIL']),
            'fpts' => $this->faker->optional()->randomFloat(2, 10, 60),
        ];
    }
}
