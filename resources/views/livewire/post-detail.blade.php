<div class="max-w-4xl mx-auto p-6">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            ← Back to Feed
        </a>
    </div>

    {{-- Post Detail --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        {{-- Post Header --}}
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                {{-- Profile Picture --}}
                @if($post->user->profile_picture)
                    <img src="{{ asset('storage/' . $post->user->profile_picture) }}" 
                         alt="{{ $post->user->name }}" 
                         class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-gray-600">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                
                <div>
                    <a href="{{ route('user.profile', $post->user->id) }}" 
                       class="text-blue-600 hover:text-blue-800 hover:underline font-medium inline-flex items-center gap-1">
                        {{ $post->user->name }}
                        
                        {{-- Friend Indicator --}}
                        @if(auth()->user()->isFriendWith($post->user->id))
                            <span class="text-green-500 text-xs" title="Friend">👥</span>
                        @endif
                    </a>
                    <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        {{-- Post Content --}}
        <div class="mb-4">
            <h1 class="text-2xl font-bold mb-3">{{ $post->title }}</h1>
            <p class="text-gray-800 leading-relaxed">{{ $post->content }}</p>
            
            {{-- Post Media --}}
            @if($post->media)
                @php
                    $isVideo = in_array(pathinfo($post->media, PATHINFO_EXTENSION), ['mp4', 'mov', 'avi']);
                @endphp
                
                @if($isVideo)
                    <video class="mt-4 w-full max-w-2xl rounded-lg" controls>
                        <source src="{{ asset('storage/'.$post->media) }}">
                    </video>
                @else
                    <img src="{{ asset('storage/'.$post->media) }}" 
                         class="mt-4 max-w-full rounded-lg" 
                         alt="Post image">
                @endif
            @endif
        </div>

        {{-- Voting --}}
        <div class="flex items-center gap-4 pt-4 border-t">
            <button wire:click="upvote" class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                👍 Upvote
            </button>
            <button wire:click="downvote" class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                👎 Downvote
            </button>
            <span class="text-gray-600 font-medium">{{ $post->votes->sum('vote') }} votes</span>
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Comments ({{ $post->comments->count() }})</h2>
        
        {{-- Comments Component --}}
        <livewire:comments :post="$post" :key="'comments-detail-'.$post->id" />
    </div>
</div>
