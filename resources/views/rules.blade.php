<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scoring Rules - NBA Fantasy</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800">NBA Fantasy</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                    @auth
                        <a href="{{ route('contests.index') }}" class="text-gray-600 hover:text-gray-900">Contests</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Scoring Rules & How to Play</h1>

        <!-- Fantasy Scoring -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Fantasy Scoring System</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="flex justify-between p-3 bg-green-50 rounded">
                    <span class="font-medium">Points</span>
                    <span class="font-bold text-green-600">+1.0 per point</span>
                </div>
                <div class="flex justify-between p-3 bg-green-50 rounded">
                    <span class="font-medium">Rebounds</span>
                    <span class="font-bold text-green-600">+1.25 per rebound</span>
                </div>
                <div class="flex justify-between p-3 bg-green-50 rounded">
                    <span class="font-medium">Assists</span>
                    <span class="font-bold text-green-600">+1.5 per assist</span>
                </div>
                <div class="flex justify-between p-3 bg-green-50 rounded">
                    <span class="font-medium">Steals</span>
                    <span class="font-bold text-green-600">+2.0 per steal</span>
                </div>
                <div class="flex justify-between p-3 bg-green-50 rounded">
                    <span class="font-medium">Blocks</span>
                    <span class="font-bold text-green-600">+2.0 per block</span>
                </div>
                <div class="flex justify-between p-3 bg-red-50 rounded">
                    <span class="font-medium">Turnovers</span>
                    <span class="font-bold text-red-600">-0.5 per turnover</span>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-bold text-blue-900 mb-2">Bonus Points</h3>
                <ul class="space-y-2 text-blue-800">
                    <li>✨ <strong>Double-Double</strong>: +1.5 points (10+ in two stat categories)</li>
                    <li>🌟 <strong>Triple-Double</strong>: +3.0 points (10+ in three stat categories)</li>
                </ul>
            </div>
        </div>

        <!-- Position Requirements -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Lineup Requirements</h2>
            <div class="space-y-3">
                <p class="text-gray-700">You must select exactly <strong>8 players</strong> filling these positions:</p>
                <div class="grid md:grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 rounded">
                        <strong>PG</strong> - Point Guard only
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <strong>SG</strong> - Shooting Guard only
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <strong>SF</strong> - Small Forward only
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <strong>PF</strong> - Power Forward only
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <strong>C</strong> - Center only
                    </div>
                    <div class="p-3 bg-blue-50 rounded">
                        <strong>G</strong> - Any Guard (PG or SG)
                    </div>
                    <div class="p-3 bg-blue-50 rounded">
                        <strong>F</strong> - Any Forward (SF or PF)
                    </div>
                    <div class="p-3 bg-purple-50 rounded">
                        <strong>UTIL</strong> - Any Position
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 bg-orange-50 rounded-lg">
                <strong class="text-orange-900">Salary Cap:</strong>
                <p class="text-orange-800 mt-1">Your 8 players must have a combined salary of <strong>$50,000 or less</strong></p>
            </div>
        </div>

        <!-- Contest Types -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Contest Types</h2>

            <div class="space-y-4">
                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="text-lg font-bold text-gray-900">50/50 Contests</h3>
                    <p class="text-gray-700">Top 50% of entries win. Lower risk, steady returns. Win approximately 2x your entry fee.</p>
                </div>

                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="text-lg font-bold text-gray-900">GPP (Guaranteed Prize Pool)</h3>
                    <p class="text-gray-700">Top-heavy payout. 1st place wins 20%, 2nd wins 10%, etc. High risk, big rewards!</p>
                </div>

                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-bold text-gray-900">Head-to-Head (H2H)</h3>
                    <p class="text-gray-700">Face off against one opponent. Winner takes all. Pure competition!</p>
                </div>
            </div>
        </div>

        <!-- How to Play -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">How to Play</h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-900">Register & Get Points</h3>
                        <p class="text-gray-700">Sign up and receive 1,000 points to start playing. No real money needed!</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">2</div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-900">Choose a Contest</h3>
                        <p class="text-gray-700">Browse available contests and select one that fits your entry fee budget.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">3</div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-900">Build Your Lineup</h3>
                        <p class="text-gray-700">Pick 8 players staying under the $50,000 salary cap. Mix superstars with value picks!</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">4</div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-900">Enter & Wait for Results</h3>
                        <p class="text-gray-700">Submit your lineup and wait for the contest to lock. Games are simulated based on player averages.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold">5</div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-900">Win Points!</h3>
                        <p class="text-gray-700">Top finishers win points based on the contest payout structure. Use winnings to enter more contests!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strategy Tips -->
        <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg shadow-sm p-6 text-white">
            <h2 class="text-2xl font-bold mb-4">Pro Tips</h2>
            <ul class="space-y-2">
                <li>✓ <strong>Balance your lineup</strong> - Don't spend all your budget on 2-3 stars</li>
                <li>✓ <strong>Target high-volume players</strong> - More minutes = more opportunity</li>
                <li>✓ <strong>Value rebounds and assists</strong> - They add up fast with the multipliers</li>
                <li>✓ <strong>Watch for defensive stats</strong> - Steals and blocks are worth 2x points!</li>
                <li>✓ <strong>Consider contest type</strong> - GPPs reward risky picks, 50/50s favor safe plays</li>
            </ul>
        </div>

        <div class="mt-8 text-center">
            @auth
                <a href="{{ route('contests.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg inline-block">
                    Start Playing Now
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg inline-block">
                    Sign Up & Get 1,000 Points Free
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
