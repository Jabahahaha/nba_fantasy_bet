<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Contest History
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Contests -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-1">Total Contests</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $totalContests }}</div>
                    </div>
                </div>

                <!-- Total Winnings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-1">Total Winnings</div>
                        <div class="text-3xl font-bold text-green-600">{{ number_format($totalWinnings) }}</div>
                        <div class="text-xs text-gray-500 mt-1">Points</div>
                    </div>
                </div>

                <!-- Win Rate -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-1">Top 3 Rate</div>
                        <div class="text-3xl font-bold text-blue-600">{{ $winRate }}%</div>
                        <div class="text-xs text-gray-500 mt-1">Top 3 finishes</div>
                    </div>
                </div>

                <!-- Average Finish -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 mb-1">Avg Finish</div>
                        <div class="text-3xl font-bold text-purple-600">{{ $avgFinish }}</div>
                        <div class="text-xs text-gray-500 mt-1">Position</div>
                    </div>
                </div>
            </div>

            <!-- Net Profit/Loss Card -->
            @php
                $netProfit = $totalWinnings - $totalSpent;
                $isProfit = $netProfit >= 0;
            @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Net Profit/Loss</h3>
                            <p class="text-sm text-gray-500">Total spent: {{ number_format($totalSpent) }} points</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">
                                {{ $isProfit ? '+' : '' }}{{ number_format($netProfit) }}
                            </div>
                            <div class="text-sm text-gray-500">Points</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contest History Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Contest History</h3>

                    @if($contests->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p class="text-lg mb-2">No completed contests yet</p>
                            <p class="text-sm">Enter some contests to see your history here!</p>
                            <a href="{{ route('contests.index') }}" class="inline-block mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Browse Contests
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contest</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Fee</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($contests as $contest)
                                        @php
                                            $userLineup = $contest->lineups->where('user_id', Auth::id())->first();
                                            $prizeWon = $userLineup ? $userLineup->prize_won : 0;
                                            $finalScore = $userLineup ? $userLineup->final_score : 0;
                                            $finalRank = $userLineup ? $userLineup->final_rank : '-';
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $contest->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $contest->contest_date->format('M d, Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $contest->contest_type === 'h2h' ? 'bg-purple-100 text-purple-800' : '' }}
                                                    {{ $contest->contest_type === '50_50' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $contest->contest_type === 'gpp' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                    {{ strtoupper(str_replace('_', '/', $contest->contest_type)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($contest->entry_fee) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ number_format($finalScore, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold
                                                    {{ $finalRank == 1 ? 'text-yellow-600' : '' }}
                                                    {{ $finalRank == 2 ? 'text-gray-600' : '' }}
                                                    {{ $finalRank == 3 ? 'text-orange-600' : '' }}
                                                    {{ $finalRank > 3 ? 'text-gray-900' : '' }}">
                                                    {{ $finalRank }}{{ $finalRank !== '-' ? '/' . $contest->lineups->count() : '' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold {{ $prizeWon > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                                    {{ $prizeWon > 0 ? '+' . number_format($prizeWon) : '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex gap-2">
                                                    @if($userLineup)
                                                        <a href="{{ route('lineups.show', $userLineup->id) }}" class="text-blue-600 hover:text-blue-900">
                                                            View Lineup
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('contests.show', $contest->id) }}" class="text-gray-600 hover:text-gray-900">
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
    </div>
</x-app-layout>
