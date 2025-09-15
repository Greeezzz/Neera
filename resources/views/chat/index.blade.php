<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-coffee-800 leading-tight flex items-center gap-2">
            ☕ Your Conversations
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-coffee p-6">
                @if($conversations->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-coffee-300 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-cream-50 text-2xl">💬</span>
                        </div>
                        <p class="text-coffee-700">No chats yet. Start a conversation from a friend's profile.</p>
                    </div>
                @else
                    <ul class="divide-y divide-coffee-200/30">
                        @foreach($conversations as $conv)
                            @php
                                $other = $conv->user_one_id === $user->id ? $conv->userTwo : $conv->userOne;
                            @endphp
                            <li>
                                <a href="{{ route('chat.show', $other) }}" class="flex items-center gap-4 p-4 hover:bg-cream-100 rounded-lg transition">
                                    @if($other->profile_picture)
                                        <img src="{{ Storage::url($other->profile_picture) }}" class="w-12 h-12 rounded-full object-cover border-2 border-coffee-300" />
                                    @else
                                        <div class="w-12 h-12 bg-coffee-400 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold">{{ strtoupper(substr($other->name,0,1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center">
                                            <p class="font-semibold text-coffee-800">{{ $other->name }}</p>
                                            <span class="text-xs text-coffee-500">{{ optional($conv->last_message_at)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
