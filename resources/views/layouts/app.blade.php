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
    <body class="font-sans antialiased theme-bg min-h-screen">
        
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
        
        <!-- Debug: Check if Livewire is loaded -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded');
                console.log('Livewire:', typeof window.Livewire);
                if (window.Livewire) {
                    console.log('✅ Livewire is loaded');
                } else {
                    console.log('❌ Livewire is NOT loaded');
                }
            });
        </script>
        
        <!-- Theme toggle script -->
        <script>
            (function(){
                const saved = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', saved);
                updateThemeIcons(saved);
            })();
            
            function updateThemeIcons(theme) {
                const lightIcon = document.getElementById('theme-icon-light');
                const darkIcon = document.getElementById('theme-icon-dark');
                const mobileText = document.getElementById('theme-text-mobile');
                
                if (lightIcon && darkIcon) {
                    if (theme === 'light') {
                        lightIcon.style.display = 'inline';
                        darkIcon.style.display = 'none';
                    } else {
                        lightIcon.style.display = 'none';
                        darkIcon.style.display = 'inline';
                    }
                }
                
                if (mobileText) {
                    mobileText.textContent = theme === 'light' ? 'Dark' : 'Light';
                }
            }
            
            window.toggleTheme = function(){
                console.log('toggleTheme called');
                const current = document.documentElement.getAttribute('data-theme') || 'light';
                console.log('Current theme:', current);
                const next = current === 'light' ? 'dark' : 'light';
                console.log('Next theme:', next);
                document.documentElement.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
                updateThemeIcons(next);
                window.dispatchEvent(new CustomEvent('theme-change', { detail: next }));
                console.log('Theme changed to:', next);
            }
            
            function closeMobileMenu() {
                // Find mobile menu and close it - this might need adjustment based on your mobile menu implementation
                const mobileMenu = document.querySelector('[data-mobile-menu]');
                if (mobileMenu) {
                    mobileMenu.style.display = 'none';
                }
            }
            
            // Delete confirmation modal
            function confirmDelete(postId) {
                if (confirm('🗑️ Are you sure you want to delete this post?\n\nThis action cannot be undone!')) {
                    // Call Livewire method directly
                    window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('deletePost', postId);
                }
            }
            
            // Image modal for full view
            function openImageModal(imageSrc) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4';
                modal.onclick = function() { document.body.removeChild(modal); };
                
                const img = document.createElement('img');
                img.src = imageSrc;
                img.className = 'max-w-full max-h-full rounded-lg shadow-2xl';
                img.onclick = function(e) { e.stopPropagation(); };
                
                modal.appendChild(img);
                document.body.appendChild(modal);
            }
        </script>
    </body>
</html>
