<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            @if($other->profile_picture)
                <img src="{{ Storage::url($other->profile_picture) }}" class="w-10 h-10 rounded-full object-cover border-2 border-coffee-300" />
            @else
                <div class="w-10 h-10 bg-coffee-400 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold">{{ strtoupper(substr($other->name,0,1)) }}</span>
                </div>
            @endif
            <div>
                <div class="text-coffee-800 font-semibold">{{ $other->name }}</div>
                <div class="text-xs text-coffee-500">Private chat</div>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="card-coffee p-0 overflow-hidden flex flex-col" style="min-height: 70vh;">
                <!-- Messages area -->
                <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-3">
                    @foreach($conversation->messages as $m)
                        <div class="flex {{ $m->sender_id === $me->id ? 'justify-end' : 'justify-start' }}" data-id="{{ $m->id }}" data-me="{{ $m->sender_id === $me->id ? '1' : '0' }}">
                            <div tabindex="0" class="group chat-bubble {{ $m->sender_id === $me->id ? 'me' : 'other' }} relative max-w-[70%] rounded-2xl px-4 pr-8 py-2 shadow {{ $m->sender_id === $me->id ? 'bg-coffee-500 text-white rounded-br-sm' : 'bg-white rounded-bl-sm' }}">
                                @if($m->body)
                                    <div class="whitespace-pre-wrap msg-body">{{ $m->body }}</div>
                                @endif
                                @if($m->image_path)
                                    <img src="{{ Storage::url($m->image_path) }}" class="mt-2 rounded-lg max-h-64" />
                                @endif
                                <div class="text-[10px] opacity-70 mt-1 text-right">{{ $m->created_at->diffForHumans() }}</div>
                                @if($m->sender_id === $me->id)
                                <div class="absolute top-1 {{ $m->sender_id === $me->id ? 'right-1' : 'left-1' }} z-10 flex items-center gap-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 sm:focus-within:opacity-100 pointer-events-none group-hover:pointer-events-auto focus-within:pointer-events-auto transition-opacity">
                                    <div class="relative">
                                        <button aria-label="Message menu" class="w-7 h-7 rounded-full bg-cream-200 hover:bg-cream-300 flex items-center justify-center shadow cursor-pointer" onclick="toggleMenu(event, {{ $m->id }})">⋮</button>
                                        <div id="menu-{{ $m->id }}" class="hidden absolute z-20 mt-1 {{ $m->sender_id === $me->id ? 'right-0' : 'left-0' }} bg-white border border-coffee-200 rounded-lg shadow-lg overflow-hidden">
                                            <button class="block w-full text-left px-3 py-2 text-sm hover:bg-cream-100" onclick="startEdit({{ $m->id }})">✏️ Edit</button>
                                            <button class="block w-full text-left px-3 py-2 text-sm hover:bg-cream-100" onclick="enterSelectMode({{ $m->id }})">🗑️ Delete…</button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Input area -->
                <form id="sendForm" action="{{ route('chat.send', $other) }}" method="POST" enctype="multipart/form-data" class="border-t border-coffee-200/40 p-3 bg-white flex items-center gap-2 sticky bottom-0">
                    @csrf
                    <label class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-cream-200 hover:bg-cream-300 cursor-pointer">
                        📎
                        <input type="file" name="image" accept="image/*" class="hidden" onchange="document.getElementById('sendForm').submit()" />
                    </label>
                    <input id="body" name="body" autocomplete="off" placeholder="Type a message..." class="flex-1 rounded-full px-4 py-2 bg-cream-100 focus:bg-white focus:ring-2 focus:ring-coffee-300 outline-none" />
                    <button class="btn-coffee">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const messagesEl = document.getElementById('messages');
        function scrollToBottom(){ messagesEl.scrollTop = messagesEl.scrollHeight; }
        scrollToBottom();

        // Render helper
        // selection state
        let selectMode = false;
        const selectedIds = new Set();

        function renderMessage(m){
            const wrapper = document.createElement('div');
            wrapper.className = 'flex ' + (m.me ? 'justify-end' : 'justify-start');
            wrapper.dataset.id = m.id;
            wrapper.dataset.me = m.me ? '1' : '0';
            const bubble = document.createElement('div');
            bubble.className = 'group chat-bubble ' + (m.me ? 'me ' : 'other ') + 'relative max-w-[70%] rounded-2xl px-4 ' + (m.me ? 'pr-8 ' : '') + 'py-2 shadow ' + (m.me ? 'bg-coffee-500 text-white rounded-br-sm' : 'bg-white rounded-bl-sm');
            if(m.body){
                const t = document.createElement('div');
                t.className = 'whitespace-pre-wrap msg-body';
                t.textContent = m.body;
                bubble.appendChild(t);
            }
            if(m.image_url){
                const img = document.createElement('img');
                img.src = m.image_url;
                img.className = 'mt-2 rounded-lg max-h-64';
                bubble.appendChild(img);
            }
            const time = document.createElement('div');
            time.className = 'text-[10px] opacity-70 mt-1 text-right';
            time.textContent = new Date(m.created_at).toLocaleTimeString();
            bubble.appendChild(time);
            if(m.me){
                const menuWrap = document.createElement('div');
                menuWrap.className = 'absolute top-1 right-1 z-10 flex items-center opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity';
                menuWrap.innerHTML = `<div class="relative">
                    <button class="w-7 h-7 rounded-full bg-cream-200 hover:bg-cream-300 flex items-center justify-center shadow cursor-pointer" onclick="toggleMenu(event, ${m.id})">⋮</button>
                    <div id="menu-${m.id}" class="hidden absolute z-20 mt-1 right-0 bg-white border border-coffee-200 rounded-lg shadow-lg overflow-hidden">
                        <button class="block w-full text-left px-3 py-2 text-sm hover:bg-cream-100" onclick="startEdit(${m.id})">✏️ Edit</button>
                        <button class="block w-full text-left px-3 py-2 text-sm hover:bg-cream-100" onclick="enterSelectMode(${m.id})">🗑️ Delete…</button>
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
                    if(node){ node.textContent = '(deleted)'; }
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
            } catch {}
        });

        function toggleMenu(e, id){
            e.stopPropagation();
            document.querySelectorAll('[id^="menu-"]').forEach(el=>el.classList.add('hidden'));
            const m = document.getElementById(`menu-${id}`); if(m) m.classList.toggle('hidden');
        }
        document.addEventListener('click', ()=>{
            document.querySelectorAll('[id^="menu-"]').forEach(el=>el.classList.add('hidden'));
        });

        function addSelectCheckbox(wrapper, id){
            if(wrapper.dataset.me !== '1') return; // only own messages selectable
            if(wrapper.querySelector('.select-box')) return;
            const box = document.createElement('label');
            box.className = 'select-box absolute -top-3 right-2 bg-cream-100 border border-coffee-200 rounded-full w-6 h-6 flex items-center justify-center shadow';
            box.innerHTML = `<input type="checkbox" class="peer sr-only" onchange="toggleSelect(${id}, this.checked)">
                             <span class="w-4 h-4 rounded-full border-2 border-coffee-400 peer-checked:bg-red-500"></span>`;
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
                bar.className = 'sticky bottom-0 z-10 p-2 bg-red-50 border-t border-red-200 text-red-700 flex items-center justify-between';
                bar.innerHTML = `
                  <div class="flex items-center gap-3">
                    <span class="text-sm">Select messages to delete</span>
                    <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs" id="sel-count">0 selected</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <button class="px-3 py-1 rounded bg-cream-200 text-coffee-800 hover:bg-cream-300" onclick="exitSelectMode()">Cancel</button>
                    <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700" onclick="bulkDelete()">Delete</button>
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
            const count = document.getElementById('sel-count'); if(count) count.textContent = `${selectedIds.size} selected`;
        }

        window.bulkDelete = async function(){
            const ids = Array.from(selectedIds).filter(id => {
                const w = messagesEl.querySelector(`[data-id="${id}"]`);
                return w && w.dataset.me === '1';
            });
            for (const id of ids){
                try{ await fetch(`{{ url('/chat/'.$other->id.'/messages') }}/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN': '{{ csrf_token() }}' }}); }catch{}
            }
            exitSelectMode();
        }

        async function delMsg(id){
            try{ await fetch(`{{ url('/chat/'.$other->id.'/messages') }}/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN': '{{ csrf_token() }}' }}); }catch{}
        }

        function startEdit(id){
            const node = messagesEl.querySelector(`[data-id="${id}"] .msg-body`);
            if(!node) return;
            const current = node.textContent;
            const input = document.createElement('textarea');
            input.className = 'w-full rounded p-2 text-coffee-800';
            input.value = current;
            node.replaceWith(input);
            input.focus();
            input.addEventListener('keydown', async (e)=>{
                if(e.key==='Enter' && !e.shiftKey){
                    e.preventDefault();
                    try{
                        await fetch(`{{ url('/chat/'.$other->id.'/messages') }}/${id}`, { method:'PATCH', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ body: input.value }) });
                    }catch{}
                }
            });
        }
    </script>
</x-app-layout>
