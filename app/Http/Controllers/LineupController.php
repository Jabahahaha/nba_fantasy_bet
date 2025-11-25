<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Lineup;
use App\Models\Player;
use App\Models\LineupPlayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LineupController extends Controller
{
    /**
     * Show lineup builder interface
     */
    public function create($contestId)
    {
        $contest = Contest::findOrFail($contestId);

        if (!$contest->isOpen()) {
            return redirect()->route('contests.index')
                ->with('error', 'This contest is no longer open for entries.');
        }

        // Check if user has enough points
        if (Auth::user()->points_balance < $contest->entry_fee) {
            return redirect()->route('contests.index')
                ->with('error', 'Insufficient points balance.');
        }

        // Get teams playing on contest date
        $teamsPlaying = \App\Models\Game::getTeamsPlayingOnDate($contest->contest_date);

        // Only show players whose teams are playing on the contest date
        $players = Player::where('is_playing', true)
            ->whereIn('team', $teamsPlaying)
            ->orderBy('salary', 'desc')
            ->get();

        return view('lineups.create', compact('contest', 'players'));
    }

    /**
     * Store a new lineup
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contest_id' => 'required|exists:contests,id',
            'lineup_name' => 'nullable|string|max:255',
            'players' => 'required|array|size:8',
            'players.*.player_id' => 'required|exists:players,id',
            'players.*.position_slot' => 'required|in:PG,SG,SF,PF,C,G,F,UTIL',
        ]);

        $contest = Contest::findOrFail($validated['contest_id']);

        // Validation checks
        if (!$contest->isOpen()) {
            return back()->with('error', 'Contest is no longer open.');
        }

        $user = Auth::user();

        if ($user->points_balance < $contest->entry_fee) {
            return back()->with('error', 'Insufficient points balance.');
        }

        // Get selected players
        $playerIds = collect($validated['players'])->pluck('player_id')->toArray();
        $players = Player::whereIn('id', $playerIds)->get()->keyBy('id');

        // Calculate total salary
        $totalSalary = 0;
        foreach ($playerIds as $playerId) {
            $totalSalary += $players[$playerId]->salary;
        }

        if ($totalSalary > 50000) {
            return back()->with('error', 'Total salary exceeds $50,000 cap.');
        }

        // Validate position requirements
        $positionSlots = collect($validated['players'])->pluck('position_slot')->toArray();
        $requiredSlots = ['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F', 'UTIL'];

        foreach ($requiredSlots as $required) {
            if (!in_array($required, $positionSlots)) {
                return back()->with('error', "Missing required position: {$required}");
            }
        }

        // Validate G slot has guard
        foreach ($validated['players'] as $playerData) {
            if ($playerData['position_slot'] === 'G') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['PG', 'SG'])) {
                    return back()->with('error', 'G slot must contain a guard (PG or SG).');
                }
            }
        }

        // Validate F slot has forward
        foreach ($validated['players'] as $playerData) {
            if ($playerData['position_slot'] === 'F') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['SF', 'PF'])) {
                    return back()->with('error', 'F slot must contain a forward (SF or PF).');
                }
            }
        }

        // Check for duplicate entry (optional - for MVP feature)
        $existingLineup = Lineup::where('user_id', $user->id)
            ->where('contest_id', $contest->id)
            ->first();

        if ($existingLineup) {
            return back()->with('error', 'You have already entered this contest.');
        }

        // Create lineup in transaction
        DB::beginTransaction();

        try {
            // Deduct entry fee
            if (!$user->deductPoints($contest->entry_fee, 'entry_fee', "Entry fee for {$contest->name}", $contest->id)) {
                throw new \Exception('Failed to deduct entry fee.');
            }

            // Create lineup
            $lineup = Lineup::create([
                'user_id' => $user->id,
                'contest_id' => $contest->id,
                'lineup_name' => $validated['lineup_name'] ?? 'My Lineup',
                'total_salary_used' => $totalSalary,
            ]);

            // Add players to lineup
            foreach ($validated['players'] as $playerData) {
                LineupPlayer::create([
                    'lineup_id' => $lineup->id,
                    'player_id' => $playerData['player_id'],
                    'position_slot' => $playerData['position_slot'],
                ]);
            }

            // Update contest entry count
            $contest->increment('current_entries');

            // Update user stats
            $user->increment('total_contests_entered');

            DB::commit();

            return redirect()->route('lineups.show', $lineup->id)
                ->with('success', 'Lineup entered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create lineup: ' . $e->getMessage());
        }
    }

    /**
     * Show user's lineups
     */
    public function index()
    {
        $user = Auth::user();

        $upcomingLineups = $user->lineups()
            ->whereHas('contest', function($q) {
                $q->where('status', 'upcoming');
            })
            ->with('contest')
            ->latest()
            ->get();

        $liveLineups = $user->lineups()
            ->whereHas('contest', function($q) {
                $q->where('status', 'live');
            })
            ->with('contest')
            ->latest()
            ->get();

        $completedLineups = $user->lineups()
            ->whereHas('contest', function($q) {
                $q->where('status', 'completed');
            })
            ->with('contest')
            ->latest()
            ->get();

        return view('lineups.index', compact('upcomingLineups', 'liveLineups', 'completedLineups'));
    }

    /**
     * Show single lineup with player performances
     */
    public function show($id)
    {
        $lineup = Lineup::with(['contest', 'lineupPlayers.player', 'user'])
            ->findOrFail($id);

        // Check authorization
        if ($lineup->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        return view('lineups.show', compact('lineup'));
    }

    /**
     * Show edit lineup form
     */
    public function edit($id)
    {
        $lineup = Lineup::with(['contest', 'lineupPlayers.player'])
            ->findOrFail($id);

        // Check authorization
        if ($lineup->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if contest is still open
        if ($lineup->contest->isLocked()) {
            return redirect()->route('lineups.show', $id)
                ->with('error', 'Contest is locked. Cannot edit lineup.');
        }

        // Get teams playing on contest date
        $teamsPlaying = \App\Models\Game::getTeamsPlayingOnDate($lineup->contest->contest_date);

        // Get available players
        $players = Player::where('is_playing', true)
            ->whereIn('team', $teamsPlaying)
            ->orderBy('salary', 'desc')
            ->get();

        // Format existing lineup for JavaScript
        $existingLineup = $lineup->lineupPlayers->map(function($lp) {
            return [
                'position_slot' => $lp->position_slot,
                'player' => [
                    'id' => $lp->player->id,
                    'name' => $lp->player->name,
                    'position' => $lp->player->position,
                    'salary' => $lp->player->salary,
                    'team' => $lp->player->team,
                    'ppg' => $lp->player->ppg
                ]
            ];
        });

        return view('lineups.edit', compact('lineup', 'players', 'existingLineup'));
    }

    /**
     * Update lineup
     */
    public function update(Request $request, $id)
    {
        $lineup = Lineup::findOrFail($id);

        // Check authorization
        if ($lineup->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if contest is still open
        if ($lineup->contest->isLocked()) {
            return back()->with('error', 'Contest is locked. Cannot edit lineup.');
        }

        $validated = $request->validate([
            'lineup_name' => 'nullable|string|max:255',
            'players' => 'required|array|size:8',
            'players.*.player_id' => 'required|exists:players,id',
            'players.*.position_slot' => 'required|in:PG,SG,SF,PF,C,G,F,UTIL',
        ]);

        // Get selected players
        $playerIds = collect($validated['players'])->pluck('player_id')->toArray();
        $players = Player::whereIn('id', $playerIds)->get()->keyBy('id');

        // Calculate total salary
        $totalSalary = 0;
        foreach ($playerIds as $playerId) {
            $totalSalary += $players[$playerId]->salary;
        }

        if ($totalSalary > 50000) {
            return back()->with('error', 'Total salary exceeds $50,000 cap.');
        }

        // Validate position requirements
        $positionSlots = collect($validated['players'])->pluck('position_slot')->toArray();
        $requiredSlots = ['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F', 'UTIL'];

        foreach ($requiredSlots as $required) {
            if (!in_array($required, $positionSlots)) {
                return back()->with('error', "Missing required position: {$required}");
            }
        }

        // Validate G and F slots
        foreach ($validated['players'] as $playerData) {
            if ($playerData['position_slot'] === 'G') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['PG', 'SG'])) {
                    return back()->with('error', 'G slot must contain a guard (PG or SG).');
                }
            }
            if ($playerData['position_slot'] === 'F') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['SF', 'PF'])) {
                    return back()->with('error', 'F slot must contain a forward (SF or PF).');
                }
            }
        }

        DB::beginTransaction();

        try {
            // Update lineup name and salary
            $lineup->lineup_name = $validated['lineup_name'] ?? $lineup->lineup_name;
            $lineup->total_salary_used = $totalSalary;
            $lineup->save();

            // Delete old players
            $lineup->lineupPlayers()->delete();

            // Add new players
            foreach ($validated['players'] as $playerData) {
                LineupPlayer::create([
                    'lineup_id' => $lineup->id,
                    'player_id' => $playerData['player_id'],
                    'position_slot' => $playerData['position_slot'],
                ]);
            }

            DB::commit();

            return redirect()->route('lineups.show', $lineup->id)
                ->with('success', 'Lineup updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update lineup: ' . $e->getMessage());
        }
    }
}
