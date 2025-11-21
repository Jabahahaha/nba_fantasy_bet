<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Game Simulation Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Auto-Simulation Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Automatic Game Simulation</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Games are automatically simulated at their scheduled start time. The system checks every 5 minutes for games that are ready to simulate.</p>
                            <p class="mt-1">You can also manually simulate games below if needed.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            <!-- Games grouped by date -->
            @forelse($games as $date => $dateGames)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">
                                {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                <span class="text-sm font-normal text-gray-500">
                                    ({{ $dateGames->count() }} {{ $dateGames->count() === 1 ? 'game' : 'games' }})
                                </span>
                            </h3>

                            <div class="flex gap-2">
                                <!-- Simulate All Games Button -->
                                <form action="{{ route('admin.games.simulate-date') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
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
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
                                            @if($dateGames->every(fn($game) => !$game->isSimulated())) disabled @endif>
                                        Reset All
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Games Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matchup</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($dateGames as $game)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($game->start_time)->format('g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $game->visitor_team }} @ {{ $game->home_team }}
                                                </div>
                                                @if($game->arena)
                                                    <div class="text-sm text-gray-500">{{ $game->arena }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($game->isSimulated())
                                                    <div class="text-sm font-bold">
                                                        <span class="{{ $game->winner === $game->visitor_team ? 'text-green-600' : '' }}">
                                                            {{ $game->visitor_score }}
                                                        </span>
                                                        -
                                                        <span class="{{ $game->winner === $game->home_team ? 'text-green-600' : '' }}">
                                                            {{ $game->home_score }}
                                                        </span>
                                                    </div>
                                                    <a href="{{ route('games.show', $game->id) }}"
                                                       class="text-xs text-blue-600 hover:text-blue-800">
                                                        View Stats →
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($game->isSimulated())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Simulated
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $game->simulated_at->diffForHumans() }}
                                                    </div>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
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
                                                                    class="text-blue-600 hover:text-blue-900">
                                                                Simulate
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Reset Single Game -->
                                                        <form action="{{ route('admin.games.reset', $game->id) }}" method="POST" class="inline"
                                                              onsubmit="return confirm('Are you sure you want to reset this game? This will delete all simulation data.');">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="text-red-600 hover:text-red-900">
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-500 text-center">No games found in the database.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
