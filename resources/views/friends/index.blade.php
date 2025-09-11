<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Header --}}
            <div class="card-coffee p-6 hover-lift animate-fade-in-up">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-coffee-500 to-coffee-600 rounded-xl flex items-center justify-center">
                        <span class="text-cream-50 text-2xl">👥</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gradient">Friends & Connections</h1>
                        <p class="text-coffee-600">Manage your friendships and connect with others</p>
                    </div>
                </div>
            </div>

            {{-- Friend Requests --}}
            @if($pendingRequests->count() > 0)
                <div class="card-coffee p-6 hover-lift animate-slide-in-left">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-coffee-700 flex items-center">
                            <span class="mr-2">👋</span>
                            Friend Requests
                        </h2>
                        <span class="bg-coffee-500 text-white text-sm px-3 py-1 rounded-full animate-pulse-soft">
                            {{ $pendingRequests->count() }}
                        </span>
                    </div>
                        
                    <div class="space-y-4">
                        @foreach($pendingRequests as $request)
                            <div class="flex items-center justify-between p-4 bg-cream-50 rounded-lg hover:bg-cream-100 transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    {{-- Profile Picture --}}
                                    @if($request->user->profile_picture)
                                        <img src="{{ asset('storage/' . $request->user->profile_picture) }}" 
                                             alt="{{ $request->user->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-coffee-300">
                                    @else
                                        <div class="w-12 h-12 bg-coffee-400 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-bold text-cream-50">
                                            {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <div>
                                    <a href="{{ route('user.profile', $request->user->id) }}" 
                                       class="font-medium text-coffee-700 hover:text-coffee-800 transition-all duration-300">
                                        {{ $request->user->name }}
                                    </a>
                                    @if($request->user->bio)
                                        <p class="text-sm text-coffee-500">{{ Str::limit($request->user->bio, 50) }}</p>
                                    @endif
                                    <p class="text-xs text-coffee-400">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <form action="{{ route('friend.accept', $request->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-coffee px-4 py-2 hover:scale-105 flex items-center space-x-1">
                                        <span>✓</span>
                                        <span>Accept</span>
                                    </button>
                                </form>
                                <form action="{{ route('friend.reject', $request->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300 hover:scale-105 flex items-center space-x-1">
                                        <span>✗</span>
                                        <span>Decline</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

            {{-- Sent Requests --}}
            @if($sentRequests->count() > 0)
                <div class="card-coffee p-6 hover-lift animate-slide-in-left" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-coffee-700 flex items-center">
                            <span class="mr-2">📤</span>
                            Sent Requests
                        </h2>
                        <span class="bg-latte-200 text-coffee-700 text-sm px-3 py-1 rounded-full">
                            {{ $sentRequests->count() }}
                        </span>
                    </div>
                        
                    <div class="space-y-4">
                        @foreach($sentRequests as $request)
                            <div class="flex items-center justify-between p-4 bg-cream-50 rounded-lg hover:bg-cream-100 transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    {{-- Profile Picture --}}
                                    @if($request->friend->profile_picture)
                                        <img src="{{ asset('storage/' . $request->friend->profile_picture) }}" 
                                             alt="{{ $request->friend->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-coffee-300">
                                    @else
                                        <div class="w-12 h-12 bg-coffee-400 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-bold text-cream-50">
                                                    {{ strtoupper(substr($request->friend->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <a href="{{ route('user.profile', $request->friend->id) }}" 
                                               class="font-medium text-blue-600 hover:text-blue-800">
                                                {{ $request->friend->name }}
                                            </a>
                                            @if($request->friend->bio)
                                                <p class="text-sm text-gray-500">{{ Str::limit($request->friend->bio, 50) }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400">Sent {{ $request->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <span class="px-4 py-2 bg-latte-200 text-coffee-700 rounded-lg font-medium">⏳ Pending</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Friends List --}}
            @if($friends->count() > 0)
                <div class="card-coffee p-6 hover-lift animate-slide-in-left" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-coffee-700 flex items-center">
                            <span class="mr-2">👥</span>
                            My Friends
                        </h2>
                        <span class="bg-coffee-500 text-white text-sm px-3 py-1 rounded-full">
                            {{ $friends->count() }}
                        </span>
                    </div>
                        
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($friends as $friend)
                            <div class="flex items-center justify-between p-4 bg-cream-50 rounded-lg hover:bg-cream-100 transition-all duration-300 hover:scale-105">
                                <div class="flex items-center gap-4">
                                    {{-- Profile Picture --}}
                                    @if($friend->profile_picture)
                                        <img src="{{ asset('storage/' . $friend->profile_picture) }}" 
                                             alt="{{ $friend->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-coffee-300">
                                    @else
                                        <div class="w-12 h-12 bg-coffee-400 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-bold text-cream-50">
                                                    {{ strtoupper(substr($friend->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <a href="{{ route('user.profile', $friend->id) }}" 
                                               class="font-medium text-coffee-700 hover:text-coffee-800 transition-all duration-300">
                                                {{ $friend->name }}
                                            </a>
                                            @if($friend->bio)
                                                <p class="text-sm text-coffee-500">{{ Str::limit($friend->bio, 40) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('friend.remove', $friend->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition-all duration-300 hover:scale-105 flex items-center space-x-1"
                                                onclick="return confirm('Remove {{ $friend->name }} from friends?')">
                                            <span>🗑️</span>
                                            <span>Remove</span>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Empty State --}}
            @if($pendingRequests->count() == 0 && $sentRequests->count() == 0 && $friends->count() == 0)
                <div class="card-coffee p-8 text-center hover-lift">
                    <div class="w-20 h-20 bg-coffee-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-cream-50 text-3xl">👥</span>
                    </div>
                    <h3 class="text-xl font-bold text-coffee-700 mb-2">No Friends Yet</h3>
                    <p class="text-coffee-600 mb-4">Start connecting with people in the community!</p>
                    <a href="{{ route('dashboard') }}" class="btn-coffee hover:scale-105">
                        🏠 Back to Home
                    </a>
                </div>
                        <div class="text-6xl mb-4">👥</div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No friends yet</h3>
                        <p class="text-gray-500">Start connecting with people by visiting their profiles!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
