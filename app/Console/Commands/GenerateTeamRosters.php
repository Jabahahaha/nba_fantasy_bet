<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class GenerateTeamRosters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:rosters {--save : Save rosters to JSON file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate team rosters with top 10 players by minutes for each NBA team';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Generating team rosters based on minutes per game...\n");

        $teams = Player::select('team')
            ->distinct()
            ->orderBy('team')
            ->pluck('team');

        $allRosters = [];

        foreach ($teams as $team) {
            $players = Player::where('team', $team)
                ->where('is_playing', true)
                ->orderBy('mpg', 'desc')
                ->take(10)
                ->get();

            if ($players->isEmpty()) {
                $this->warn("⚠ {$team}: No players found");
                continue;
            }

            // Display roster
            $this->info("🏀 {$team} - Top 10 Players by Minutes:");
            $this->table(
                ['#', 'Player', 'Pos', 'MPG', 'PPG', 'Salary'],
                $players->map(function($player, $index) {
                    return [
                        $index + 1,
                        $player->name,
                        $player->position,
                        number_format($player->mpg, 1),
                        number_format($player->ppg, 1),
                        '$' . number_format($player->salary),
                    ];
                })
            );

            $totalSalary = $players->sum('salary');
            $avgMinutes = $players->avg('mpg');
            $avgPoints = $players->avg('ppg');

            $this->info("  Total Salary: $" . number_format($totalSalary));
            $this->info("  Avg Minutes: " . number_format($avgMinutes, 1));
            $this->info("  Avg Points: " . number_format($avgPoints, 1) . "\n");

            $allRosters[$team] = [
                'team' => $team,
                'roster' => $players->map(function($player) {
                    return [
                        'id' => $player->id,
                        'name' => $player->name,
                        'position' => $player->position,
                        'mpg' => $player->mpg,
                        'ppg' => $player->ppg,
                        'rpg' => $player->rpg,
                        'apg' => $player->apg,
                        'salary' => $player->salary,
                    ];
                })->toArray(),
                'total_salary' => $totalSalary,
                'avg_minutes' => round($avgMinutes, 1),
                'avg_points' => round($avgPoints, 1),
            ];
        }

        // Summary
        $this->info("\n" . str_repeat('=', 60));
        $this->info("📊 SUMMARY");
        $this->info(str_repeat('=', 60));
        $this->info("Total Teams: " . $teams->count());
        $this->info("Total Rosters Generated: " . count($allRosters));

        if ($this->option('save')) {
            $filename = storage_path('app/team_rosters.json');
            file_put_contents($filename, json_encode($allRosters, JSON_PRETTY_PRINT));
            $this->info("\n✓ Rosters saved to: {$filename}");
        }

        $this->showPositionBreakdown($allRosters);

        return 0;
    }

    /**
     * Show position distribution across all rosters
     */
    private function showPositionBreakdown(array $rosters): void
    {
        $this->info("\n" . str_repeat('=', 60));
        $this->info("📋 POSITION BREAKDOWN (All Teams Combined)");
        $this->info(str_repeat('=', 60));

        $positions = [];
        foreach ($rosters as $roster) {
            foreach ($roster['roster'] as $player) {
                $pos = $player['position'];
                if (!isset($positions[$pos])) {
                    $positions[$pos] = ['count' => 0, 'total_mpg' => 0];
                }
                $positions[$pos]['count']++;
                $positions[$pos]['total_mpg'] += $player['mpg'];
            }
        }

        $positionData = [];
        foreach ($positions as $pos => $data) {
            $positionData[] = [
                $pos,
                $data['count'],
                number_format($data['total_mpg'] / $data['count'], 1),
            ];
        }

        $this->table(
            ['Position', 'Total Players', 'Avg MPG'],
            $positionData
        );
    }
}
