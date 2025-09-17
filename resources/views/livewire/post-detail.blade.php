<div class="max-w-4xl mx-auto p-6">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-coffee-400 text-cream-50 rounded-lg shadow hover:bg-coffee-500 transition">
            ← Back to Feed
        </a>
    </div>

    {{-- Post Detail --}}
    <div class="card-coffee p-6 mb-6">
        {{-- Post Header --}}
    <div class="flex items-start mb-4">
            <div class="flex items-center gap-3">
                {{-- Profile Picture --}}
                @if($post->user->profile_picture)
                    <img src="{{ asset('storage/' . $post->user->profile_picture) }}" 
                         alt="{{ $post->user->name }}" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-coffee-300 dark:border-coffee-500">
                @else
                    <div class="w-10 h-10 bg-coffee-300 dark:bg-coffee-600 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-cream-50 dark:text-cream-100">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                
                <div>
                    <a href="{{ route('user.profile', $post->user->id) }}" 
                       class="text-coffee-900 dark:text-coffee-200 hover:text-coffee-700 dark:hover:text-coffee-100 hover:underline font-medium inline-flex items-center gap-1">
                        {{ $post->user->name }}
                        @if(auth()->user()->isFriendWith($post->user->id))
                            <span class="text-coffee-500 text-xs" title="Friend">👥</span>
                        @endif
                    </a>
                    <p class="text-coffee-muted">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        {{-- Post Content --}}
        <div class="mb-4">
            <h1 class="text-2xl font-bold mb-3 text-gradient">{{ $post->title }}</h1>
            <p class="leading-relaxed text-coffee-contrast">{{ $post->content }}</p>
            @if($post->media)
                @php
                    $isVideo = in_array(pathinfo($post->media, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']);
                @endphp
                @if($isVideo)
                    <video class="mt-4 w-full max-w-2xl rounded-lg border border-coffee-200 dark:border-coffee-600 shadow" controls>
                        <source src="{{ asset('storage/'.$post->media) }}">
                    </video>
                @else
                    <img src="{{ asset('storage/'.$post->media) }}" 
                         class="mt-4 max-w-full rounded-lg border border-coffee-200 dark:border-coffee-600 shadow" 
                         alt="Post image">
                @endif
            @endif
    </div>

    {{-- Voting --}}
    <div class="flex items-center gap-4 pt-4 border-t border-coffee-100 dark:border-coffee-600">
            <button wire:click="upvote" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105 {{ $this->hasUpvoted() ? 'bg-coffee-500 text-cream-100 shadow-lg' : 'btn-coffee-like' }}">
                <span class="text-lg">👍</span>
                <span class="font-medium">{{ $post->upvotes_count ?? 0 }}</span>
            </button>
            
            <button wire:click="downvote" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105 {{ $this->hasDownvoted() ? 'bg-coffee-700 text-cream-100 shadow-lg' : 'btn-coffee-dislike' }}">
                <span class="text-lg">👎</span>
                <span class="font-medium">{{ $post->downvotes_count ?? 0 }}</span>
            </button>
            
            <div class="ml-4 vote-total">
                <span class="font-medium text-sm">
                    {{ ($post->upvotes_count ?? 0) - ($post->downvotes_count ?? 0) }} total votes
                </span>
            </div>
    </div>
    </div>

    {{-- Comments Section --}}
    <div class="card-coffee p-6 mt-6">
        <h2 class="text-xl font-bold mb-4 text-gradient">Comments ({{ $post->comments_count ?? 0 }})</h2>
        
        {{-- Comment Form --}}
        <div class="mb-6 p-4 card-coffee">
            <form wire:submit.prevent="addComment" class="space-y-3">
                <div class="flex items-start gap-3">
                    {{-- User Avatar --}}
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-8 h-8 rounded-full object-cover border-2 border-coffee-300">
                    @else
                        <div class="w-8 h-8 bg-coffee-300 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-cream-50">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    
                    {{-- Comment Input --}}
                    <div class="flex-1">
                        <textarea 
                            wire:model="content"
                            placeholder="Write a comment..." 
                            class="input-coffee resize-none"
                            rows="3"
                        ></textarea>
                        @error('content') 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        
                        {{-- Media Upload --}}
                        <div class="mt-3 flex items-center gap-3">
                            <input 
                                type="file" 
                                wire:model="media" 
                                accept="image/*"
                                class="hidden" 
                                id="comment-media"
                            >
                            <label 
                                for="comment-media" 
                                class="flex items-center gap-2 px-3 py-2 bg-cream-100 dark:bg-coffee-700 text-coffee-800 dark:text-latte-200 rounded-lg hover:bg-cream-200 dark:hover:bg-coffee-600 transition-colors cursor-pointer"
                            >
                                📎 Add Photo
                            </label>
                            
                            {{-- Loading indicator --}}
                            <div wire:loading wire:target="media" class="text-coffee-600 dark:text-coffee-400 text-sm">
                                📷 Uploading...
                            </div>
                        </div>
                        
                        @error('media') 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        
                        {{-- Media Preview --}}
                        @if($media)
                            <div class="mt-3 relative inline-block">
                                <img src="{{ $media->temporaryUrl() }}" class="max-h-32 rounded-lg border border-coffee-200 dark:border-coffee-600" alt="Preview">
                                <button 
                                    type="button" 
                                    wire:click="$set('media', null)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                                >
                                    ✕
                                </button>
                            </div>
                        @endif
                        
                        <div class="flex justify-end mt-3">
                            <button 
                                type="submit" 
                                class="btn-coffee-primary"
                                wire:loading.attr="disabled"
                                wire:target="addComment"
                            >
                                <span wire:loading.remove wire:target="addComment">💬 Post Comment</span>
                                <span wire:loading wire:target="addComment">⏳ Posting...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        @if($post->comments_count > 0)
            {{-- Comments Component --}}
            <livewire:comments :post="$post" :key="'comments-detail-'.$post->id" />
        @else
            {{-- No Comments State --}}
            <div class="text-center py-8 text-coffee-600">
                <span class="text-4xl mb-3 block">�</span>
                <p class="text-lg mb-1">No comments yet</p>
                <p class="text-sm text-coffee-400">Be the first to share your thoughts!</p>
            </div>
        @endif
    </div>
</div>
