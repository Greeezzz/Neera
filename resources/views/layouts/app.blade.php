<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-cream-50 via-cream-100 to-latte-50 min-h-screen">
        
        <!-- Background decorations -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-32 h-32 bg-coffee-200 rounded-full opacity-20 animate-float"></div>
            <div class="absolute top-40 right-20 w-24 h-24 bg-cream-300 rounded-full opacity-30 animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-20 left-20 w-28 h-28 bg-latte-200 rounded-full opacity-25 animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-40 right-10 w-20 h-20 bg-coffee-300 rounded-full opacity-20 animate-float" style="animation-delay: 0.5s;"></div>
        </div>

        <div class="min-h-screen relative">
            @include('layouts.navigation')

            <!-- Page Heading -->
                @isset($header)
                    <header class="glass shadow-lg border-b border-coffee-200/20">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <div class="animate-fade-in-up">
                                {{ $header }}
                            </div>
                        </div>
                    </header>
                @endisset

            <!-- Page Content -->
            <main class="relative z-10">
                <div class="animate-fade-in-up" style="animation-delay: 0.2s;">
                    {{ $slot }}
                </div>
            </main>
        </div>
        
        @livewireScripts
        
        <!-- Alpine.js untuk animasi interaktif -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
