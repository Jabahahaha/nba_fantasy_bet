<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white">
            Contest History
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Contests -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-blue-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-blue-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Contests</div>
                        <div class="text-4xl font-black text-white">{{ $totalContests }}</div>
                    </div>
                </div>

                <!-- Total Winnings -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-green-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-green-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Winnings</div>
                        <div class="text-4xl font-black accent-green">{{ number_format($totalWinnings) }}</div>
                        <div class="text-xs text-gray-500 mt-1">Points</div>
                    </div>
                </div>

                <!-- Win Rate -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-purple-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-purple-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Top 3 Rate</div>
                        <div class="text-4xl font-black text-purple-400">{{ $winRate }}%</div>
                        <div class="text-xs text-gray-500 mt-1">Top 3 finishes</div>
                    </div>
                </div>

                <!-- Average Finish -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-orange-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-orange-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Avg Finish</div>
                        <div class="text-4xl font-black text-orange-400">{{ $avgFinish }}</div>
                        <div class="text-xs text-gray-500 mt-1">Position</div>
                    </div>
                </div>
            </div>

            <!-- Net Profit/Loss Card -->
            @php
                $netProfit = $totalWinnings - $totalSpent;
                $isProfit = $netProfit >= 0;
            @endphp
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-6 {{ $isProfit ? 'hover:border-green-500/50' : 'hover:border-red-500/50' }} transition">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-{{ $isProfit ? 'green' : 'red' }}-500/10 rounded-xl p-4">
                                <svg class="w-8 h-8 text-{{ $isProfit ? 'green' : 'red' }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($isProfit)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white mb-1">Net {{ $isProfit ? 'Profit' : 'Loss' }}</h3>
                                <p class="text-sm text-gray-400">Total spent: <span class="font-bold text-white">{{ number_format($totalSpent) }}</span> points</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            <div class="text-5xl font-black {{ $isProfit ? 'accent-green' : 'text-red-400' }}">
                                {{ $isProfit ? '+' : '' }}{{ number_format($netProfit) }}
                            </div>
                            <div class="text-sm text-gray-500">Points</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contest History Table -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-2xl font-black text-white">Contest History</h3>
                </div>

                @if($contests->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-xl font-bold text-gray-400 mb-2">No completed contests yet</p>
                        <p class="text-sm text-gray-500 mb-6">Enter some contests to see your history here!</p>
                        <a href="{{ route('contests.index') }}" class="inline-block bg-accent-green hover:bg-green-600 text-black font-black py-3 px-8 rounded-xl transition shadow-lg shadow-green-500/20">
                            BROWSE CONTESTS
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Contest</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Entry Fee</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Prize</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($contests as $contest)
                                    @php
                                        $userLineup = $contest->lineups->where('user_id', Auth::id())->first();
                                        $prizeWon = $userLineup ? $userLineup->prize_won : 0;
                                        $finalScore = $userLineup ? $userLineup->final_score : 0;
                                        $finalRank = $userLineup ? $userLineup->final_rank : '-';
                                    @endphp
                                    <tr class="hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-white">{{ $contest->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">{{ $contest->contest_date->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg
                                                {{ $contest->contest_type === 'H2H' ? 'bg-green-500/10 text-green-400 border border-green-500/30' : '' }}
                                                {{ $contest->contest_type === '50-50' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/30' : '' }}
                                                {{ $contest->contest_type === 'GPP' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/30' : '' }}">
                                                {{ $contest->contest_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-white">{{ number_format($contest->entry_fee) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-white">{{ number_format($finalScore, 1) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-black
                                                {{ $finalRank == 1 ? 'text-yellow-400' : '' }}
                                                {{ $finalRank == 2 ? 'text-gray-300' : '' }}
                                                {{ $finalRank == 3 ? 'text-orange-400' : '' }}
                                                {{ $finalRank > 3 ? 'text-gray-400' : '' }}">
                                                {{ $finalRank }}{{ $finalRank !== '-' ? '/' . $contest->lineups->count() : '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-black {{ $prizeWon > 0 ? 'accent-green' : 'text-gray-500' }}">
                                                {{ $prizeWon > 0 ? '+' . number_format($prizeWon) : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex gap-3">
                                                @if($userLineup)
                                                    <a href="{{ route('lineups.show', $userLineup->id) }}" class="text-blue-400 hover:text-blue-300 font-bold">
                                                        Lineup
                                                    </a>
                                                @endif
                                                <a href="{{ route('contests.show', $contest->id) }}" class="text-gray-400 hover:text-white font-bold">
                                                    Leaderboard
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
