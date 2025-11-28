<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display transaction history for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::where('user_id', $user->id)
            ->with('contest')
            ->orderBy('created_at', 'desc');

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->paginate(25);

        $stats = [
            'total_deposits' => Transaction::where('user_id', $user->id)
                ->where('amount', '>', 0)
                ->sum('amount'),
            'total_withdrawals' => abs(Transaction::where('user_id', $user->id)
                ->where('amount', '<', 0)
                ->sum('amount')),
            'total_prizes' => Transaction::where('user_id', $user->id)
                ->where('type', 'contest_won')
                ->sum('amount'),
            'total_entries' => abs(Transaction::where('user_id', $user->id)
                ->where('type', 'entry_fee')
                ->sum('amount')),
        ];

        return view('transactions.index', compact('transactions', 'stats'));
    }
}
