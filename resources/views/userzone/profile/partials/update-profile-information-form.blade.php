<section>
    <header class="mb-6">
        <h2 class="text-2xl font-black text-white flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-bold text-gray-300 mb-2">{{ __('Name') }}</label>
            <input id="name" name="name" type="text"
                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500 px-4 py-3"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-300 mb-2">{{ __('Email') }}</label>
            <input id="email" name="email" type="email"
                   class="w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500 px-4 py-3"
                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                    <p class="text-sm text-yellow-300 mb-2">
                        {{ __('Your email address is unverified.') }}
                    </p>

                    <button form="send-verification" class="text-sm font-bold text-blue-400 hover:text-blue-300 underline">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-sm text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-accent-green hover:bg-green-600 text-black px-8 py-3 rounded-xl font-black transition shadow-lg shadow-green-500/20">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
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
