<section>
    <header class="mb-6">
        <h2 class="text-2xl font-black text-white flex items-center gap-3">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-gray-300 mb-2">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-purple-500 focus:ring-purple-500 px-4 py-3"
                   autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-gray-300 mb-2">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password"
                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-purple-500 focus:ring-purple-500 px-4 py-3"
                   autocomplete="new-password" />
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-gray-300 mb-2">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-purple-500 focus:ring-purple-500 px-4 py-3"
                   autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-xl font-black transition shadow-lg shadow-purple-500/20">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-green-400 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
