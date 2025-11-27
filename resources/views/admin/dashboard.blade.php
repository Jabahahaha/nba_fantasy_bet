<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-white flex items-center gap-3">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Admin Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Actions -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.contests.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-xl font-black hover:from-blue-700 hover:to-blue-800 transition shadow-lg shadow-blue-600/20 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Contest
                </a>
                <a href="{{ route('admin.games.index') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-black hover:from-purple-700 hover:to-purple-800 transition shadow-lg shadow-purple-600/20 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Manage Games
                </a>
                <a href="{{ route('admin.update.data') }}" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl font-black hover:from-green-700 hover:to-green-800 transition shadow-lg shadow-green-600/20 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Update Data
                </a>
                <a href="{{ route('admin.rosters.index') }}" class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-4 rounded-xl font-black hover:from-orange-700 hover:to-orange-800 transition shadow-lg shadow-orange-600/20 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Roster Manager
                </a>
            </div>

            <!-- Contests -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-blue-600/10 to-purple-600/10">
                    <h3 class="text-2xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        All Contests
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Entries</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($contests as $contest)
                                <tr class="hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-white">{{ $contest->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg
                                            {{ $contest->status === 'upcoming' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/30' : '' }}
                                            {{ $contest->status === 'live' ? 'bg-orange-500/10 text-orange-400 border border-orange-500/30' : '' }}
                                            {{ $contest->status === 'completed' ? 'bg-green-500/10 text-green-400 border border-green-500/30' : '' }}
                                            {{ $contest->status === 'cancelled' ? 'bg-red-500/10 text-red-400 border border-red-500/30' : '' }}">
                                            {{ ucfirst($contest->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-white">
                                            {{ $contest->current_entries }} <span class="text-gray-500">/</span> {{ $contest->max_entries }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-300">{{ $contest->contest_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            @if($contest->status === 'cancelled')
                                                <span class="text-gray-500 text-sm font-bold">Cancelled</span>
                                            @elseif($contest->status === 'completed')
                                                <a href="{{ route('contests.show', $contest->id) }}" class="text-blue-400 hover:text-blue-300 font-bold text-sm">
                                                    View Results
                                                </a>
                                            @else
                                                <form method="POST" action="{{ route('admin.contests.simulate', $contest->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-orange-600 hover:bg-orange-500 text-white px-4 py-2 text-sm rounded-lg font-bold transition">
                                                        Simulate
                                                    </button>
                                                </form>

                                                @if($contest->canBeCancelled())
                                                    <button onclick="showCancelModal({{ $contest->id }}, '{{ $contest->name }}', {{ $contest->current_entries }}, {{ $contest->getTotalRefundAmount() }})"
                                                            class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 text-sm rounded-lg font-bold transition">
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
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-green-600/10 to-emerald-600/10">
                    <h3 class="text-2xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Top Users by Points
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Points Balance</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Contests Entered</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Total Winnings</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-black mr-3 shadow-lg">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-bold text-white">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-black text-white">{{ number_format($user->points_balance) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-300">{{ $user->total_contests_entered }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-black accent-green">{{ number_format($user->total_winnings) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Contest Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-0 border border-gray-700 w-96 shadow-2xl rounded-xl bg-gray-800 overflow-hidden">
            <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-red-600/20 to-orange-600/20">
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Cancel Contest
                </h3>
            </div>

            <div class="p-6">
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                    <p class="text-sm font-bold text-red-300 mb-3">Contest: <span id="modal-contest-name" class="text-white"></span></p>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Entries:</span>
                        <span id="modal-entries" class="font-bold text-white"></span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-400">Total Refund:</span>
                        <span id="modal-refund" class="font-black accent-green"></span>
                    </div>
                </div>

                <div class="mb-6 flex items-start gap-3 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl">
                    <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-sm text-yellow-200 font-semibold">
                        All participants will receive a full refund. This action cannot be undone.
                    </p>
                </div>

                <form id="cancelForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="cancellation_reason" value="Contest cancelled by admin">

                    <div class="flex gap-3">
                        <button type="button"
                                onclick="hideCancelModal()"
                                class="flex-1 bg-gray-700 hover:bg-gray-600 text-white px-4 py-3 rounded-xl font-bold transition border border-gray-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 bg-red-600 hover:bg-red-500 text-white px-4 py-3 rounded-xl font-black transition shadow-lg shadow-red-600/20">
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
