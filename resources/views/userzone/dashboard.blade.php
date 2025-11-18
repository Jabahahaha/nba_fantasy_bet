<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-blue-100">Your current balance: <span class="font-bold text-xl">{{ number_format(Auth::user()->points_balance) }} points</span></p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Lineups -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Lineups</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ Auth::user()->lineups()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Contests -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active Contests</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Contest::where('status', 'upcoming')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Winnings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Winnings</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format(Auth::user()->transactions()->where('type', 'prize')->sum('amount')) }} pts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Games -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's NBA Games</h3>

                    @php
                        $todaysGames = \App\Models\Game::getGamesForDate(now());
                    @endphp

                    @if($todaysGames->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($todaysGames as $game)
                                <div class="border border-gray-200 rounded-lg p-4 {{ $game->status === 'completed' ? 'bg-gray-50' : '' }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-medium {{ $game->status === 'completed' ? 'text-green-600' : 'text-blue-600' }}">
                                            {{ ucfirst($game->status) }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($game->start_time)->format('g:i A') }}
                                        </span>
                                    </div>

                                    @if($game->status === 'completed')
                                        <!-- Completed Game -->
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center {{ $game->winner === $game->visitor_team ? 'font-bold' : '' }}">
                                                <span class="text-sm">{{ $game->visitor_team }}</span>
                                                <span class="text-lg">{{ $game->visitor_score }}</span>
                                            </div>
                                            <div class="flex justify-between items-center {{ $game->winner === $game->home_team ? 'font-bold' : '' }}">
                                                <span class="text-sm">{{ $game->home_team }}</span>
                                                <span class="text-lg">{{ $game->home_score }}</span>
                                            </div>
                                        </div>
                                        @if($game->winner)
                                            <div class="mt-2 text-center">
                                                <a href="{{ route('games.show', $game->id) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                    View Stats →
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <!-- Scheduled Game -->
                                        <div class="text-center py-2">
                                            <div class="text-sm font-medium text-gray-700">
                                                {{ $game->visitor_team }} <span class="text-gray-400">@</span> {{ $game->home_team }}
                                            </div>
                                            @if($game->arena)
                                                <div class="text-xs text-gray-500 mt-1">{{ $game->arena }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2">No games scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Contests -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Available Contests</h3>
                        <a href="{{ route('contests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All →</a>
                    </div>

                    @php
                        $upcomingContests = \App\Models\Contest::where('status', 'upcoming')
                            ->where('lock_time', '>', now())
                            ->orderBy('lock_time', 'asc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($upcomingContests->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingContests as $contest)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $contest->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $contest->contest_type }} • Entry: {{ $contest->entry_fee }} pts • Prize: {{ number_format($contest->prize_pool) }} pts</p>
                                            <p class="text-xs text-gray-400 mt-1">Locks: {{ \Carbon\Carbon::parse($contest->lock_time)->format('M d, Y g:i A') }}</p>
                                        </div>
                                        <a href="{{ route('lineups.create', $contest->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                            Enter
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="mt-2">No contests available at the moment.</p>
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.contests.create') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                    Create a Contest
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Lineups -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">My Recent Lineups</h3>
                        <a href="{{ route('lineups.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All →</a>
                    </div>

                    @php
                        $recentLineups = Auth::user()->lineups()->with('contest')->latest()->limit(5)->get();
                    @endphp

                    @if($recentLineups->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentLineups as $lineup)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $lineup->contest->name }}</h4>
                                            <p class="text-sm text-gray-500">
                                                @if($lineup->contest->status == 'completed')
                                                    Final Score: {{ number_format($lineup->total_points, 2) }} pts
                                                    @if($lineup->rank)
                                                        • Rank: #{{ $lineup->rank }}
                                                    @endif
                                                @else
                                                    Status: {{ ucfirst($lineup->contest->status) }}
                                                @endif
                                            </p>
                                        </div>
                                        <a href="{{ route('lineups.show', $lineup->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-2">You haven't created any lineups yet.</p>
                            <a href="{{ route('contests.index') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                Browse Contests
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
