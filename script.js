function toggleChat() {
    const chatBox = document.getElementById('chat-box');
    if (chatBox.style.display === 'none' || chatBox.style.display === '') {
        chatBox.style.display = 'block';
    } else {
        chatBox.style.display = 'none';
    }
}

function sendMessage() {
    const chatInput = document.getElementById('chat-input');
    const message = chatInput.value.trim();

    if (message) {
        const chatContent = document.querySelector('.chat-content');

        // Display the user's message
        const userMessageElement = document.createElement('div');
        userMessageElement.classList.add('message', 'sent');
        userMessageElement.innerHTML = `<p>${message}</p>`;
        chatContent.appendChild(userMessageElement);

        chatContent.scrollTop = chatContent.scrollHeight;
        chatInput.value = '';

        // Simulate a response from the chat bot after a short delay
        setTimeout(() => {
            const botMessageElement = document.createElement('div');
            botMessageElement.classList.add('message', 'received');
            botMessageElement.innerHTML = `<p>Thank you for your message! How can I assist you further?</p>`;
            chatContent.appendChild(botMessageElement);

            chatContent.scrollTop = chatContent.scrollHeight;
        }, 1000);
    }
}
