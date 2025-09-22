@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Stories Section with Coffee Milk Theme -->
    <div class="card-coffee mb-8 overflow-hidden">
        <!-- Coffee Milk Header Background -->
        <div class="relative bg-gradient-to-r from-orange-50 via-amber-50 to-yellow-50 dark:from-orange-900/20 dark:via-amber-900/20 dark:to-yellow-900/20 border-b-2 border-orange-100 dark:border-orange-800">
            <div class="absolute inset-0 opacity-5 dark:opacity-3" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><g fill="%23A0522D"><circle cx="10" cy="10" r="1.5"/><circle cx="50" cy="20" r="1.5"/><circle cx="25" cy="40" r="1.5"/><circle cx="40" cy="5" r="1.5"/><circle cx="5" cy="35" r="1.5"/><circle cx="55" cy="50" r="1.5"/></g></svg>'); background-size: 60px 60px;"></div>
            
            <div class="relative p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-300 via-amber-300 to-yellow-300 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600 dark:from-orange-300 dark:via-amber-300 dark:to-yellow-300 bg-clip-text text-transparent">Coffee Stories</h2>
                            <p class="text-orange-600 dark:text-orange-400 text-sm">Share your daily coffee moments</p>
                        </div>
                    </div>
                    <a href="{{ route('stories.create') }}" class="group relative inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-orange-400 via-amber-400 to-yellow-400 hover:from-orange-500 hover:via-amber-500 hover:to-yellow-500 text-white font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Brew Story
                        
                        <!-- Coffee steam effect -->
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-white/50 rounded-full animate-ping"></div>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if($storiesData->count() > 0)
                <div class="relative">
                    <!-- Enhanced Left Arrow -->
                    <button onclick="scrollStories('left')" class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 dark:from-amber-600 dark:to-orange-700 rounded-full shadow-xl flex items-center justify-center text-white hover:shadow-2xl hover:scale-110 transition-all duration-300 group" style="display: none;" id="stories-left-arrow">
                        <svg class="w-6 h-6 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Stories Container -->
                    <div id="stories-container" class="flex space-x-6 overflow-x-auto pb-6 scroll-smooth px-8" style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($storiesData as $data)
                            <div class="flex-shrink-0 relative group">
                                <a href="{{ route('user.stories', $data['user']) }}" class="block group-hover:scale-105 transition-all duration-300">
                                    <div class="relative">
                                        <!-- Enhanced Avatar with Coffee Ring Animation -->
                                        <div class="relative">
                                            <!-- Outer animated ring for unseen stories -->
                                            @if($data['has_unseen'])
                                                <div class="absolute inset-0 rounded-full animate-spin" style="background: conic-gradient(from 0deg, #f59e0b, #ea580c, #eab308, #f59e0b); animation-duration: 3s;">
                                                    <div class="absolute inset-1 rounded-full bg-white dark:bg-gray-900"></div>
                                                </div>
                                            @endif
                                            
                                            <!-- Main avatar container -->
                                            <div class="relative w-20 h-20 rounded-full overflow-hidden {{ $data['has_unseen'] ? 'ring-4 ring-gradient-to-r from-amber-400 via-orange-400 to-yellow-400 ring-offset-2 ring-offset-white dark:ring-offset-gray-900' : 'ring-3 ring-gray-200 dark:ring-gray-700' }} shadow-xl hover:shadow-2xl transition-all duration-300">
                                                @if($data['user']->profile_picture)
                                                    <img src="{{ Storage::url($data['user']->profile_picture) }}" alt="{{ $data['user']->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-amber-200 via-orange-200 to-yellow-200 dark:from-amber-700 dark:via-orange-700 dark:to-yellow-700 flex items-center justify-center relative overflow-hidden">
                                                        <!-- Coffee pattern background -->
                                                        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><g fill="%23D97706"><circle cx="8" cy="8" r="1"/><circle cx="32" cy="16" r="1"/><circle cx="16" cy="28" r="1"/><circle cx="28" cy="4" r="1"/></g></svg>'); background-size: 40px 40px;"></div>
                                                        <span class="relative text-amber-800 dark:text-amber-200 font-bold text-xl">{{ substr($data['user']->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Coffee cup badge with story count -->
                                            <div class="absolute -bottom-1 -right-1 bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-500 text-white text-xs rounded-full w-7 h-7 flex items-center justify-center font-bold shadow-lg border-2 border-white dark:border-gray-900 hover:scale-110 transition-all duration-300">
                                                {{ $data['stories']->count() }}
                                            </div>
                                            
                                            <!-- Online status indicator (coffee steam) -->
                                            @if($data['has_unseen'])
                                                <div class="absolute -top-1 -right-1">
                                                    <div class="flex space-x-0.5">
                                                        <div class="w-1 h-3 bg-amber-400 rounded-full animate-bounce" style="animation-delay: 0ms; animation-duration: 1s;"></div>
                                                        <div class="w-1 h-2 bg-orange-400 rounded-full animate-bounce" style="animation-delay: 200ms; animation-duration: 1s;"></div>
                                                        <div class="w-1 h-3 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 400ms; animation-duration: 1s;"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <!-- Enhanced User Name -->
                                    <div class="mt-3 text-center">
                                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-200 truncate max-w-20 mx-auto">
                                            {{ $data['user']->name }}
                                        </p>
                                        @if($data['has_unseen'])
                                            <div class="flex items-center justify-center mt-1">
                                                <div class="w-2 h-2 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full animate-pulse"></div>
                                                <span class="text-xs text-amber-600 dark:text-amber-400 ml-1 font-medium">New</span>
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Viewed</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Enhanced Right Arrow -->
                    <button onclick="scrollStories('right')" class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 dark:from-amber-600 dark:to-orange-700 rounded-full shadow-xl flex items-center justify-center text-white hover:shadow-2xl hover:scale-110 transition-all duration-300 group" style="display: none;" id="stories-right-arrow">
                        <svg class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            @else
                <!-- Empty State with Coffee Theme -->
                <div class="text-center py-12">
                    <div class="relative inline-block mb-6">
                        <!-- Coffee cup illustration -->
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-amber-100 via-orange-100 to-yellow-100 dark:from-amber-800 dark:via-orange-800 dark:to-yellow-800 rounded-full flex items-center justify-center shadow-xl">
                            <svg class="w-12 h-12 text-amber-600 dark:text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a2 2 0 01-2 2H4a2 2 0 01-2-2V8a1 1 0 00-.553.894l2 12A2 2 0 005.618 22h8.764a2 2 0 002.171-1.106l2-12z"/>
                            </svg>
                        </div>
                        <!-- Steam animation -->
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 flex space-x-1">
                            <div class="w-1 h-4 bg-amber-300 dark:bg-amber-500 rounded-full animate-bounce opacity-70" style="animation-delay: 0ms; animation-duration: 2s;"></div>
                            <div class="w-1 h-3 bg-orange-300 dark:bg-orange-500 rounded-full animate-bounce opacity-70" style="animation-delay: 400ms; animation-duration: 2s;"></div>
                            <div class="w-1 h-4 bg-yellow-300 dark:bg-yellow-500 rounded-full animate-bounce opacity-70" style="animation-delay: 800ms; animation-duration: 2s;"></div>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-amber-700 via-orange-600 to-yellow-600 dark:from-amber-300 dark:via-orange-300 dark:to-yellow-300 bg-clip-text text-transparent mb-3">No Coffee Stories Yet</h3>
                    <p class="text-amber-600 dark:text-amber-400 mb-6 text-lg max-w-md mx-auto">Ready to brew your first story? Share your daily coffee moments with friends!</p>
                    
                    <a href="{{ route('stories.create') }}" class="inline-flex items-center px-8 py-4 rounded-full bg-gradient-to-r from-amber-500 via-orange-500 to-yellow-500 hover:from-amber-600 hover:via-orange-600 hover:to-yellow-600 text-white font-bold shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 text-lg group">
                        <svg class="w-6 h-6 mr-3 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Brew Your First Story
                        
                        <!-- Sparkle effect -->
                        <div class="absolute inset-0 rounded-full">
                            <div class="absolute top-2 right-4 w-1 h-1 bg-white rounded-full animate-ping"></div>
                            <div class="absolute bottom-3 left-6 w-1 h-1 bg-white rounded-full animate-ping" style="animation-delay: 1s;"></div>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Feed Section -->
    <div class="space-y-8">
        <div class="card-coffee overflow-hidden">
            <!-- Coffee-themed header -->
            <div class="relative bg-gradient-to-r from-amber-50 via-orange-50 to-yellow-50 dark:from-amber-900/10 dark:via-orange-900/10 dark:to-yellow-900/10 border-b border-amber-100 dark:border-amber-800/50">
                <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><g fill="%23D97706"><path d="M50 20c-5 0-10 5-10 10s5 10 10 10 10-5 10-10-5-10-10-10zm0 5c2.5 0 5 2.5 5 5s-2.5 5-5 5-5-2.5-5-5 2.5-5 5-5z"/></g></svg>'); background-size: 100px 100px;"></div>
                
                <div class="relative p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-400 via-orange-400 to-yellow-400 rounded-full shadow-lg mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold bg-gradient-to-r from-amber-700 via-orange-600 to-yellow-600 dark:from-amber-300 dark:via-orange-300 dark:to-yellow-300 bg-clip-text text-transparent mb-3">Coffee Feed</h3>
                    <p class="text-amber-600 dark:text-amber-400 text-lg max-w-2xl mx-auto">Discover the latest posts, coffee reviews, and updates from your coffee community. Stay connected with fellow coffee enthusiasts!</p>
                    
                    <!-- Decorative coffee beans -->
                    <div class="absolute top-4 left-8 transform rotate-12">
                        <div class="w-3 h-5 bg-amber-600 rounded-full opacity-20"></div>
                    </div>
                    <div class="absolute top-8 right-12 transform -rotate-12">
                        <div class="w-2 h-4 bg-orange-600 rounded-full opacity-20"></div>
                    </div>
                    <div class="absolute bottom-6 left-16 transform rotate-45">
                        <div class="w-2 h-3 bg-yellow-600 rounded-full opacity-20"></div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 dark:from-amber-900/20 dark:via-orange-900/20 dark:to-yellow-900/20 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                    <p class="text-center text-amber-700 dark:text-amber-300 text-lg font-medium">
                        ☕ Your regular posts and coffee updates will appear here ☕
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced JavaScript with Coffee Theme -->
<script>
function scrollStories(direction) {
    const container = document.getElementById('stories-container');
    const scrollAmount = 300;
    
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
    
    // Update arrow visibility after scroll
    setTimeout(updateArrows, 300);
}

function updateArrows() {
    const container = document.getElementById('stories-container');
    const leftArrow = document.getElementById('stories-left-arrow');
    const rightArrow = document.getElementById('stories-right-arrow');
    
    if (!container || !leftArrow || !rightArrow) return;
    
    const isScrollable = container.scrollWidth > container.clientWidth;
    const isAtStart = container.scrollLeft <= 10; // Small buffer
    const isAtEnd = container.scrollLeft >= (container.scrollWidth - container.clientWidth - 10);
    
    if (isScrollable) {
        leftArrow.style.display = isAtStart ? 'none' : 'flex';
        rightArrow.style.display = isAtEnd ? 'none' : 'flex';
    } else {
        leftArrow.style.display = 'none';
        rightArrow.style.display = 'none';
    }
}

// Initialize coffee-themed interactions
document.addEventListener('DOMContentLoaded', function() {
    updateArrows();
    
    const container = document.getElementById('stories-container');
    if (container) {
        container.addEventListener('scroll', updateArrows);
        // Enhanced hide scrollbar
        container.style.scrollbarWidth = 'none';
        container.style.msOverflowStyle = 'none';
        
        // Add coffee aroma effect on hover
        container.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        container.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
        });
    }
});

// Add coffee bubble effect on story interaction
function addCoffeeBubble(element) {
    const bubble = document.createElement('div');
    bubble.className = 'absolute w-2 h-2 bg-orange-400 rounded-full pointer-events-none';
    bubble.style.left = Math.random() * 20 + 'px';
    bubble.style.top = Math.random() * 20 + 'px';
    bubble.style.animation = 'bubble 1s ease-out forwards';
    
    element.appendChild(bubble);
    setTimeout(() => bubble.remove(), 1000);
}

// Add CSS for bubble animation
const style = document.createElement('style');
style.textContent = `
    @keyframes bubble {
        0% { opacity: 1; transform: translateY(0) scale(1); }
        100% { opacity: 0; transform: translateY(-20px) scale(0); }
    }
    
    @keyframes steam {
        0%, 100% { transform: translateY(0) scale(1); opacity: 0.7; }
        50% { transform: translateY(-5px) scale(1.1); opacity: 1; }
    }
    
    .coffee-steam {
        transition: opacity 0.3s ease;
    }
`;
document.head.appendChild(style);
</script>
@endsection