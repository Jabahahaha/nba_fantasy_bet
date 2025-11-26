<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Lineups') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'upcoming' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs -->
            <div class="mb-6 flex space-x-4">
                <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-md">
                    Upcoming ({{ $upcomingLineups->count() }})
                </button>
                <button @click="tab = 'live'" :class="tab === 'live' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-md">
                    Live ({{ $liveLineups->count() }})
                </button>
                <button @click="tab = 'completed'" :class="tab === 'completed' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-md">
                    Completed ({{ $completedLineups->count() }})
                </button>
            </div>

            <!-- Upcoming Lineups -->
            <div x-show="tab === 'upcoming'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($upcomingLineups as $lineup)
                        <div class="border-b pb-4 mb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-lg">{{ $lineup->lineup_name ?? 'Lineup #' . $lineup->id }}</h3>
                                        @php
                                            $userEntriesForContest = $lineup->contest->getUserEntryCount(Auth::id());
                                        @endphp
                                        @if($userEntriesForContest > 1)
                                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                {{ $userEntriesForContest }} entries
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $lineup->contest->name }}</p>
                                    <p class="text-xs text-gray-500">Entry Fee: {{ number_format($lineup->contest->entry_fee) }} pts</p>
                                </div>
                                <div class="flex gap-2">
                                    @if(!$lineup->contest->isLocked())
                                        <a href="{{ route('lineups.edit', $lineup->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">
                                            Edit
                                        </a>
                                    @endif
                                    <a href="{{ route('lineups.show', $lineup->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">No upcoming lineups.</p>
                    @endforelse
                </div>
            </div>

            <!-- Live Lineups -->
            <div x-show="tab === 'live'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($liveLineups as $lineup)
                        <div class="border-b pb-4 mb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $lineup->lineup_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $lineup->contest->name }}</p>
                                    <p class="text-sm font-bold text-blue-600">IN PROGRESS</p>
                                </div>
                                <a href="{{ route('lineups.show', $lineup->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">No live lineups.</p>
                    @endforelse
                </div>
            </div>

            <!-- Completed Lineups -->
            <div x-show="tab === 'completed'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($completedLineups as $lineup)
                        <div class="border-b pb-4 mb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $lineup->lineup_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $lineup->contest->name }}</p>
                                    <div class="mt-2">
                                        <p class="text-sm">Fantasy Points: <span class="font-bold">{{ $lineup->fantasy_points_scored }}</span></p>
                                        <p class="text-sm">Rank: <span class="font-bold">{{ $lineup->final_rank }} / {{ $lineup->contest->current_entries }}</span></p>
                                        @if($lineup->prize_won > 0)
                                            <p class="text-sm text-green-600 font-bold">Won: {{ number_format($lineup->prize_won) }} pts!</p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('lineups.show', $lineup->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">No completed lineups.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
