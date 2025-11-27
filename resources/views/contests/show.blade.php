<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-white">
                {{ $contest->name }}
            </h2>
            <a href="{{ route('contests.index') }}" class="text-green-400 hover:text-green-300 text-sm font-bold">
                ← Back to Lobby
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Cancelled Contest Alert -->
            @if($contest->isCancelled())
                <div class="bg-red-900/30 border-l-4 border-red-500 rounded-xl p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-red-400 mb-2">Contest Cancelled</h3>
                            <p class="text-red-200 mb-3">All entry fees have been refunded to participants.</p>
                            @if($contest->cancellation_reason)
                                <div class="bg-gray-900 border border-red-500/30 rounded-lg p-4">
                                    <p class="text-sm font-semibold text-gray-300 mb-1">Reason:</p>
                                    <p class="text-sm text-gray-400">{{ $contest->cancellation_reason }}</p>
                                </div>
                            @endif
                            @if($contest->cancelled_at)
                                <p class="text-xs text-red-400 mt-3">Cancelled {{ $contest->cancelled_at->diffForHumans() }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Main Contest Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contest Details Card -->
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-black text-white">Contest Details</h3>
                            <span class="px-4 py-2 rounded-lg text-sm font-black {{ $contest->contest_type == '50-50' ? 'bg-blue-500/10 text-blue-400' : ($contest->contest_type == 'GPP' ? 'bg-purple-500/10 text-purple-400' : 'bg-green-500/10 text-green-400') }}">
                                {{ $contest->contest_type }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-gray-900 rounded-xl p-4">
                                <p class="text-xs text-gray-400 mb-1">Entry Fee</p>
                                <p class="text-2xl font-black text-white">{{ number_format($contest->entry_fee) }}</p>
                                <p class="text-xs text-gray-500">points</p>
                            </div>

                            <div class="bg-gray-900 rounded-xl p-4">
                                <p class="text-xs text-gray-400 mb-1">Prize Pool</p>
                                <p class="text-2xl font-black accent-green">{{ number_format($contest->prize_pool) }}</p>
                                <p class="text-xs text-gray-500">points</p>
                            </div>

                            <div class="bg-gray-900 rounded-xl p-4">
                                <p class="text-xs text-gray-400 mb-1">Entries</p>
                                <p class="text-2xl font-black text-white">{{ $contest->current_entries }}</p>
                                <p class="text-xs text-gray-500">/ {{ $contest->max_entries }}</p>
                            </div>

                            <div class="bg-gray-900 rounded-xl p-4">
                                <p class="text-xs text-gray-400 mb-1">Status</p>
                                @if($contest->isLocked())
                                    <p class="text-xl font-black text-red-400">LOCKED</p>
                                    <p class="text-xs text-gray-500">No entries</p>
                                @else
                                    <p class="text-xl font-black accent-green">OPEN</p>
                                    <p class="text-xs text-gray-500">Accepting entries</p>
                                @endif
                            </div>
                        </div>

                        <!-- User Entry Status -->
                        @auth
                            @php
                                $userEntryCount = $contest->getUserEntryCount(Auth::id());
                                $remainingEntries = $contest->getUserRemainingEntries(Auth::id());
                            @endphp
                            @if($userEntryCount > 0 || !$contest->isLocked())
                                <div class="mt-6 pt-6 border-t border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-400 mb-1">Your Entries</p>
                                            <p class="text-3xl font-black text-white">{{ $userEntryCount }} <span class="text-lg text-gray-500">/ {{ $contest->max_entries_per_user }}</span></p>
                                        </div>
                                        @if(!$contest->isLocked() && $remainingEntries > 0)
                                            <a href="{{ route('lineups.create', $contest->id) }}" class="px-8 py-4 bg-accent-green text-black rounded-xl hover:bg-green-600 font-black text-lg transition shadow-lg shadow-green-500/20">
                                                ENTER CONTEST
                                                @if($userEntryCount > 0)
                                                    <div class="text-xs mt-1 opacity-80">({{ $remainingEntries }} slots left)</div>
                                                @endif
                                            </a>
                                        @elseif($userEntryCount >= $contest->max_entries_per_user)
                                            <div class="px-6 py-3 bg-gray-700 text-gray-400 rounded-xl font-bold">
                                                Max Entries Reached
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <!-- Games for this Contest -->
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                        <h3 class="text-xl font-black text-white mb-4">NBA Games - {{ \Carbon\Carbon::parse($contest->contest_date)->format('F j, Y') }}</h3>

                        @php
                            $contestGames = \App\Models\Game::getGamesForDate($contest->contest_date);
                        @endphp

                        @if($contestGames->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($contestGames as $game)
                                    <div class="bg-gray-900 border border-gray-700 rounded-xl p-4 {{ $game->status === 'completed' ? 'border-green-500/30' : '' }}">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-xs font-bold px-2 py-1 rounded {{ $game->status === 'completed' ? 'bg-green-500/10 text-green-400' : 'bg-blue-500/10 text-blue-400' }}">
                                                {{ ucfirst($game->status) }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($game->start_time)->format('g:i A') }}
                                            </span>
                                        </div>

                                        @if($game->status === 'completed')
                                            <!-- Completed Game -->
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center {{ $game->winner === $game->visitor_team ? 'text-white font-black' : 'text-gray-400' }}">
                                                    <span class="text-sm">{{ $game->visitor_team }}</span>
                                                    <span class="text-xl">{{ $game->visitor_score }}</span>
                                                </div>
                                                <div class="h-px bg-gray-700"></div>
                                                <div class="flex justify-between items-center {{ $game->winner === $game->home_team ? 'text-white font-black' : 'text-gray-400' }}">
                                                    <span class="text-sm">{{ $game->home_team }}</span>
                                                    <span class="text-xl">{{ $game->home_score }}</span>
                                                </div>
                                            </div>
                                            @if($game->winner)
                                                <div class="mt-3 text-center">
                                                    <span class="text-xs text-green-400 font-semibold">FINAL</span>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Scheduled Game -->
                                            <div class="text-center py-2">
                                                <div class="font-bold text-gray-300">
                                                    {{ $game->visitor_team }}
                                                </div>
                                                <div class="text-xs text-gray-600 my-1">@</div>
                                                <div class="font-bold text-gray-300">
                                                    {{ $game->home_team }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>No games scheduled for this date.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Leaderboard -->
                    @if($contest->status !== 'upcoming')
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                            <h3 class="text-xl font-black text-white mb-4">Leaderboard</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-700">
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Rank</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">User</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Lineup</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase">Points</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase">Prize</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @forelse($contest->lineups->sortByDesc('fantasy_points_scored')->take(20) as $lineup)
                                            <tr class="hover:bg-gray-900 transition {{ $lineup->user_id == Auth::id() ? 'bg-green-900/20' : '' }}">
                                                <td class="px-4 py-3">
                                                    <span class="font-black {{ $lineup->final_rank <= 3 ? 'text-yellow-400 text-lg' : 'text-white' }}">
                                                        {{ $lineup->final_rank ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="font-bold text-white">{{ $lineup->user->name }}</span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('lineups.show', $lineup->id) }}" class="text-green-400 hover:text-green-300 font-medium">
                                                        {{ $lineup->lineup_name ?? 'Lineup #' . $lineup->id }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <span class="font-black text-white text-lg">{{ number_format($lineup->fantasy_points_scored ?? 0, 1) }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    @if($lineup->prize_won > 0)
                                                        <span class="font-black accent-green text-lg">{{ number_format($lineup->prize_won) }}</span>
                                                    @else
                                                        <span class="text-gray-500">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">No entries yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Countdown Timer -->
                    @php
                        $secondsUntilLock = $contest->getSecondsUntilLock();
                    @endphp
                    @if($secondsUntilLock > 0 && $contest->status === 'upcoming')
                        <div class="bg-gradient-to-br from-orange-600 to-red-600 rounded-2xl p-6 text-center">
                            <p class="text-sm text-orange-100 mb-2 font-semibold">Contest Locks In</p>
                            <div
                                x-data="countdown({{ $secondsUntilLock }})"
                                x-init="start()"
                                class="text-4xl font-black text-white mb-3"
                            >
                                <span x-text="display"></span>
                            </div>
                            <p class="text-xs text-orange-200">
                                {{ $contest->lock_time->format('M j, Y g:i A') }}
                            </p>
                        </div>
                    @else
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 text-center">
                            <div class="text-red-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-400">Contest Locked</p>
                            <p class="text-lg font-black text-red-400 mt-1">No More Entries</p>
                        </div>
                    @endif

                    <!-- Prize Breakdown -->
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                        <h4 class="text-lg font-black text-white mb-4">Prize Breakdown</h4>
                        <div class="space-y-3">
                            @if($contest->contest_type === '50-50')
                                <div class="bg-gray-900 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-400">Top 50%</span>
                                        <span class="font-bold accent-green">{{ number_format($contest->entry_fee * 2) }} pts</span>
                                    </div>
                                </div>
                            @elseif($contest->contest_type === 'GPP')
                                <div class="space-y-2">
                                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-yellow-100 font-bold">1st Place</span>
                                            <span class="font-black text-white">{{ number_format($contest->prize_pool * 0.4) }} pts</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-900 rounded-lg p-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-400">2nd-5th</span>
                                            <span class="font-bold text-white">Varies</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-900 rounded-lg p-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-400">Top 20%</span>
                                            <span class="font-bold text-gray-300">Win prizes</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-900 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-400">Winner</span>
                                        <span class="font-bold accent-green">{{ number_format($contest->prize_pool) }} pts</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contest Rules -->
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                        <h4 class="text-lg font-black text-white mb-3">Contest Rules</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>8 players under $50,000 salary cap</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Max {{ $contest->max_entries_per_user }} entries per user</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Points based on real NBA stats</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Edit lineup until lock time</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function countdown(seconds) {
            return {
                remaining: Math.max(0, seconds),
                display: '',
                interval: null,

                start() {
                    if (this.remaining <= 0) {
                        this.display = 'LOCKED';
                        return;
                    }

                    this.updateDisplay();
                    this.interval = setInterval(() => {
                        if (this.remaining > 0) {
                            this.remaining--;
                            this.updateDisplay();
                        } else {
                            clearInterval(this.interval);
                            this.display = 'LOCKED';
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    }, 1000);
                },

                updateDisplay() {
                    if (this.remaining <= 0) {
                        this.display = 'LOCKED';
                        return;
                    }

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
</x-app-layout>
