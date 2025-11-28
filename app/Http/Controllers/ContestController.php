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
            'max_entries_per_user' => 'required|integer|min:1|max:150',
            'contest_date' => 'required|date',
            'lock_time' => 'required|date',
        ], [
            'name.required' => 'Contest name is required.',
            'name.max' => 'Contest name cannot exceed 255 characters.',
            'contest_type.required' => 'Please select a contest type.',
            'contest_type.in' => 'Invalid contest type selected. Must be 50-50, GPP, or H2H.',
            'entry_fee.required' => 'Entry fee is required.',
            'entry_fee.integer' => 'Entry fee must be a whole number.',
            'entry_fee.min' => 'Entry fee must be at least 1 point.',
            'max_entries.required' => 'Maximum entries is required.',
            'max_entries.integer' => 'Maximum entries must be a whole number.',
            'max_entries.min' => 'Contest must allow at least 2 entries.',
            'max_entries_per_user.required' => 'Maximum entries per user is required.',
            'max_entries_per_user.integer' => 'Maximum entries per user must be a whole number.',
            'max_entries_per_user.min' => 'Users must be allowed at least 1 entry.',
            'max_entries_per_user.max' => 'Maximum entries per user cannot exceed 150.',
            'contest_date.required' => 'Contest date is required.',
            'contest_date.date' => 'Contest date must be a valid date.',
            'lock_time.required' => 'Lock time is required.',
            'lock_time.date' => 'Lock time must be a valid date and time.',
        ]);

        $validated['prize_pool'] = (int) ($validated['entry_fee'] * $validated['max_entries'] * 0.9);
        $validated['status'] = 'upcoming';
        $validated['current_entries'] = 0;

        $contest = Contest::create($validated);

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

    /**
     * Show contest history for authenticated user
     */
    public function history()
    {
        $user = Auth::user();

        $contests = Contest::whereHas('lineups', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['lineups' => function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->with('contest');
        }])
        ->where('status', 'completed')
        ->orderBy('contest_date', 'desc')
        ->get();

        $totalContests = $contests->count();
        $totalWinnings = $contests->sum(function($contest) use ($user) {
            return $contest->lineups->where('user_id', $user->id)->sum('prize_won');
        });
        $totalSpent = $contests->sum(function($contest) use ($user) {
            return $contest->entry_fee * $contest->lineups->where('user_id', $user->id)->count();
        });

        $topThreeFinishes = $contests->filter(function($contest) use ($user) {
            $lineup = $contest->lineups->where('user_id', $user->id)->first();
            return $lineup && $lineup->final_rank && $lineup->final_rank <= 3;
        })->count();

        $winRate = $totalContests > 0 ? round(($topThreeFinishes / $totalContests) * 100, 1) : 0;

        $finishes = $contests->map(function($contest) use ($user) {
            $lineup = $contest->lineups->where('user_id', $user->id)->first();
            return $lineup ? $lineup->final_rank : null;
        })->filter()->values();

        $avgFinish = $finishes->count() > 0 ? round($finishes->avg(), 1) : 0;

        return view('contests.history', compact(
            'contests',
            'totalContests',
            'totalWinnings',
            'totalSpent',
            'winRate',
            'avgFinish'
        ));
    }

    /**
     * Cancel a contest and refund all participants (admin only)
     */
    public function cancel(Request $request, $id)
    {
        $contest = Contest::findOrFail($id);

        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        if (!$contest->canBeCancelled()) {
            return back()->with('error', 'This contest cannot be cancelled. It may already be completed or cancelled.');
        }

        $cancellationReason = $request->input('cancellation_reason', 'Contest cancelled by admin');

        $affectedUsers = $contest->getAffectedUsersCount();
        $totalRefund = $contest->getTotalRefundAmount();

        if ($contest->cancel($cancellationReason)) {
            return redirect()->route('admin.dashboard')
                ->with('success', "Contest '{$contest->name}' has been cancelled. {$affectedUsers} users will be refunded a total of {$totalRefund} points.");
        } else {
            return back()->with('error', 'Failed to cancel contest. Please check the logs and try again.');
        }
    }
}
