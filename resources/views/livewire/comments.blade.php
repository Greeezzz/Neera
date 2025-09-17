<div class="space-y-4">
    {{-- List Comments --}}
    @foreach($comments as $comment)
        <div class="card-coffee p-4 shadow-sm">
            {{-- Comment Header --}}
            <div class="flex items-start gap-3 mb-3">
                {{-- Profile Picture --}}
                @if($comment->user->profile_picture)
                    <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                         alt="{{ $comment->user->name }}" 
                         class="w-8 h-8 rounded-full object-cover border-2 border-coffee-300">
                @else
                    <div class="w-8 h-8 bg-coffee-300 rounded-full flex items-center justify-center">
                        <span class="text-xs font-bold text-cream-50">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                
                <div class="flex-1">
                    {{-- User Info --}}
                    <div class="flex items-center gap-2 mb-1">
                                <a href="{{ route('user.profile', $comment->user->id) }}" 
                                    class="text-sm font-medium text-coffee-800 dark:text-coffee-300 hover:text-coffee-900 dark:hover:text-coffee-100 hover:underline inline-flex items-center gap-1">
                            {{ $comment->user->name }}
                            
                            {{-- Friend Indicator --}}
                            @if(auth()->user()->isFriendWith($comment->user->id))
                                <span class="text-coffee-500 dark:text-coffee-400 text-xs" title="Friend">👥</span>
                            @endif
                        </a>
                        
                        <span class="text-xs text-coffee-400 dark:text-coffee-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    
                    {{-- Comment Content --}}
                    <p class="text-coffee-800 dark:text-coffee-200 leading-relaxed">{{ $comment->content }}</p>
                    
                    {{-- Comment Media --}}
                    @if($comment->media)
                        @php
                            $isVideo = in_array(pathinfo($comment->media, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']);
                        @endphp
                        @if($isVideo)
                            <video class="mt-3 max-h-48 rounded-lg border border-coffee-200 dark:border-gray-600 shadow" controls>
                                <source src="{{ asset('storage/'.$comment->media) }}">
                            </video>
                        @else
                            <img src="{{ asset('storage/'.$comment->media) }}" 
                                 class="mt-3 max-h-48 rounded-lg border border-coffee-200 dark:border-gray-600 shadow cursor-pointer hover:shadow-lg transition-shadow" 
                                 alt="Comment image"
                                 onclick="openImageModal('{{ asset('storage/' . $comment->media) }}')">
                        @endif
                    @endif
                </div>
            </div>
            
            {{-- Comment Actions --}}
        <div class="flex items-center gap-3 ml-11">
        <button wire:click="likeComment({{ $comment->id }})" 
            class="flex items-center gap-1 px-3 py-1 rounded-lg text-xs transition-all duration-200 {{ $comment->votes->where('user_id', auth()->id())->where('vote', 1)->count() > 0 ? 'bg-coffee-500 text-cream-100 shadow' : 'btn-coffee-like' }}">
                    <span>👍</span>
                    <span>{{ $comment->votes->where('vote', 1)->count() }}</span>
                </button>
                
        <button wire:click="dislikeComment({{ $comment->id }})" 
            class="flex items-center gap-1 px-3 py-1 rounded-lg text-xs transition-all duration-200 {{ $comment->votes->where('user_id', auth()->id())->where('vote', -1)->count() > 0 ? 'bg-coffee-700 text-cream-100 shadow' : 'btn-coffee-dislike' }}">
                    <span>👎</span>
                    <span>{{ $comment->votes->where('vote', -1)->count() }}</span>
                </button>
                
                <button wire:click="showReply({{ $comment->id }})" 
                        class="btn-coffee-comment flex items-center gap-1 px-3 py-1 rounded-lg text-xs transition-colors">
                    <span>💬</span>
                    <span>Reply</span>
                </button>

                @if(auth()->id() === $comment->user_id || (auth()->user() && auth()->user()->isAdmin()))
                    <button wire:click="startEdit({{ $comment->id }})" class="btn-coffee-edit px-3 py-1 rounded-lg text-xs">✏️ Edit</button>
                    <button 
                        wire:click="deleteComment({{ $comment->id }})"
                        onclick="confirm('Delete this comment?') || event.stopImmediatePropagation()"
                        class="btn-coffee-delete px-3 py-1 rounded-lg text-xs">🗑️ Delete</button>
                @endif
            </div>

            {{-- Reply Form --}}
            @if($replyTo === $comment->id)
                <div class="ml-11 mt-3 p-3 card-coffee">
                    <form wire:submit.prevent="reply({{ $comment->id }})" class="space-y-3">
                        <textarea wire:model="replyContent" 
                                  placeholder="Write a reply..." 
                                  class="input-coffee" 
                                  rows="2"></textarea>
                        
                        {{-- Reply Media Upload --}}
                        <div class="flex items-center gap-3">
                            <input 
                                type="file" 
                                wire:model="replyMedia" 
                                accept="image/*"
                                class="hidden" 
                                id="reply-media-{{ $comment->id }}"
                            >
                            <label 
                                for="reply-media-{{ $comment->id }}" 
                                class="flex items-center gap-2 px-3 py-1 bg-latte-100 dark:bg-coffee-700 text-coffee-700 dark:text-latte-200 rounded text-xs hover:bg-latte-200 dark:hover:bg-coffee-600 transition-colors cursor-pointer"
                            >
                                📎 Photo
                            </label>
                            
                            <div wire:loading wire:target="replyMedia" class="text-coffee-600 dark:text-coffee-400 text-xs">
                                📷 Uploading...
                            </div>
                        </div>
                        
                        @error('replyMedia') 
                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                        @enderror
                        
                        {{-- Reply Media Preview --}}
                        @if($replyMedia)
                            <div class="relative inline-block">
                                <img src="{{ $replyMedia->temporaryUrl() }}" class="max-h-24 rounded border border-coffee-200 dark:border-coffee-600" alt="Preview">
                                <button 
                                    type="button" 
                                    wire:click="$set('replyMedia', null)"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600"
                                >
                                    ✕
                                </button>
                            </div>
                        @endif
                        
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="btn-coffee-primary text-xs px-3 py-1"
                                    wire:loading.attr="disabled"
                                    wire:target="reply">
                                <span wire:loading.remove wire:target="reply">Reply</span>
                                <span wire:loading wire:target="reply">Replying...</span>
                            </button>
                        </div>
                        @error('replyContent') 
                            <p class="text-red-500 text-xs">{{ $message }}</p> 
                        @enderror
                    </form>
                </div>
            @endif

            {{-- Inline Edit Form --}}
            @if($editingCommentId === $comment->id)
                <div class="ml-11 mt-3 p-3 card-coffee">
                    <div class="space-y-3">
                        <textarea wire:model.defer="editContent" rows="3" class="input-coffee" placeholder="Edit your comment..."></textarea>
                        <div class="flex gap-2 justify-end">
                            <button type="button" wire:click="cancelEdit" class="btn-coffee-delete px-3 py-1 text-xs">Cancel</button>
                            <button type="button" wire:click="updateComment" class="btn-coffee-primary text-xs px-3 py-1">Save</button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Show Replies --}}
            @if($comment->replies->count() > 0)
                <div class="ml-8 mt-3 space-y-3">
                    @foreach($comment->replies as $reply)
                        <div class="card-coffee p-3">
                            {{-- Reply Header --}}
                            <div class="flex items-start gap-2 mb-2">
                                @if($reply->user->profile_picture)
                                    <img src="{{ asset('storage/' . $reply->user->profile_picture) }}" 
                                         alt="{{ $reply->user->name }}" 
                                         class="w-6 h-6 rounded-full object-cover border border-coffee-300">
                                @else
                                    <div class="w-6 h-6 bg-coffee-300 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-cream-50">
                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-medium text-coffee-900 dark:text-cream-100">{{ $reply->user->name }}</span>
                                        <span class="text-xs text-coffee-600 dark:text-coffee-400">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-coffee-900 dark:text-cream-100">{{ $reply->content }}</p>
                                    
                                    {{-- Reply Media --}}
                                    @if($reply->media)
                                <img src="{{ asset('storage/'.$reply->media) }}" 
                                    class="mt-2 max-h-32 rounded border border-coffee-200 dark:border-coffee-600 cursor-pointer hover:shadow-lg transition-shadow" 
                                             alt="Reply image"
                                             onclick="openImageModal('{{ asset('storage/' . $reply->media) }}')">
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Reply Actions --}}
                            <div class="flex items-center gap-3 ml-8">
                                <button wire:click="likeComment({{ $reply->id }})" 
                                        class="flex items-center gap-1 px-2 py-1 rounded text-xs transition-all duration-200 group {{ $reply->votes->where('user_id', auth()->id())->where('vote', 1)->count() > 0 ? 'bg-coffee-500 text-cream-100' : 'btn-coffee-like' }}">
                                    <span>👍</span>
                                    <span class="group-hover:text-cream-100">{{ $reply->votes->where('vote', 1)->count() }}</span>
                                </button>
                                
                                <button wire:click="dislikeComment({{ $reply->id }})" 
                                        class="flex items-center gap-1 px-2 py-1 rounded text-xs transition-all duration-200 group {{ $reply->votes->where('user_id', auth()->id())->where('vote', -1)->count() > 0 ? 'bg-coffee-700 text-cream-100' : 'btn-coffee-dislike' }}">
                                    <span>👎</span>
                                    <span class="group-hover:text-cream-100">{{ $reply->votes->where('vote', -1)->count() }}</span>
                                </button>

                                @if(auth()->id() === $reply->user_id || (auth()->user() && auth()->user()->isAdmin()))
                                    <button wire:click="startEdit({{ $reply->id }})" class="btn-coffee-edit px-2 py-1 rounded text-xs">✏️ Edit</button>
                                    <button 
                                        wire:click="deleteComment({{ $reply->id }})"
                                        onclick="confirm('Delete this reply?') || event.stopImmediatePropagation()"
                                        class="btn-coffee-delete px-2 py-1 rounded text-xs">🗑️ Delete</button>
                                @endif
                            </div>

                            {{-- Inline Edit for Reply --}}
                            @if($editingCommentId === $reply->id)
                                <div class="ml-8 mt-3 p-3 card-coffee">
                                    <div class="space-y-3">
                                        <textarea wire:model.defer="editContent" rows="3" class="input-coffee" placeholder="Edit your reply..."></textarea>
                                        <div class="flex gap-2 justify-end">
                                            <button type="button" wire:click="cancelEdit" class="btn-coffee-delete px-3 py-1 text-xs">Cancel</button>
                                            <button type="button" wire:click="updateComment" class="btn-coffee-primary text-xs px-3 py-1">Save</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
