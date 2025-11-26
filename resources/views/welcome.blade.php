<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NBA Fantasy Bet</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-orange-500 to-purple-600">
        <!-- Navigation -->
        <nav class="bg-white/10 backdrop-blur-md border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-white">NBA Fantasy Bet</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-white hover:text-gray-200">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-white hover:text-gray-200">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-white text-purple-600 px-4 py-2 rounded-md font-semibold hover:bg-gray-100">Sign up</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-6xl font-extrabold text-white mb-6">
                    Play NBA Daily Fantasy
                </h1>
                <p class="text-2xl text-white/90 mb-12">
                    Build your dream lineup and compete for points
                </p>

                <div class="flex justify-center space-x-4 mb-16">
                    @auth
                        <a href="{{ route('contests.index') }}" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition">
                            View Contests
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition">
                            Get Started Free
                        </a>
                    @endauth
                </div>
            </div>

            <!-- How It Works -->
            <div class="grid md:grid-cols-3 gap-8 mt-20">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-8 text-center">
                    <div class="text-4xl font-bold text-white mb-4">1</div>
                    <h3 class="text-xl font-bold text-white mb-2">Pick Players</h3>
                    <p class="text-white/80">Build an 8-player lineup under $50,000 salary cap</p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-xl p-8 text-center">
                    <div class="text-4xl font-bold text-white mb-4">2</div>
                    <h3 class="text-xl font-bold text-white mb-2">Enter Contests</h3>
                    <p class="text-white/80">Join 50/50s, GPPs, or Head-to-Head matchups</p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-xl p-8 text-center">
                    <div class="text-4xl font-bold text-white mb-4">3</div>
                    <h3 class="text-xl font-bold text-white mb-2">Win Points</h3>
                    <p class="text-white/80">Climb the leaderboard and win virtual points</p>
                </div>
            </div>

            <!-- Features -->
            <div class="mt-20 bg-white/10 backdrop-blur-md rounded-xl p-12">
                <h2 class="text-3xl font-bold text-white text-center mb-8">Why Play?</h2>
                <div class="grid md:grid-cols-2 gap-6 text-white/90">
                    <div>✓ 100% Free to play - no real money required</div>
                    <div>✓ Start with 1,000 points bonus</div>
                    <div>✓ Multiple contest types</div>
                    <div>✓ Realistic NBA player stats</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
