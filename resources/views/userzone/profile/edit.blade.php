<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profile Settings
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-xl rounded-xl">
                <div class="max-w-2xl">
                    @include('userzone.profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-xl rounded-xl">
                <div class="max-w-2xl">
                    @include('userzone.profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-xl rounded-xl">
                <div class="max-w-2xl">
                    @include('userzone.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
