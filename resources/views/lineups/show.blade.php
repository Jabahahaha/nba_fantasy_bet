<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $lineup->lineup_name }} - {{ $lineup->contest->name }}
            </h2>
            @if($lineup->user_id === Auth::id() && !$lineup->contest->isLocked())
                <a href="{{ route('lineups.edit', $lineup->id) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Lineup
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Lineup Stats -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Salary</p>
                            <p class="text-lg font-bold">${{ number_format($lineup->total_salary_used) }}</p>
                        </div>
                        @if($lineup->fantasy_points_scored)
                            <div>
                                <p class="text-sm text-gray-500">Fantasy Points</p>
                                <p class="text-lg font-bold">{{ $lineup->fantasy_points_scored }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Final Rank</p>
                                <p class="text-lg font-bold">{{ $lineup->final_rank }} / {{ $lineup->contest->current_entries }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Prize Won</p>
                                <p class="text-lg font-bold {{ $lineup->prize_won > 0 ? 'text-green-600' : '' }}">
                                    {{ number_format($lineup->prize_won) }} pts
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Player Performance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Player Performance</h3>
                    <div class="space-y-4">
                        @foreach($lineup->lineupPlayers as $lineupPlayer)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-bold text-lg">{{ $lineupPlayer->player->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $lineupPlayer->position_slot }} | {{ $lineupPlayer->player->position }} - {{ $lineupPlayer->player->team }}
                                        </p>
                                        <p class="text-sm text-gray-500">Salary: ${{ number_format($lineupPlayer->player->salary) }}</p>
                                    </div>
                                    @if($lineupPlayer->fantasy_points_earned)
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-blue-600">{{ $lineupPlayer->fantasy_points_earned }} pts</p>
                                        </div>
                                    @endif
                                </div>

                                @if($lineupPlayer->simulated_points !== null)
                                    <div class="grid grid-cols-6 gap-2 mt-3 text-sm">
                                        <div class="text-center">
                                            <p class="text-gray-500">PTS</p>
                                            <p class="font-bold">{{ $lineupPlayer->simulated_points }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-gray-500">REB</p>
                                            <p class="font-bold">{{ $lineupPlayer->simulated_rebounds }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-gray-500">AST</p>
                                            <p class="font-bold">{{ $lineupPlayer->simulated_assists }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-gray-500">STL</p>
                                            <p class="font-bold">{{ $lineupPlayer->simulated_steals }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-gray-500">BLK</p>
                                            <p class="font-bold">{{ $lineupPlayer->simulated_blocks }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-gray-500">TO</p>
                                            <p class="font-bold text-red-600">{{ $lineupPlayer->simulated_turnovers }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
