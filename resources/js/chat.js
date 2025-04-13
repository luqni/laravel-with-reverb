import Echo from 'laravel-echo';

const chatContainer = document.querySelector('.chat-container');
const messageForm = document.querySelector('#message-form');
const messageInput = document.querySelector('#message-input');
const currentUserId = document.querySelector('meta[name="user-id"]').content;

if (messageForm && chatContainer) {
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value;
        if (!message) return;

        const receiverId = chatContainer.dataset.receiverId;
        const url = receiverId ? `/chat/${receiverId}/send` : '/chat/send';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            messageInput.value = '';
            const messageHtml = createMessageHtml(data.message, true);
            chatContainer.insertAdjacentHTML('beforeend', messageHtml);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    });

    const channelName = chatContainer.dataset.receiverId 
        ? `chat.${currentUserId}.${chatContainer.dataset.receiverId}`
        : 'chat';

    window.Echo.channel(channelName)
        .listen('.message', (data) => {
            if (data.user.id !== currentUserId) {
                const messageHtml = createMessageHtml(data);
                chatContainer.insertAdjacentHTML('beforeend', messageHtml);
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
}
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}