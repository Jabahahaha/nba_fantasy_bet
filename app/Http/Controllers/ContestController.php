<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ContestController extends Controller
{
    /**
     * Display all contests
     */
    public function index(Request $request)
    {
        $type = $request->get('type');

        $query = Contest::query();

        if ($type) {
            $query->where('contest_type', $type);
        }

        $contests = $query->where('status', 'upcoming')
            ->orderBy('lock_time')
            ->get();

        return view('contests.index', compact('contests'));
    }

    /**
     * Display single contest with leaderboard
     */
    public function show($id)
    {
        $contest = Contest::with(['lineups.user', 'lineups' => function($query) {
            $query->orderBy('final_rank');
        }])->findOrFail($id);

        $payouts = $contest->payouts;

        return view('contests.show', compact('contest', 'payouts'));
    }

    /**
     * Store a new contest (admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contest_type' => 'required|in:50-50,GPP,H2H',
            'entry_fee' => 'required|integer|min:1',
            'max_entries' => 'required|integer|min:2',
            'contest_date' => 'required|date',
            'lock_time' => 'required|date',
        ]);

        // Calculate prize pool (assuming 10% rake)
        $validated['prize_pool'] = (int) ($validated['entry_fee'] * $validated['max_entries'] * 0.9);
        $validated['status'] = 'upcoming';
        $validated['current_entries'] = 0;

        $contest = Contest::create($validated);

        // Calculate payout structure
        $contest->calculatePrizes();

        return redirect()->route('contests.show', $contest->id)
            ->with('success', 'Contest created successfully!');
    }

    /**
     * Lock contest (can be called manually or via scheduler)
     */
    public function lock($id)
    {
        $contest = Contest::findOrFail($id);

        if ($contest->lock_time->isPast() && $contest->status === 'upcoming') {
            $contest->lock();
            return response()->json(['message' => 'Contest locked successfully']);
        }

        return response()->json(['message' => 'Contest cannot be locked yet'], 400);
    }

    /**
     * Show games for a specific date
     */
    public function games(Request $request)
    {
        $dateString = $request->get('date', today()->toDateString());
        $date = Carbon::parse($dateString);

        $games = Game::getGamesForDate($date);

        return view('contests.games', compact('games', 'date'));
    }
}
