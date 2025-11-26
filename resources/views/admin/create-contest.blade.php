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
                    <form method="POST" action="{{ route('admin.contests.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contest Name</label>
                            <input type="text" name="name" required class="w-full rounded-md border-gray-300" placeholder="Evening Showdown">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contest Type</label>
                            <select name="contest_type" required class="w-full rounded-md border-gray-300">
                                <option value="50-50">50/50</option>
                                <option value="GPP">GPP (Guaranteed Prize Pool)</option>
                                <option value="H2H">Head-to-Head</option>
                            </select>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Entry Fee (points)</label>
                                <input type="number" name="entry_fee" required min="1" class="w-full rounded-md border-gray-300" placeholder="100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Total Entries</label>
                                <input type="number" name="max_entries" required min="2" class="w-full rounded-md border-gray-300" placeholder="100">
                                <p class="text-xs text-gray-500 mt-1">Total number of entries allowed in contest</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Entries Per User</label>
                            <input type="number" name="max_entries_per_user" required min="1" max="150" value="150" class="w-full rounded-md border-gray-300" placeholder="150">
                            <p class="text-xs text-gray-500 mt-1">Maximum number of lineups each user can enter (1-150)</p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contest Date</label>
                                <input type="date" name="contest_date" required class="w-full rounded-md border-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lock Time</label>
                                <input type="datetime-local" name="lock_time" required class="w-full rounded-md border-gray-300">
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
