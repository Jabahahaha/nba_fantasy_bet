<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Game Simulation Management
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Auto-Simulation Info -->
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-6 mb-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-blue-300 mb-2">Automatic Game Simulation</h3>
                        <div class="text-sm text-blue-200 space-y-1">
                            <p>Games are automatically simulated at their scheduled start time. The system checks every 5 minutes for games that are ready to simulate.</p>
                            <p>You can also manually simulate games below if needed.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl mb-6 flex items-center gap-3" role="alert">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl mb-6 flex items-center gap-3" role="alert">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-500/20 border border-blue-500/50 text-blue-400 px-6 py-4 rounded-xl mb-6 flex items-center gap-3" role="alert">
                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-bold">{{ session('info') }}</span>
                </div>
            @endif

            <!-- Games grouped by date -->
            @forelse($games as $date => $dateGames)
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-xl rounded-xl mb-6" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                    <!-- Date Header - Clickable -->
                    <div class="px-6 py-5 cursor-pointer hover:bg-gray-750 transition-colors bg-gradient-to-r from-purple-600/20 to-pink-600/20" @click="open = !open">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <!-- Dropdown Arrow -->
                                <svg class="w-5 h-5 text-purple-400 transition-transform duration-200"
                                     :class="{ 'rotate-90': open }"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>

                                <div>
                                    <h3 class="text-xl font-black text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                    </h3>
                                    <div class="flex gap-4 text-sm text-gray-400 mt-1 font-bold">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                            {{ $dateGames->count() }} {{ $dateGames->count() === 1 ? 'game' : 'games' }}
                                        </span>
                                        <span class="text-gray-600">•</span>
                                        <span class="flex items-center gap-1 text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $dateGames->where('status', 'completed')->count() }} simulated
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3" @click.stop>
                                <!-- Simulate All Games Button -->
                                <form action="{{ route('admin.games.simulate-date') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-6 rounded-xl text-sm transition shadow-lg shadow-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                                            @if($dateGames->every(fn($game) => $game->isSimulated())) disabled @endif>
                                        Simulate All
                                    </button>
                                </form>

                                <!-- Reset All Games Button -->
                                <form action="{{ route('admin.games.reset-date') }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to reset all games for this date? This will delete all simulation data.');">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white font-black py-3 px-6 rounded-xl text-sm transition shadow-lg shadow-red-500/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                                            @if($dateGames->every(fn($game) => !$game->isSimulated())) disabled @endif>
                                        Reset All
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Games Table - Collapsible -->
                    <div x-show="open"
                         x-collapse
                         class="border-t border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Matchup</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Score</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($dateGames as $game)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-white flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($game->start_time)->format('g:i A') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                            <span class="text-white font-black text-xs">{{ substr($game->visitor_team, 0, 3) }}</span>
                                                        </div>
                                                        <span class="text-sm font-bold text-white">{{ $game->visitor_team }}</span>
                                                    </div>
                                                    <span class="text-gray-500 font-bold">@</span>
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                                            <span class="text-white font-black text-xs">{{ substr($game->home_team, 0, 3) }}</span>
                                                        </div>
                                                        <span class="text-sm font-bold text-white">{{ $game->home_team }}</span>
                                                    </div>
                                                </div>
                                                @if($game->arena)
                                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $game->arena }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($game->isSimulated())
                                                    <div class="text-base font-black">
                                                        <span class="{{ $game->winner === $game->visitor_team ? 'accent-green' : 'text-gray-500' }}">
                                                            {{ $game->visitor_score }}
                                                        </span>
                                                        <span class="text-gray-600">-</span>
                                                        <span class="{{ $game->winner === $game->home_team ? 'accent-green' : 'text-gray-500' }}">
                                                            {{ $game->home_score }}
                                                        </span>
                                                    </div>
                                                    <a href="{{ route('games.show', $game->id) }}"
                                                       class="text-xs text-blue-400 hover:text-blue-300 font-bold flex items-center gap-1 mt-1">
                                                        View Stats
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-600 font-bold">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($game->isSimulated())
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-lg bg-green-500/20 border border-green-500/50 text-green-400 uppercase tracking-wider">
                                                        Simulated
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1 font-bold">
                                                        {{ $game->simulated_at->diffForHumans() }}
                                                    </div>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-lg bg-gray-700 border border-gray-600 text-gray-400 uppercase tracking-wider">
                                                        Scheduled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex gap-2">
                                                    @if(!$game->isSimulated())
                                                        <!-- Simulate Single Game -->
                                                        <form action="{{ route('admin.games.simulate', $game->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="text-blue-400 hover:text-blue-300 font-bold transition">
                                                                Simulate
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Reset Single Game -->
                                                        <form action="{{ route('admin.games.reset', $game->id) }}" method="POST" class="inline"
                                                              onsubmit="return confirm('Are you sure you want to reset this game? This will delete all simulation data.');">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="text-red-400 hover:text-red-300 font-bold transition">
                                                                Reset
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-xl rounded-xl">
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-700 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-400 text-lg font-bold">No games found in the database.</p>
                        <p class="text-gray-500 text-sm mt-2">Upload game schedule data to get started</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
