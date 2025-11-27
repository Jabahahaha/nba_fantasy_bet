<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white">
            My Lineups
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ tab: 'upcoming' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs -->
            <div class="mb-6 flex flex-wrap gap-3">
                <button @click="tab = 'upcoming'"
                        :class="tab === 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700'"
                        class="px-6 py-3 rounded-xl font-bold transition">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Upcoming</span>
                        <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $upcomingLineups->count() }}</span>
                    </div>
                </button>
                <button @click="tab = 'live'"
                        :class="tab === 'live' ? 'bg-orange-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700'"
                        class="px-6 py-3 rounded-xl font-bold transition">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Live</span>
                        <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $liveLineups->count() }}</span>
                    </div>
                </button>
                <button @click="tab = 'completed'"
                        :class="tab === 'completed' ? 'bg-accent-green text-black' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700'"
                        class="px-6 py-3 rounded-xl font-bold transition">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Completed</span>
                        <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $completedLineups->count() }}</span>
                    </div>
                </button>
                @if($cancelledLineups->count() > 0)
                    <button @click="tab = 'cancelled'"
                            :class="tab === 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700'"
                            class="px-6 py-3 rounded-xl font-bold transition">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Cancelled</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $cancelledLineups->count() }}</span>
                        </div>
                    </button>
                @endif
            </div>

            <!-- Upcoming Lineups -->
            <div x-show="tab === 'upcoming'" class="space-y-4">
                @forelse($upcomingLineups as $lineup)
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-blue-500/50 transition group">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-black text-xl text-white group-hover:text-blue-400 transition">
                                            {{ $lineup->lineup_name ?? 'Lineup #' . $lineup->id }}
                                        </h3>
                                        @php
                                            $userEntriesForContest = $lineup->contest->getUserEntryCount(Auth::id());
                                        @endphp
                                        @if($userEntriesForContest > 1)
                                            <span class="text-xs bg-purple-500/10 text-purple-400 px-3 py-1 rounded-full font-bold border border-purple-500/30">
                                                {{ $userEntriesForContest }} entries
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-gray-300 mb-3">{{ $lineup->contest->name }}</p>
                                    <div class="flex flex-wrap items-center gap-4 text-sm">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-gray-400">Entry:</span>
                                            <span class="font-bold text-white">{{ number_format($lineup->contest->entry_fee) }} pts</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-gray-400">{{ $lineup->contest->contest_date->format('M j, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-3 flex-shrink-0">
                                    @if(!$lineup->contest->isLocked())
                                        <a href="{{ route('lineups.edit', $lineup->id) }}" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 font-bold text-center transition border border-gray-600">
                                            EDIT
                                        </a>
                                    @endif
                                    <a href="{{ route('lineups.show', $lineup->id) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 font-bold text-center transition shadow-lg shadow-blue-600/20">
                                        VIEW
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-400 text-lg font-semibold mb-2">No upcoming lineups</p>
                        <p class="text-gray-500 text-sm">Enter a contest to create your first lineup</p>
                    </div>
                @endforelse
            </div>

            <!-- Live Lineups -->
            <div x-show="tab === 'live'" class="space-y-4">
                @forelse($liveLineups as $lineup)
                    <div class="bg-gray-800 border border-orange-500/50 rounded-xl overflow-hidden hover:border-orange-500 transition group">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-black text-xl text-white group-hover:text-orange-400 transition">
                                            {{ $lineup->lineup_name ?? 'Lineup #' . $lineup->id }}
                                        </h3>
                                        <span class="flex items-center gap-2 text-xs bg-orange-500/10 text-orange-400 px-3 py-1 rounded-full font-bold border border-orange-500/30">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                            </span>
                                            LIVE
                                        </span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-300 mb-3">{{ $lineup->contest->name }}</p>
                                    <p class="text-sm text-orange-400 font-bold">Contest in progress</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('lineups.show', $lineup->id) }}" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-500 font-bold text-center transition shadow-lg shadow-orange-600/20">
                                        VIEW LIVE
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <p class="text-gray-400 text-lg font-semibold mb-2">No live lineups</p>
                        <p class="text-gray-500 text-sm">Your active lineups will appear here</p>
                    </div>
                @endforelse
            </div>

            <!-- Completed Lineups -->
            <div x-show="tab === 'completed'" class="space-y-4">
                @forelse($completedLineups as $lineup)
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-green-500/50 transition group">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-black text-xl text-white group-hover:text-green-400 transition mb-2">
                                        {{ $lineup->lineup_name ?? 'Lineup #' . $lineup->id }}
                                    </h3>
                                    <p class="text-sm font-semibold text-gray-300 mb-4">{{ $lineup->contest->name }}</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="bg-gray-900 rounded-lg p-3 border border-gray-700">
                                            <p class="text-xs text-gray-400 mb-1">Fantasy Points</p>
                                            <p class="text-2xl font-black text-white">{{ number_format($lineup->fantasy_points_scored, 1) }}</p>
                                        </div>
                                        <div class="bg-gray-900 rounded-lg p-3 border border-gray-700">
                                            <p class="text-xs text-gray-400 mb-1">Final Rank</p>
                                            <p class="text-2xl font-black text-white">{{ $lineup->final_rank }} <span class="text-sm text-gray-500">/ {{ $lineup->contest->current_entries }}</span></p>
                                        </div>
                                        @if($lineup->prize_won > 0)
                                            <div class="bg-green-500/10 rounded-lg p-3 border border-green-500/30">
                                                <p class="text-xs text-green-400 mb-1 font-bold">Prize Won</p>
                                                <p class="text-2xl font-black text-green-400">{{ number_format($lineup->prize_won) }} pts</p>
                                            </div>
                                        @else
                                            <div class="bg-gray-900 rounded-lg p-3 border border-gray-700">
                                                <p class="text-xs text-gray-400 mb-1">Prize Won</p>
                                                <p class="text-2xl font-black text-gray-500">0 pts</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('lineups.show', $lineup->id) }}" class="px-6 py-3 bg-accent-green text-black rounded-lg hover:bg-green-600 font-black text-center transition shadow-lg shadow-green-500/20">
                                        VIEW RESULTS
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-400 text-lg font-semibold mb-2">No completed lineups</p>
                        <p class="text-gray-500 text-sm">Your contest history will appear here</p>
                    </div>
                @endforelse
            </div>

            <!-- Cancelled Lineups -->
            <div x-show="tab === 'cancelled'" class="space-y-4">
                @forelse($cancelledLineups as $lineup)
                    <div class="bg-gray-800 border border-red-500/50 rounded-xl overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-black text-xl text-white">
                                            {{ $lineup->lineup_name ?? 'Lineup #' . $lineup->id }}
                                        </h3>
                                        <span class="text-xs bg-red-500/10 text-red-400 px-3 py-1 rounded-full font-bold border border-red-500/30">
                                            CANCELLED
                                        </span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-300 mb-4">{{ $lineup->contest->name }}</p>
                                    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm text-red-300 font-semibold mb-2">This contest was cancelled</p>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm text-green-400 font-bold">Refunded: {{ number_format($lineup->contest->entry_fee) }} pts</span>
                                                </div>
                                                @if($lineup->contest->cancellation_reason)
                                                    <p class="text-xs text-gray-400 mt-2"><strong class="text-gray-300">Reason:</strong> {{ $lineup->contest->cancellation_reason }}</p>
                                                @endif
                                                @if($lineup->contest->cancelled_at)
                                                    <p class="text-xs text-gray-500 mt-1">Cancelled {{ $lineup->contest->cancelled_at->diffForHumans() }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('lineups.show', $lineup->id) }}" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 font-bold text-center transition border border-gray-600">
                                        VIEW DETAILS
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-gray-400 text-lg font-semibold mb-2">No cancelled lineups</p>
                        <p class="text-gray-500 text-sm">Cancelled contests will appear here</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
