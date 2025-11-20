<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    /**
     * Show roster manager dashboard.
     */
    public function index(Request $request)
    {
        $teamFilter = $request->input('team');
        $search = $request->input('search');

        $teams = Player::select('team')
            ->distinct()
            ->orderBy('team')
            ->pluck('team');

        $playersQuery = Player::query()
            ->when($teamFilter, fn ($query) => $query->where('team', $teamFilter))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('team', 'like', "%{$search}%");
                });
            })
            ->orderBy('team')
            ->orderBy('roster_rank')
            ->orderByDesc('mpg');

        $players = $playersQuery->paginate(25)->withQueryString();

        $summary = [
            'total_active' => Player::where('roster_status', 'active')->count(),
            'total_bench' => Player::where('roster_status', 'bench')->count(),
            'total_inactive' => Player::where('roster_status', 'inactive')->count(),
            'total_playing' => Player::where('is_playing', true)->count(),
        ];

        return view('admin.rosters.index', [
            'players' => $players,
            'teams' => $teams,
            'teamFilter' => $teamFilter,
            'search' => $search,
            'summary' => $summary,
        ]);
    }

    /**
     * Update a single player's roster metadata.
     */
    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'roster_status' => 'required|in:active,bench,inactive',
            'roster_rank' => 'nullable|integer|min:1|max:30',
            'is_playing' => 'required|boolean',
        ]);

        $player->roster_status = $validated['roster_status'];
        $player->is_playing = (bool) $validated['is_playing'];
        $player->roster_rank = $validated['roster_rank'] ?? ($player->roster_rank ?? null);
        $player->save();

        return back()->with('success', "{$player->name} roster updated.");
    }

    /**
     * Automatically set roster ranks/status for teams by MPG.
     */
    public function rebalance(Request $request)
    {
        $validated = $request->validate([
            'team' => 'nullable|string',
            'top' => 'required|integer|min=1|max=15',
        ]);

        $teams = $validated['team']
            ? collect([$validated['team']])
            : Player::select('team')->distinct()->pluck('team');

        $updatedTeams = 0;

        foreach ($teams as $team) {
            $activePlayers = Player::where('team', $team)
                ->where('is_playing', true)
                ->orderByDesc('mpg')
                ->get();

            $benchPlayers = Player::where('team', $team)
                ->where('is_playing', false)
                ->get();

            if ($activePlayers->isEmpty() && $benchPlayers->isEmpty()) {
                continue;
            }

            $rank = 1;
            foreach ($activePlayers as $player) {
                $player->roster_rank = $rank;
                $player->roster_status = $rank <= $validated['top'] ? 'active' : 'bench';
                $player->save();
                $rank++;
            }

            foreach ($benchPlayers as $player) {
                $player->roster_rank = null;
                $player->roster_status = 'inactive';
                $player->save();
            }

            $updatedTeams++;
        }

        $message = $validated['team']
            ? "Roster updated for {$validated['team']}."
            : "Roster updated for {$updatedTeams} teams.";

        return back()->with('success', $message);
    }
}

