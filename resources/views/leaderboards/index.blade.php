<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Global Leaderboards
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Top Winners -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Top Winners
                    </h3>
                    <p class="text-gray-600 mb-4">Highest total winnings across all contests</p>

                    @if($topWinners->isEmpty())
                        <p class="text-gray-500 text-center py-8">No data available yet</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Winnings</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($topWinners as $index => $user)
                                        <tr class="{{ $index < 3 ? 'bg-yellow-50' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="text-lg font-bold
                                                    {{ $index === 0 ? 'text-yellow-500' : '' }}
                                                    {{ $index === 1 ? 'text-gray-400' : '' }}
                                                    {{ $index === 2 ? 'text-orange-600' : '' }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="font-medium">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-lg font-bold text-green-600">{{ number_format($user->total_winnings) }}</span>
                                                <span class="text-sm text-gray-500"> pts</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Most Active Players -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Most Active Players
                    </h3>
                    <p class="text-gray-600 mb-4">Players with most contests entered</p>

                    @if($mostActive->isEmpty())
                        <p class="text-gray-500 text-center py-8">No data available yet</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Contests Entered</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($mostActive as $index => $user)
                                        <tr class="{{ $index < 3 ? 'bg-blue-50' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="text-lg font-bold
                                                    {{ $index === 0 ? 'text-yellow-500' : '' }}
                                                    {{ $index === 1 ? 'text-gray-400' : '' }}
                                                    {{ $index === 2 ? 'text-orange-600' : '' }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="font-medium">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-lg font-bold text-blue-600">{{ $user->contests_entered }}</span>
                                                <span class="text-sm text-gray-500"> contests</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Best Win Rate -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Best Win Rate
                    </h3>
                    <p class="text-gray-600 mb-4">Highest percentage of top 3 finishes (minimum 5 contests)</p>

                    @if($bestWinRate->isEmpty())
                        <p class="text-gray-500 text-center py-8">No players with 5+ contests yet</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Win Rate</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Contests</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($bestWinRate as $index => $user)
                                        <tr class="{{ $index < 3 ? 'bg-purple-50' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="text-lg font-bold
                                                    {{ $index === 0 ? 'text-yellow-500' : '' }}
                                                    {{ $index === 1 ? 'text-gray-400' : '' }}
                                                    {{ $index === 2 ? 'text-orange-600' : '' }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold mr-3">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="font-medium">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-lg font-bold text-purple-600">{{ $user->win_rate }}%</span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-sm text-gray-600">{{ $user->contests_entered }} entered</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Highest Single Scores -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                        </svg>
                        Highest Single Scores
                    </h3>
                    <p class="text-gray-600 mb-4">Best individual contest performances</p>

                    @if($highestScores->isEmpty())
                        <p class="text-gray-500 text-center py-8">No completed contests yet</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contest</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Score</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($highestScores as $index => $lineup)
                                        <tr class="{{ $index < 3 ? 'bg-red-50' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="text-lg font-bold
                                                    {{ $index === 0 ? 'text-yellow-500' : '' }}
                                                    {{ $index === 1 ? 'text-gray-400' : '' }}
                                                    {{ $index === 2 ? 'text-orange-600' : '' }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white font-bold mr-3">
                                                        {{ strtoupper(substr($lineup->user_name, 0, 1)) }}
                                                    </div>
                                                    <span class="font-medium">{{ $lineup->user_name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm">
                                                    <div class="font-medium">{{ $lineup->contest_name }}</div>
                                                    <div class="text-gray-500">{{ \Carbon\Carbon::parse($lineup->contest_date)->format('M d, Y') }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-lg font-bold text-red-600">{{ number_format($lineup->fantasy_points_scored, 2) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Most Profitable -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        Most Profitable
                    </h3>
                    <p class="text-gray-600 mb-4">Highest net profit (winnings minus entry fees)</p>

                    @if($mostProfitable->isEmpty())
                        <p class="text-gray-500 text-center py-8">No profitable players yet</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net Profit</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($mostProfitable as $index => $user)
                                        <tr class="{{ $index < 3 ? 'bg-green-50' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="text-lg font-bold
                                                    {{ $index === 0 ? 'text-yellow-500' : '' }}
                                                    {{ $index === 1 ? 'text-gray-400' : '' }}
                                                    {{ $index === 2 ? 'text-orange-600' : '' }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mr-3">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="font-medium">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-lg font-bold text-green-600">+{{ number_format($user->net_profit) }}</span>
                                                <span class="text-sm text-gray-500"> pts</span>
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
