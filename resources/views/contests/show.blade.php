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
                    <div class="grid md:grid-cols-5 gap-4 mb-4">
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
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            @if($contest->isLocked())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    🔒 Locked
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    ✓ Open
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- User Entry Info -->
                    @auth
                        @php
                            $userEntryCount = $contest->getUserEntryCount(Auth::id());
                            $remainingEntries = $contest->getUserRemainingEntries(Auth::id());
                        @endphp
                        @if($userEntryCount > 0 || !$contest->isLocked())
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-500">Your Entries</p>
                                        <p class="text-lg font-bold">{{ $userEntryCount }} / {{ $contest->max_entries_per_user }}</p>
                                    </div>
                                    @if(!$contest->isLocked() && $remainingEntries > 0)
                                        <div class="text-right">
                                            <a href="{{ route('lineups.create', $contest->id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded">
                                                Enter Contest ({{ $remainingEntries }} left)
                                            </a>
                                        </div>
                                    @elseif($userEntryCount >= $contest->max_entries_per_user)
                                        <div class="text-right">
                                            <span class="text-sm text-gray-500">Maximum entries reached</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endauth

                    <!-- Countdown Timer -->
                    @if(!$contest->isLocked())
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-2">Contest Locks In</p>
                                <div
                                    x-data="countdown({{ $contest->getSecondsUntilLock() }})"
                                    x-init="start()"
                                    class="text-3xl font-bold text-blue-600"
                                >
                                    <span x-text="display"></span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    Lock Time: {{ $contest->lock_time->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-500">Contest Locked</p>
                                <p class="text-lg font-semibold text-red-600">No more entries allowed</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <script>
                function countdown(seconds) {
                    return {
                        remaining: seconds,
                        display: '',
                        interval: null,

                        start() {
                            this.updateDisplay();
                            this.interval = setInterval(() => {
                                if (this.remaining > 0) {
                                    this.remaining--;
                                    this.updateDisplay();
                                } else {
                                    clearInterval(this.interval);
                                    this.display = 'LOCKED';
                                    // Reload page when contest locks
                                    setTimeout(() => window.location.reload(), 1000);
                                }
                            }, 1000);
                        },

                        updateDisplay() {
                            const days = Math.floor(this.remaining / 86400);
                            const hours = Math.floor((this.remaining % 86400) / 3600);
                            const minutes = Math.floor((this.remaining % 3600) / 60);
                            const seconds = this.remaining % 60;

                            if (days > 0) {
                                this.display = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                            } else if (hours > 0) {
                                this.display = `${hours}h ${minutes}m ${seconds}s`;
                            } else if (minutes > 0) {
                                this.display = `${minutes}m ${seconds}s`;
                            } else {
                                this.display = `${seconds}s`;
                            }
                        }
                    }
                }
            </script>

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
