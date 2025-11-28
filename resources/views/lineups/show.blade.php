<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-2xl text-white">
                    {{ $lineup->lineup_name }}
                </h2>
                <p class="text-gray-400 text-sm mt-1">{{ $lineup->contest->name }}</p>
            </div>
            @if($lineup->user_id === Auth::id() && !$lineup->contest->isLocked())
                <a href="{{ route('lineups.edit', $lineup->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-6 rounded-xl transition">
                    Edit Lineup
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-400 px-6 py-4 rounded-xl mb-6 font-bold">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-400 px-6 py-4 rounded-xl mb-6 font-bold">
                    ✗ {{ session('error') }}
                </div>
            @endif

            <!-- Lineup Stats -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-blue-600/20 to-purple-600/20">
                    <h3 class="text-xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Lineup Summary
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="bg-gray-900 border border-gray-700 rounded-xl p-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Total Salary</p>
                            <p class="text-3xl font-black text-white">${{ number_format($lineup->total_salary_used) }}</p>
                            <p class="text-xs text-gray-500 mt-1">of $50,000</p>
                        </div>
                        @if($lineup->total_fpts)
                            <div class="bg-gray-900 border border-gray-700 rounded-xl p-4">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Fantasy Points</p>
                                <p class="text-3xl font-black accent-green">{{ number_format($lineup->total_fpts, 1) }}</p>
                                <p class="text-xs text-gray-500 mt-1">Total FPTS</p>
                            </div>
                        @endif
                        @if($lineup->final_rank)
                            <div class="bg-gray-900 border border-gray-700 rounded-xl p-4">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Final Rank</p>
                                <p class="text-3xl font-black text-white">
                                    #{{ $lineup->final_rank }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">of {{ $lineup->contest->current_entries }} entries</p>
                            </div>
                            <div class="bg-gray-900 border border-gray-700 rounded-xl p-4">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Prize Won</p>
                                <p class="text-3xl font-black {{ $lineup->prize_won > 0 ? 'accent-green' : 'text-gray-500' }}">
                                    {{ $lineup->prize_won > 0 ? '+' : '' }}{{ number_format($lineup->prize_won) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">points</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Player Performance -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-emerald-600/20 to-teal-600/20">
                    <h3 class="text-xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Player Performance
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($lineup->lineupPlayers as $lineupPlayer)
                        <div class="bg-gray-900 border border-gray-700 rounded-xl p-5 hover:border-gray-600 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <!-- Player Info -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-black text-xs">{{ $lineupPlayer->position_slot }}</span>
                                        </div>
                                        <div>
                                            <p class="font-black text-white text-lg">{{ $lineupPlayer->player->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-bold px-2 py-0.5 bg-blue-500/10 text-blue-400 rounded">{{ $lineupPlayer->player->position }}</span>
                                                <span class="text-xs font-semibold text-gray-400">{{ $lineupPlayer->player->team }}</span>
                                                <span class="text-xs text-gray-500">•</span>
                                                <span class="text-xs font-semibold text-gray-400">${{ number_format($lineupPlayer->player->salary) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Game Stats (if available) -->
                                    @php
                                        $gameStats = null;
                                        if ($lineup->contest->status === 'completed') {
                                            $games = \App\Models\Game::where('game_date', $lineup->contest->contest_date)->get();
                                            foreach ($games as $game) {
                                                $stat = \App\Models\GamePlayerStat::where('game_id', $game->id)
                                                    ->where('player_id', $lineupPlayer->player_id)
                                                    ->first();
                                                if ($stat) {
                                                    $gameStats = $stat;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if($gameStats)
                                        <div class="grid grid-cols-7 gap-3 mt-4 bg-gray-800 rounded-lg p-3">
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-bold mb-1">PTS</p>
                                                <p class="text-lg font-black text-white">{{ $gameStats->points }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-bold mb-1">REB</p>
                                                <p class="text-lg font-black text-white">{{ $gameStats->rebounds }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-bold mb-1">AST</p>
                                                <p class="text-lg font-black text-white">{{ $gameStats->assists }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-bold mb-1">STL</p>
                                                <p class="text-lg font-black text-white">{{ $gameStats->steals }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-bold mb-1">BLK</p>
                                                <p class="text-lg font-black text-white">{{ $gameStats->blocks }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-bold mb-1">TO</p>
                                                <p class="text-lg font-black text-red-400">{{ $gameStats->turnovers }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-bold mb-1">MIN</p>
                                                <p class="text-lg font-black text-white">{{ $gameStats->minutes_played }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 bg-gray-800 border border-gray-700 rounded-lg p-3">
                                            <p class="text-sm text-gray-400">Game not yet played</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Fantasy Points (Right Side) -->
                                @if($lineupPlayer->fpts)
                                    <div class="ml-6 text-right">
                                        <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-xl px-4 py-3">
                                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">FPTS</p>
                                            <p class="text-3xl font-black accent-green">{{ number_format($lineupPlayer->fpts, 1) }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="ml-6 text-right">
                                        <div class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3">
                                            <p class="text-xs font-bold text-gray-500 uppercase mb-1">FPTS</p>
                                            <p class="text-2xl font-black text-gray-600">--</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('contests.show', $lineup->contest_id) }}"
                   class="inline-flex items-center gap-2 text-gray-400 hover:text-white font-bold transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Contest
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
