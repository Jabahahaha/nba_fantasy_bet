<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            Games - {{ $date->format('F d, Y') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            @if($games->isNotEmpty() && $games->first()->isSimulated())
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-blue-500/50 transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-blue-500/10 rounded-xl p-3">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Games</div>
                            <div class="text-4xl font-black text-white">{{ $games->count() }}</div>
                        </div>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-green-500/50 transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-green-500/10 rounded-xl p-3">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Avg Total Points</div>
                            <div class="text-4xl font-black text-white">{{ number_format($games->avg('visitor_score') + $games->avg('home_score'), 1) }}</div>
                        </div>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-purple-500/50 transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-purple-500/10 rounded-xl p-3">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Highest Score</div>
                            <div class="text-4xl font-black text-white">{{ $games->max('visitor_score') > $games->max('home_score') ? $games->max('visitor_score') : $games->max('home_score') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Games List -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-xl rounded-xl">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-orange-600/20 to-red-600/20">
                    <h3 class="text-2xl font-black text-white flex items-center gap-3">
                        <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Game Scores
                    </h3>
                    <p class="text-sm text-gray-400 mt-2">Live scores and results for {{ $date->format('F d, Y') }}</p>
                </div>

                <div class="p-6">
                    @if($games->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-700 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-400 text-lg font-bold">No games scheduled for this date.</p>
                            <p class="text-gray-500 text-sm mt-2">Check back later or select a different date</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($games as $game)
                                <div class="border rounded-xl p-6 transition {{ $game->isSimulated() ? 'bg-gray-900 border-green-500/30 hover:border-green-500/50' : 'bg-gray-900 border-gray-600 hover:border-gray-500' }}">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <!-- Visitor Team -->
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                        <span class="text-white font-black text-sm">{{ substr($game->visitor_team, 0, 3) }}</span>
                                                    </div>
                                                    <span class="font-black text-xl text-white">{{ $game->visitor_team }}</span>
                                                </div>
                                                @if($game->visitor_score !== null)
                                                    <span class="text-3xl font-black {{ $game->winner === $game->visitor_team ? 'accent-green' : 'text-gray-500' }}">
                                                        {{ $game->visitor_score }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-600 text-2xl font-bold">-</span>
                                                @endif
                                            </div>

                                            <!-- Home Team -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                                        <span class="text-white font-black text-sm">{{ substr($game->home_team, 0, 3) }}</span>
                                                    </div>
                                                    <span class="font-black text-xl text-white">{{ $game->home_team }}</span>
                                                </div>
                                                @if($game->home_score !== null)
                                                    <span class="text-3xl font-black {{ $game->winner === $game->home_team ? 'accent-green' : 'text-gray-500' }}">
                                                        {{ $game->home_score }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-600 text-2xl font-bold">-</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="ml-8 text-right">
                                            @if($game->isSimulated())
                                                <span class="inline-block text-xs bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-2 rounded-lg font-black uppercase tracking-wider mb-2">
                                                    FINAL
                                                </span>
                                                <p class="text-xs text-gray-500 font-bold">
                                                    {{ $game->simulated_at->diffForHumans() }}
                                                </p>
                                            @else
                                                <span class="inline-block text-xs bg-gray-700 border border-gray-600 text-gray-300 px-4 py-2 rounded-lg font-black mb-2">
                                                    {{ $game->start_time->format('g:i A') }}
                                                </span>
                                            @endif

                                            @if($game->arena)
                                                <p class="text-xs text-gray-500 mt-1 flex items-center justify-end gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $game->arena }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($game->winner)
                                        <div class="mt-4 pt-4 border-t border-gray-700">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                                </svg>
                                                <p class="text-sm text-gray-300">
                                                    <span class="font-black accent-green">{{ $game->winner }}</span>
                                                    <span class="text-gray-400">wins by</span>
                                                    <span class="font-black text-white">{{ abs($game->visitor_score - $game->home_score) }}</span>
                                                    <span class="text-gray-400">points</span>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
