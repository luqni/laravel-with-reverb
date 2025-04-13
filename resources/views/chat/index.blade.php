@extends('layouts.chat')

@section('title', 'Chat Grup')

@section('sidebar')
<div class="flex-none p-4 border-b border-gray-200">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Chat Grup</h1>
        <a href="{{ route('chat.contacts') }}" class="text-whatsapp-dark hover:text-opacity-80">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </a>
    </div>
</div>

<div class="flex-1 overflow-y-auto p-4 space-y-4">
    <div class="text-center text-sm text-gray-500 py-2">
        Chat grup untuk semua pengguna
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col h-full">
    <!-- Chat Header -->
    <div class="flex-none p-4 bg-white border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-whatsapp-dark flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Chat Grup</h2>
                <p class="text-sm text-gray-500">Chat bersama semua pengguna</p>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messagesContainer">
        @foreach($messages as $message)
            <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                <div class="message-bubble {{ $message->sender_id === Auth::id() ? 'message-sent bg-whatsapp-light' : 'message-received bg-white' }} rounded-lg p-3 shadow-sm">
                    @if($message->sender_id !== Auth::id())
                        <div class="flex items-center gap-1 mb-1">
                            <p class="text-xs font-medium text-whatsapp-dark">{{ $message->sender->name }}</p>
                            <div class="w-2 h-2 rounded-full {{ $message->sender->is_online ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                        </div>
                    @endif
                    <div class="{{ $message->is_emoji ? 'emoji-message' : 'text-gray-800' }}">
                        {{ $message->content }}
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="flex-none p-4 bg-white border-t border-gray-200">
        <div class="flex items-end space-x-2">
            <div class="flex-1 relative">
                <textarea 
                    id="messageInput"
                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-whatsapp-dark"
                    rows="1"
                    placeholder="Ketik pesan..."></textarea>
                <button 
                    id="emojiButton"
                    class="absolute right-2 bottom-2 text-gray-500 hover:text-gray-700"
                    type="button">
                    😊
                </button>
            </div>
            <button 
                id="sendButton"
                class="flex-none px-4 py-2 bg-whatsapp-dark text-white rounded-lg hover:bg-opacity-90 focus:outline-none"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const messagesContainer = document.getElementById('messagesContainer');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const emojiButton = document.getElementById('emojiButton');
    const emojiPicker = document.getElementById('emojiPicker');

    // Scroll ke pesan terbaru
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Toggle emoji picker
    emojiButton.addEventListener('click', () => {
        emojiPicker.classList.toggle('hidden');
    });

    // Pilih emoji
    emojiPicker.addEventListener('emoji-click', event => {
        messageInput.value = event.detail.unicode;
        emojiPicker.classList.add('hidden');
        sendMessage(true);
    });

    // Kirim pesan
    sendButton.addEventListener('click', () => sendMessage(false));
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage(false);
        }
    });

    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    function sendMessage(isEmoji) {
        const content = messageInput.value.trim();
        if (!content) return;

        fetch('{{ route("chat.send-message") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                content: content,
                is_emoji: isEmoji
            })
        })
        .then(response => response.json())
        .then(message => {
            appendMessage(message);
            messageInput.value = '';
            messageInput.style.height = 'auto';
        });
    }

    function appendMessage(message) {
        const div = document.createElement('div');
        div.className = `flex ${message.sender_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'}`;
        
        div.innerHTML = `
            <div class="message-bubble ${message.sender_id === {{ Auth::id() }} ? 'message-sent bg-whatsapp-light' : 'message-received bg-white'} rounded-lg p-3 shadow-sm">
                ${message.sender_id !== {{ Auth::id() }} ? `<p class="text-xs font-medium text-whatsapp-dark mb-1">${message.sender.name}</p>` : ''}
                <div class="${message.is_emoji ? 'emoji-message' : 'text-gray-800'}">
                    ${message.content}
                </div>
                <p class="text-xs text-gray-500 mt-1">${new Date(message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}</p>
            </div>
        `;

        messagesContainer.appendChild(div);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // WebSocket connection
    window.Echo.private('chat')
        .listen('ChatMessageEvent', (e) => {
            if (!e.message.receiver_id) {
                appendMessage(e.message);
            }
        });

    window.Echo.channel('users.online')
        .listen('UserOnlineEvent', (e) => {
            const userStatusDots = document.querySelectorAll(`[data-user-id="${e.user_id}"]`);
            userStatusDots.forEach(dot => {
                dot.className = `w-2 h-2 rounded-full ${e.is_online ? 'bg-green-500' : 'bg-gray-400'}`;
            });
        });
</script>
@endsection