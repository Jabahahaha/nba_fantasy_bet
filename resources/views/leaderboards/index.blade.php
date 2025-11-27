<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white">
            Global Leaderboards
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Top Winners -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-yellow-600/20 to-orange-600/20">
                    <h3 class="text-2xl font-black text-white mb-2 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Top Winners
                    </h3>
                    <p class="text-gray-300 text-sm">Highest total winnings across all contests</p>
                </div>

                @if($topWinners->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-gray-400 font-semibold">No data available yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Player</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Total Winnings</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($topWinners as $index => $user)
                                    <tr class="hover:bg-gray-700/50 transition {{ $index < 3 ? 'bg-yellow-500/5' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($index === 0)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    <span class="text-xl font-black text-yellow-400">#1</span>
                                                </div>
                                            @elseif($index === 1)
                                                <span class="text-xl font-black text-gray-300">#2</span>
                                            @elseif($index === 2)
                                                <span class="text-xl font-black text-orange-400">#3</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-500">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center text-white font-black mr-4 shadow-lg">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="font-bold text-white text-lg">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xl font-black text-yellow-400">{{ number_format($user->total_winnings) }}</span>
                                            <span class="text-sm text-gray-500 ml-1">pts</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Most Active Players -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-blue-600/20 to-cyan-600/20">
                    <h3 class="text-2xl font-black text-white mb-2 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Most Active Players
                    </h3>
                    <p class="text-gray-300 text-sm">Players with most contests entered</p>
                </div>

                @if($mostActive->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-gray-400 font-semibold">No data available yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Player</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Contests Entered</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($mostActive as $index => $user)
                                    <tr class="hover:bg-gray-700/50 transition {{ $index < 3 ? 'bg-blue-500/5' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($index === 0)
                                                <span class="text-xl font-black text-yellow-400">#1</span>
                                            @elseif($index === 1)
                                                <span class="text-xl font-black text-gray-300">#2</span>
                                            @elseif($index === 2)
                                                <span class="text-xl font-black text-orange-400">#3</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-500">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-black mr-4 shadow-lg">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="font-bold text-white text-lg">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xl font-black text-blue-400">{{ $user->contests_entered }}</span>
                                            <span class="text-sm text-gray-500 ml-1">contests</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Best Win Rate -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-purple-600/20 to-pink-600/20">
                    <h3 class="text-2xl font-black text-white mb-2 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Best Win Rate
                    </h3>
                    <p class="text-gray-300 text-sm">Highest percentage of top 3 finishes (minimum 5 contests)</p>
                </div>

                @if($bestWinRate->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-400 font-semibold">No players with 5+ contests yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Player</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Win Rate</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Contests</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($bestWinRate as $index => $user)
                                    <tr class="hover:bg-gray-700/50 transition {{ $index < 3 ? 'bg-purple-500/5' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($index === 0)
                                                <span class="text-xl font-black text-yellow-400">#1</span>
                                            @elseif($index === 1)
                                                <span class="text-xl font-black text-gray-300">#2</span>
                                            @elseif($index === 2)
                                                <span class="text-xl font-black text-orange-400">#3</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-500">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-black mr-4 shadow-lg">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="font-bold text-white text-lg">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xl font-black text-purple-400">{{ $user->win_rate }}%</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm text-gray-400">{{ $user->contests_entered }} entered</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Highest Single Scores -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-red-600/20 to-orange-600/20">
                    <h3 class="text-2xl font-black text-white mb-2 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                        </svg>
                        Highest Single Scores
                    </h3>
                    <p class="text-gray-300 text-sm">Best individual contest performances</p>
                </div>

                @if($highestScores->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <p class="text-gray-400 font-semibold">No completed contests yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Player</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Contest</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Score</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($highestScores as $index => $lineup)
                                    <tr class="hover:bg-gray-700/50 transition {{ $index < 3 ? 'bg-red-500/5' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($index === 0)
                                                <span class="text-xl font-black text-yellow-400">#1</span>
                                            @elseif($index === 1)
                                                <span class="text-xl font-black text-gray-300">#2</span>
                                            @elseif($index === 2)
                                                <span class="text-xl font-black text-orange-400">#3</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-500">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center text-white font-black mr-4 shadow-lg">
                                                    {{ strtoupper(substr($lineup->user_name, 0, 1)) }}
                                                </div>
                                                <span class="font-bold text-white text-lg">{{ $lineup->user_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm">
                                                <div class="font-bold text-white">{{ $lineup->contest_name }}</div>
                                                <div class="text-gray-400">{{ \Carbon\Carbon::parse($lineup->contest_date)->format('M d, Y') }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xl font-black text-red-400">{{ number_format($lineup->fantasy_points_scored, 1) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Most Profitable -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-green-600/20 to-emerald-600/20">
                    <h3 class="text-2xl font-black text-white mb-2 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        Most Profitable
                    </h3>
                    <p class="text-gray-300 text-sm">Highest net profit (winnings minus entry fees)</p>
                </div>

                @if($mostProfitable->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-400 font-semibold">No profitable players yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Player</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Net Profit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($mostProfitable as $index => $user)
                                    <tr class="hover:bg-gray-700/50 transition {{ $index < 3 ? 'bg-green-500/5' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($index === 0)
                                                <span class="text-xl font-black text-yellow-400">#1</span>
                                            @elseif($index === 1)
                                                <span class="text-xl font-black text-gray-300">#2</span>
                                            @elseif($index === 2)
                                                <span class="text-xl font-black text-orange-400">#3</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-500">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-black mr-4 shadow-lg">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="font-bold text-white text-lg">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xl font-black accent-green">+{{ number_format($user->net_profit) }}</span>
                                            <span class="text-sm text-gray-500 ml-1">pts</span>
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
