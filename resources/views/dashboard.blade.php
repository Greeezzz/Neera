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

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Posts Section (Left - Main) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="animate-slide-in-left">
                        <livewire:posts />
                    </div>
                </div>

                <!-- Sidebar (Right) -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                        <div class="card-coffee p-6 hover-lift">
                            <h3 class="text-lg font-bold text-coffee-700 mb-4 flex items-center">
                                <span class="mr-2">📊</span>
                                Your Activity
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-cream-100 rounded-lg">
                                    <span class="text-coffee-600">Posts</span>
                                    <span class="font-bold text-coffee-700">{{ auth()->user()->posts->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-latte-100 rounded-lg">
                                    <span class="text-coffee-600">Comments</span>
                                    <span class="font-bold text-coffee-700">{{ auth()->user()->comments->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-cream-200 rounded-lg">
                                    <span class="text-coffee-600">Friends</span>
                                    <span class="font-bold text-coffee-700">{{ auth()->user()->friends()->count() }}</span>
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
