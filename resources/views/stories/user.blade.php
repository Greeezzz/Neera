@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- User header -->
    <div class="card-coffee mb-6">
        <div class="p-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-coffee-200 dark:border-coffee-600">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-coffee-200 dark:bg-coffee-700 flex items-center justify-center">
                            <span class="text-coffee-600 dark:text-coffee-300 font-semibold text-xl">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-coffee-800 dark:text-coffee-100">{{ $user->name }}'s Stories</h1>
                    <p class="text-coffee-600 dark:text-coffee-400">{{ $stories->count() }} active {{ Str::plural('story', $stories->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($stories->count() > 0)
        <!-- Stories grid -->
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($stories as $index => $story)
                <a href="{{ route('user.stories.view', [$user, $index]) }}" class="group">
                    <div class="relative aspect-[3/4] rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                        @if($story->media_type === 'image')
                            <img src="{{ Storage::url($story->media_path) }}" alt="Story" class="w-full h-full object-cover">
                        @elseif($story->media_type === 'video')
                            <video src="{{ Storage::url($story->media_path) }}" class="w-full h-full object-cover" muted>
                                <source src="{{ Storage::url($story->media_path) }}" type="video/mp4">
                            </video>
                            <!-- Video play icon -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-8 h-8 bg-black bg-opacity-50 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        
                        <!-- Story info -->
                        <div class="absolute bottom-3 left-3 right-3 text-white">
                            @if($story->caption)
                                <p class="text-sm line-clamp-2 mb-1">{{ $story->caption }}</p>
                            @endif
                            <p class="text-xs opacity-80">{{ $story->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <!-- View count -->
                        <div class="absolute top-3 right-3">
                            <div class="bg-black bg-opacity-50 rounded-full px-2 py-1 flex items-center space-x-1">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="text-white text-xs">{{ $story->getViewersCount() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <!-- No stories state -->
        <div class="card-coffee">
            <div class="p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-coffee-400 dark:text-coffee-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                <h3 class="text-xl font-medium text-coffee-800 dark:text-coffee-200 mb-2">No Active Stories</h3>
                <p class="text-coffee-600 dark:text-coffee-400 mb-6">{{ $user->name }} doesn't have any active stories right now.</p>
                
                @if(Auth::id() === $user->id)
                    <a href="{{ route('stories.create') }}" class="btn-coffee-primary">Create Your First Story</a>
                @else
                    <a href="{{ route('stories.index') }}" class="btn-coffee-secondary">Back to Stories</a>
                @endif
            </div>
        </div>
    @endif

    <!-- Back button -->
    <div class="mt-6">
        <a href="{{ route('stories.index') }}" class="btn-coffee-secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to All Stories
        </a>
    </div>
</div>
@endsection