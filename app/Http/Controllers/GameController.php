<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display the specified game with stats
     */
    public function show(Game $game)
    {
        // Load player stats with player details
        $game->load(['playerStats.player']);

        // Separate stats by team and sort by minutes per game (descending)
        $visitorStats = $game->playerStats->filter(function ($stat) use ($game) {
            return $stat->player->team === $game->visitor_team;
        })->sortByDesc(function ($stat) {
            return $stat->player->mpg;
        });

        $homeStats = $game->playerStats->filter(function ($stat) use ($game) {
            return $stat->player->team === $game->home_team;
        })->sortByDesc(function ($stat) {
            return $stat->player->mpg;
        });

        return view('games.show', compact('game', 'visitorStats', 'homeStats'));
    }
}
