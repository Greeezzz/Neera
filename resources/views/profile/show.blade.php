<x-app-layout>
    <div class="pb-10 min-h-screen bg-gradient-to-br from-cream-50 via-white to-latte-50 dark:from-coffee-900 dark:via-coffee-800 dark:to-coffee-900">
        <!-- Hero Cover + Avatar Section -->
        <div class="relative h-72 md:h-80 w-full overflow-hidden">
            <!-- Dynamic gradient background -->
            <div class="absolute inset-0 bg-gradient-to-br from-coffee-400 via-coffee-500 to-latte-500 dark:from-coffee-800 dark:via-coffee-700 dark:to-coffee-600"></div>
            <!-- Animated overlay -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.3),transparent_50%),radial-gradient(circle_at_80%_80%,rgba(255,255,255,0.2),transparent_50%)] dark:bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.1),transparent_50%)]"></div>
            <!-- Floating particles effect -->
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-10 left-10 w-2 h-2 bg-white rounded-full animate-pulse"></div>
                <div class="absolute top-20 right-20 w-1 h-1 bg-white rounded-full animate-ping"></div>
                <div class="absolute bottom-20 left-1/4 w-3 h-3 bg-white/50 rounded-full animate-bounce"></div>
                <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-white/40 rounded-full animate-pulse delay-75"></div>
            </div>
            
            <!-- Profile info container -->
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 md:left-24 md:translate-x-0 flex items-end gap-6 pb-6 px-4 w-full max-w-6xl mx-auto">
                <!-- Enhanced Avatar -->
                <div class="relative group">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                             class="w-36 h-36 md:w-44 md:h-44 rounded-full object-cover ring-4 ring-white shadow-2xl transition-transform duration-300 group-hover:scale-105" 
                             alt="{{ $user->name }} avatar">
                    @else
                        <div class="w-36 h-36 md:w-44 md:h-44 rounded-full bg-gradient-to-br from-coffee-300 via-coffee-400 to-coffee-500 flex items-center justify-center ring-4 ring-white shadow-2xl transition-transform duration-300 group-hover:scale-105">
                            <span class="text-5xl font-bold text-white drop-shadow-lg">{{ strtoupper(substr($user->name,0,1)) }}</span>
                        </div>
                    @endif
                    <!-- Online status indicator -->
                    <div class="absolute bottom-3 right-3 w-6 h-6 bg-green-400 rounded-full ring-4 ring-white shadow-lg"></div>
                </div>
                
                <!-- User info with enhanced styling -->
                <div class="flex-1 text-white pb-2">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl md:text-5xl font-black tracking-tight drop-shadow-lg bg-gradient-to-r from-white to-cream-100 bg-clip-text text-transparent">
                            {{ $user->name }}
                        </h1>
                        <!-- Verification badge -->
                        <div class="bg-blue-500 rounded-full p-1 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    
                    @if($user->bio)
                        <p class="mt-1 max-w-2xl text-base md:text-lg text-white/95 leading-relaxed font-medium drop-shadow">
                            {{ $user->bio }}
                        </p>
                    @endif
                    
                    <!-- Enhanced stats with modern design -->
                    <div class="mt-6 flex flex-wrap gap-3 text-sm md:text-base">
                        <div class="bg-white/20 backdrop-blur-md border border-white/30 px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/30 transition-all duration-300 cursor-pointer">
                            <span class="text-lg">📝</span>
                            <span class="font-semibold">{{ $postCount }}</span>
                            <span class="text-white/80">Posts</span>
                        </div>
                        <div class="bg-white/20 backdrop-blur-md border border-white/30 px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/30 transition-all duration-300 cursor-pointer">
                            <span class="text-lg">👥</span>
                            <span class="font-semibold">{{ $friendCount }}</span>
                            <span class="text-white/80">Friends</span>
                        </div>
                        <div class="bg-white/20 backdrop-blur-md border border-white/30 px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/30 transition-all duration-300 cursor-pointer">
                            <span class="text-lg">👀</span>
                            <span class="font-semibold">{{ $followerCount }}</span>
                            <span class="text-white/80">Followers</span>
                        </div>
                        <div class="bg-white/20 backdrop-blur-md border border-white/30 px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/30 transition-all duration-300 cursor-pointer">
                            <span class="text-lg">➡️</span>
                            <span class="font-semibold">{{ $followingCount }}</span>
                            <span class="text-white/80">Following</span>
                        </div>
                        <div class="bg-white/20 backdrop-blur-md border border-white/30 px-4 py-2 rounded-full flex items-center gap-2">
                            <span class="text-lg">📅</span>
                            <span class="text-white/80">Joined {{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Actions Bar -->
        <div class="bg-white/80 backdrop-blur-xl dark:bg-coffee-800/90 border-b border-coffee-100 dark:border-coffee-600 shadow-lg">
            <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row md:items-center gap-4 py-6">
                <div class="flex-1 flex flex-wrap gap-4 order-2 md:order-1">
                    @if(!$isOwn)
                        @if($isFollowing)
                            <form action="{{ route('follow.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="group relative bg-gradient-to-r from-coffee-500 to-coffee-600 hover:from-coffee-600 hover:to-coffee-700 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                    <span class="text-lg">👤</span>
                                    Unfollow
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-white/0 to-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('follow.store', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="group relative bg-gradient-to-r from-coffee-500 to-coffee-600 hover:from-coffee-600 hover:to-coffee-700 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                    <span class="text-lg">➕</span>
                                    Follow
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-white/0 to-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </button>
                            </form>
                        @endif
                        
                        @php $friendshipStatus = auth()->user()->friendshipStatusWith($user->id); @endphp
                        @if($friendshipStatus === null)
                            <form action="{{ route('friend.request', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="group relative bg-gradient-to-r from-latte-400 to-latte-500 hover:from-latte-500 hover:to-latte-600 text-coffee-800 font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                    <span class="text-lg">🤝</span>
                                    Add Friend
                                </button>
                            </form>
                        @elseif($friendshipStatus === 'pending')
                            <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-800 font-semibold px-6 py-3 rounded-full shadow-lg flex items-center gap-2">
                                <span class="text-lg animate-pulse">⏳</span>
                                Pending
                            </span>
                        @elseif($friendshipStatus === 'accepted')
                            <span class="bg-gradient-to-r from-green-400 to-green-500 text-green-800 font-semibold px-6 py-3 rounded-full shadow-lg flex items-center gap-2">
                                <span class="text-lg">✅</span>
                                Friends
                            </span>
                        @endif
                        
                        <a href="{{ route('chat.show', $user) }}" class="group relative bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                            <span class="text-lg">💬</span>
                            Chat
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}" class="group relative bg-gradient-to-r from-coffee-500 to-coffee-600 hover:from-coffee-600 hover:to-coffee-700 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                            <span class="text-lg">⚙️</span>
                            Edit Profile
                        </a>
                        <a href="{{ route('dashboard') }}#create" class="group relative bg-gradient-to-r from-latte-400 to-latte-500 hover:from-latte-500 hover:to-latte-600 text-coffee-800 font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                            <span class="text-lg">➕</span>
                            Add Post
                        </a>
                    @endif
                </div>
                
                <!-- Tab navigation -->
                <div class="order-1 md:order-2 flex gap-2">
                    <button class="tab-active bg-gradient-to-r from-coffee-400 to-coffee-500 text-white px-6 py-3 rounded-full font-semibold shadow-lg" type="button">
                        Timeline
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced Posts Section -->
        <div class="max-w-6xl mx-auto px-4 mt-6 space-y-4">
            @if($posts->count())
                @foreach($posts as $post)
                    <div class="group bg-white/95 dark:bg-coffee-800/90 backdrop-blur-md rounded-xl p-4 shadow-sm hover:shadow-lg border border-coffee-100/40 dark:border-coffee-600/40 transition-all duration-400 hover:-translate-y-0.5 animate-fade-in-up">
                        <div class="flex items-start gap-4">
                            <!-- Post avatar -->
                            <div class="w-11 h-11 rounded-full overflow-hidden ring-2 ring-coffee-200/50 dark:ring-coffee-600/50 shadow group-hover:ring-coffee-300 dark:group-hover:ring-coffee-500 transition-all duration-300">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-coffee-400 to-coffee-500 flex items-center justify-center">
                                        <span class="text-xl font-bold text-white">{{ strtoupper(substr($user->name,0,1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <!-- Post header -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <h3 class="font-semibold text-coffee-900 dark:text-cream-100 text-lg group-hover:text-coffee-700 dark:group-hover:text-cream-50 transition-colors duration-300 truncate">
                                            {{ $post->title }}
                                        </h3>
                                        <div class="w-2 h-2 bg-coffee-300 rounded-full"></div>
                                    </div>
                                    <time class="text-sm text-coffee-500 dark:text-coffee-400 font-medium bg-coffee-50 dark:bg-coffee-700 px-3 py-1 rounded-full">
                                        {{ $post->created_at->diffForHumans() }}
                                    </time>
                                </div>
                                
                                <!-- Post content -->
                                <div class="text-coffee-700 dark:text-coffee-200 whitespace-pre-wrap mb-2 leading-relaxed text-sm">
                                    {{ Str::limit($post->content, 250) }}
                                </div>
                                
                                <!-- Media -->
                                @if($post->media)
                                    @php $isVideo = in_array(pathinfo($post->media, PATHINFO_EXTENSION), ['mp4','mov','avi']); @endphp
                                    <div class="mb-3 rounded-lg overflow-hidden shadow group-hover:shadow-md transition-shadow duration-300">
                                        @if($isVideo)
                                            <video controls class="max-h-64 w-auto rounded-lg shadow-lg">
                                                <source src="{{ asset('storage/'.$post->media) }}" />
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/'.$post->media) }}" class="max-h-64 w-auto rounded-lg shadow-lg cursor-pointer hover:shadow-xl transition-shadow hover:scale-105 transition-transform duration-500" alt="media" />
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Enhanced post actions -->
                                <div class="flex items-center justify-between pt-2 border-t border-coffee-100 dark:border-coffee-600">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-2 text-xs">
                                            <div class="flex items-center gap-1 bg-latte-100 dark:bg-coffee-700 px-2 py-1 rounded-full">
                                                <span class="text-lg">👍</span>
                                                <span class="font-semibold text-coffee-700 dark:text-latte-200">{{ $post->votes->where('vote',1)->count() }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 bg-coffee-100 dark:bg-coffee-800 px-2 py-1 rounded-full">
                                                <span class="text-lg">👎</span>
                                                <span class="font-semibold text-coffee-700 dark:text-coffee-300">{{ $post->votes->where('vote',-1)->count() }}</span>
                                            </div>
                                        </div>
                                        <div class="text-coffee-500 dark:text-coffee-400 flex items-center gap-1 text-xs">
                                            <span class="text-base">💬</span>
                                            <span class="font-medium">Comments</span>
                                        </div>
                                        
                                    </div>
                                    
                                    <a href="{{ route('post.detail', $post->id) }}" 
                                       class="bg-gradient-to-r from-coffee-500 to-coffee-600 hover:from-coffee-600 hover:to-coffee-700 text-white font-semibold px-4 py-1.5 rounded-full shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-1 text-sm">
                                        <span>View</span>
                                        <span class="text-base">→</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white/95 dark:bg-coffee-800/90 backdrop-blur-xl rounded-2xl p-10 text-center shadow-md border border-coffee-100/40 dark:border-coffee-600/40 animate-fade-in-up">
                    <div class="w-24 h-24 bg-gradient-to-br from-coffee-300 to-coffee-400 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <span class="text-4xl">📝</span>
                    </div>
                    <h3 class="text-xl font-bold text-coffee-700 dark:text-coffee-200 mb-2">No posts yet</h3>
                    <p class="text-coffee-500 dark:text-coffee-400 mb-6">{{ $isOwn ? "Share your first thought with the world!" : "This user hasn't shared anything yet." }}</p>
                    @if($isOwn)
                        <a href="{{ route('dashboard') }}#create" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-coffee-500 to-coffee-600 hover:from-coffee-600 hover:to-coffee-700 text-white font-semibold px-7 py-3 rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                            <span class="text-lg">✨</span>
                            Create Your First Post
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>