<?php

namespace App\Services;

class SalaryCalculator
{
    /**
     * Calculate player salary based on stats
     */
    public static function calculate(array $stats): int
    {
        $ppg = $stats['ppg'] ?? 0;
        $rpg = $stats['rpg'] ?? 0;
        $apg = $stats['apg'] ?? 0;
        $spg = $stats['spg'] ?? 0;
        $bpg = $stats['bpg'] ?? 0;
        $topg = $stats['topg'] ?? 0;
        $mpg = $stats['mpg'] ?? 0;
        $position = $stats['position'] ?? 'C';

        // Base calculation
        $base = ($ppg * 1.0 +
                 $rpg * 1.25 +
                 $apg * 1.5 +
                 $spg * 2.0 +
                 $bpg * 2.0 -
                 $topg * 0.5) * 200;

        // Position multiplier
        $positionMultipliers = [
            'PG' => 1.0,
            'SG' => 0.98,
            'SF' => 0.95,
            'PF' => 0.92,
            'C' => 0.88,
        ];
        $positionMultiplier = $positionMultipliers[$position] ?? 1.0;

        // Minutes multiplier
        if ($mpg >= 35) {
            $minutesMultiplier = 1.15;
        } elseif ($mpg >= 30) {
            $minutesMultiplier = 1.05;
        } elseif ($mpg >= 25) {
            $minutesMultiplier = 0.95;
        } elseif ($mpg >= 20) {
            $minutesMultiplier = 0.85;
        } elseif ($mpg >= 15) {
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
