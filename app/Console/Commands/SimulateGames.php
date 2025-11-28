<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\GameSimulator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SimulateGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:simulate {--date= : The date to simulate games for (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically simulate games that have reached their start time';

    protected $gameSimulator;

    public function __construct(GameSimulator $gameSimulator)
    {
        parent::__construct();
        $this->gameSimulator = $gameSimulator;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : now()->toDateString();

        $this->info("Checking for games to simulate on {$date}...");

        $games = Game::whereDate('game_date', $date)
            ->where('status', 'scheduled')
            ->where('start_time', '<=', now())
            ->get();

        if ($games->isEmpty()) {
            $this->info('No games found that need simulation.');
            return 0;
        }

        $this->info("Found {$games->count()} game(s) ready for simulation.");

        $simulatedCount = 0;
        foreach ($games as $game) {
            try {
                $this->info("Simulating: {$game->visitor_team} @ {$game->home_team}");
                $this->gameSimulator->simulateGame($game);
                $simulatedCount++;
                $this->line("  ✓ Final Score: {$game->visitor_team} {$game->visitor_score} - {$game->home_score} {$game->home_team}");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: " . $e->getMessage());
            }
        }

        $this->info("\nSimulation complete!");
        $this->line("Successfully simulated: {$simulatedCount} game(s)");

        return 0;
    }
}
