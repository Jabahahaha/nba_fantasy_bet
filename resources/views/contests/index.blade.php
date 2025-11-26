<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contest Lobby') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contest Type Descriptions -->
            @if(request('type'))
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    @if(request('type') == '50-50')
                        <h3 class="font-semibold text-blue-900 mb-2">50/50 Contests</h3>
                        <p class="text-sm text-blue-800">In a 50/50 contest, the top half of all participants win! If 100 people enter, the top 50 finishers each win double their entry fee. Perfect for consistent returns with lower risk.</p>
                    @elseif(request('type') == 'GPP')
                        <h3 class="font-semibold text-blue-900 mb-2">GPP (Guaranteed Prize Pool) Tournaments</h3>
                        <p class="text-sm text-blue-800">GPP tournaments offer massive prize pools with top-heavy payouts. Only the top 20-30% of finishers win prizes, but first place can win big! Higher risk, higher reward.</p>
                    @elseif(request('type') == 'H2H')
                        <h3 class="font-semibold text-blue-900 mb-2">Head-to-Head Contests</h3>
                        <p class="text-sm text-blue-800">Face off against one opponent. The highest scoring lineup wins and takes home approximately 90% of the combined entry fees (10% goes to platform fees). Winner takes all!</p>
                    @endif
                </div>
            @else
                <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">How Contests Work</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                        <div>
                            <span class="font-medium text-blue-600">50/50:</span> Top half wins double entry
                        </div>
                        <div>
                            <span class="font-medium text-purple-600">GPP:</span> Top 20-30% win, big prizes for top finishers
                        </div>
                        <div>
                            <span class="font-medium text-green-600">H2H:</span> 1v1 matchup, winner takes all
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="mb-6 flex space-x-4">
                <a href="{{ route('contests.index') }}" class="px-4 py-2 rounded-md {{ !request('type') ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }}">
                    All Contests
                </a>
                <a href="{{ route('contests.index', ['type' => '50-50']) }}" class="px-4 py-2 rounded-md {{ request('type') == '50-50' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }}">
                    50/50
                </a>
                <a href="{{ route('contests.index', ['type' => 'GPP']) }}" class="px-4 py-2 rounded-md {{ request('type') == 'GPP' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }}">
                    GPP
                </a>
                <a href="{{ route('contests.index', ['type' => 'H2H']) }}" class="px-4 py-2 rounded-md {{ request('type') == 'H2H' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }}">
                    Head-to-Head
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contest Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Fee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize Pool</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entries</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Starts At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($contests as $contest)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('contests.show', $contest->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                            {{ $contest->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $contest->contest_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($contest->entry_fee) }} pts
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($contest->prize_pool) }} pts
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $contest->current_entries }} / {{ $contest->max_entries }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $contest->lock_time->format('M d, Y g:i A') }}
                                        @if(!$contest->isLocked())
                                            <br>
                                            <span class="text-xs text-blue-600 font-medium">
                                                Locks in {{ $contest->lock_time->diffForHumans() }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @auth
                                            @php
                                                $userEntries = $contest->getUserEntryCount(Auth::id());
                                                $remainingEntries = $contest->getUserRemainingEntries(Auth::id());
                                            @endphp
                                            @if($contest->isOpen() && $remainingEntries > 0)
                                                <a href="{{ route('lineups.create', $contest->id) }}" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                                    Enter
                                                    @if($userEntries > 0)
                                                        <span class="text-xs">({{ $userEntries }}/{{ $contest->max_entries_per_user }})</span>
                                                    @endif
                                                </a>
                                            @elseif($contest->isOpen() && $remainingEntries == 0)
                                                <span class="text-sm text-gray-500">Max entries ({{ $userEntries }})</span>
                                            @elseif($contest->isLocked())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    🔒 Locked
                                                </span>
                                            @else
                                                <span class="text-gray-400">Full</span>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                                Login to Join
                                            </a>
                                        @endauth
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No contests available at the moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
