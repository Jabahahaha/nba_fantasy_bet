<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team',
        'position',
        'ppg',
        'rpg',
        'apg',
        'spg',
        'bpg',
        'topg',
        'mpg',
        'salary',
        'is_playing',
    ];

    protected $casts = [
        'ppg' => 'decimal:1',
        'rpg' => 'decimal:1',
        'apg' => 'decimal:1',
        'spg' => 'decimal:1',
        'bpg' => 'decimal:1',
        'topg' => 'decimal:1',
        'mpg' => 'decimal:1',
        'is_playing' => 'boolean',
    ];

    /**
     * Get all lineups this player is in
     */
    public function lineups()
    {
        return $this->belongsToMany(Lineup::class, 'lineup_players')
            ->withPivot([
                'position_slot',
                'simulated_points',
                'simulated_rebounds',
                'simulated_assists',
                'simulated_steals',
                'simulated_blocks',
                'simulated_turnovers',
                'fantasy_points_earned'
            ]);
    }

    /**
     * Calculate salary based on player stats
     */
    public function calculateSalary(): int
    {
        // Base calculation
        $base = ($this->ppg * 1.0 +
                 $this->rpg * 1.25 +
                 $this->apg * 1.5 +
                 $this->spg * 2.0 +
                 $this->bpg * 2.0 -
                 $this->topg * 0.5) * 200;

        // Position multiplier
        $positionMultipliers = [
            'PG' => 1.0,
            'SG' => 0.98,
            'SF' => 0.95,
            'PF' => 0.92,
            'C' => 0.88,
        ];
        $positionMultiplier = $positionMultipliers[$this->position] ?? 1.0;

        // Minutes multiplier
        if ($this->mpg >= 35) {
            $minutesMultiplier = 1.15;
        } elseif ($this->mpg >= 30) {
            $minutesMultiplier = 1.05;
        } elseif ($this->mpg >= 25) {
            $minutesMultiplier = 0.95;
        } elseif ($this->mpg >= 20) {
            $minutesMultiplier = 0.85;
        } elseif ($this->mpg >= 15) {
            $minutesMultiplier = 0.75;
        } else {
            $minutesMultiplier = 0.65;
        }

        // Apply multipliers
        $salary = $base * $positionMultiplier * $minutesMultiplier;

        // Ensure within bounds
        $salary = max(3000, min(12000, $salary));

        // Round to nearest 100
        return (int) (round($salary / 100) * 100);
    }
}
