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
        ], [
            'contest_id.required' => 'Contest selection is required.',
            'contest_id.exists' => 'The selected contest does not exist.',
            'lineup_name.max' => 'Lineup name cannot exceed 255 characters.',
            'players.required' => 'You must select players for your lineup.',
            'players.size' => 'Your lineup must contain exactly 8 players.',
            'players.*.player_id.required' => 'All position slots must be filled.',
            'players.*.player_id.exists' => 'One or more selected players are invalid.',
            'players.*.position_slot.required' => 'All positions must be assigned.',
            'players.*.position_slot.in' => 'Invalid position slot selected.',
        ]);

        $contest = Contest::findOrFail($validated['contest_id']);

        // Validation checks
        if (!$contest->isOpen()) {
            return back()->with('error', 'This contest is no longer accepting entries. It may be locked or already started.');
        }

        $user = Auth::user();

        if ($user->points_balance < $contest->entry_fee) {
            $needed = $contest->entry_fee - $user->points_balance;
            return back()->with('error', "Insufficient points balance. You need {$needed} more points to enter this contest (Entry fee: {$contest->entry_fee} points, Your balance: {$user->points_balance} points).");
        }

        // Check if user has reached max entries for this contest
        if (!$contest->canUserEnter($user->id)) {
            return back()->with('error', "You have reached the maximum number of entries ({$contest->max_entries_per_user}) for this contest. Cannot submit additional lineups.");
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
            $over = $totalSalary - 50000;
            return back()->with('error', "Total salary of \${$totalSalary} exceeds the \$50,000 salary cap by \${$over}. Please adjust your lineup.");
        }

        // Validate position requirements
        $positionSlots = collect($validated['players'])->pluck('position_slot')->toArray();
        $requiredSlots = ['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F', 'UTIL'];

        foreach ($requiredSlots as $required) {
            if (!in_array($required, $positionSlots)) {
                return back()->with('error', "Your lineup is missing the required {$required} position. Please fill all 8 roster spots.");
            }
        }

        // Validate G slot has guard
        foreach ($validated['players'] as $playerData) {
            if ($playerData['position_slot'] === 'G') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['PG', 'SG'])) {
                    return back()->with('error', "The G (Guard) slot must contain a Point Guard (PG) or Shooting Guard (SG). {$player->name} is a {$player->position}.");
                }
            }
        }

        // Validate F slot has forward
        foreach ($validated['players'] as $playerData) {
            if ($playerData['position_slot'] === 'F') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['SF', 'PF'])) {
                    return back()->with('error', "The F (Forward) slot must contain a Small Forward (SF) or Power Forward (PF). {$player->name} is a {$player->position}.");
                }
            }
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
                $q->where('status', 'upcoming')->whereNull('cancelled_at');
            })
            ->with('contest')
            ->latest()
            ->get();

        $liveLineups = $user->lineups()
            ->whereHas('contest', function($q) {
                $q->where('status', 'live')->whereNull('cancelled_at');
            })
            ->with('contest')
            ->latest()
            ->get();

        $completedLineups = $user->lineups()
            ->whereHas('contest', function($q) {
                $q->where('status', 'completed')->whereNull('cancelled_at');
            })
            ->with('contest')
            ->latest()
            ->get();

        $cancelledLineups = $user->lineups()
            ->whereHas('contest', function($q) {
                $q->where('status', 'cancelled')->orWhereNotNull('cancelled_at');
            })
            ->with('contest')
            ->latest()
            ->get();

        return view('lineups.index', compact('upcomingLineups', 'liveLineups', 'completedLineups', 'cancelledLineups'));
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
            return back()->with('error', 'This contest is locked and lineups can no longer be edited. The contest has already started or lock time has passed.');
        }

        $validated = $request->validate([
            'lineup_name' => 'nullable|string|max:255',
            'players' => 'required|array|size:8',
            'players.*.player_id' => 'required|exists:players,id',
            'players.*.position_slot' => 'required|in:PG,SG,SF,PF,C,G,F,UTIL',
        ], [
            'lineup_name.max' => 'Lineup name cannot exceed 255 characters.',
            'players.required' => 'You must select players for your lineup.',
            'players.size' => 'Your lineup must contain exactly 8 players.',
            'players.*.player_id.required' => 'All position slots must be filled.',
            'players.*.player_id.exists' => 'One or more selected players are invalid.',
            'players.*.position_slot.required' => 'All positions must be assigned.',
            'players.*.position_slot.in' => 'Invalid position slot selected.',
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
            $over = $totalSalary - 50000;
            return back()->with('error', "Total salary of \${$totalSalary} exceeds the \$50,000 salary cap by \${$over}. Please adjust your lineup.");
        }

        // Validate position requirements
        $positionSlots = collect($validated['players'])->pluck('position_slot')->toArray();
        $requiredSlots = ['PG', 'SG', 'SF', 'PF', 'C', 'G', 'F', 'UTIL'];

        foreach ($requiredSlots as $required) {
            if (!in_array($required, $positionSlots)) {
                return back()->with('error', "Your lineup is missing the required {$required} position. Please fill all 8 roster spots.");
            }
        }

        // Validate G and F slots
        foreach ($validated['players'] as $playerData) {
            if ($playerData['position_slot'] === 'G') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['PG', 'SG'])) {
                    return back()->with('error', "The G (Guard) slot must contain a Point Guard (PG) or Shooting Guard (SG). {$player->name} is a {$player->position}.");
                }
            }
            if ($playerData['position_slot'] === 'F') {
                $player = $players[$playerData['player_id']];
                if (!in_array($player->position, ['SF', 'PF'])) {
                    return back()->with('error', "The F (Forward) slot must contain a Small Forward (SF) or Power Forward (PF). {$player->name} is a {$player->position}.");
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
