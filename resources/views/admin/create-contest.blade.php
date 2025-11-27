<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ __('Create New Contest') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-xl rounded-xl">
                <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-blue-600/20 to-purple-600/20">
                    <h3 class="text-xl font-black text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Contest Details
                    </h3>
                    <p class="text-sm text-gray-400 mt-2">Fill in the details below to create a new contest</p>
                </div>

                <div class="p-8">
                    <!-- Error Messages -->
                    <x-form-errors />

                    <form method="POST" action="{{ route('admin.contests.store') }}" class="space-y-6">
                        @csrf

                        <!-- Contest Name -->
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Contest Name
                            </label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-3 @error('name') border-red-500 @enderror"
                                   placeholder="Evening Showdown">
                            <x-input-error field="name" />
                        </div>

                        <!-- Contest Type -->
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                Contest Type
                            </label>
                            <select name="contest_type" required
                                    class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-purple-500 focus:ring-purple-500 px-4 py-3 @error('contest_type') border-red-500 @enderror">
                                <option value="50-50" {{ old('contest_type') == '50-50' ? 'selected' : '' }}>50/50</option>
                                <option value="GPP" {{ old('contest_type') == 'GPP' ? 'selected' : '' }}>GPP (Guaranteed Prize Pool)</option>
                                <option value="H2H" {{ old('contest_type') == 'H2H' ? 'selected' : '' }}>Head-to-Head</option>
                            </select>
                            <x-input-error field="contest_type" />
                        </div>

                        <!-- Entry Fee & Max Total Entries -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Entry Fee (points)
                                </label>
                                <input type="number" name="entry_fee" required min="1" value="{{ old('entry_fee') }}"
                                       class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-yellow-500 focus:ring-yellow-500 px-4 py-3 @error('entry_fee') border-red-500 @enderror"
                                       placeholder="100">
                                <x-input-error field="entry_fee" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Max Total Entries
                                </label>
                                <input type="number" name="max_entries" required min="2" value="{{ old('max_entries') }}"
                                       class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500 px-4 py-3 @error('max_entries') border-red-500 @enderror"
                                       placeholder="100">
                                <p class="text-xs text-gray-500 mt-1">Total number of entries allowed in contest</p>
                                <x-input-error field="max_entries" />
                            </div>
                        </div>

                        <!-- Max Entries Per User -->
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline-block mr-1 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Max Entries Per User
                            </label>
                            <input type="number" name="max_entries_per_user" required min="1" max="150" value="{{ old('max_entries_per_user', 150) }}"
                                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-cyan-500 focus:ring-cyan-500 px-4 py-3 @error('max_entries_per_user') border-red-500 @enderror"
                                   placeholder="150">
                            <p class="text-xs text-gray-500 mt-1">Maximum number of lineups each user can enter (1-150)</p>
                            <x-input-error field="max_entries_per_user" />
                        </div>

                        <!-- Contest Date & Lock Time -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Contest Date
                                </label>
                                <input type="date" name="contest_date" required value="{{ old('contest_date') }}"
                                       class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-orange-500 focus:ring-orange-500 px-4 py-3 @error('contest_date') border-red-500 @enderror">
                                <x-input-error field="contest_date" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Lock Time
                                </label>
                                <input type="datetime-local" name="lock_time" required value="{{ old('lock_time') }}"
                                       class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-red-500 focus:ring-red-500 px-4 py-3 @error('lock_time') border-red-500 @enderror">
                                <x-input-error field="lock_time" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-accent-green hover:bg-green-600 text-black px-6 py-4 rounded-xl font-black transition shadow-lg shadow-green-500/20 flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Contest
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
