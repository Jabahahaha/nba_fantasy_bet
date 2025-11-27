<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8">
                    <h3 class="text-3xl font-black text-white mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <div class="flex items-center space-x-3 text-white/90">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xl">Your balance: <span class="font-black text-2xl">{{ number_format(Auth::user()->points_balance) }}</span> points</span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Lineups -->
                <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden hover:border-green-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400 mb-1">Total Lineups</p>
                                <p class="text-4xl font-black text-white">{{ Auth::user()->lineups()->count() }}</p>
                            </div>
                            <div class="bg-blue-500/10 rounded-xl p-4">
                                <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Contests -->
                <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden hover:border-green-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400 mb-1">Active Contests</p>
                                <p class="text-4xl font-black text-white">{{ \App\Models\Contest::where('status', 'upcoming')->count() }}</p>
                            </div>
                            <div class="bg-green-500/10 rounded-xl p-4">
                                <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Winnings -->
                <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden hover:border-green-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400 mb-1">Total Winnings</p>
                                <p class="text-4xl font-black accent-green">{{ number_format(Auth::user()->transactions()->where('type', 'prize')->sum('amount')) }}</p>
                            </div>
                            <div class="bg-yellow-500/10 rounded-xl p-4">
                                <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Contests -->
            <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-black text-white">Available Contests</h3>
                        <a href="{{ route('contests.index') }}" class="px-4 py-2 bg-accent-green text-black rounded-lg hover:bg-green-600 text-sm font-bold transition">
                            View All →
                        </a>
                    </div>

                    @php
                        $upcomingContests = \App\Models\Contest::where('status', 'upcoming')
                            ->where('lock_time', '>', now())
                            ->orderBy('lock_time', 'asc')
                            ->limit(4)
                            ->get();
                    @endphp

                    @if($upcomingContests->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($upcomingContests as $contest)
                                <div class="bg-gray-900 border border-gray-700 rounded-xl p-5 hover:border-green-500/50 transition group">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-white text-lg mb-1 group-hover:text-green-400 transition">{{ $contest->name }}</h4>
                                            <div class="flex items-center space-x-3 text-sm">
                                                <span class="px-2 py-1 bg-blue-500/10 text-blue-400 rounded text-xs font-semibold">{{ $contest->contest_type }}</span>
                                                <span class="text-gray-400">{{ $contest->current_entries }}/{{ $contest->max_entries }} entries</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-gray-800 rounded-lg p-3">
                                            <p class="text-xs text-gray-400 mb-1">Entry Fee</p>
                                            <p class="text-lg font-bold text-white">{{ number_format($contest->entry_fee) }} pts</p>
                                        </div>
                                        <div class="bg-gray-800 rounded-lg p-3">
                                            <p class="text-xs text-gray-400 mb-1">Prize Pool</p>
                                            <p class="text-lg font-bold accent-green">{{ number_format($contest->prize_pool) }} pts</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Locks {{ \Carbon\Carbon::parse($contest->lock_time)->diffForHumans() }}</span>
                                        <a href="{{ route('lineups.create', $contest->id) }}" class="px-4 py-2 bg-accent-green text-black rounded-lg hover:bg-green-600 text-sm font-bold transition">
                                            Enter
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-400 text-lg font-medium">No contests available at the moment.</p>
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.contests.create') }}" class="mt-4 inline-block px-6 py-3 bg-accent-green text-black rounded-lg hover:bg-green-600 font-bold transition">
                                    Create a Contest
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Today's Games -->
                <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-black text-white mb-4">Today's NBA Games</h3>

                        @php
                            $todaysGames = \App\Models\Game::getGamesForDate(now());
                        @endphp

                        @if($todaysGames->count() > 0)
                            <div class="space-y-3">
                                @foreach($todaysGames->take(4) as $game)
                                    <div class="bg-gray-900 border border-gray-700 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-semibold px-2 py-1 rounded {{ $game->status === 'completed' ? 'bg-green-500/10 text-green-400' : 'bg-blue-500/10 text-blue-400' }}">
                                                {{ ucfirst($game->status) }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($game->start_time)->format('g:i A') }}
                                            </span>
                                        </div>

                                        @if($game->status === 'completed')
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center {{ $game->winner === $game->visitor_team ? 'text-white font-bold' : 'text-gray-400' }}">
                                                    <span>{{ $game->visitor_team }}</span>
                                                    <span class="text-xl">{{ $game->visitor_score }}</span>
                                                </div>
                                                <div class="flex justify-between items-center {{ $game->winner === $game->home_team ? 'text-white font-bold' : 'text-gray-400' }}">
                                                    <span>{{ $game->home_team }}</span>
                                                    <span class="text-xl">{{ $game->home_score }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center text-gray-300 font-medium">
                                                {{ $game->visitor_team }} <span class="text-gray-600">@</span> {{ $game->home_team }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>No games scheduled for today.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Lineups -->
                <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-black text-white">My Recent Lineups</h3>
                            <a href="{{ route('lineups.index') }}" class="text-green-400 hover:text-green-300 text-sm font-bold">View All →</a>
                        </div>

                        @php
                            $recentLineups = Auth::user()->lineups()->with('contest')->latest()->limit(4)->get();
                        @endphp

                        @if($recentLineups->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentLineups as $lineup)
                                    <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 hover:border-green-500/50 transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-bold text-white mb-1">{{ $lineup->contest->name }}</h4>
                                                <p class="text-sm text-gray-400">
                                                    @if($lineup->contest->status == 'completed')
                                                        Score: <span class="accent-green font-semibold">{{ number_format($lineup->fantasy_points_scored ?? 0, 1) }} pts</span>
                                                        @if($lineup->final_rank)
                                                            • Rank #{{ $lineup->final_rank }}
                                                        @endif
                                                    @else
                                                        <span class="text-blue-400">{{ ucfirst($lineup->contest->status) }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <a href="{{ route('lineups.show', $lineup->id) }}" class="text-green-400 hover:text-green-300 text-sm font-bold">
                                                View →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-gray-400 mb-3">You haven't created any lineups yet.</p>
                                <a href="{{ route('contests.index') }}" class="inline-block px-4 py-2 bg-accent-green text-black rounded-lg hover:bg-green-600 font-bold text-sm transition">
                                    Browse Contests
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
