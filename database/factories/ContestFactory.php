<?php

namespace Database\Factories;

use App\Models\Contest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContestFactory extends Factory
{
    protected $model = Contest::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true) . ' Contest',
            'contest_type' => $this->faker->randomElement(['50-50', 'GPP', 'H2H']),
            'entry_fee' => $this->faker->randomElement([50, 100, 250, 500, 1000]),
            'max_entries' => $this->faker->randomElement([2, 10, 50, 100, 1000]),
            'max_entries_per_user' => 150,
            'contest_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'lock_time' => $this->faker->dateTimeBetween('now', '+30 days'),
            'status' => $this->faker->randomElement(['open', 'locked', 'completed', 'cancelled']),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'locked',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }
}
