<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            {{ __('Update Data') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl mb-6 flex items-center gap-3" role="alert">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl mb-6 flex items-center gap-3" role="alert">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="mb-6 bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-xl" x-data="{ activeTab: '{{ session('roster_output') ? 'roster' : (session('schedule_output') ? 'schedule' : 'roster') }}' }">
                <nav class="flex border-b border-gray-700 bg-gray-900">
                    <button
                        @click="activeTab = 'roster'"
                        :class="activeTab === 'roster' ? 'bg-blue-500/20 border-b-2 border-blue-500 text-blue-400' : 'text-gray-400 hover:text-gray-300 hover:bg-gray-800'"
                        class="flex-1 py-4 px-6 font-black text-sm uppercase tracking-wider transition flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Update Roster
                    </button>
                    <button
                        @click="activeTab = 'schedule'"
                        :class="activeTab === 'schedule' ? 'bg-green-500/20 border-b-2 border-green-500 text-green-400' : 'text-gray-400 hover:text-gray-300 hover:bg-gray-800'"
                        class="flex-1 py-4 px-6 font-black text-sm uppercase tracking-wider transition flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Update Schedule
                    </button>
                </nav>

                <!-- Update Roster Tab -->
                <div x-show="activeTab === 'roster'" class="p-8">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-white flex items-center gap-3 mb-3">
                            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Update Player Roster
                        </h3>
                        <p class="text-sm text-gray-400">Upload a CSV file to update player statistics and team assignments</p>
                    </div>

                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-6 mb-6">
                        <div class="flex items-start gap-3 mb-3">
                            <svg class="w-6 h-6 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-black text-blue-300 mb-2">CSV Format Required:</h4>
                                <p class="text-sm text-blue-200 mb-2">The CSV file should match the format of <code class="bg-blue-500/20 px-2 py-1 rounded text-blue-300 font-mono text-xs">nba_player_stats_cleaned.csv</code></p>
                                <p class="text-xs text-blue-300 mb-1">Required columns: Player, Team, Pos, PTS/G_2, TRB, AST, STL, BLK, TOV, MP, PlayerID</p>
                                <p class="text-xs text-blue-300">Players will be matched by name and updated with new stats and team assignments.</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.roster.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Select CSV File
                            </label>
                            <input
                                type="file"
                                name="csv_file"
                                accept=".csv"
                                required
                                class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-blue-500/20 file:text-blue-400 hover:file:bg-blue-500/30 file:cursor-pointer"
                            >
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-xl font-black transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Update Roster
                        </button>
                    </form>

                    @if(session('roster_output'))
                        <div class="mt-6 bg-gray-900 border border-gray-700 rounded-xl overflow-hidden">
                            <div class="p-4 bg-gray-800 border-b border-gray-700">
                                <h4 class="font-black text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Update Results:
                                </h4>
                            </div>
                            <div class="p-4">
                                <pre class="text-sm text-gray-300 overflow-x-auto font-mono">{{ session('roster_output') }}</pre>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Update Schedule Tab -->
                <div x-show="activeTab === 'schedule'" class="p-8">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-white flex items-center gap-3 mb-3">
                            <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Update Game Schedule
                        </h3>
                        <p class="text-sm text-gray-400">Upload a CSV file to update the game schedule</p>
                    </div>

                    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-6 mb-6">
                        <div class="flex items-start gap-3 mb-3">
                            <svg class="w-6 h-6 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-black text-green-300 mb-2">CSV Format Required:</h4>
                                <p class="text-sm text-green-200 mb-2">The CSV file should match the format of <code class="bg-green-500/20 px-2 py-1 rounded text-green-300 font-mono text-xs">nba_calendar_cleaned.csv</code></p>
                                <p class="text-xs text-green-300 mb-1">Required columns: Date, Start (ET), Visitor/Neutral, Home/Neutral, Arena, Notes</p>
                                <p class="text-xs text-green-300">New games will be added, existing games will be updated based on date and teams.</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.schedule.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Select CSV File
                            </label>
                            <input
                                type="file"
                                name="csv_file"
                                accept=".csv"
                                required
                                class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500 px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-green-500/20 file:text-green-400 hover:file:bg-green-500/30 file:cursor-pointer"
                            >
                        </div>

                        <button type="submit" class="w-full bg-accent-green hover:bg-green-600 text-black px-6 py-4 rounded-xl font-black transition shadow-lg shadow-green-500/20 flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Update Schedule
                        </button>
                    </form>

                    @if(session('schedule_output'))
                        <div class="mt-6 bg-gray-900 border border-gray-700 rounded-xl overflow-hidden">
                            <div class="p-4 bg-gray-800 border-b border-gray-700">
                                <h4 class="font-black text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Update Results:
                                </h4>
                            </div>
                            <div class="p-4">
                                <pre class="text-sm text-gray-300 overflow-x-auto font-mono">{{ session('schedule_output') }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
