@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card-coffee">
        <div class="p-6">
            <!-- Story header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-orange-200 dark:border-orange-600">
                        @if($story->user->profile_picture)
                            <img src="{{ Storage::url($story->user->profile_picture) }}" alt="{{ $story->user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-orange-200 dark:bg-orange-700 flex items-center justify-center">
                                <span class="text-orange-600 dark:text-orange-300 font-semibold">{{ substr($story->user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-orange-800 dark:text-orange-100">{{ $story->user->name }}</h3>
                        <p class="text-sm text-orange-600 dark:text-orange-400">{{ $story->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                @if($story->user_id === Auth::id())
                    <form action="{{ route('stories.destroy', $story) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="group px-4 py-2 bg-gradient-to-r from-red-400 to-pink-400 hover:from-red-500 hover:to-pink-500 text-white rounded-lg font-medium transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl" onclick="return confirm('Delete this coffee story? ☕')">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Story content -->
            <div class="mb-6">
                @if($story->media_type === 'image')
                    <img src="{{ Storage::url($story->media_path) }}" alt="Story" class="w-full rounded-lg shadow-lg">
                @elseif($story->media_type === 'video')
                    <video src="{{ Storage::url($story->media_path) }}" class="w-full rounded-lg shadow-lg" controls></video>
                @endif
            </div>

            <!-- Caption -->
            @if($story->caption)
                <div class="mb-6">
                    <p class="text-orange-700 dark:text-orange-300">{{ $story->caption }}</p>
                </div>
            @endif

            <!-- Story stats -->
            <div class="flex items-center justify-between text-sm text-orange-600 dark:text-orange-400 border-t border-orange-200 dark:border-orange-700 pt-4">
                <span>{{ $story->getViewersCount() }} {{ Str::plural('view', $story->getViewersCount()) }}</span>
                <span>Expires {{ $story->expires_at->diffForHumans() }}</span>
            </div>

            <!-- Actions -->
            <div class="flex justify-between mt-6">
                <a href="{{ route('stories.index') }}" class="px-6 py-2 bg-orange-100 hover:bg-orange-200 dark:bg-orange-800 dark:hover:bg-orange-700 text-orange-800 dark:text-orange-100 rounded-lg font-medium transition-all duration-300">
                    Back to Stories
                </a>
                
                <a href="{{ route('user.stories', $story->user) }}" class="px-6 py-2 bg-gradient-to-r from-orange-400 to-amber-400 hover:from-orange-500 hover:to-amber-500 text-white rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                    View All from {{ $story->user->name }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection