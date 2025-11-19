<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Actions -->
            <div class="mb-6 flex space-x-4">
                <a href="{{ route('admin.contests.create') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700">
                    Create Contest
                </a>
                <a href="{{ route('admin.games.index') }}" class="bg-purple-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-purple-700">
                    Manage Games
                </a>
                <a href="{{ route('admin.import.players') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700">
                    Import Players
                </a>
            </div>

            <!-- Contests -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">All Contests</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entries</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($contests as $contest)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contest->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            {{ $contest->status === 'upcoming' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $contest->status === 'live' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $contest->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ $contest->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contest->current_entries }} / {{ $contest->max_entries }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contest->contest_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($contest->status !== 'completed')
                                            <form method="POST" action="{{ route('admin.contests.simulate', $contest->id) }}">
                                                @csrf
                                                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-700">
                                                    Simulate
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('contests.show', $contest->id) }}" class="text-blue-600 hover:text-blue-900">View Results</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Users -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Top Users by Points</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points Balance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contests Entered</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Winnings</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">{{ number_format($user->points_balance) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->total_contests_entered }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-600">{{ number_format($user->total_winnings) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
