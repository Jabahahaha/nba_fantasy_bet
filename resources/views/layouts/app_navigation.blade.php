<nav x-data="{ open: false }" class="bg-black border-b border-gray-800 sticky top-0 z-50 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center mr-10">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <span class="text-2xl font-black text-white">NBA</span>
                        <span class="text-2xl font-black accent-green">FANTASY</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 lg:flex">
                    <a href="{{ route('contests.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('contests.index') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }} transition">
                        Lobby
                    </a>
                    <a href="{{ route('lineups.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('lineups.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }} transition">
                        My Lineups
                    </a>
                    <a href="{{ route('contests.history') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('contests.history') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }} transition">
                        History
                    </a>
                    <a href="{{ route('transactions.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('transactions.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }} transition">
                        Transactions
                    </a>
                    <a href="{{ route('leaderboards.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('leaderboards.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white hover:bg-gray-800' }} transition">
                        Leaderboards
                    </a>
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-bold rounded-lg {{ request()->routeIs('admin.*') ? 'bg-orange-600 text-white' : 'bg-orange-600/80 text-white hover:bg-orange-600' }} transition">
                            Admin
                        </a>
                    @endif
                </div>
            </div>

            <!-- Points Balance & Settings -->
            <div class="hidden lg:flex lg:items-center lg:space-x-4">
                <!-- Points Balance -->
                <div class="flex items-center space-x-2 px-4 py-2 bg-accent-green/10 border border-green-500/30 rounded-lg">
                    <svg class="w-5 h-5 accent-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold accent-green">{{ number_format(Auth::user()->points_balance) }}</span>
                    <span class="text-xs text-green-400">pts</span>
                </div>

                <!-- User Dropdown -->
                <x-breeze.dropdown align="right" width="48" contentClasses="py-0 bg-gray-800 border border-gray-700">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg text-gray-300 hover:text-white hover:bg-gray-800 transition">
                            <div class="mr-2">{{ Auth::user()->name }}</div>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm font-bold text-gray-300 hover:bg-gray-700 hover:text-white rounded-t-lg transition">
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-3 text-sm font-bold text-red-400 hover:bg-gray-700 hover:text-red-300 rounded-b-lg transition">
                                Log Out
                            </button>
                        </form>
                    </x-slot>
                </x-breeze.dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex items-center lg:hidden">
                <!-- Points on Mobile -->
                <div class="mr-4 px-3 py-1.5 bg-accent-green/10 border border-green-500/30 rounded-lg">
                    <span class="text-sm font-bold accent-green">{{ number_format(Auth::user()->points_balance) }}</span>
                </div>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-gray-800">
        <div class="px-4 pt-4 pb-3 space-y-2 bg-gray-900">
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-base font-semibold rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                Dashboard
            </a>
            <a href="{{ route('contests.index') }}" class="block px-4 py-3 text-base font-semibold rounded-lg {{ request()->routeIs('contests.index') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                Lobby
            </a>
            <a href="{{ route('lineups.index') }}" class="block px-4 py-3 text-base font-semibold rounded-lg {{ request()->routeIs('lineups.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                My Lineups
            </a>
            <a href="{{ route('contests.history') }}" class="block px-4 py-3 text-base font-semibold rounded-lg {{ request()->routeIs('contests.history') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                History
            </a>
            <a href="{{ route('transactions.index') }}" class="block px-4 py-3 text-base font-semibold rounded-lg {{ request()->routeIs('transactions.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                Transactions
            </a>
            <a href="{{ route('leaderboards.index') }}" class="block px-4 py-3 text-base font-semibold rounded-lg {{ request()->routeIs('leaderboards.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                Leaderboards
            </a>
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-base font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition">
                    Admin Panel
                </a>
            @endif
        </div>

        <!-- Mobile User Section -->
        <div class="px-4 py-4 border-t border-gray-800 bg-gray-900">
            <div class="mb-3">
                <div class="font-bold text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-semibold rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition">
                    Profile Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm font-semibold rounded-lg text-red-400 hover:bg-gray-800 hover:text-red-300 transition">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
