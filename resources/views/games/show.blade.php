<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $game->visitor_team }} vs {{ $game->home_team }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Game Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($game->game_date)->format('F j, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($game->start_time)->format('g:i A') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-2">Final Score</p>
                            <div class="flex justify-center items-center space-x-4">
                                <div class="text-center {{ $game->winner === $game->visitor_team ? 'font-bold text-green-700' : '' }}">
                                    <p class="text-2xl">{{ $game->visitor_team }}</p>
                                    <p class="text-4xl font-bold">{{ $game->visitor_score }}</p>
                                </div>
                                <div class="text-2xl text-gray-400">-</div>
                                <div class="text-center {{ $game->winner === $game->home_team ? 'font-bold text-green-700' : '' }}">
                                    <p class="text-2xl">{{ $game->home_team }}</p>
                                    <p class="text-4xl font-bold">{{ $game->home_score }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Arena</p>
                            <p class="text-lg font-bold">{{ $game->arena ?? 'N/A' }}</p>
                            @if($game->winner)
                                <p class="text-sm text-green-600 font-semibold mt-2">Winner: {{ $game->winner }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Tables -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Visitor Team Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 {{ $game->winner === $game->visitor_team ? 'text-green-700' : '' }}">
                            {{ $game->visitor_team }} Stats
                            @if($game->winner === $game->visitor_team)
                                <span class="text-sm font-normal text-green-600">(Winner)</span>
                            @endif
                        </h3>

                        @if($visitorStats->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">PTS</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">REB</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">AST</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">STL</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">BLK</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">TO</th>
                                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">FPTS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($visitorStats as $stat)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 py-3 whitespace-nowrap font-medium">{{ $stat->player->name }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->points }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->rebounds }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->assists }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->steals }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->blocks }}</td>
                                                <td class="px-2 py-3 text-center text-red-600">{{ $stat->turnovers }}</td>
                                                <td class="px-3 py-3 text-center font-bold text-blue-600">{{ number_format($stat->fantasy_points, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-gray-100 font-bold">
                                            <td class="px-3 py-3">TOTAL</td>
                                            <td class="px-2 py-3 text-center">{{ $visitorStats->sum('points') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $visitorStats->sum('rebounds') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $visitorStats->sum('assists') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $visitorStats->sum('steals') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $visitorStats->sum('blocks') }}</td>
                                            <td class="px-2 py-3 text-center text-red-600">{{ $visitorStats->sum('turnovers') }}</td>
                                            <td class="px-3 py-3 text-center text-blue-600">{{ number_format($visitorStats->sum('fantasy_points'), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No stats available</p>
                        @endif
                    </div>
                </div>

                <!-- Home Team Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 {{ $game->winner === $game->home_team ? 'text-green-700' : '' }}">
                            {{ $game->home_team }} Stats
                            @if($game->winner === $game->home_team)
                                <span class="text-sm font-normal text-green-600">(Winner)</span>
                            @endif
                        </h3>

                        @if($homeStats->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">PTS</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">REB</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">AST</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">STL</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">BLK</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">TO</th>
                                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">FPTS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($homeStats as $stat)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 py-3 whitespace-nowrap font-medium">{{ $stat->player->name }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->points }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->rebounds }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->assists }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->steals }}</td>
                                                <td class="px-2 py-3 text-center">{{ $stat->blocks }}</td>
                                                <td class="px-2 py-3 text-center text-red-600">{{ $stat->turnovers }}</td>
                                                <td class="px-3 py-3 text-center font-bold text-blue-600">{{ number_format($stat->fantasy_points, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-gray-100 font-bold">
                                            <td class="px-3 py-3">TOTAL</td>
                                            <td class="px-2 py-3 text-center">{{ $homeStats->sum('points') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $homeStats->sum('rebounds') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $homeStats->sum('assists') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $homeStats->sum('steals') }}</td>
                                            <td class="px-2 py-3 text-center">{{ $homeStats->sum('blocks') }}</td>
                                            <td class="px-2 py-3 text-center text-red-600">{{ $homeStats->sum('turnovers') }}</td>
                                            <td class="px-3 py-3 text-center text-blue-600">{{ number_format($homeStats->sum('fantasy_points'), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No stats available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
