<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200" x-data="{ activeTab: 'roster' }">
                <nav class="-mb-px flex space-x-8">
                    <button
                        @click="activeTab = 'roster'"
                        :class="activeTab === 'roster' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Update Roster
                    </button>
                    <button
                        @click="activeTab = 'schedule'"
                        :class="activeTab === 'schedule' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Update Schedule
                    </button>
                </nav>

                <!-- Update Roster Tab -->
                <div x-show="activeTab === 'roster'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Update Player Roster</h3>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-blue-900 mb-2">CSV Format Required:</h4>
                            <p class="text-sm text-blue-800 mb-2">The CSV file should match the format of <code class="bg-blue-100 px-1 rounded">nba_player_stats_cleaned.csv</code></p>
                            <p class="text-xs text-blue-700">Required columns: Player, Team, Pos, PTS/G_2, TRB, AST, STL, BLK, TOV, MP, PlayerID</p>
                            <p class="text-xs text-blue-700 mt-2">Players will be matched by name and updated with new stats and team assignments.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.roster.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select CSV File
                                </label>
                                <input
                                    type="file"
                                    name="csv_file"
                                    accept=".csv"
                                    required
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>

                            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg font-bold hover:bg-blue-700">
                                Update Roster
                            </button>
                        </form>

                        @if(session('roster_output'))
                            <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                                <h4 class="font-semibold mb-2">Update Results:</h4>
                                <pre class="text-sm overflow-x-auto">{{ session('roster_output') }}</pre>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Update Schedule Tab -->
                <div x-show="activeTab === 'schedule'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Update Game Schedule</h3>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-green-900 mb-2">CSV Format Required:</h4>
                            <p class="text-sm text-green-800 mb-2">The CSV file should match the format of <code class="bg-green-100 px-1 rounded">nba_calendar_cleaned.csv</code></p>
                            <p class="text-xs text-green-700">Required columns: Date, Start (ET), Visitor/Neutral, Home/Neutral, Arena, Notes</p>
                            <p class="text-xs text-green-700 mt-2">New games will be added, existing games will be updated based on date and teams.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.schedule.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select CSV File
                                </label>
                                <input
                                    type="file"
                                    name="csv_file"
                                    accept=".csv"
                                    required
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-green-500 focus:border-green-500"
                                >
                            </div>

                            <button type="submit" class="w-full bg-green-500 text-white px-4 py-3 rounded-lg font-bold hover:bg-green-700">
                                Update Schedule
                            </button>
                        </form>

                        @if(session('schedule_output'))
                            <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                                <h4 class="font-semibold mb-2">Update Results:</h4>
                                <pre class="text-sm overflow-x-auto">{{ session('schedule_output') }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
