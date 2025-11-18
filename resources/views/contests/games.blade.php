<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Games - {{ $date->format('F d, Y') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Game Scores</h3>

                    @if($games->isEmpty())
                        <p class="text-gray-500 text-center py-8">No games scheduled for this date.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($games as $game)
                                <div class="border rounded-lg p-4 {{ $game->isSimulated() ? 'bg-green-50' : 'bg-gray-50' }}">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <!-- Visitor Team -->
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-bold text-lg">{{ $game->visitor_team }}</span>
                                                @if($game->visitor_score !== null)
                                                    <span class="text-2xl font-bold {{ $game->winner === $game->visitor_team ? 'text-green-600' : 'text-gray-600' }}">
                                                        {{ $game->visitor_score }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </div>

                                            <!-- Home Team -->
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-lg">{{ $game->home_team }}</span>
                                                @if($game->home_score !== null)
                                                    <span class="text-2xl font-bold {{ $game->winner === $game->home_team ? 'text-green-600' : 'text-gray-600' }}">
                                                        {{ $game->home_score }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="ml-6 text-right">
                                            @if($game->isSimulated())
                                                <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                                                    FINAL
                                                </span>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $game->simulated_at->diffForHumans() }}
                                                </p>
                                            @else
                                                <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded-full">
                                                    {{ $game->start_time->format('g:i A') }}
                                                </span>
                                            @endif

                                            @if($game->arena)
                                                <p class="text-xs text-gray-500 mt-1">{{ $game->arena }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($game->winner)
                                        <div class="mt-3 pt-3 border-t">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold text-green-600">{{ $game->winner }}</span> wins by {{ abs($game->visitor_score - $game->home_score) }} points
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            @if($games->isNotEmpty() && $games->first()->isSimulated())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Quick Stats</h3>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-3xl font-bold text-blue-600">{{ $games->count() }}</p>
                                <p class="text-sm text-gray-600">Total Games</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-3xl font-bold text-green-600">{{ number_format($games->avg('visitor_score') + $games->avg('home_score'), 1) }}</p>
                                <p class="text-sm text-gray-600">Avg Total Points</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <p class="text-3xl font-bold text-purple-600">{{ $games->max('visitor_score') > $games->max('home_score') ? $games->max('visitor_score') : $games->max('home_score') }}</p>
                                <p class="text-sm text-gray-600">Highest Score</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
