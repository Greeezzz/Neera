<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="mb-8 animate-fade-in-up">
                <div class="card-coffee p-8 text-center hover-lift">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-coffee-400 to-coffee-500 rounded-full flex items-center justify-center animate-pulse-soft">
                            <span class="text-cream-50 text-3xl font-bold">N</span>
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-gradient mb-2">Welcome to Neera</h1>
                    <p class="text-coffee-600 text-lg">Share your thoughts, connect with friends, and enjoy meaningful conversations</p>
                </div>
            </div>

            <!-- Stories Section -->
            <div class="mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="card-coffee p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-coffee-800 dark:text-coffee-100 flex items-center">
                            <span class="mr-2">📸</span>
                            Stories
                        </h2>
                        <a href="{{ route('stories.index') }}" class="text-sm text-coffee-600 dark:text-coffee-300 hover:text-coffee-800 dark:hover:text-coffee-100">View All</a>
                    </div>
                    
                    @php
                        $user = Auth::user();
                        $followingIds = $user->following()->pluck('following_id')->push($user->id);
                        $usersWithStories = App\Models\User::whereIn('id', $followingIds)
                            ->whereHas('stories', function($query) {
                                $query->where('expires_at', '>', now());
                            })
                            ->with(['activeStories'])
                            ->get();
                    @endphp
                    
                    <div class="flex space-x-4 overflow-x-auto pb-2">
                        <!-- Add Story Button (for current user) -->
                        <div class="flex-shrink-0 text-center">
                            <a href="{{ route('stories.create') }}" class="block">
                                <div class="relative w-16 h-16 rounded-full border-2 border-dashed border-coffee-400 dark:border-coffee-500 flex items-center justify-center hover:border-coffee-500 dark:hover:border-coffee-400 transition-colors">
                                    <svg class="w-6 h-6 text-coffee-400 dark:text-coffee-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <p class="mt-2 text-xs text-coffee-600 dark:text-coffee-300 font-medium">Add Story</p>
                            </a>
                        </div>
                        
                        <!-- Users with Stories -->
                        @foreach($usersWithStories as $userWithStory)
                            <div class="flex-shrink-0 text-center">
                                <a href="{{ route('user.stories', $userWithStory) }}" class="block">
                                    <div class="relative">
                                        <!-- Avatar with story ring -->
                                        <div class="relative w-16 h-16 rounded-full overflow-hidden ring-4 ring-coffee-500 dark:ring-coffee-400 ring-offset-2 ring-offset-white dark:ring-offset-coffee-900">
                                            @if($userWithStory->avatar)
                                                <img src="{{ Storage::url($userWithStory->avatar) }}" alt="{{ $userWithStory->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-coffee-200 dark:bg-coffee-700 flex items-center justify-center">
                                                    <span class="text-coffee-600 dark:text-coffee-300 font-semibold text-lg">{{ substr($userWithStory->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Live indicator -->
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white dark:border-coffee-900 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">●</span>
                                        </div>
                                    </div>
                                    
                                    <!-- User name -->
                                    <p class="mt-2 text-xs text-coffee-600 dark:text-coffee-300 font-medium truncate max-w-16">
                                        {{ $userWithStory->name }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                        
                        @if($usersWithStories->count() === 0)
                            <div class="flex-1 text-center py-4">
                                <p class="text-coffee-500 dark:text-coffee-400 text-sm">No stories available. Be the first to share!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Posts Section (Left - Main) -->
                <div class="space-y-6">
                    <div class="animate-slide-in-left">
                        <livewire:posts />
                    </div>
                </div>

                <!-- Sidebar (Right) -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                        <div class="card-coffee p-6 hover-lift">
                            <h3 class="text-lg font-bold mb-4 flex items-center">
                                <span class="mr-2">📊</span>
                                Your Activity
                            </h3>
                            <div class="space-y-3">
                                <div class="stat-chip">
                                    <span>Posts</span>
                                    <span class="font-bold">{{ auth()->user()->posts->count() }}</span>
                                </div>
                                <div class="stat-chip">
                                    <span>Comments</span>
                                    <span class="font-bold">{{ auth()->user()->comments->count() }}</span>
                                </div>
                                <div class="stat-chip">
                                    <span>Friends</span>
                                    <span class="font-bold">{{ auth()->user()->friends()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Friend Requests -->
                    @php
                        $pendingRequests = App\Models\Friendship::where('friend_id', auth()->id())->where('status', 'pending')->with('user')->latest()->take(3)->get();
                    @endphp
                    
                    @if($pendingRequests->count() > 0)
                        <div class="animate-fade-in-up" style="animation-delay: 0.5s;">
                            <div class="card-coffee p-6 hover-lift">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-coffee-700 flex items-center">
                                        <span class="mr-2">👋</span>
                                        Friend Requests
                                    </h3>
                                    <span class="bg-coffee-500 text-white text-xs px-2 py-1 rounded-full animate-pulse-soft">
                                        {{ $pendingRequests->count() }}
                                    </span>
                                </div>
                                <div class="space-y-3">
                                    @foreach($pendingRequests as $request)
                                        <div class="flex items-center justify-between p-3 bg-cream-50 rounded-lg hover:bg-cream-100 transition-all duration-300">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-coffee-400 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-sm font-bold">{{ substr($request->user->name, 0, 1) }}</span>
                                                </div>
                                                <span class="text-coffee-700 font-medium">{{ $request->user->name }}</span>
                                            </div>
                                            <div class="flex space-x-1">
                                                <form action="{{ route('friend.accept', $request->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-coffee-500 text-white p-1 rounded-full text-xs hover:bg-coffee-600 transition-all duration-300 hover:scale-110">
                                                        ✓
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($pendingRequests->count() >= 3)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('friends.index') }}" class="text-coffee-600 hover:text-coffee-700 text-sm font-medium transition-all duration-300">
                                            View all requests →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Recent Friends -->
                    @php
                        $recentFriends = auth()->user()->friendsQuery()->latest()->take(5)->get();
                    @endphp
                    
                    @if($recentFriends->count() > 0)
                        <div class="animate-fade-in-up" style="animation-delay: 0.7s;">
                            <div class="card-coffee p-6 hover-lift">
                                <h3 class="text-lg font-bold text-coffee-700 mb-4 flex items-center">
                                    <span class="mr-2">👥</span>
                                    Recent Friends
                                </h3>
                                <div class="space-y-3">
                                    @foreach($recentFriends as $friend)
                                        <a href="{{ route('user.profile', $friend->id) }}" 
                                           class="flex items-center space-x-3 p-3 bg-cream-50 rounded-lg hover:bg-cream-100 transition-all duration-300 hover:scale-105">
                                            <div class="w-8 h-8 bg-coffee-400 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">{{ substr($friend->name, 0, 1) }}</span>
                                            </div>
                                            <span class="text-coffee-700 font-medium">{{ $friend->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('friends.index') }}" class="text-coffee-600 hover:text-coffee-700 text-sm font-medium transition-all duration-300">
                                        View all friends →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
