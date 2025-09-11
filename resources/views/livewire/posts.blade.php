<div class="space-y-6">
    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="card-coffee p-4 border-l-4 border-coffee-400 animate-fade-in-up" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-5 h-5 rounded-full bg-coffee-400 flex items-center justify-center mr-3">
                        <span class="text-white text-xs">✓</span>
                    </div>
                    <span class="text-coffee-700 font-medium">{{ session('message') }}</span>
                </div>
                <button @click="show = false" class="text-coffee-400 hover:text-coffee-600 transition-all duration-300">
                    ✕
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="card-coffee p-4 border-l-4 border-red-400 bg-red-50 animate-fade-in-up"
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-5 h-5 rounded-full bg-red-400 flex items-center justify-center mr-3">
                        <span class="text-white text-xs">!</span>
                    </div>
                    <span class="text-red-700 font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-400 hover:text-red-600 transition-all duration-300">
                    ✕
                </button>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="card-coffee p-6 hover-lift">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gradient mb-2">✍️ Share Your Thoughts</h2>
                <p class="text-coffee-600">What's brewing in your mind today?</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-sm text-coffee-500 bg-cream-100 px-3 py-1 rounded-full">
                    {{ $posts->count() }} posts
                </div>
                <button wire:click="refreshPosts" 
                        class="btn-cream hover:scale-105 flex items-center gap-2"
                        wire:loading.attr="disabled"
                        wire:target="refreshPosts">
                    <span wire:loading.remove wire:target="refreshPosts">🔄 Refresh</span>
                    <span wire:loading wire:target="refreshPosts">⏳ Refreshing...</span>
                </button>
            </div>
        </div>
    </div>
    
    {{-- Loading indicator --}}
    <div wire:loading.delay class="text-coffee-500 text-sm flex items-center justify-center p-4">
        <div class="animate-pulse-soft">🔄 Updating posts...</div>
    </div>
    
    {{-- Create Post --}}
    <div class="card-coffee p-6 hover-lift">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-coffee-400 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <span class="font-medium text-coffee-700">{{ auth()->user()->name }}</span>
                    <p class="text-sm text-coffee-500">Share what's on your mind</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <input type="text" 
                       wire:model="title" 
                       placeholder="What's your post about?" 
                       class="w-full border-2 border-cream-200 rounded-xl p-4 focus:border-coffee-400 focus:ring-2 focus:ring-coffee-200 transition-all duration-300 bg-cream-50">
                
                <textarea wire:model="content" 
                          placeholder="Share your thoughts, experiences, or ask a question..." 
                          rows="4"
                          class="w-full border-2 border-cream-200 rounded-xl p-4 focus:border-coffee-400 focus:ring-2 focus:ring-coffee-200 transition-all duration-300 bg-cream-50 resize-none"></textarea>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2 cursor-pointer text-coffee-600 hover:text-coffee-700 transition-all duration-300">
                            <input type="file" wire:model="media" class="hidden">
                            <div class="btn-cream flex items-center space-x-2 hover:scale-105">
                                <span>📎</span>
                                <span>Add Media</span>
                            </div>
                        </label>
                        @if($media)
                            <div class="text-sm text-coffee-600 bg-cream-100 px-2 py-1 rounded">
                                📄 File selected: {{ $media->getClientOriginalName() }}
                            </div>
                        @endif
                    </div>
                    
                    <button type="submit" 
                            class="btn-coffee flex items-center space-x-2 hover:scale-105"
                            wire:loading.attr="disabled"
                            wire:target="save">
                        <span wire:loading.remove wire:target="save">🚀 Publish</span>
                        <span wire:loading wire:target="save">⏳ Publishing...</span>
                    </button>
                </div>
                
                @if($errors->any())
                    <div class="space-y-1">
                        @error('title') <p class="text-red-500 text-sm flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p> @enderror
                        @error('content') <p class="text-red-500 text-sm flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p> @enderror
                        @error('media') <p class="text-red-500 text-sm flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>
        </form>
    </div>
    </form>

    {{-- Posts List --}}
    <div class="space-y-6">
        @foreach($posts as $post)
            <div class="card-coffee p-6 hover-lift animate-fade-in-up">
                @if($editingPostId === $post->id)
                    {{-- Edit Form --}}
                    <form wire:submit.prevent="updatePost" class="space-y-4">
                        <input type="text" 
                               wire:model="editTitle" 
                               class="w-full border-2 border-cream-200 rounded-xl p-4 focus:border-coffee-400 focus:ring-2 focus:ring-coffee-200 transition-all duration-300 bg-cream-50" 
                               placeholder="Post title">
                        <textarea wire:model="editContent" 
                                  class="w-full border-2 border-cream-200 rounded-xl p-4 focus:border-coffee-400 focus:ring-2 focus:ring-coffee-200 transition-all duration-300 bg-cream-50 resize-none" 
                                  placeholder="Write something..." 
                                  rows="3"></textarea>
                        
                        @if($post->media)
                            <div class="mt-4 p-4 bg-cream-100 rounded-lg">
                                <p class="text-sm text-coffee-600 mb-2">Current media:</p>
                                @php
                                    $isVideo = in_array(pathinfo($post->media, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']);
                                @endphp
                                @if($isVideo)
                                    <video class="w-32 h-32 object-cover rounded-lg" controls>
                                        <source src="{{ asset('storage/'.$post->media) }}">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/'.$post->media) }}" class="w-32 h-32 object-cover rounded-lg" alt="Current image">
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex gap-3">
                            <button type="submit" class="btn-coffee flex items-center space-x-2 hover:scale-105">
                                <span>💾</span>
                                <span>Update</span>
                            </button>
                            <button type="button" wire:click="cancelEdit" class="btn-cream flex items-center space-x-2 hover:scale-105">
                                <span>✕</span>
                                <span>Cancel</span>
                            </button>
                        </div>
                        @error('editTitle') <p class="text-red-500 text-sm flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p> @enderror
                        @error('editContent') <p class="text-red-500 text-sm flex items-center"><span class="mr-1">⚠️</span>{{ $message }}</p> @enderror
                    </form>
                @endif
                
                {{-- Normal Post Display --}}
                @if($editingPostId !== $post->id)
                    {{-- Post Header --}}
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if($post->user->profile_picture)
                                <img src="{{ Storage::url($post->user->profile_picture) }}" 
                                     alt="Profile" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-coffee-300">
                            @else
                                <div class="w-10 h-10 bg-coffee-400 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($post->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('user.profile', $post->user->id) }}" class="font-medium text-coffee-700 hover:text-coffee-800 transition-all duration-300">
                                    {{ $post->user->name }}
                                </a>
                                <p class="text-xs text-coffee-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        @if($post->user_id === Auth::id())
                            <div class="flex gap-2">
                                <button wire:click="editPost({{ $post->id }})" 
                                        class="px-3 py-1 bg-coffee-400 text-white rounded-lg text-xs hover:bg-coffee-500 transition-all duration-300 hover:scale-105 flex items-center space-x-1">
                                    <span>✏️</span>
                                    <span>Edit</span>
                                </button>
                                <button wire:click="deletePost({{ $post->id }})" 
                                        onclick="return confirm('Are you sure?')"
                                        class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600 transition-all duration-300 hover:scale-105 flex items-center space-x-1">
                                    <span>🗑️</span>
                                    <span>Delete</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Post Content --}}
                    <div class="mb-4">
                        <h2 class="font-bold text-xl text-coffee-800 mb-2">{{ $post->title }}</h2>
                        <p class="text-coffee-700 leading-relaxed">{{ $post->content }}</p>
                    </div>

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
                                <img src="{{ asset('storage/'.$post->media) }}" class="max-h-64 rounded-lg shadow-lg" alt="Post image">
                            @endif
                        </div>
                    @endif

                    {{-- Post Footer --}}
                    <div class="border-t border-cream-200 pt-4 mt-4">
                        {{-- Voting and Actions --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                {{-- Voting Section with Status Indicator --}}
                                @php
                                    $userVote = $post->votes()->where('user_id', auth()->id())->first();
                                    $upvoted = $userVote && $userVote->vote == 1;
                                    $downvoted = $userVote && $userVote->vote == -1;
                                    $totalVotes = $post->votes->sum('vote');
                                @endphp
                                
                                <div class="flex items-center space-x-2">
                                    <button wire:click="upvote({{ $post->id }})" 
                                            class="flex items-center space-x-1 px-3 py-2 rounded-lg transition-all duration-300 hover:scale-105 {{ $upvoted ? 'bg-coffee-500 text-white' : 'bg-cream-100 text-coffee-600 hover:bg-cream-200' }}">
                                        <span>{{ $upvoted ? '👍' : '👍🏻' }}</span>
                                        <span class="text-sm font-medium">{{ $upvoted ? 'Upvoted' : 'Upvote' }}</span>
                                    </button>
                                    
                                    <div class="px-3 py-2 bg-latte-100 rounded-lg">
                                        <span class="text-coffee-700 font-bold">{{ $totalVotes }}</span>
                                    </div>
                                    
                                    <button wire:click="downvote({{ $post->id }})" 
                                            class="flex items-center space-x-1 px-3 py-2 rounded-lg transition-all duration-300 hover:scale-105 {{ $downvoted ? 'bg-red-500 text-white' : 'bg-cream-100 text-coffee-600 hover:bg-cream-200' }}">
                                        <span>{{ $downvoted ? '👎' : '👎🏻' }}</span>
                                        <span class="text-sm font-medium">{{ $downvoted ? 'Downvoted' : 'Downvote' }}</span>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- Comments Button --}}
                            <a href="{{ route('post.detail', $post->id) }}" 
                               class="btn-cream flex items-center space-x-2 hover:scale-105">
                                <span>💬</span>
                                <span>Comments ({{ $post->comments->count() }})</span>
                            </a>
                        </div>
                        
                        {{-- Post Meta Info --}}
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-cream-100">
                            <div class="flex items-center space-x-2 text-sm text-coffee-500">
                                <span>By</span>
                                <a href="{{ route('user.profile', $post->user->id) }}" 
                                   class="text-coffee-600 hover:text-coffee-700 font-medium transition-all duration-300 inline-flex items-center gap-1">
                                    {{ $post->user->name }}
                                    {{-- Friend Indicator --}}
                                    @if(auth()->user()->isFriendWith($post->user->id))
                                        <span class="text-coffee-400 text-xs" title="Friend">👥</span>
                                    @endif
                                </a>
                            </div>
                            
                            <div class="text-xs text-coffee-400">
                                {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Remove the always-show comments section --}}
            </div>
        @endforeach
    </div>
</div>
