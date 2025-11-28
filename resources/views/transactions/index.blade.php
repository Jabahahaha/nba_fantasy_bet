<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            Transaction History
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-green-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-green-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Deposits</div>
                        <div class="text-4xl font-black accent-green">+{{ number_format($stats['total_deposits']) }}</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-red-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-red-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Entry Fees</div>
                        <div class="text-4xl font-black text-red-400">-{{ number_format($stats['total_entries']) }}</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-yellow-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-yellow-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Prizes Won</div>
                        <div class="text-4xl font-black text-yellow-400">+{{ number_format($stats['total_prizes']) }}</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-blue-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-blue-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Current Balance</div>
                        <div class="text-4xl font-black text-white">{{ number_format(Auth::user()->points_balance) }}</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-yellow-600/20 to-orange-600/20">
                    <h3 class="text-xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter Transactions
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">Type</label>
                            <select name="type" class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-yellow-500 focus:ring-yellow-500 px-4 py-3">
                                <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="entry_fee" {{ request('type') == 'entry_fee' ? 'selected' : '' }}>Entry Fees</option>
                                <option value="contest_won" {{ request('type') == 'contest_won' ? 'selected' : '' }}>Prizes Won</option>
                                <option value="daily_bonus" {{ request('type') == 'daily_bonus' ? 'selected' : '' }}>Daily Bonus</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-yellow-500 focus:ring-yellow-500 px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-yellow-500 focus:ring-yellow-500 px-4 py-3">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-black px-6 py-3 rounded-xl font-black transition shadow-lg shadow-yellow-500/20">
                                Filter
                            </button>
                            <a href="{{ route('transactions.index') }}" class="px-6 py-3 rounded-xl border border-gray-600 text-gray-300 hover:bg-gray-700 font-bold transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-yellow-600/20 to-orange-600/20">
                    <h3 class="text-2xl font-black text-white flex items-center gap-3">
                        <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        All Transactions
                    </h3>
                    <p class="text-sm text-gray-400 mt-2">Complete history of your point transactions</p>
                </div>

                @if($transactions->isEmpty())
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-700 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-gray-400 text-lg font-bold">No transactions found</p>
                        <p class="text-gray-500 text-sm mt-2">Your transaction history will appear here</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Balance After</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-750 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-white">{{ $transaction->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $transaction->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $typeColors = [
                                                    'entry_fee' => 'bg-red-500/20 border-red-500/50 text-red-400',
                                                    'contest_won' => 'bg-green-500/20 border-green-500/50 text-green-400',
                                                    'daily_bonus' => 'bg-blue-500/20 border-blue-500/50 text-blue-400',
                                                ];
                                                $typeLabels = [
                                                    'entry_fee' => 'Entry Fee',
                                                    'contest_won' => 'Prize Won',
                                                    'daily_bonus' => 'Daily Bonus',
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 text-xs font-black rounded-lg border uppercase tracking-wider {{ $typeColors[$transaction->type] ?? 'bg-gray-700 border-gray-600 text-gray-400' }}">
                                                {{ $typeLabels[$transaction->type] ?? ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-white">{{ $transaction->description }}</div>
                                            @if($transaction->contest)
                                                <a href="{{ route('contests.show', $transaction->contest) }}" class="text-xs text-blue-400 hover:text-blue-300 font-bold flex items-center gap-1 mt-1">
                                                    View Contest
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="text-lg font-black {{ $transaction->amount > 0 ? 'accent-green' : 'text-red-400' }}">
                                                {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="text-sm font-bold text-gray-300">{{ number_format($transaction->balance_after) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="p-6 border-t border-gray-700">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
