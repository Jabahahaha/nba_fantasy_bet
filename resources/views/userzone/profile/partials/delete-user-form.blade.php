<section class="space-y-6">
    <header class="mb-6">
        <h2 class="text-2xl font-black text-white flex items-center gap-3">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-black transition shadow-lg shadow-red-500/20"
    >{{ __('Delete Account') }}</button>

    <x-breeze.modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="bg-gray-800 rounded-xl overflow-hidden">
            <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-red-600/20 to-orange-600/20">
                <h2 class="text-2xl font-black text-white flex items-center gap-3">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <p class="text-sm text-gray-300 mb-6">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold text-gray-300 mb-2">{{ __('Password') }}</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-red-500 focus:ring-red-500 px-4 py-3"
                        placeholder="{{ __('Password') }}"
                    />
                    @error('password', 'userDeletion')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-black transition shadow-lg shadow-red-500/20">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </x-breeze.modal>
</section>
