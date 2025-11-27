<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            Contest Lobby
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Contest Type Info Banner -->
            @if(request('type'))
                <div class="mb-6 bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6">
                    @if(request('type') == '50-50')
                        <div class="flex items-start space-x-4">
                            <div class="bg-blue-500/10 rounded-lg p-3">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-black text-xl text-white mb-2">50/50 Contests</h3>
                                <p class="text-gray-300 leading-relaxed">Top half of all participants win double their entry fee. Perfect for consistent returns with lower risk. If 100 people enter, the top 50 finishers each win!</p>
                            </div>
                        </div>
                    @elseif(request('type') == 'GPP')
                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-500/10 rounded-lg p-3">
                                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-black text-xl text-white mb-2">GPP (Guaranteed Prize Pool)</h3>
                                <p class="text-gray-300 leading-relaxed">Massive prize pools with top-heavy payouts. Only top 20-30% win, but first place wins BIG! Higher risk, higher reward.</p>
                            </div>
                        </div>
                    @elseif(request('type') == 'H2H')
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-500/10 rounded-lg p-3">
                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-black text-xl text-white mb-2">Head-to-Head</h3>
                                <p class="text-gray-300 leading-relaxed">1v1 showdown! Highest scoring lineup wins approximately 90% of combined entry fees. Winner takes all!</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('contests.index') }}" class="px-6 py-3 rounded-xl font-bold transition {{ !request('type') ? 'bg-accent-green text-black' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700' }}">
                    All Contests
                </a>
                <a href="{{ route('contests.index', ['type' => '50-50']) }}" class="px-6 py-3 rounded-xl font-bold transition {{ request('type') == '50-50' ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700' }}">
                    50/50
                </a>
                <a href="{{ route('contests.index', ['type' => 'GPP']) }}" class="px-6 py-3 rounded-xl font-bold transition {{ request('type') == 'GPP' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700' }}">
                    GPP
                </a>
                <a href="{{ route('contests.index', ['type' => 'H2H']) }}" class="px-6 py-3 rounded-xl font-bold transition {{ request('type') == 'H2H' ? 'bg-green-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700' }}">
                    Head-to-Head
                </a>
            </div>

            <!-- Contests Grid -->
            @forelse($contests as $contest)
                <div class="mb-4 bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-green-500/50 transition group">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                            <!-- Contest Info -->
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <!-- Contest Type Badge -->
                                    <div class="flex-shrink-0">
                                        <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-black {{ $contest->contest_type == '50-50' ? 'bg-blue-500/10 text-blue-400' : ($contest->contest_type == 'GPP' ? 'bg-purple-500/10 text-purple-400' : 'bg-green-500/10 text-green-400') }}">
                                            {{ $contest->contest_type }}
                                        </span>
                                    </div>

                                    <!-- Contest Details -->
                                    <div class="flex-1">
                                        <h3 class="text-xl font-black text-white mb-2 group-hover:text-green-400 transition">
                                            <a href="{{ route('contests.show', $contest->id) }}">{{ $contest->name }}</a>
                                        </h3>

                                        <div class="flex flex-wrap items-center gap-4 text-sm">
                                            <!-- Entry Fee -->
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                </svg>
                                                <span class="text-gray-400">Entry:</span>
                                                <span class="font-bold text-white">{{ number_format($contest->entry_fee) }} pts</span>
                                            </div>

                                            <!-- Prize Pool -->
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-gray-400">Prize:</span>
                                                <span class="font-bold accent-green">{{ number_format($contest->prize_pool) }} pts</span>
                                            </div>

                                            <!-- Entries -->
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                <span class="text-gray-400">Entries:</span>
                                                <span class="font-bold text-white">{{ $contest->current_entries }}/{{ $contest->max_entries }}</span>
                                                @php
                                                    $fillPercentage = ($contest->current_entries / $contest->max_entries) * 100;
                                                @endphp
                                                @if($fillPercentage >= 80)
                                                    <span class="text-xs px-2 py-0.5 bg-red-500/10 text-red-400 rounded font-semibold">FILLING FAST</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Lock Time -->
                                        <div class="mt-2 flex items-center space-x-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Locks {{ $contest->lock_time->diffForHumans() }} • {{ $contest->lock_time->format('M d, g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="flex-shrink-0">
                                @php
                                    $userEntries = $contest->getUserEntryCount(Auth::id());
                                    $remainingEntries = $contest->getUserRemainingEntries(Auth::id());
                                @endphp

                                @if($contest->isOpen() && $remainingEntries > 0)
                                    <a href="{{ route('lineups.create', $contest->id) }}" class="block px-8 py-4 bg-accent-green text-black rounded-xl hover:bg-green-600 font-black text-center transition shadow-lg shadow-green-500/20">
                                        <div class="text-lg">ENTER</div>
                                        @if($userEntries > 0)
                                            <div class="text-xs mt-1 opacity-80">({{ $userEntries }}/{{ $contest->max_entries_per_user }} entries)</div>
                                        @endif
                                    </a>
                                @elseif($userEntries > 0 && $remainingEntries == 0)
                                    <div class="px-8 py-4 bg-gray-700 text-gray-400 rounded-xl font-bold text-center">
                                        <div>MAX ENTRIES</div>
                                        <div class="text-xs mt-1">({{ $userEntries }}/{{ $contest->max_entries_per_user }})</div>
                                    </div>
                                @else
                                    <div class="px-8 py-4 bg-gray-700 text-gray-400 rounded-xl font-bold text-center">
                                        LOCKED
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-12 text-center">
                    <svg class="mx-auto h-20 w-20 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-2xl font-black text-white mb-2">No Contests Available</h3>
                    <p class="text-gray-400 mb-6">There are no contests matching your criteria right now.</p>
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.contests.create') }}" class="inline-block px-6 py-3 bg-accent-green text-black rounded-xl hover:bg-green-600 font-bold transition">
                            Create a Contest
                        </a>
                    @endif
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
