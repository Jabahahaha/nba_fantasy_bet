<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contest;
use App\Models\Lineup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * Show global leaderboards
     */
    public function index()
    {
        // Top Winners - Users with highest total winnings
        $topWinners = User::select('users.*')
            ->selectRaw('COALESCE(SUM(lineups.prize_won), 0) as total_winnings')
            ->leftJoin('lineups', 'users.id', '=', 'lineups.user_id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.points_balance', 'users.is_admin', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderByDesc('total_winnings')
            ->havingRaw('total_winnings > 0')
            ->limit(10)
            ->get();

        // Most Active - Users with most contests entered
        $mostActive = User::select('users.*')
            ->selectRaw('COUNT(DISTINCT lineups.contest_id) as contests_entered')
            ->leftJoin('lineups', 'users.id', '=', 'lineups.user_id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.points_balance', 'users.is_admin', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderByDesc('contests_entered')
            ->havingRaw('contests_entered > 0')
            ->limit(10)
            ->get();

        // Best Win Rate - Users with highest percentage of top 3 finishes (min 5 contests)
        $bestWinRate = User::select('users.*')
            ->selectRaw('COUNT(DISTINCT lineups.contest_id) as contests_entered')
            ->selectRaw('SUM(CASE WHEN lineups.final_rank <= 3 THEN 1 ELSE 0 END) as top_three_finishes')
            ->selectRaw('ROUND((SUM(CASE WHEN lineups.final_rank <= 3 THEN 1 ELSE 0 END) * 100.0 / COUNT(DISTINCT lineups.contest_id)), 1) as win_rate')
            ->join('lineups', 'users.id', '=', 'lineups.user_id')
            ->join('contests', 'lineups.contest_id', '=', 'contests.id')
            ->where('contests.status', 'completed')
            ->whereNotNull('lineups.final_rank')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.points_balance', 'users.is_admin', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->havingRaw('COUNT(DISTINCT lineups.contest_id) >= 5')
            ->orderByDesc('win_rate')
            ->limit(10)
            ->get();

        // Highest Single Score - Best single contest performance
        $highestScores = Lineup::select('lineups.*', 'users.name as user_name', 'contests.name as contest_name', 'contests.contest_date')
            ->join('users', 'lineups.user_id', '=', 'users.id')
            ->join('contests', 'lineups.contest_id', '=', 'contests.id')
            ->where('contests.status', 'completed')
            ->whereNotNull('lineups.fantasy_points_scored')
            ->orderByDesc('lineups.fantasy_points_scored')
            ->limit(10)
            ->get();

        // Most Profitable - Highest profit (winnings - entry fees)
        $mostProfitable = User::select('users.*')
            ->selectRaw('COALESCE(SUM(lineups.prize_won), 0) as total_winnings')
            ->selectRaw('COALESCE(SUM(contests.entry_fee), 0) as total_spent')
            ->selectRaw('COALESCE(SUM(lineups.prize_won), 0) - COALESCE(SUM(contests.entry_fee), 0) as net_profit')
            ->leftJoin('lineups', 'users.id', '=', 'lineups.user_id')
            ->leftJoin('contests', 'lineups.contest_id', '=', 'contests.id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.points_balance', 'users.is_admin', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderByDesc('net_profit')
            ->havingRaw('net_profit > 0')
            ->limit(10)
            ->get();

        return view('leaderboards.index', compact(
            'topWinners',
            'mostActive',
            'bestWinRate',
            'highestScores',
            'mostProfitable'
        ));
    }
}
