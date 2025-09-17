<div class="space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="glass rounded-xl p-4 border border-coffee-200/30 bg-green-50/80 text-green-800 animate-fade-in">
            <div class="flex items-center justify-between">
                <span class="font-medium">{{ session('message') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">✕</button>
            </div>
        </div>
    @endif

    <!-- Create Post Form -->
    <div class="card-coffee p-6 shadow-lg">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-coffee-500 to-coffee-600 rounded-full flex items-center justify-center shadow-md">
                <span class="text-cream-50 font-bold">📝</span>
            </div>
            <h2 class="text-xl font-semibold text-gradient">Share Your Thoughts</h2>
        </div>
        
        <form wire:submit.prevent="save" class="space-y-4">
            <!-- Title Field -->
        <div>
                <label for="title" class="block text-sm font-medium text-coffee-700 dark:text-coffee-300 mb-2">Post Title</label>
                <input 
                    type="text"
                    wire:model="title" 
                    id="title"
            class="input-coffee w-full"
                    placeholder="What's the topic of your post?"
                />
                @error('title') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Content Field -->
        <div>
                <label for="content" class="block text-sm font-medium text-coffee-700 dark:text-coffee-300 mb-2">Content</label>
                <textarea 
                    wire:model="content" 
                    id="content"
                    rows="4" 
            class="input-coffee w-full resize-none"
                    placeholder="Share your thoughts, ideas, or what you're working on..."
                ></textarea>
                @error('content') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Media Upload -->
            <div>
                <label for="media" class="block text-sm font-medium text-coffee-700 dark:text-coffee-300 mb-2">Add Media (Optional)</label>
                <div class="border-2 border-dashed border-coffee-200 dark:border-coffee-600 rounded-lg p-4 hover:border-coffee-300 dark:hover:border-coffee-500 transition-colors bg-cream-50 dark:bg-coffee-800">
                    <input 
                        type="file" 
                        wire:model="media" 
                        id="media"
                        accept="image/*,video/*"
                        class="block w-full text-sm text-coffee-600 dark:text-coffee-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-latte-100 dark:file:bg-coffee-700 file:text-coffee-700 dark:file:text-coffee-200 hover:file:bg-latte-200 dark:hover:file:bg-coffee-600"
                    />
                    <p class="text-xs text-coffee-500 dark:text-coffee-400 mt-1">Support: JPG, PNG, MP4, MOV, AVI (Max: 20MB)</p>
                </div>
                @error('media') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-sm text-coffee-600 dark:text-coffee-400">{{ count($posts) }} posts</span>
                <button 
                    type="submit" 
                    class="btn-coffee-primary"
                    wire:loading.attr="disabled"
                    wire:target="save"
                >
                    <span wire:loading.remove wire:target="save">🚀 Share Post</span>
                    <span wire:loading wire:target="save">⏳ Posting...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Search Bar -->
    <div class="card-coffee p-4">
        <form method="GET" action="{{ request()->url() }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-coffee-500">🔎</span>
                <input
                    name="search"
                    value="{{ $search }}"
                    type="text"
                    placeholder="Search posts, content, or users..."
                    class="input-coffee w-full pl-9"
                    wire:model.debounce.300ms="search"
                />
            </div>
            <button type="submit" class="btn-coffee-primary px-4 py-2 text-sm">Search</button>
            @if(request('search'))
                <a href="{{ request()->url() }}" class="btn-coffee-delete px-3 py-2 text-sm">Clear</a>
            @endif
        </form>
    </div>

    <!-- Posts List -->
    <div class="space-y-6">
        @forelse($posts as $post)
            <div class="card-coffee group relative overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                {{-- Decorative Coffee Theme Elements --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-latte-100/40 dark:from-coffee-600/20 to-transparent rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-coffee-100/30 dark:from-latte-600/10 to-transparent rounded-tr-full"></div>
                
                <div class="relative z-10 p-6">
                    <div class="flex items-start space-x-4">
                        {{-- Enhanced Profile Picture --}}
                        <div class="relative">
                            <a href="{{ route('user.profile', $post->user->id) }}" title="View profile"
                               class="block w-14 h-14 rounded-full overflow-hidden ring-2 ring-coffee-200 dark:ring-coffee-600 group-hover:ring-coffee-300 dark:group-hover:ring-coffee-500 transition-all duration-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-coffee-400">
                                @if($post->user->profile_picture)
                                    <img src="{{ asset('storage/' . $post->user->profile_picture) }}" 
                                         alt="{{ $post->user->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-coffee-400 via-coffee-500 to-coffee-600 flex items-center justify-center">
                                        <span class="text-cream-50 font-bold text-xl">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </a>
                            {{-- Online Indicator --}}
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                            @if(auth()->user()->isFriendWith($post->user->id))
                                <div class="absolute -top-1 -left-1 bg-latte-200 text-coffee-800 dark:bg-coffee-700 dark:text-latte-200 text-[10px] px-1.5 py-0.5 rounded-full shadow" title="Friend">👥</div>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            {{-- Enhanced Header --}}
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('user.profile', $post->user->id) }}" class="font-medium text-base text-coffee-900 dark:text-cream-100 hover:underline" style="color: var(--coffee-900)">{{ $post->user->name }}</a>
                                    @if(auth()->user()->isFriendWith($post->user->id))
                                        <span class="text-coffee-600 dark:text-latte-300" title="Friend">👥</span>
                                    @endif
                                    <span class="px-2 py-0.5 bg-latte-200 text-coffee-800 border border-coffee-300 dark:bg-coffee-700 dark:text-latte-200 dark:border-coffee-600 text-xs rounded-full font-medium">
                                        {{ $post->user->role ?? 'Member' }}
                                    </span>
                                </div>
                                <time class="text-sm text-coffee-500 dark:text-coffee-400 font-medium">{{ $post->created_at->diffForHumans() }}</time>
                            </div>
                            
                            {{-- Enhanced Title --}}
                            @if($post->title)
                                <h4 
                                    wire:click="showPostDetail({{ $post->id }})"
                                    class="text-xl font-bold text-gradient mb-3 cursor-pointer hover:opacity-80 transition-all duration-300 line-clamp-2"
                                >
                                    {{ $post->title }}
                                </h4>
                            @endif
                            
                            {{-- Enhanced Content --}}
                            <div class="text-coffee-700 dark:text-coffee-200 leading-relaxed mb-5 whitespace-pre-wrap text-base">{{ $post->content }}</div>
                        
                        @if($post->media)
                            <div class="mb-4">
                                @php
                                    $mediaPath = $post->media;
                                    $isVideo = str_contains($mediaPath, '.mp4') || str_contains($mediaPath, '.mov') || str_contains($mediaPath, '.avi');
                                @endphp
                                
                                @if($isVideo)
                                    <video controls class="max-h-64 w-full rounded-lg shadow-lg">
                                        <source src="{{ asset('storage/' . $post->media) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $post->media) }}" 
                                         class="max-h-64 w-auto rounded-lg shadow-lg cursor-pointer hover:shadow-xl transition-shadow" 
                                         alt="Post image"
                                         onclick="openImageModal('{{ asset('storage/' . $post->media) }}')">
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex items-center space-x-6 text-sm">
                            <button 
                                wire:click="vote({{ $post->id }}, 'up')"
                                class="btn-coffee-like flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-300 hover:scale-105"
                            >
                                <span class="text-lg">👍</span>
                                <span class="font-medium">{{ $post->upvotes_count ?? 0 }}</span>
                            </button>

                            <button 
                                wire:click="vote({{ $post->id }}, 'down')"
                                class="btn-coffee-dislike flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-300 hover:scale-105"
                            >
                                <span class="text-lg">👎</span>
                                <span class="font-medium">{{ $post->downvotes_count ?? 0 }}</span>
                            </button>
                            
                            <a 
                                href="{{ route('post.detail', $post->id) }}"
                                class="btn-coffee-comment flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-300 hover:scale-105"
                            >
                                <span class="text-lg">💬</span>
                                <span class="font-medium">{{ $post->comments->count() }}</span>
                                <span class="hidden sm:inline text-sm">Comments</span>
                            </a>

                            @if(auth()->id() === $post->user_id)
                                <button 
                                    wire:click="editPost({{ $post->id }})"
                                    class="flex items-center space-x-2 text-coffee-600 hover:text-blue-600 transition-colors duration-200 hover:scale-105"
                                >
                                    <span>✏️</span>
                                    <span>Edit</span>
                                </button>

                                <button 
                                    wire:click="deletePost({{ $post->id }})"
                                    onclick="return confirm('Are you sure you want to delete this post?')"
                                    class="flex items-center space-x-2 text-coffee-600 hover:text-red-600 transition-colors duration-200 hover:scale-105"
                                >
                                    <span>🗑️</span>
                                    <span>Delete</span>
                                </button>
                            @endif
                        </div>
                        
                        <!-- Comments Section -->
                        @if(isset($showingComments[$post->id]))
                            <div class="mt-4 pt-4 border-t border-coffee-200/30">
                                <div class="space-y-3">
                                    <!-- Comment Form -->
                                    <div class="flex space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-coffee-400 to-coffee-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-cream-50 font-bold text-sm">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <textarea 
                                                placeholder="Write a comment..." 
                                                class="w-full px-3 py-2 text-sm border border-coffee-200 rounded-lg focus:ring-2 focus:ring-coffee-400 focus:border-transparent resize-none bg-cream-50"
                                                rows="2"
                                            ></textarea>
                                            <button class="mt-2 px-3 py-1 bg-coffee-500 text-cream-50 rounded-lg text-sm hover:bg-coffee-600 transition-colors">
                                                Comment
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Sample Comments -->
                                    <div class="text-sm text-coffee-600 italic">💬 Comment system coming soon...</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="glass rounded-xl p-8 border border-coffee-200/30 text-center">
                <div class="w-16 h-16 bg-cream-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">📝</span>
                </div>
                <h3 class="text-lg font-medium text-coffee-800 mb-2">No posts yet</h3>
                <p class="text-coffee-600">Be the first to share your thoughts!</p>
            </div>
        @endforelse
    </div>
</div>