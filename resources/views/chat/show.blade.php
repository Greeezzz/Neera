<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('chat.index') }}" class="p-2 rounded-full bg-cream-200 dark:bg-coffee-700 hover:bg-cream-300 dark:hover:bg-coffee-600 transition-colors">
                    <svg class="w-5 h-5 text-coffee-600 dark:text-cream-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                @if($other->profile_picture)
                    <img src="{{ Storage::url($other->profile_picture) }}" class="w-10 h-10 rounded-full object-cover border-2 border-coffee-300 dark:border-coffee-400" />
                @else
                    <div class="w-10 h-10 bg-gradient-to-br from-coffee-400 to-coffee-500 dark:from-coffee-500 dark:to-coffee-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr($other->name,0,1)) }}</span>
                    </div>
                @endif
                <div>
                    <div class="text-coffee-800 dark:text-cream-200 font-semibold">{{ $other->name }}</div>
                    <div class="text-xs text-coffee-500 dark:text-cream-400 flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span>Online</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="toggleInfo()" class="p-2 rounded-full bg-cream-200 dark:bg-coffee-700 hover:bg-cream-300 dark:hover:bg-coffee-600 transition-colors">
                    <svg class="w-5 h-5 text-coffee-600 dark:text-cream-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-cream-50 to-coffee-100/30 dark:from-coffee-900 dark:to-coffee-800 transition-colors duration-300">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="card-coffee overflow-hidden flex flex-col bg-white/90 dark:bg-coffee-800/90 backdrop-blur-sm border border-cream-300 dark:border-coffee-600 shadow-xl" style="height: 85vh;">
                <!-- Messages area -->
                <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                    @foreach($conversation->messages as $m)
                        <div class="flex {{ $m->sender_id === $me->id ? 'justify-end' : 'justify-start' }} group" data-id="{{ $m->id }}" data-me="{{ $m->sender_id === $me->id ? '1' : '0' }}">
                            <div tabindex="0" class="chat-bubble {{ $m->sender_id === $me->id ? 'me' : 'other' }} relative max-w-[75%] md:max-w-[60%] rounded-2xl px-4 py-3 shadow-md hover:shadow-lg transition-all duration-300 {{ $m->sender_id === $me->id ? 'bg-gradient-to-br from-coffee-500 to-coffee-600 text-white rounded-br-md' : 'bg-white dark:bg-coffee-700 text-coffee-800 dark:text-cream-200 rounded-bl-md' }}">
                                @if($m->body)
                                    <div class="whitespace-pre-wrap msg-body text-sm leading-relaxed">{{ $m->body }}</div>
                                @endif
                                @if($m->image_path)
                                    <div class="mt-2 relative group/image">
                                        <img src="{{ Storage::url($m->image_path) }}" class="rounded-xl max-h-64 w-full object-cover shadow-md hover:shadow-lg transition-shadow cursor-pointer" onclick="openImageModal('{{ Storage::url($m->image_path) }}')" />
                                        <div class="absolute inset-0 bg-black opacity-0 hover:opacity-20 transition-opacity rounded-xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between mt-2">
                                    <div class="text-[10px] {{ $m->sender_id === $me->id ? 'text-cream-200' : 'text-coffee-400 dark:text-cream-500' }} opacity-80">
                                        {{ $m->created_at->format('H:i') }}
                                    </div>
                                    @if($m->sender_id === $me->id)
                                        <div class="flex items-center text-cream-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <svg class="w-3 h-3 -ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                @if($m->sender_id === $me->id)
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <div class="relative">
                                            <button aria-label="Message menu" class="w-7 h-7 rounded-full bg-cream-200/80 hover:bg-cream-300 dark:bg-coffee-600/80 dark:hover:bg-coffee-500 flex items-center justify-center shadow cursor-pointer backdrop-blur-sm" onclick="toggleMenu(event, {{ $m->id }})">
                                                <svg class="w-4 h-4 text-coffee-600 dark:text-cream-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                            <div id="menu-{{ $m->id }}" class="hidden absolute z-20 mt-1 right-0 bg-white dark:bg-coffee-700 border border-coffee-200 dark:border-coffee-600 rounded-lg shadow-xl overflow-hidden backdrop-blur-sm">
                                                <button class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-coffee-700 dark:text-cream-200 hover:bg-cream-100 dark:hover:bg-coffee-600 transition-colors" onclick="startEdit({{ $m->id }})">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" onclick="enterSelectMode({{ $m->id }})">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Typing indicator -->
                <div id="typing-indicator" class="hidden px-4 pb-2">
                    <div class="flex justify-start">
                        <div class="bg-cream-200 dark:bg-coffee-600 rounded-2xl px-4 py-2 rounded-bl-md">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-coffee-400 dark:bg-cream-400 rounded-full animate-bounce"></div>
                                <div class="w-2 h-2 bg-coffee-400 dark:bg-cream-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                <div class="w-2 h-2 bg-coffee-400 dark:bg-cream-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input area -->
                <div class="border-t border-coffee-200/40 dark:border-coffee-600/40 p-4 bg-white/80 dark:bg-coffee-800/80 backdrop-blur-sm">
                    <form id="sendForm" action="{{ route('chat.send', $other) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-3">
                        @csrf
                        <label class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-br from-coffee-400 to-coffee-500 hover:from-coffee-500 hover:to-coffee-600 cursor-pointer transition-all duration-300 hover:scale-105 shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="handleFileSelect(this)" />
                        </label>
                        
                        <div class="flex-1 relative">
                            <textarea 
                                id="body" 
                                name="body" 
                                autocomplete="off" 
                                placeholder="Type a message..." 
                                rows="1"
                                class="w-full max-h-32 resize-none rounded-xl px-4 py-3 bg-cream-100/80 dark:bg-coffee-700/80 border border-coffee-200/40 dark:border-coffee-600/40 focus:bg-white dark:focus:bg-coffee-700 focus:border-coffee-400 dark:focus:border-coffee-500 focus:ring-2 focus:ring-coffee-300/30 dark:focus:ring-coffee-500/30 outline-none text-coffee-800 dark:text-cream-200 placeholder-coffee-500 dark:placeholder-cream-400 transition-all duration-300"
                                oninput="autoResize(this); handleTyping()"
                                onkeydown="handleKeyPress(event)"
                            ></textarea>
                            <div id="file-preview" class="hidden mt-2 p-2 bg-cream-100 dark:bg-coffee-600 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <img id="preview-image" class="w-12 h-12 rounded object-cover">
                                    <span id="preview-name" class="text-sm text-coffee-700 dark:text-cream-200"></span>
                                    <button type="button" onclick="clearFilePreview()" class="ml-auto text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-r from-coffee-500 to-coffee-600 hover:from-coffee-600 hover:to-coffee-700 text-white font-medium shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div id="info-sidebar" class="fixed top-0 right-0 h-full w-80 bg-white/95 dark:bg-coffee-800/95 backdrop-blur-sm border-l border-coffee-200 dark:border-coffee-600 shadow-2xl transform translate-x-full transition-transform duration-300 z-40">
        <div class="p-6 border-b border-coffee-200 dark:border-coffee-600">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-coffee-800 dark:text-cream-200">Chat Info</h3>
                <button onclick="toggleInfo()" class="p-2 rounded-full hover:bg-coffee-100 dark:hover:bg-coffee-700 transition-colors">
                    <svg class="w-5 h-5 text-coffee-600 dark:text-cream-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- User Profile Section -->
            <div class="text-center">
                @if($other->profile_picture)
                    <img src="{{ Storage::url($other->profile_picture) }}" class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-coffee-300 dark:border-coffee-500 shadow-lg" />
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-coffee-400 to-coffee-500 dark:from-coffee-500 dark:to-coffee-600 rounded-full flex items-center justify-center mx-auto border-4 border-coffee-300 dark:border-coffee-500 shadow-lg">
                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr($other->name,0,1)) }}</span>
                    </div>
                @endif
                <h4 class="mt-3 text-xl font-bold text-coffee-800 dark:text-cream-200">{{ $other->name }}</h4>
                @if($other->bio)
                    <p class="mt-2 text-sm text-coffee-600 dark:text-cream-300 leading-relaxed">{{ $other->bio }}</p>
                @endif
                <div class="flex items-center justify-center gap-1 mt-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-xs text-coffee-500 dark:text-cream-400">Online</span>
                </div>
            </div>
        </div>
        
        <!-- User Stats -->
        <div class="p-6 border-b border-coffee-200 dark:border-coffee-600">
            <h5 class="text-sm font-semibold text-coffee-700 dark:text-cream-300 mb-3">User Stats</h5>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-coffee-600 dark:text-cream-400">Joined</span>
                    <span class="text-sm font-medium text-coffee-800 dark:text-cream-200">{{ $other->created_at->format('M Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-coffee-600 dark:text-cream-400">Posts</span>
                    <span class="text-sm font-medium text-coffee-800 dark:text-cream-200">{{ $other->posts()->count() ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-coffee-600 dark:text-cream-400">Friends</span>
                    <span class="text-sm font-medium text-coffee-800 dark:text-cream-200">{{ $other->friends()->count() ?? 0 }}</span>
                </div>
                @if(method_exists($other, 'followers'))
                <div class="flex justify-between items-center">
                    <span class="text-sm text-coffee-600 dark:text-cream-400">Followers</span>
                    <span class="text-sm font-medium text-coffee-800 dark:text-cream-200">{{ $other->followers()->count() ?? 0 }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        <div class="p-6">
            <h5 class="text-sm font-semibold text-coffee-700 dark:text-cream-300 mb-3">Actions</h5>
            <div class="space-y-3">
                <a href="{{ route('user.profile', $other->id) }}" class="w-full flex items-center gap-3 p-3 rounded-lg bg-coffee-100 dark:bg-coffee-700 hover:bg-coffee-200 dark:hover:bg-coffee-600 transition-colors">
                    <span class="text-lg">👤</span>
                    <span class="text-sm font-medium text-coffee-800 dark:text-cream-200">View Profile</span>
                </a>
                @if(auth()->user()->friendshipStatusWith($other->id) === null)
                <button class="w-full flex items-center gap-3 p-3 rounded-lg bg-latte-100 dark:bg-coffee-700 hover:bg-latte-200 dark:hover:bg-coffee-600 transition-colors">
                    <span class="text-lg">👥</span>
                    <span class="text-sm font-medium text-coffee-800 dark:text-cream-200">Add Friend</span>
                </button>
                @endif
                <button class="w-full flex items-center gap-3 p-3 rounded-lg bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                    <span class="text-lg">🚫</span>
                    <span class="text-sm font-medium text-red-700 dark:text-red-400">Block User</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Overlay for sidebar -->
    <div id="info-overlay" class="fixed inset-0 bg-black/50 hidden transition-opacity duration-300 z-30" onclick="toggleInfo()"></div>

    <!-- Image Modal -->
    <div id="image-modal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-full">
            <img id="modal-image" class="max-w-full max-h-full rounded-lg shadow-2xl">
            <button onclick="closeImageModal()" class="absolute top-2 right-2 w-8 h-8 bg-black bg-opacity-50 text-white rounded-full flex items-center justify-center hover:bg-opacity-75 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
        const messagesEl = document.getElementById('messages');
        let selectMode = false;
        const selectedIds = new Set();
        let typingTimer;
        let isTyping = false;

        function scrollToBottom(){ 
            messagesEl.scrollTop = messagesEl.scrollHeight; 
        }
        scrollToBottom();

        // Auto-resize textarea
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 128) + 'px';
        }

        // Handle typing indicator
        function handleTyping() {
            if (!isTyping) {
                isTyping = true;
                // Send typing start event
            }
            
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                isTyping = false;
                // Send typing stop event
            }, 2000);
        }

        // Handle Enter key press
        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                document.getElementById('sendForm').submit();
            }
        }

        // File handling
        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-name').textContent = file.name;
                    document.getElementById('file-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function clearFilePreview() {
            document.getElementById('file-preview').classList.add('hidden');
            document.querySelector('input[name="image"]').value = '';
        }

        // Image modal
        function openImageModal(src) {
            document.getElementById('modal-image').src = src;
            document.getElementById('image-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('image-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Message rendering
        function renderMessage(m){
            const wrapper = document.createElement('div');
            wrapper.className = 'flex ' + (m.me ? 'justify-end' : 'justify-start') + ' group';
            wrapper.dataset.id = m.id;
            wrapper.dataset.me = m.me ? '1' : '0';
            
            const bubble = document.createElement('div');
            bubble.className = 'chat-bubble ' + (m.me ? 'me ' : 'other ') + 'relative max-w-[75%] md:max-w-[60%] rounded-2xl px-4 py-3 shadow-md hover:shadow-lg transition-all duration-300 ' + (m.me ? 'bg-gradient-to-br from-coffee-500 to-coffee-600 text-white rounded-br-md' : 'bg-white dark:bg-coffee-700 text-coffee-800 dark:text-cream-200 rounded-bl-md');
            
            if(m.body){
                const t = document.createElement('div');
                t.className = 'whitespace-pre-wrap msg-body text-sm leading-relaxed';
                t.textContent = m.body;
                bubble.appendChild(t);
            }
            
            if(m.image_url){
                const imgContainer = document.createElement('div');
                imgContainer.className = 'mt-2 relative group/image';
                const img = document.createElement('img');
                img.src = m.image_url;
                img.className = 'rounded-xl max-h-64 w-full object-cover shadow-md hover:shadow-lg transition-shadow cursor-pointer';
                img.onclick = () => openImageModal(m.image_url);
                imgContainer.appendChild(img);
                bubble.appendChild(imgContainer);
            }
            
            const timeContainer = document.createElement('div');
            timeContainer.className = 'flex items-center justify-between mt-2';
            
            const time = document.createElement('div');
            time.className = 'text-[10px] ' + (m.me ? 'text-cream-200' : 'text-coffee-400 dark:text-cream-500') + ' opacity-80';
            time.textContent = new Date(m.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'});
            timeContainer.appendChild(time);
            
            if(m.me){
                const readStatus = document.createElement('div');
                readStatus.className = 'flex items-center text-cream-200';
                readStatus.innerHTML = `
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="w-3 h-3 -ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                `;
                timeContainer.appendChild(readStatus);
            }
            
            bubble.appendChild(timeContainer);
            
            if(m.me){
                const menuWrap = document.createElement('div');
                menuWrap.className = 'absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200';
                menuWrap.innerHTML = `<div class="relative">
                    <button class="w-7 h-7 rounded-full bg-cream-200/80 hover:bg-cream-300 dark:bg-coffee-600/80 dark:hover:bg-coffee-500 flex items-center justify-center shadow cursor-pointer backdrop-blur-sm" onclick="toggleMenu(event, ${m.id})">
                        <svg class="w-4 h-4 text-coffee-600 dark:text-cream-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                    <div id="menu-${m.id}" class="hidden absolute z-20 mt-1 right-0 bg-white dark:bg-coffee-700 border border-coffee-200 dark:border-coffee-600 rounded-lg shadow-xl overflow-hidden backdrop-blur-sm">
                        <button class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-coffee-700 dark:text-cream-200 hover:bg-cream-100 dark:hover:bg-coffee-600 transition-colors" onclick="startEdit(${m.id})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                        <button class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" onclick="enterSelectMode(${m.id})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>`;
                bubble.appendChild(menuWrap);
            }
            
            wrapper.appendChild(bubble);
            messagesEl.appendChild(wrapper);
            if(selectMode) addSelectCheckbox(wrapper, m.id);
        }

        // SSE real-time stream
        let lastId = {{ optional($conversation->messages->last())->id ?? 0 }};
        const es = new EventSource(`{{ route('chat.stream', $other) }}?since=${encodeURIComponent(new Date().toISOString())}`);
        es.addEventListener('message', (evt) => {
            try {
                const m = JSON.parse(evt.data);
                if(m.deleted){
                    const node = messagesEl.querySelector(`[data-id="${m.id}"] .msg-body`);
                    if(node){ 
                        node.textContent = '(deleted)'; 
                        node.style.fontStyle = 'italic';
                        node.style.opacity = '0.7';
                    }
                    return;
                }
                const existing = messagesEl.querySelector(`[data-id="${m.id}"]`);
                if(existing){
                    const body = existing.querySelector('.msg-body');
                    if(body) body.textContent = m.body ?? '';
                }else{
                    renderMessage(m);
                }
                scrollToBottom();
            } catch(e) {
                console.error('Error parsing SSE message:', e);
            }
        });

        // Menu functions
        function toggleMenu(e, id){
            e.stopPropagation();
            document.querySelectorAll('[id^="menu-"]').forEach(el=>el.classList.add('hidden'));
            const m = document.getElementById(`menu-${id}`); 
            if(m) m.classList.toggle('hidden');
        }

        document.addEventListener('click', ()=>{
            document.querySelectorAll('[id^="menu-"]').forEach(el=>el.classList.add('hidden'));
        });

        // Selection mode functions
        function addSelectCheckbox(wrapper, id){
            if(wrapper.dataset.me !== '1') return;
            if(wrapper.querySelector('.select-box')) return;
            const box = document.createElement('label');
            box.className = 'select-box absolute -top-3 right-2 bg-cream-100 dark:bg-coffee-600 border border-coffee-200 dark:border-coffee-500 rounded-full w-6 h-6 flex items-center justify-center shadow-md';
            box.innerHTML = `<input type="checkbox" class="peer sr-only" onchange="toggleSelect(${id}, this.checked)">
                             <span class="w-4 h-4 rounded-full border-2 border-coffee-400 dark:border-coffee-400 peer-checked:bg-red-500 peer-checked:border-red-500"></span>`;
            wrapper.style.position = 'relative';
            wrapper.appendChild(box);
        }

        function enterSelectMode(initialId){
            selectMode = true;
            selectedIds.clear();
            messagesEl.querySelectorAll('[data-id]').forEach(w => addSelectCheckbox(w, w.dataset.id));
            if(initialId){ toggleSelect(initialId, true); }
            showBulkBar();
        }

        function showBulkBar(){
            let bar = document.getElementById('bulk-bar');
            if(!bar){
                bar = document.createElement('div');
                bar.id = 'bulk-bar';
                bar.className = 'sticky bottom-0 z-10 p-3 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30 border-t border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 flex items-center justify-between backdrop-blur-sm';
                bar.innerHTML = `
                  <div class="flex items-center gap-3">
                    <span class="text-sm font-medium">Select messages to delete</span>
                    <span class="px-3 py-1 rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 text-xs font-semibold" id="sel-count">0 selected</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <button class="px-4 py-2 rounded-full bg-cream-200 dark:bg-coffee-700 text-coffee-800 dark:text-cream-200 hover:bg-cream-300 dark:hover:bg-coffee-600 transition-colors font-medium" onclick="exitSelectMode()">Cancel</button>
                    <button class="px-4 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors font-medium shadow-md" onclick="bulkDelete()">Delete</button>
                  </div>`;
                document.querySelector('.card-coffee').appendChild(bar);
            }
        }

        window.exitSelectMode = function(){
            selectMode = false;
            selectedIds.clear();
            document.getElementById('bulk-bar')?.remove();
            messagesEl.querySelectorAll('.select-box').forEach(el=>el.remove());
        }

        window.toggleSelect = function(id, checked){
            if(checked) selectedIds.add(Number(id)); else selectedIds.delete(Number(id));
            const count = document.getElementById('sel-count'); 
            if(count) count.textContent = `${selectedIds.size} selected`;
        }

        window.bulkDelete = async function(){
            const ids = Array.from(selectedIds).filter(id => {
                const w = messagesEl.querySelector(`[data-id="${id}"]`);
                return w && w.dataset.me === '1';
            });
            
            if(ids.length > 0 && confirm(`Are you sure you want to delete ${ids.length} message(s)?`)) {
                for (const id of ids){
                    try{ 
                        await fetch(`{{ url('/chat/'.$other->id.'/messages') }}/${id}`, { 
                            method:'DELETE', 
                            headers:{ 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }); 
                    }catch(e){
                        console.error('Error deleting message:', e);
                    }
                }
            }
            exitSelectMode();
        }

        async function delMsg(id){
            if(confirm('Are you sure you want to delete this message?')) {
                try{ 
                    await fetch(`{{ url('/chat/'.$other->id.'/messages') }}/${id}`, { 
                        method:'DELETE', 
                        headers:{ 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }); 
                }catch(e){
                    console.error('Error deleting message:', e);
                }
            }
        }

        function startEdit(id){
            const node = messagesEl.querySelector(`[data-id="${id}"] .msg-body`);
            if(!node) return;
            const current = node.textContent;
            const input = document.createElement('textarea');
            input.className = 'w-full rounded-lg p-3 text-coffee-800 dark:text-cream-200 bg-white dark:bg-coffee-700 border border-coffee-200 dark:border-coffee-600 focus:border-coffee-400 dark:focus:border-coffee-500 focus:ring-2 focus:ring-coffee-300/30 dark:focus:ring-coffee-500/30 outline-none resize-none';
            input.value = current;
            input.style.minHeight = '60px';
            node.replaceWith(input);
            input.focus();
            
            // Auto resize
            function autoResizeEdit() {
                input.style.height = 'auto';
                input.style.height = input.scrollHeight + 'px';
            }
            input.addEventListener('input', autoResizeEdit);
            autoResizeEdit();
            
            input.addEventListener('keydown', async (e)=>{
                if(e.key==='Enter' && !e.shiftKey){
                    e.preventDefault();
                    try{
                        const response = await fetch(`{{ url('/chat/'.$other->id.'/messages') }}/${id}`, { 
                            method:'PATCH', 
                            headers:{ 
                                'Content-Type':'application/json',
                                'X-CSRF-TOKEN':'{{ csrf_token() }}' 
                            }, 
                            body: JSON.stringify({ body: input.value }) 
                        });
                        if(!response.ok) throw new Error('Failed to update message');
                    }catch(e){
                        console.error('Error updating message:', e);
                        alert('Failed to update message. Please try again.');
                    }
                } else if(e.key === 'Escape') {
                    // Cancel edit
                    const newBody = document.createElement('div');
                    newBody.className = 'whitespace-pre-wrap msg-body text-sm leading-relaxed';
                    newBody.textContent = current;
                    input.replaceWith(newBody);
                }
            });
        }

        function toggleInfo() {
            const sidebar = document.getElementById('info-sidebar');
            const overlay = document.getElementById('info-overlay');
            
            if (sidebar.classList.contains('translate-x-full')) {
                // Show sidebar
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
            } else {
                // Hide sidebar
                sidebar.classList.add('translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        // Form submission
        document.getElementById('sendForm').addEventListener('submit', function(e) {
            const bodyInput = document.getElementById('body');
            const fileInput = document.querySelector('input[name="image"]');
            
            if (!bodyInput.value.trim() && !fileInput.files.length) {
                e.preventDefault();
                return;
            }
            
            // Clear inputs after submission
            setTimeout(() => {
                bodyInput.value = '';
                autoResize(bodyInput);
                clearFilePreview();
            }, 100);
        });
    </script>

    <style>
        .card-coffee {
            @apply rounded-xl shadow-xl;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            @apply bg-cream-100 dark:bg-coffee-700;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            @apply bg-coffee-400 dark:bg-coffee-500 rounded-full;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            @apply bg-coffee-500 dark:bg-coffee-400;
        }
        
        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, opacity;
            transition-duration: 300ms;
            transition-timing-function: ease-in-out;
        }
        
        /* Chat bubble animations */
        .chat-bubble {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Typing indicator animation */
        .animate-bounce {
            animation: bounce 1.4s infinite;
        }
        
        /* Glassmorphism effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }
        
        /* Focus states */
        .focus\:ring-coffee-300\/30:focus {
            --tw-ring-color: rgb(120 113 108 / 0.3);
        }
        
        /* Dark mode focus states */
        .dark .dark\:focus\:ring-coffee-500\/30:focus {
            --tw-ring-color: rgb(87 83 78 / 0.3);
        }
    </style>
</x-app-layout>