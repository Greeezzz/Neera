<div class="mt-4 space-y-4">
    {{-- Add Comment --}}
    <form wire:submit.prevent="save" class="space-y-2">
        <textarea wire:model="content" placeholder="Write a comment..." class="w-full border rounded p-2" required></textarea>
        <input type="file" wire:model="media" accept="image/*,video/*">
        
        {{-- Loading indicator --}}
        <div wire:loading wire:target="media" class="text-blue-500 text-sm">📎 Uploading...</div>
        <div wire:loading wire:target="save" class="text-green-500 text-sm">💬 Posting comment...</div>
        
        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded" wire:loading.attr="disabled">Send</button>
        @error('content') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        @error('media') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </form>

    {{-- List Comments --}}
    @foreach($comments as $comment)
        <div class="border p-2 rounded bg-gray-50">
            <p>{{ $comment->content }}</p>
            @if($comment->media)
                @php
                    $isVideo = in_array(pathinfo($comment->media, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']);
                @endphp
                @if($isVideo)
                    <video class="mt-2 max-h-64 w-auto" controls>
                        <source src="{{ asset('storage/'.$comment->media) }}">
                    </video>
                @else
                    <img src="{{ asset('storage/'.$comment->media) }}" class="mt-2 max-h-64 rounded" alt="Comment image">
                @endif
            @endif
            
            <div class="flex items-center justify-between mt-2">
                <div class="flex items-center gap-2">
                    {{-- Profile Picture --}}
                    @if($comment->user->profile_picture)
                        <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                             alt="{{ $comment->user->name }}" 
                             class="w-5 h-5 rounded-full object-cover">
                    @else
                        <div class="w-5 h-5 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-gray-600">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    
                    {{-- Clickable Username with Friend Indicator --}}
                    <a href="{{ route('user.profile', $comment->user->id) }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium inline-flex items-center gap-1">
                        {{ $comment->user->name }}
                        
                        {{-- Friend Indicator --}}
                        @if(auth()->user()->isFriendWith($comment->user->id))
                            <span class="text-green-500 text-xs" title="Friend">👥</span>
                        @endif
                    </a>
                    
                    <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <button wire:click="likeComment({{ $comment->id }})" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                        👍 {{ $comment->votes->where('vote', 1)->count() }}
                    </button>
                    <button wire:click="dislikeComment({{ $comment->id }})" class="px-2 py-1 bg-red-500 text-white rounded text-xs">
                        👎 {{ $comment->votes->where('vote', -1)->count() }}
                    </button>
                    <button wire:click="showReply({{ $comment->id }})" class="px-2 py-1 bg-gray-500 text-white rounded text-xs">
                        Reply
                    </button>
                </div>
            </div>

            {{-- Reply Form --}}
            @if($replyTo === $comment->id)
                <form wire:submit.prevent="reply({{ $comment->id }})" class="mt-2 space-y-2">
                    <textarea wire:model="replyContent" placeholder="Write a reply..." class="w-full border rounded p-2" rows="2"></textarea>
                    <button class="bg-green-500 text-white px-2 py-1 rounded text-xs">Reply</button>
                    @error('replyContent') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </form>
            @endif

            {{-- Show Replies --}}
            @if($comment->replies->count() > 0)
                <div class="ml-4 mt-2 space-y-2">
                    @foreach($comment->replies as $reply)
                        <div class="border p-2 rounded bg-white">
                            <p>{{ $reply->content }}</p>
                            
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-sm text-gray-500">By {{ $reply->user->name }}</p>
                                
                                <div class="flex items-center gap-2">
                                    <button wire:click="likeComment({{ $reply->id }})" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                                        👍 {{ $reply->votes->where('vote', 1)->count() }}
                                    </button>
                                    <button wire:click="dislikeComment({{ $reply->id }})" class="px-2 py-1 bg-red-500 text-white rounded text-xs">
                                        👎 {{ $reply->votes->where('vote', -1)->count() }}
                                    </button>
                                    <button wire:click="showReply({{ $reply->id }})" class="px-2 py-1 bg-gray-500 text-white rounded text-xs">
                                        Reply
                                    </button>
                                </div>
                            </div>

                            {{-- Reply to Reply Form --}}
                            @if($replyTo === $reply->id)
                                <form wire:submit.prevent="reply({{ $comment->id }})" class="mt-2 space-y-2">
                                    <textarea wire:model="replyContent" placeholder="Write a reply..." class="w-full border rounded p-2" rows="2"></textarea>
                                    <button class="bg-green-500 text-white px-2 py-1 rounded text-xs">Reply</button>
                                    @error('replyContent') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                                </form>
                            @endif

                            {{-- Nested Replies --}}
                            @if($reply->replies->count() > 0)
                                <div class="ml-4 mt-2 space-y-2">
                                    @foreach($reply->replies as $nestedReply)
                                        <div class="border p-2 rounded bg-gray-50">
                                            <p>{{ $nestedReply->content }}</p>
                                            
                                            <div class="flex items-center justify-between mt-2">
                                                <p class="text-sm text-gray-500">By {{ $nestedReply->user->name }}</p>
                                                
                                                <div class="flex items-center gap-2">
                                                    <button wire:click="likeComment({{ $nestedReply->id }})" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                                                        👍 {{ $nestedReply->votes->where('vote', 1)->count() }}
                                                    </button>
                                                    <button wire:click="dislikeComment({{ $nestedReply->id }})" class="px-2 py-1 bg-red-500 text-white rounded text-xs">
                                                        👎 {{ $nestedReply->votes->where('vote', -1)->count() }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
