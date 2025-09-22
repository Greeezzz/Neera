<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-coffee-800 dark:text-cream-200 leading-tight flex items-center gap-2">
            ☕ Your Conversations
        </h2>
    </x-slot>

    <div class="py-6 min-h-screen bg-gradient-to-br from-cream-50 to-coffee-100/30 dark:from-coffee-900 dark:to-coffee-800 transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-coffee p-6 backdrop-blur-sm bg-white/80 dark:bg-coffee-800/80 border border-cream-300 dark:border-coffee-600 shadow-xl">
                @if($conversations->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-gradient-to-br from-coffee-400 to-coffee-500 dark:from-coffee-500 dark:to-coffee-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <span class="text-cream-50 text-3xl">💬</span>
                        </div>
                        <h3 class="text-xl font-semibold text-coffee-700 dark:text-cream-200 mb-2">No conversations yet</h3>
                        <p class="text-coffee-600 dark:text-cream-300">Start a conversation from a friend's profile to get started.</p>
                        <div class="mt-6">
                            <a href="{{ route('friends') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-coffee-500 to-coffee-600 hover:from-coffee-600 hover:to-coffee-700 text-white font-medium rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <span class="mr-2">👥</span>
                                Find Friends
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($conversations as $conv)
                            @php
                                $other = $conv->user_one_id === $user->id ? $conv->userTwo : $conv->userOne;
                                $lastMessage = $conv->messages()->latest()->first();
                            @endphp
                            <div class="group">
                                <a href="{{ route('chat.show', $other) }}" class="flex items-center gap-4 p-4 hover:bg-gradient-to-r hover:from-cream-100 hover:to-coffee-50 dark:hover:from-coffee-700 dark:hover:to-coffee-600 rounded-xl transition-all duration-300 border border-transparent hover:border-coffee-200 dark:hover:border-coffee-500 hover:shadow-lg">
                                    <div class="relative">
                                        @if($other->profile_picture)
                                            <img src="{{ Storage::url($other->profile_picture) }}" class="w-14 h-14 rounded-full object-cover border-3 border-coffee-300 dark:border-coffee-400 shadow-md group-hover:shadow-lg transition-shadow" />
                                        @else
                                            <div class="w-14 h-14 bg-gradient-to-br from-coffee-400 to-coffee-500 dark:from-coffee-500 dark:to-coffee-600 rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                                                <span class="text-white font-bold text-lg">{{ strtoupper(substr($other->name,0,1)) }}</span>
                                            </div>
                                        @endif
                                        @if($other->is_online ?? false)
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-coffee-800 rounded-full"></div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-1">
                                            <h3 class="font-semibold text-coffee-800 dark:text-cream-200 text-lg truncate">{{ $other->name }}</h3>
                                            @if($lastMessage)
                                                <span class="text-xs text-coffee-500 dark:text-cream-400 font-medium">
                                                    {{ $lastMessage->created_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($lastMessage)
                                            <div class="flex items-center justify-between">
                                                <p class="text-coffee-600 dark:text-cream-300 text-sm truncate flex-1 mr-2">
                                                    @if($lastMessage->sender_id === $user->id)
                                                        <span class="text-coffee-500 dark:text-cream-400">You: </span>
                                                    @endif
                                                    @if($lastMessage->image_path)
                                                        <span class="flex items-center gap-1">
                                                            📷 <span>Photo</span>
                                                        </span>
                                                    @else
                                                        {{ Str::limit($lastMessage->body, 40) }}
                                                    @endif
                                                </p>
                                                @if($conv->unread_count ?? 0 > 0)
                                                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                                        {{ min($conv->unread_count, 9) }}{{ $conv->unread_count > 9 ? '+' : '' }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-coffee-500 dark:text-cream-400 text-sm italic">No messages yet</p>
                                        @endif
                                    </div>
                                    
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-5 h-5 text-coffee-400 dark:text-cream-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto refresh for new messages (optional)
        setInterval(function() {
            // You can implement real-time updates here with Livewire or SSE
            // Example: Livewire.emit('refreshConversations');
        }, 30000); // Refresh every 30 seconds
    </script>

    <style>
        .card-coffee {
            @apply rounded-xl shadow-lg;
        }
        
        /* Smooth transitions for theme switching */
        * {
            transition-property: background-color, border-color, color;
            transition-duration: 300ms;
            transition-timing-function: ease-in-out;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            @apply bg-cream-100 dark:bg-coffee-800;
        }
        
        ::-webkit-scrollbar-thumb {
            @apply bg-coffee-400 dark:bg-coffee-600 rounded-full;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            @apply bg-coffee-500 dark:bg-coffee-500;
        }
    </style>
</x-app-layout>