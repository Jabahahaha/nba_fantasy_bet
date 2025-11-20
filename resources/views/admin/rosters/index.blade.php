<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roster Manager') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded">
                    <p class="font-bold mb-1">There was a problem with your submission:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <p class="text-sm text-gray-500 uppercase">Active Roster</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($summary['total_active']) }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <p class="text-sm text-gray-500 uppercase">Bench</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ number_format($summary['total_bench']) }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <p class="text-sm text-gray-500 uppercase">Inactive</p>
                    <p class="text-3xl font-bold text-gray-600">{{ number_format($summary['total_inactive']) }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <p class="text-sm text-gray-500 uppercase">Marked as Playing</p>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($summary['total_playing']) }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.rosters.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Team</label>
                        <select name="team" class="w-full rounded border-gray-300">
                            <option value="">All Teams</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team }}" @selected($team === $teamFilter)>{{ $team }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ $search }}" class="w-full rounded border-gray-300" placeholder="Player or team name">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                        <a href="{{ route('admin.rosters.index') }}" class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Auto rebalance -->
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.rosters.rebalance') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Team (optional)</label>
                        <select name="team" class="w-full rounded border-gray-300">
                            <option value="">All Teams</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team }}">{{ $team }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Active Slots</label>
                        <input type="number" name="top" value="10" min="1" max="15" class="w-full rounded border-gray-300">
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                            Auto-assign Active Rosters
                        </button>
                    </div>
                </form>
                <p class="text-xs text-gray-500 mt-2">Ranks by MPG. Non-playing players are marked inactive automatically.</p>
            </div>

            <!-- Players table -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">MPG</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Playing</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($players as $player)
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'bench' => 'bg-yellow-100 text-yellow-800',
                                        'inactive' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $player->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $player->position }}</div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-sm">{{ $player->team }}</td>
                                    <td class="px-4 py-3">{{ number_format($player->mpg, 1) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded {{ $statusColors[$player->roster_status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($player->roster_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $player->roster_rank ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded {{ $player->is_playing ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-600' }}">
                                            {{ $player->is_playing ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('admin.rosters.update', $player) }}" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex flex-wrap gap-2">
                                                <select name="roster_status" class="rounded border-gray-300 text-sm">
                                                    <option value="active" @selected($player->roster_status === 'active')>Active</option>
                                                    <option value="bench" @selected($player->roster_status === 'bench')>Bench</option>
                                                    <option value="inactive" @selected($player->roster_status === 'inactive')>Inactive</option>
                                                </select>
                                                <input type="number" name="roster_rank" value="{{ $player->roster_rank }}" class="w-20 rounded border-gray-300 text-sm" placeholder="Rank" min="1">
                                                <div class="flex items-center space-x-2">
                                                    <input type="hidden" name="is_playing" value="0">
                                                    <input type="checkbox" name="is_playing" value="1" @checked($player->is_playing)>
                                                    <span class="text-xs text-gray-600">Playing</span>
                                                </div>
                                            </div>
                                            <button type="submit" class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                                        No players found for this filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $players->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

