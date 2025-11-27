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
                <a href="{{ route('admin.update.data') }}" class="bg-green-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700">
                    Update Data
                </a>
                <a href="{{ route('admin.rosters.index') }}" class="bg-gray-800 text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-900">
                    Roster Manager
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
                                            {{ $contest->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $contest->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($contest->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contest->current_entries }} / {{ $contest->max_entries }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contest->contest_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            @if($contest->status === 'cancelled')
                                                <span class="text-gray-500 text-sm">Cancelled</span>
                                            @elseif($contest->status === 'completed')
                                                <a href="{{ route('contests.show', $contest->id) }}" class="text-blue-600 hover:text-blue-900">View Results</a>
                                            @else
                                                <form method="POST" action="{{ route('admin.contests.simulate', $contest->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-orange-500 text-white px-3 py-1 text-sm rounded hover:bg-orange-700">
                                                        Simulate
                                                    </button>
                                                </form>

                                                @if($contest->canBeCancelled())
                                                    <button onclick="showCancelModal({{ $contest->id }}, '{{ $contest->name }}', {{ $contest->current_entries }}, {{ $contest->getTotalRefundAmount() }})"
                                                            class="bg-red-500 text-white px-3 py-1 text-sm rounded hover:bg-red-700">
                                                        Cancel
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
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

    <!-- Cancel Contest Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Cancel Contest</h3>

                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-sm text-yellow-800 font-semibold mb-2">Contest: <span id="modal-contest-name"></span></p>
                    <p class="text-sm text-gray-700">Entries: <span id="modal-entries"></span></p>
                    <p class="text-sm text-gray-700">Total Refund: <span id="modal-refund" class="font-bold"></span> points</p>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    All participants will receive a full refund of their entry fees. This action cannot be undone.
                </p>

                <form id="cancelForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="cancellation_reason" value="Contest cancelled by admin">

                    <div class="flex gap-3">
                        <button type="button"
                                onclick="hideCancelModal()"
                                class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showCancelModal(contestId, contestName, entries, refund) {
            document.getElementById('modal-contest-name').textContent = contestName;
            document.getElementById('modal-entries').textContent = entries;
            document.getElementById('modal-refund').textContent = refund.toLocaleString();
            document.getElementById('cancelForm').action = `/admin/contests/${contestId}/cancel`;
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideCancelModal();
            }
        });
    </script>
</x-app-layout>
