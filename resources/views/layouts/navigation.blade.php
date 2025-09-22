<nav class="glass border-b border-coffee-200/20 sticky top-0 z-50 backdrop-blur-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 hover-lift">
                        <div class="w-10 h-10 bg-gradient-to-br from-coffee-500 to-coffee-600 rounded-xl flex items-center justify-center shadow-lg animate-pulse-soft">
                            <span class="text-cream-50 font-bold text-xl">N</span>
                        </div>
                        <span class="text-xl font-bold text-gradient">Neera</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-coffee-700 hover:text-coffee-800 transition-all duration-300 hover:scale-105 {{ request()->routeIs('dashboard') ? 'border-b-2 border-coffee-500' : '' }}">
                        🏠 Home
                    </a>
                    
                    <a href="{{ route('stories.index') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-coffee-700 hover:text-coffee-800 transition-all duration-300 hover:scale-105 {{ request()->routeIs('stories.*') ? 'border-b-2 border-coffee-500' : '' }}">
                        📸 Stories
                    </a>
                    
                    <a href="{{ route('friends.index') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-coffee-700 hover:text-coffee-800 transition-all duration-300 hover:scale-105 {{ request()->routeIs('friends.index') ? 'border-b-2 border-coffee-500' : '' }}">
                        👥 Friends
                    </a>

                    <a href="{{ route('chat.index') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-coffee-700 hover:text-coffee-800 transition-all duration-300 hover:scale-105 {{ request()->routeIs('chat.*') ? 'border-b-2 border-coffee-500' : '' }}">
                        💬 Chat
                    </a>
                </div>
            </div>

            <!-- Theme Toggle + Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- Theme toggle (desktop) -->
                <button type="button"
                        onclick="window.toggleTheme()"
                        id="theme-toggle-desktop"
                        aria-label="Toggle theme"
                        class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-cream-200/60 text-coffee-700 hover:bg-cream-200 transition-all duration-300 hover:scale-105 shadow-sm border border-coffee-200/30">
                    <span id="theme-icon-light">🌙</span>
                    <span id="theme-icon-dark" style="display: none;">🌞</span>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 btn-cream text-coffee-700 hover:text-coffee-800 focus:outline-none transition-all duration-300 hover:scale-105">
                            <div class="flex items-center space-x-2">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ Storage::url(auth()->user()->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-8 h-8 rounded-full object-cover border-2 border-coffee-300">
                                @else
                                    <div class="w-8 h-8 bg-coffee-400 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('user.profile', auth()->id())" 
                                       class="flex items-center space-x-2">
                            <span>👤</span>
                            <span>{{ __('My Profile') }}</span>
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('chat.index')" 
                                       class="flex items-center space-x-2">
                            <span>💬</span>
                            <span>{{ __('Chat') }}</span>
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('stories.index')" 
                                       class="flex items-center space-x-2">
                            <span>📸</span>
                            <span>{{ __('Stories') }}</span>
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('friends.index')" 
                                       class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span>👥</span>
                                <span>{{ __('Friends') }}</span>
                            </div>
                            @php
                                $pendingCount = App\Models\Friendship::where('friend_id', auth()->id())->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="bg-coffee-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse-soft ml-2">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('profile.edit')" 
                                       class="flex items-center space-x-2">
                            <span>⚙️</span>
                            <span>{{ __('Profile Settings') }}</span>
                        </x-dropdown-link>

                        <div class="border-t border-coffee-200/30 my-2"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center space-x-2 text-red-600 hover:bg-red-50 hover:text-red-700">
                                <span>🚪</span>
                                <span>{{ __('Log Out') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-coffee-500 hover:text-coffee-700 hover:bg-cream-100 focus:outline-none focus:bg-cream-100 focus:text-coffee-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden glass border-t border-coffee-200/20">
        <!-- Responsive Navigation Links -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                   class="text-coffee-700 hover:bg-cream-100">
                🏠 Home
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stories.index')" :active="request()->routeIs('stories.*')"
                                   class="text-coffee-700 hover:bg-cream-100">
                📸 Stories
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('friends.index')" :active="request()->routeIs('friends.index')"
                                   class="text-coffee-700 hover:bg-cream-100">
                👥 Friends
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')"
                                   class="text-coffee-700 hover:bg-cream-100">
                💬 Chat
            </x-responsive-nav-link>
            <!-- Theme toggle (mobile) -->
            <div class="px-4">
                <button type="button" onclick="toggleTheme(); closeMobileMenu()"
                        id="theme-toggle-mobile"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg bg-cream-100 text-coffee-700 hover:bg-cream-200 transition border border-coffee-200/30">
                    <span>🌓 Theme</span>
                    <span id="theme-text-mobile">Dark</span>
                </button>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-coffee-200/20">
            <div class="px-4">
                <div class="font-medium text-base text-coffee-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-coffee-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('user.profile', auth()->id())" 
                                       class="text-coffee-700 hover:bg-cream-100">
                    👤 {{ __('My Profile') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('profile.edit')" 
                                       class="text-coffee-700 hover:bg-cream-100">
                    ⚙️ {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-coffee-700 hover:bg-red-100">
                        🚪 {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
