<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $contest->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contest Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Contest Type</p>
                            <p class="text-lg font-bold">{{ $contest->contest_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Entry Fee</p>
                            <p class="text-lg font-bold">{{ number_format($contest->entry_fee) }} pts</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Prize Pool</p>
                            <p class="text-lg font-bold">{{ number_format($contest->prize_pool) }} pts</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Entries</p>
                            <p class="text-lg font-bold">{{ $contest->current_entries }} / {{ $contest->max_entries }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Games for this Contest -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Games on {{ \Carbon\Carbon::parse($contest->contest_date)->format('F j, Y') }}</h3>

                    @php
                        $contestGames = \App\Models\Game::getGamesForDate($contest->contest_date);
                    @endphp

                    @if($contestGames->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($contestGames as $game)
                                <div class="border border-gray-200 rounded-lg p-4 {{ $game->status === 'completed' ? 'bg-green-50 border-green-200' : '' }}">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-xs font-medium px-2 py-1 rounded {{ $game->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($game->status) }}
                                        </span>
                                        <span class="text-xs text-gray-600">
                                            {{ \Carbon\Carbon::parse($game->start_time)->format('g:i A') }}
                                        </span>
                                    </div>

                                    @if($game->status === 'completed')
                                        <!-- Completed Game with Scores -->
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center {{ $game->winner === $game->visitor_team ? 'font-bold text-green-700' : 'text-gray-700' }}">
                                                <span>{{ $game->visitor_team }}</span>
                                                <span class="text-xl">{{ $game->visitor_score }}</span>
                                            </div>
                                            <div class="border-t border-gray-200"></div>
                                            <div class="flex justify-between items-center {{ $game->winner === $game->home_team ? 'font-bold text-green-700' : 'text-gray-700' }}">
                                                <span>{{ $game->home_team }}</span>
                                                <span class="text-xl">{{ $game->home_score }}</span>
                                            </div>
                                        </div>
                                        @if($game->winner)
                                            <div class="mt-3 pt-3 border-t border-green-200 text-center">
                                                <span class="text-xs font-semibold text-green-700">
                                                    Final
                                                </span>
                                                <a href="{{ route('games.show', $game->id) }}" class="block mt-2 text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                    View Stats →
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <!-- Scheduled Game -->
                                        <div class="text-center py-3">
                                            <div class="text-base font-semibold text-gray-800">
                                                {{ $game->visitor_team }}
                                            </div>
                                            <div class="text-xs text-gray-500 my-2">vs</div>
                                            <div class="text-base font-semibold text-gray-800">
                                                {{ $game->home_team }}
                                            </div>
                                        </div>
                                        @if($game->arena)
                                            <div class="mt-2 text-xs text-gray-500 text-center">
                                                {{ $game->arena }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No games scheduled for this date.</p>
                    @endif
                </div>
            </div>

            <!-- Leaderboard -->
            @if($contest->status !== 'upcoming')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Leaderboard</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lineup Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fantasy Points</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prize</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($contest->lineups as $lineup)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $lineup->final_rank ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $lineup->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('lineups.show', $lineup->id) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $lineup->lineup_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $lineup->fantasy_points_scored ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($lineup->prize_won > 0)
                                                <span class="text-green-600 font-bold">{{ number_format($lineup->prize_won) }} pts</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No entries yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
