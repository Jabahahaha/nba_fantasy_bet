<?php

namespace Database\Factories;

use App\Models\Contest;
use App\Models\Lineup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LineupFactory extends Factory
{
    protected $model = Lineup::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'contest_id' => Contest::factory(),
            'name' => $this->faker->words(2, true) . ' Lineup',
        ];
    }
}
