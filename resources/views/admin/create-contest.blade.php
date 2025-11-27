<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Contest') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Error Messages -->
                    <x-form-errors />

                    <form method="POST" action="{{ route('admin.contests.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contest Name</label>
                            <input type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-md border-gray-300 @error('name') border-red-500 @enderror" placeholder="Evening Showdown">
                            <x-input-error field="name" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contest Type</label>
                            <select name="contest_type" required class="w-full rounded-md border-gray-300 @error('contest_type') border-red-500 @enderror">
                                <option value="50-50" {{ old('contest_type') == '50-50' ? 'selected' : '' }}>50/50</option>
                                <option value="GPP" {{ old('contest_type') == 'GPP' ? 'selected' : '' }}>GPP (Guaranteed Prize Pool)</option>
                                <option value="H2H" {{ old('contest_type') == 'H2H' ? 'selected' : '' }}>Head-to-Head</option>
                            </select>
                            <x-input-error field="contest_type" />
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Entry Fee (points)</label>
                                <input type="number" name="entry_fee" required min="1" value="{{ old('entry_fee') }}" class="w-full rounded-md border-gray-300 @error('entry_fee') border-red-500 @enderror" placeholder="100">
                                <x-input-error field="entry_fee" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Total Entries</label>
                                <input type="number" name="max_entries" required min="2" value="{{ old('max_entries') }}" class="w-full rounded-md border-gray-300 @error('max_entries') border-red-500 @enderror" placeholder="100">
                                <p class="text-xs text-gray-500 mt-1">Total number of entries allowed in contest</p>
                                <x-input-error field="max_entries" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Entries Per User</label>
                            <input type="number" name="max_entries_per_user" required min="1" max="150" value="{{ old('max_entries_per_user', 150) }}" class="w-full rounded-md border-gray-300 @error('max_entries_per_user') border-red-500 @enderror" placeholder="150">
                            <p class="text-xs text-gray-500 mt-1">Maximum number of lineups each user can enter (1-150)</p>
                            <x-input-error field="max_entries_per_user" />
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contest Date</label>
                                <input type="date" name="contest_date" required value="{{ old('contest_date') }}" class="w-full rounded-md border-gray-300 @error('contest_date') border-red-500 @enderror">
                                <x-input-error field="contest_date" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lock Time</label>
                                <input type="datetime-local" name="lock_time" required value="{{ old('lock_time') }}" class="w-full rounded-md border-gray-300 @error('lock_time') border-red-500 @enderror">
                                <x-input-error field="lock_time" />
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg font-bold hover:bg-blue-700">
                            Create Contest
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
