<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Profile Header --}}
            <div class="card-coffee p-8 hover-lift mb-6 animate-fade-in-up">
                <div class="flex items-start space-x-6">
                    {{-- Profile Picture --}}
                    <div class="flex-shrink-0">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                                 alt="{{ $user->name }}'s profile picture" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-coffee-300 shadow-lg">
                        @else
                            <div class="w-24 h-24 bg-coffee-400 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-3xl font-bold text-cream-50">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Profile Info --}}
                    <div class="flex-grow">
                        <div class="flex items-center justify-between">
                            <h1 class="text-3xl font-bold text-gradient">{{ $user->name }}</h1>
                            
                            {{-- Friend Actions (only show if not current user) --}}
                            @if(auth()->id() !== $user->id)
                                @php
                                    $friendshipStatus = auth()->user()->friendshipStatusWith($user->id);
                                @endphp
                                
                                <div class="flex gap-3">
                                    @if($friendshipStatus === null)
                                        {{-- No friendship exists - show add friend button --}}
                                        <form action="{{ route('friend.request', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-coffee flex items-center space-x-2 hover:scale-105">
                                                <span>➕</span>
                                                <span>Add Friend</span>
                                            </button>
                                        </form>
                                    @elseif($friendshipStatus === 'pending')
                                        {{-- Check who sent the request --}}
                                        @php
                                            $pendingRequest = App\Models\Friendship::where('user_id', auth()->id())->where('friend_id', $user->id)->first();
                                        @endphp
                                        
                                        @if($pendingRequest)
                                            {{-- Current user sent request --}}
                                            <span class="px-4 py-2 bg-latte-200 text-coffee-700 rounded-lg font-medium">⏳ Request Sent</span>
                                        @else
                                            {{-- User sent request to current user --}}
                                            @php
                                                $incomingRequest = App\Models\Friendship::where('user_id', $user->id)->where('friend_id', auth()->id())->first();
                                            @endphp
                                            <div class="flex gap-3">
                                                <form action="{{ route('friend.accept', $incomingRequest->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-coffee px-3 py-2 text-sm hover:scale-105 flex items-center space-x-1">
                                                        <span>✓</span>
                                                        <span>Accept</span>
                                                    </button>
                                                </form>
                                                <form action="{{ route('friend.reject', $incomingRequest->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition-all duration-300 hover:scale-105 flex items-center space-x-1">
                                                        <span>✗</span>
                                                        <span>Reject</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @elseif($friendshipStatus === 'accepted')
                                        {{-- Already friends --}}
                                        <div class="flex items-center gap-3">
                                            <span class="px-4 py-2 bg-coffee-100 text-coffee-700 rounded-lg text-sm font-medium flex items-center space-x-1">
                                                <span>✅</span>
                                                <span>Friends</span>
                                            </span>
                                            <form action="{{ route('friend.remove', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition-all duration-300 hover:scale-105 flex items-center space-x-1"
                                                        onclick="return confirm('Remove friend?')">
                                                    <span>🗑️</span>
                                                    <span>Remove</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @else
                                {{-- If viewing own profile, show edit button --}}
                                <div class="flex gap-3">
                                    <a href="{{ route('profile.edit') }}" 
                                       class="btn-coffee flex items-center space-x-2 hover:scale-105">
                                        <span>⚙️</span>
                                        <span>Edit Profile</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        @if($user->bio)
                            <p class="mt-4 text-coffee-600 leading-relaxed">{{ $user->bio }}</p>
                        @endif
                        <div class="mt-4 flex items-center space-x-6 text-sm">
                            <div class="flex items-center space-x-1 text-coffee-600">
                                <span>📝</span>
                                <span class="font-medium">{{ $posts->count() }} posts</span>
                            </div>
                            <div class="flex items-center space-x-1 text-coffee-600">
                                <span>👥</span>
                                <span class="font-medium">{{ $user->friends()->count() }} friends</span>
                            </div>
                            <div class="flex items-center space-x-1 text-coffee-500">
                                <span>📅</span>
                                <span>Member since {{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User's Posts --}}
            <div class="card-coffee p-6 hover-lift animate-slide-in-left" style="animation-delay: 0.2s;">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-coffee-400 rounded-full flex items-center justify-center">
                        <span class="text-cream-50 text-lg">📝</span>
                    </div>
                    <h2 class="text-xl font-bold text-gradient">Posts by {{ $user->name }}</h2>
                </div>
                
                @if($posts->count() > 0)
                    <div class="space-y-6">
                        @foreach($posts as $post)
                            <div class="bg-cream-50 rounded-lg p-6 hover:bg-cream-100 transition-all duration-300 hover:scale-105">
                                <h3 class="font-bold text-xl text-coffee-800 mb-3">{{ $post->title }}</h3>
                                <p class="text-coffee-700 leading-relaxed mb-4">{{ Str::limit($post->content, 200) }}</p>
                                
                                @if($post->media)
                                    @php
                                        $isVideo = in_array(pathinfo($post->media, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']);
                                    @endphp
                                    <div class="mb-4">
                                        @if($isVideo)
                                            <video class="w-full max-w-md rounded-lg shadow-lg" controls>
                                                <source src="{{ asset('storage/'.$post->media) }}">
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/'.$post->media) }}" 
                                                 class="max-h-64 rounded-lg shadow-lg object-cover" 
                                             alt="Post image">
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-coffee-500">{{ $post->created_at->diffForHumans() }}</span>
                                        <div class="flex items-center space-x-1">
                                            <span class="text-coffee-600">👍</span>
                                            <span class="text-coffee-600 font-medium">{{ $post->votes->sum('vote') }} votes</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('post.detail', $post->id) }}" 
                                       class="btn-cream text-sm hover:scale-105 flex items-center space-x-1">
                                        <span>💬</span>
                                        <span>View Post</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-coffee-300 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-cream-50 text-2xl">📝</span>
                        </div>
                        <p class="text-coffee-600 text-lg">No posts yet from {{ $user->name }}</p>
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('dashboard') }}" class="btn-coffee mt-4 hover:scale-105">
                                ✍️ Create Your First Post
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
