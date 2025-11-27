<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet"/>

    <!-- Tailwind CSS & Alpine.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.1/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .accent-green { color: #00C853; }
        .bg-accent-green { background-color: #00C853; }
        .hover\:bg-accent-green-dark:hover { background-color: #00A843; }
        .bg-card-dark { background-color: #1a1d29; }
        .border-accent { border-color: #00C853; }
        .bg-dark-900 { background-color: #0a0d14; }
        .bg-dark-800 { background-color: #12151f; }
        .bg-dark-700 { background-color: #1a1d29; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-900 text-gray-100">
<div class="min-h-screen bg-gray-900">
    @include('layouts.app_navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-gray-800 shadow-lg border-b border-gray-700">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-900/50 border-l-4 border-green-500 text-green-200 px-6 py-4 rounded-lg shadow-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-red-900/50 border-l-4 border-red-500 text-red-200 px-6 py-4 rounded-lg shadow-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
</body>
</html>
