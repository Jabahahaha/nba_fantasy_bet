<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\GameSimulator;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GameSimulationController extends Controller
{
    protected $gameSimulator;

    public function __construct(GameSimulator $gameSimulator)
    {
        $this->gameSimulator = $gameSimulator;
    }

    /**
     * Show games management page
     */
    public function index()
    {
        // Get all games grouped by date
        $games = Game::orderBy('game_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy('game_date');

        return view('admin.games.index', compact('games'));
    }

    /**
     * Simulate all games for a specific date
     */
    public function simulateDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = $request->date;
        $games = Game::getGamesForDate($date);

        if ($games->isEmpty()) {
            return back()->with('error', 'No games found for this date.');
        }

        $simulatedCount = 0;
        foreach ($games as $game) {
            if (!$game->isSimulated()) {
                $this->gameSimulator->simulateGame($game);
                $simulatedCount++;
            }
        }

        if ($simulatedCount === 0) {
            return back()->with('info', 'All games for this date were already simulated.');
        }

        return back()->with('success', "Successfully simulated {$simulatedCount} game(s) for " . Carbon::parse($date)->format('F j, Y'));
    }

    /**
     * Simulate a single game
     */
    public function simulateGame(Game $game)
    {
        if ($game->isSimulated()) {
            return back()->with('info', 'This game was already simulated.');
        }

        $this->gameSimulator->simulateGame($game);

        return back()->with('success', 'Game simulated successfully: ' . $game->visitor_team . ' vs ' . $game->home_team);
    }

    /**
     * Reset all games for a specific date
     */
    public function resetDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = $request->date;
        $games = Game::getGamesForDate($date);

        if ($games->isEmpty()) {
            return back()->with('error', 'No games found for this date.');
        }

        $resetCount = 0;
        foreach ($games as $game) {
            if ($game->isSimulated()) {
                $game->resetSimulation();
                $resetCount++;
            }
        }

        if ($resetCount === 0) {
            return back()->with('info', 'No simulated games found for this date.');
        }

        return back()->with('success', "Successfully reset {$resetCount} game(s) for " . Carbon::parse($date)->format('F j, Y'));
    }

    /**
     * Reset a single game
     */
    public function resetGame(Game $game)
    {
        if (!$game->isSimulated()) {
            return back()->with('info', 'This game has not been simulated yet.');
        }

        $game->resetSimulation();

        return back()->with('success', 'Game reset successfully: ' . $game->visitor_team . ' vs ' . $game->home_team);
    }
}
