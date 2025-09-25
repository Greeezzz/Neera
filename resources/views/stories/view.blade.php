@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-100 to-yellow-50 dark:from-amber-900/40 dark:via-orange-900/40 dark:to-yellow-900/40 relative">
    <!-- Close button -->
    <div class="absolute top-4 left-4 z-40">
        <a href="{{ route('stories.index') }}" class="group w-10 h-10 rounded-full bg-white/95 dark:bg-amber-800/90 shadow-lg flex items-center justify-center text-amber-800 dark:text-amber-200 hover:bg-amber-50 dark:hover:bg-amber-700 transition-all duration-200 border border-amber-200 dark:border-amber-600">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>
    </div>
    
    <!-- Story progress indicators -->
    <div class="absolute top-4 left-1/2 transform -translate-x-1/2 z-30 flex space-x-2 px-4">
        @foreach($stories as $idx => $s)
            <div class="h-1.5 bg-amber-200/60 dark:bg-amber-700/60 rounded-full overflow-hidden shadow-sm" style="width: 60px;">
                <div class="h-full rounded-full transition-all duration-300 ease-out {{ $idx <= $index ? 'w-full bg-gradient-to-r from-amber-500 to-orange-500' : 'w-0 bg-gradient-to-r from-amber-500 to-orange-500' }}"></div>
            </div>
        @endforeach
    </div>

    <!-- User info with coffee milk design -->
    <div class="absolute top-16 left-4 right-4 z-30">
        <div class="bg-white/98 dark:bg-amber-900/95 backdrop-blur-sm rounded-lg border border-amber-200 dark:border-amber-600 p-3 shadow-lg">
            <div class="flex items-center space-x-3">
                <!-- Enhanced Profile Picture -->
                <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-amber-400 dark:ring-amber-500 shadow-md">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-amber-300 to-orange-400 dark:from-amber-600 dark:to-orange-600 flex items-center justify-center">
                            <span class="text-white dark:text-amber-200 font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1">
                    <h3 class="text-amber-900 dark:text-amber-100 font-semibold text-base">{{ $user->name }}</h3>
                    <p class="text-amber-700 dark:text-amber-300 text-sm flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $story->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Story content with coffee milk theme -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-28 pb-36">
        <div class="relative max-w-lg w-full">
            <!-- Coffee milk frame -->
            <div class="absolute -inset-2 bg-gradient-to-br from-amber-200 via-orange-200 to-yellow-200 dark:from-amber-800 dark:via-orange-800 dark:to-yellow-800 rounded-3xl blur-sm opacity-30"></div>
            
            <!-- Content container -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-white dark:bg-amber-900/90 border-2 border-amber-300 dark:border-amber-600">
                @if($story->media_type === 'image')
                    <img src="{{ Storage::url($story->media_path) }}" alt="Story" class="w-full h-auto max-h-[75vh] object-cover">
                @elseif($story->media_type === 'video')
                    <video src="{{ Storage::url($story->media_path) }}" class="w-full h-auto max-h-[75vh] object-cover" controls autoplay></video>
                @endif
                
                <!-- Coffee gradient overlay for better readability if there's a caption -->
                @if($story->caption)
                    <div class="absolute inset-0 bg-gradient-to-t from-amber-900/40 via-transparent to-transparent"></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Caption with coffee theme -->
    @if($story->caption)
        <div class="absolute bottom-24 left-4 right-4 z-30">
            <div class="bg-amber-50/98 dark:bg-amber-900/98 backdrop-blur-sm rounded-xl border border-amber-300 dark:border-amber-600 p-4 shadow-xl">
                <p class="text-amber-900 dark:text-amber-100 font-medium leading-relaxed text-center">{{ $story->caption }}</p>
            </div>
        </div>
    @endif

    <!-- Navigation arrows with better positioning and coffee theme -->
    @if($index > 0)
        <a href="{{ route('user.stories.view', [$user, $index - 1]) }}" class="group absolute left-4 top-1/2 transform -translate-y-1/2 z-30 w-12 h-12 rounded-full bg-amber-100/95 dark:bg-amber-800/90 shadow-xl flex items-center justify-center text-amber-800 dark:text-amber-200 hover:bg-amber-200/95 dark:hover:bg-amber-700/90 transition-all duration-200 border border-amber-300 dark:border-amber-600">
            <svg class="w-6 h-6 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
    @endif

    @if($index < $stories->count() - 1)
        <a href="{{ route('user.stories.view', [$user, $index + 1]) }}" class="group absolute right-4 top-1/2 transform -translate-y-1/2 z-30 w-12 h-12 rounded-full bg-amber-100/95 dark:bg-amber-800/90 shadow-xl flex items-center justify-center text-amber-800 dark:text-amber-200 hover:bg-amber-200/95 dark:hover:bg-amber-700/90 transition-all duration-200 border border-amber-300 dark:border-amber-600">
            <svg class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    @endif

    <!-- Story info with coffee theme -->
    <div class="absolute bottom-4 left-4 right-4 z-30">
        <div class="bg-amber-50/98 dark:bg-amber-900/98 backdrop-blur-sm rounded-xl border border-amber-300 dark:border-amber-600 p-4 shadow-xl">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center space-x-2 text-amber-900 dark:text-amber-100">
                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">{{ $story->getViewersCount() }} views</span>
                </div>
                <div class="flex items-center space-x-2 text-amber-900 dark:text-amber-100">
                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">{{ $index + 1 }} of {{ $stories->count() }}</span>
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