@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-amber-50 dark:from-amber-900 dark:to-orange-900 relative">
    <!-- Close button -->
    <div class="absolute top-6 left-6 z-30">
        <a href="{{ route('stories.index') }}" class="group w-12 h-12 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm flex items-center justify-center text-amber-800 dark:text-amber-200 hover:bg-white/90 dark:hover:bg-gray-700/90 transition-all duration-300 border border-white/30 dark:border-gray-600/30 shadow-lg">
            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>
    </div>
    
    <!-- Story progress indicators -->
    <div class="absolute top-8 left-1/2 transform -translate-x-1/2 z-30 flex space-x-3 px-4">
        @foreach($stories as $idx => $s)
            <div class="h-1.5 bg-white/30 dark:bg-black/30 rounded-full overflow-hidden backdrop-blur-sm" style="width: {{ min(200, 80) }}px;">
                <div class="h-full rounded-full transition-all duration-500 ease-out {{ $idx <= $index ? 'w-full bg-gradient-to-r from-amber-400 to-orange-500' : 'w-0 bg-gradient-to-r from-amber-400 to-orange-500' }}"></div>
            </div>
        @endforeach
    </div>

    <!-- User info with cleaner design -->
    <div class="absolute top-24 left-8 right-8 z-30">
        <div class="bg-white/10 dark:bg-gray-800/10 backdrop-blur-md rounded-xl border border-white/20 dark:border-gray-600/20 p-4 shadow-lg">
            <div class="flex items-center space-x-4">
                <!-- Enhanced Profile Picture -->
                <div class="relative">
                    <div class="w-12 h-12 rounded-full overflow-hidden ring-3 ring-orange-300 dark:ring-orange-600 p-0.5">
                        <div class="w-full h-full rounded-full overflow-hidden bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-800 dark:to-amber-800">
                            @if($user->profile_picture)
                                <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-orange-200 via-amber-200 to-yellow-200 dark:from-orange-700 dark:via-amber-700 dark:to-yellow-700 flex items-center justify-center">
                                    <span class="text-orange-800 dark:text-orange-200 font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-orange-900 dark:text-orange-100 font-bold text-lg">{{ $user->name }}</h3>
                    <p class="text-orange-700 dark:text-orange-300 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $story->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Story content with coffee mug frame -->
    <div class="flex items-center justify-center min-h-screen p-8 pt-44 pb-36">
        <div class="relative max-w-sm">
            <!-- Content -->
            <div class="relative rounded-2xl overflow-hidden shadow-xl border border-white/30 dark:border-gray-600/30">
                @if($story->media_type === 'image')
                    <img src="{{ Storage::url($story->media_path) }}" alt="Story" class="w-full h-auto max-h-96 object-cover">
                @elseif($story->media_type === 'video')
                    <video src="{{ Storage::url($story->media_path) }}" class="w-full h-auto max-h-96 object-cover" controls autoplay></video>
                @endif
            </div>
        </div>
    </div>

    <!-- Caption with coffee theme -->
    @if($story->caption)
        <div class="absolute bottom-24 left-6 right-6 z-30">
            <div class="bg-amber-900/80 dark:bg-amber-100/80 backdrop-blur-md rounded-xl border border-amber-400/30 dark:border-amber-600/30 p-4 shadow-lg">
                <p class="text-amber-100 dark:text-amber-800 font-medium leading-relaxed">{{ $story->caption }}</p>
            </div>
        </div>
    @endif

    <!-- Navigation arrows with coffee theme -->
    @if($index > 0)
        <a href="{{ route('user.stories.view', [$user, $index - 1]) }}" class="group absolute left-6 top-1/2 transform -translate-y-1/2 z-30 w-14 h-14 rounded-full bg-gradient-to-br from-amber-400 via-orange-400 to-yellow-400 dark:from-amber-600 dark:via-orange-600 dark:to-yellow-600 flex items-center justify-center text-white shadow-xl hover:shadow-2xl hover:scale-110 transition-all duration-300">
            <svg class="w-7 h-7 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
    @endif

    @if($index < $stories->count() - 1)
        <a href="{{ route('user.stories.view', [$user, $index + 1]) }}" class="group absolute right-6 top-1/2 transform -translate-y-1/2 z-30 w-14 h-14 rounded-full bg-gradient-to-br from-amber-400 via-orange-400 to-yellow-400 dark:from-amber-600 dark:via-orange-600 dark:to-yellow-600 flex items-center justify-center text-white shadow-xl hover:shadow-2xl hover:scale-110 transition-all duration-300">
            <svg class="w-7 h-7 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    @endif

    <!-- Story info with coffee cup icons -->
    <div class="absolute bottom-6 left-6 right-6 z-30">
        <div class="bg-white/10 dark:bg-black/10 backdrop-blur-md rounded-xl border border-white/20 dark:border-black/20 p-4">
            <div class="flex items-center justify-between text-amber-800 dark:text-amber-200">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ $story->getViewersCount() }} {{ Str::plural('sip', $story->getViewersCount()) }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ $index + 1 }} of {{ $stories->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-advance script with coffee animation -->
<script>
let progressBar;
let startTime;

function startProgress() {
    const progressBars = document.querySelectorAll('.h-1\\.5 .h-full');
    if (progressBars[{{ $index }}]) {
        progressBar = progressBars[{{ $index }}];
        startTime = Date.now();
        animateProgress();
    }
}

function animateProgress() {
    const elapsed = Date.now() - startTime;
    const progress = Math.min(elapsed / 5000, 1); // 5 seconds
    
    if (progressBar) {
        progressBar.style.width = (progress * 100) + '%';
    }
    
    if (progress < 1) {
        requestAnimationFrame(animateProgress);
    } else {
        @if($index < $stories->count() - 1)
            window.location.href = "{{ route('user.stories.view', [$user, $index + 1]) }}";
        @else
            window.location.href = "{{ route('stories.index') }}";
        @endif
    }
}

// Start progress animation when page loads
document.addEventListener('DOMContentLoaded', startProgress);

// Pause/resume on click
document.addEventListener('click', function(e) {
    if (!e.target.closest('a') && !e.target.closest('button')) {
        // Toggle pause/resume functionality could be added here
    }
});
</script>
@endsection