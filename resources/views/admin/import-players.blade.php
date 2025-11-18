<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Players') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Upload CSV File</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        CSV should have columns: name, team, position, ppg, rpg, apg, spg, bpg, topg, mpg
                    </p>

                    <form method="POST" action="{{ route('admin.import.process') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <input type="file" name="csv_file" accept=".csv" required class="w-full border rounded-md p-2">
                        </div>

                        <button type="submit" class="w-full bg-green-500 text-white px-4 py-3 rounded-lg font-bold hover:bg-green-700">
                            Import Players
                        </button>
                    </form>

                    @if(session('output'))
                        <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                            <pre class="text-sm">{{ session('output') }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
