<?php

namespace Database\Factories;

use App\Models\Contest;
use App\Models\ContestEntry;
use App\Models\Lineup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContestEntryFactory extends Factory
{
    protected $model = ContestEntry::class;

    public function definition(): array
    {
        return [
            'contest_id' => Contest::factory(),
            'user_id' => User::factory(),
            'lineup_id' => Lineup::factory(),
            'entry_fee_paid' => $this->faker->randomElement([50, 100, 250, 500, 1000]),
            'score' => $this->faker->optional()->randomFloat(2, 50, 350),
            'rank' => $this->faker->optional()->numberBetween(1, 100),
            'winnings' => $this->faker->optional()->numberBetween(0, 10000),
        ];
    }
}
