<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            {{ __('Roster Manager') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl flex items-center gap-3">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl">
                    <p class="font-black mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        There was a problem with your submission:
                    </p>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-7">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-green-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-green-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Active Roster</div>
                        <div class="text-4xl font-black text-white">{{ number_format($summary['total_active']) }}</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-yellow-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-yellow-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Bench</div>
                        <div class="text-4xl font-black text-white">{{ number_format($summary['total_bench']) }}</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-gray-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-gray-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Inactive</div>
                        <div class="text-4xl font-black text-white">{{ number_format($summary['total_inactive']) }}</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-blue-500/50 transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-blue-500/10 rounded-xl p-3">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Marked as Playing</div>
                        <div class="text-4xl font-black text-white">{{ number_format($summary['total_playing']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-blue-600/20 to-cyan-600/20">
                    <h3 class="text-xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter Players
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.rosters.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">Team</label>
                            <select name="team" class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-3">
                                <option value="">All Teams</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team }}" @selected($team === $teamFilter)>{{ $team }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-300 mb-2">Search</label>
                            <input type="text" name="search" value="{{ $search }}"
                                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-3"
                                   placeholder="Player or team name">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-black transition shadow-lg shadow-blue-500/20">
                                Filter
                            </button>
                            <a href="{{ route('admin.rosters.index') }}" class="px-6 py-3 rounded-xl border border-gray-600 text-gray-300 hover:bg-gray-700 font-bold transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Auto Rebalance -->
            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-purple-600/20 to-pink-600/20">
                    <h3 class="text-xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Auto-assign Active Rosters
                    </h3>
                    <p class="text-sm text-gray-400 mt-2">Automatically rank players by MPG and assign roster status</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.rosters.rebalance') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">Team (optional)</label>
                            <select name="team" class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-purple-500 focus:ring-purple-500 px-4 py-3">
                                <option value="">All Teams</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team }}">{{ $team }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">Active Slots</label>
                            <input type="number" name="top" value="10" min="1" max="15"
                                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-purple-500 focus:ring-purple-500 px-4 py-3">
                        </div>
                        <div class="md:col-span-2 flex items-end">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-black transition shadow-lg shadow-purple-500/20 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Auto-assign Active Rosters
                            </button>
                        </div>
                    </form>
                    <div class="mt-3 bg-purple-500/10 border border-purple-500/30 rounded-lg p-3">
                        <p class="text-xs text-purple-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ranks by MPG. Non-playing players are marked inactive automatically.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Players Table -->
            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-emerald-600/20 to-teal-600/20">
                    <h3 class="text-xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Player Roster
                    </h3>
                    <p class="text-sm text-gray-400 mt-2">Manage player roster status and rankings</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Player</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Team</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">MPG</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Playing</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse ($players as $player)
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-500/20 border-green-500/50 text-green-400',
                                        'bench' => 'bg-yellow-500/20 border-yellow-500/50 text-yellow-400',
                                        'inactive' => 'bg-gray-700 border-gray-600 text-gray-400',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-white">{{ $player->name }}</div>
                                        <div class="text-sm text-gray-400 font-bold">{{ $player->position }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-gray-700 border border-gray-600 rounded-lg text-sm font-black text-white">
                                            {{ $player->team }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-white font-bold">{{ number_format($player->mpg, 1) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-black rounded-lg border uppercase tracking-wider {{ $statusColors[$player->roster_status] ?? 'bg-gray-700 border-gray-600 text-gray-400' }}">
                                            {{ ucfirst($player->roster_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-white font-bold">{{ $player->roster_rank ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-black rounded-lg border {{ $player->is_playing ? 'bg-blue-500/20 border-blue-500/50 text-blue-400' : 'bg-gray-700 border-gray-600 text-gray-400' }}">
                                            {{ $player->is_playing ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.rosters.update', $player) }}" class="space-y-3">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex flex-wrap gap-2">
                                                <select name="roster_status" class="bg-gray-900 border-gray-600 text-white rounded-lg text-sm px-3 py-2 focus:border-emerald-500 focus:ring-emerald-500">
                                                    <option value="active" @selected($player->roster_status === 'active')>Active</option>
                                                    <option value="bench" @selected($player->roster_status === 'bench')>Bench</option>
                                                    <option value="inactive" @selected($player->roster_status === 'inactive')>Inactive</option>
                                                </select>
                                                <input type="number" name="roster_rank" value="{{ $player->roster_rank }}"
                                                       class="w-20 bg-gray-900 border-gray-600 text-white rounded-lg text-sm px-3 py-2 focus:border-emerald-500 focus:ring-emerald-500"
                                                       placeholder="Rank" min="1">
                                                <div class="flex items-center gap-2 bg-gray-900 border border-gray-600 rounded-lg px-3 py-2">
                                                    <input type="hidden" name="is_playing" value="0">
                                                    <input type="checkbox" name="is_playing" value="1" @checked($player->is_playing) class="rounded border-gray-600 text-blue-500 focus:ring-blue-500">
                                                    <span class="text-xs text-gray-400 font-bold">Playing</span>
                                                </div>
                                            </div>
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-black px-4 py-2 rounded-lg transition shadow-lg shadow-emerald-500/20">
                                                Save Changes
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-700 rounded-full mb-4">
                                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-400 text-lg font-bold">No players found for this filter.</p>
                                        <p class="text-gray-500 text-sm mt-2">Try adjusting your search criteria</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-6 border-t border-gray-700">
                        {{ $players->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
