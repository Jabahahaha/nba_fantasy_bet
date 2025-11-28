<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class SetTeamRosters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:rosters {--top=10 : Number of players per team to mark as active roster}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set active roster for each team based on minutes per game';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $topCount = (int) $this->option('top');

        $this->info("Setting team rosters (Top {$topCount} by minutes)...\n");

        $teams = Player::select('team')
            ->distinct()
            ->orderBy('team')
            ->pluck('team');

        $totalActive = 0;
        $totalBench = 0;

        foreach ($teams as $team) {
            $players = Player::where('team', $team)
                ->where('is_playing', true)
                ->orderBy('mpg', 'desc')
                ->get();

            if ($players->isEmpty()) {
                continue;
            }

            $activeCount = 0;
            $benchCount = 0;

            foreach ($players as $index => $player) {
                $rank = $index + 1;

                if ($rank <= $topCount) {
                    // Active roster
                    $player->roster_status = 'active';
                    $player->roster_rank = $rank;
                    $activeCount++;
                } else {
                    // Bench
                    $player->roster_status = 'bench';
                    $player->roster_rank = $rank;
                    $benchCount++;
                }

                $player->save();
            }

            $totalActive += $activeCount;
            $totalBench += $benchCount;

            $this->info("✓ {$team}: {$activeCount} active, {$benchCount} bench");
        }

        $this->info("\n" . str_repeat('=', 60));
        $this->info("📊 SUMMARY");
        $this->info(str_repeat('=', 60));
        $this->info("Total Teams: " . $teams->count());
        $this->info("Total Active Roster: {$totalActive}");
        $this->info("Total Bench: {$totalBench}");
        $this->info("Total Players: " . ($totalActive + $totalBench));

        return 0;
    }
}
