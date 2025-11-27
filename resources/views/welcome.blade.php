<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NBA Fantasy Bet - Daily Fantasy Basketball</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #0F2027 0%, #203A43 50%, #2C5364 100%);
        }
        .accent-green { color: #00C853; }
        .bg-accent-green { background-color: #00C853; }
        .bg-card-dark { background-color: #1a1d29; }
        .border-accent { border-color: #00C853; }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <!-- Navigation -->
    <nav class="bg-black/80 backdrop-blur-lg border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-8">
                    <h1 class="text-2xl font-black tracking-tight">
                        <span class="text-white">NBA</span>
                        <span class="accent-green"> FANTASY</span>
                    </h1>
                    @auth
                        <div class="hidden md:flex space-x-6 text-sm font-medium">
                            <a href="{{ route('contests.index') }}" class="text-gray-300 hover:text-white transition">Lobby</a>
                            <a href="{{ route('lineups.index') }}" class="text-gray-300 hover:text-white transition">My Lineups</a>
                            <a href="{{ route('leaderboards.index') }}" class="text-gray-300 hover:text-white transition">Leaderboards</a>
                        </div>
                    @endauth
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-semibold text-white bg-gray-800 rounded-lg hover:bg-gray-700 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-300 hover:text-white transition">
                                Log In
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 text-sm font-bold text-black bg-accent-green rounded-lg hover:bg-green-600 transition shadow-lg shadow-green-500/20">
                                    Sign Up Free
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-gradient relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900/50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="text-center">
                <div class="inline-block px-4 py-1.5 bg-green-500/10 border border-green-500/30 rounded-full text-green-400 text-sm font-semibold mb-6">
                    🏀 100% Free • No Deposits Required
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                    Play NBA Daily<br/>
                    <span class="accent-green">Fantasy Basketball</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 mb-10 max-w-3xl mx-auto">
                    Build your dream lineup, compete in contests, and climb the leaderboard. Start with 10,000 free points.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                    @auth
                        <a href="{{ route('contests.index') }}" class="px-8 py-4 text-lg font-bold text-black bg-accent-green rounded-xl hover:bg-green-600 transition shadow-xl shadow-green-500/30">
                            View Live Contests
                        </a>
                        <a href="{{ route('lineups.create', 1) }}" class="px-8 py-4 text-lg font-bold text-white bg-gray-800 rounded-xl hover:bg-gray-700 transition">
                            Build Lineup
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 text-lg font-bold text-black bg-accent-green rounded-xl hover:bg-green-600 transition shadow-xl shadow-green-500/30">
                            Start Playing Free
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-bold text-white bg-gray-800 rounded-xl hover:bg-gray-700 transition">
                            Sign In
                        </a>
                    @endauth
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 max-w-2xl mx-auto">
                    <div class="bg-black/30 backdrop-blur-sm rounded-xl p-4 border border-gray-800">
                        <div class="text-3xl font-black accent-green">10K+</div>
                        <div class="text-sm text-gray-400 mt-1">Starting Points</div>
                    </div>
                    <div class="bg-black/30 backdrop-blur-sm rounded-xl p-4 border border-gray-800">
                        <div class="text-3xl font-black accent-green">3</div>
                        <div class="text-sm text-gray-400 mt-1">Contest Types</div>
                    </div>
                    <div class="bg-black/30 backdrop-blur-sm rounded-xl p-4 border border-gray-800">
                        <div class="text-3xl font-black accent-green">100%</div>
                        <div class="text-sm text-gray-400 mt-1">Free to Play</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-gray-900 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-white mb-4">How It Works</h2>
                <p class="text-gray-400 text-lg">Get started in 3 easy steps</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-card-dark rounded-2xl p-8 border border-gray-800 hover:border-green-500/50 transition">
                    <div class="w-16 h-16 bg-green-500/10 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl font-black accent-green">1</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Select Contest</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Choose from 50/50s, GPPs, or Head-to-Head contests. Entry fees start from just 100 points.
                    </p>
                </div>

                <div class="bg-card-dark rounded-2xl p-8 border border-gray-800 hover:border-green-500/50 transition">
                    <div class="w-16 h-16 bg-green-500/10 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl font-black accent-green">2</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Build Lineup</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Draft 8 players under a $50,000 salary cap. Use real NBA stats to make your picks.
                    </p>
                </div>

                <div class="bg-card-dark rounded-2xl p-8 border border-gray-800 hover:border-green-500/50 transition">
                    <div class="w-16 h-16 bg-green-500/10 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl font-black accent-green">3</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Win Big</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Your lineup earns points as NBA games are played. Top performers win prizes!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contest Types -->
    <div class="bg-black py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-white mb-4">Contest Types</h2>
                <p class="text-gray-400 text-lg">Find your style of play</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-black mb-3">50/50</h3>
                    <p class="text-blue-100 mb-6">Top 50% of entries win double their entry fee. Consistent returns!</p>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-sm text-blue-200 mb-1">Example Prize</div>
                        <div class="text-2xl font-bold">200 pts</div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-black mb-3">GPP</h3>
                    <p class="text-purple-100 mb-6">Top-heavy payouts with massive prizes for 1st place. Go big!</p>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-sm text-purple-200 mb-1">Example Prize</div>
                        <div class="text-2xl font-bold">10,000+ pts</div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-black mb-3">Head-to-Head</h3>
                    <p class="text-green-100 mb-6">1v1 showdown. Winner takes ~90% of combined entry fees!</p>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-sm text-green-200 mb-1">Example Prize</div>
                        <div class="text-2xl font-bold">180 pts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                Ready to Start Winning?
            </h2>
            <p class="text-xl text-green-100 mb-8">
                Join now and get 10,000 free points to enter contests immediately
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-block px-10 py-4 text-xl font-bold text-green-600 bg-white rounded-xl hover:bg-gray-100 transition shadow-2xl">
                    Create Free Account
                </a>
            @else
                <a href="{{ route('contests.index') }}" class="inline-block px-10 py-4 text-xl font-bold text-green-600 bg-white rounded-xl hover:bg-gray-100 transition shadow-2xl">
                    View Contests Now
                </a>
            @endguest
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-black py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p class="mb-2">&copy; 2025 NBA Fantasy Bet. For entertainment purposes only.</p>
                <p>All NBA team names and player statistics are used for educational purposes.</p>
            </div>
        </div>
    </div>
</body>
</html>
