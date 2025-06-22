<!-- Floating Chatbot Widget -->
<div id="chatbot-widget" class="chatbot-widget" style="display: none;">
    <!-- Chatbot Toggle Button -->
    <div id="chatbot-toggle" class="chatbot-toggle">
        <i class="fas fa-robot"></i>
        <span class="chatbot-badge" id="chatbot-badge" style="display: none;">1</span>
    </div>

    <!-- Chatbot Window -->
    <div id="chatbot-window" class="chatbot-window" style="display: none;">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <div class="chatbot-avatar me-2">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">AI Assistant</h6>
                    <small class="text-muted">CekPenyewa.com</small>
                </div>
                <div class="chatbot-status">
                    <span class="status-dot online"></span>
                </div>
                <button class="btn btn-sm btn-link text-white p-1 ms-2" id="chatbot-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="chatbot-messages" id="chatbot-messages">
            <!-- Welcome Message -->
            <div class="message bot-message">
                <div class="message-content">
                    <div class="message-bubble">
                        <p class="mb-1">👋 Halo! Saya AI Assistant CekPenyewa.com.</p>
                        <p class="mb-0">Ada yang bisa saya bantu?</p>
                    </div>
                    <small class="message-time">Baru saja</small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="chatbot-quick-actions" id="chatbot-quick-actions">
            <button class="quick-btn" data-message="Bagaimana cara mendaftar?">
                <i class="fas fa-user-plus"></i>
                Cara Daftar
            </button>
            <button class="quick-btn" data-message="Bagaimana cara menggunakan sistem?">
                <i class="fas fa-question-circle"></i>
                Cara Pakai
            </button>
            <button class="quick-btn" data-message="Apa itu rental owner?">
                <i class="fas fa-car"></i>
                Rental Owner
            </button>
        </div>

        <!-- Input Area -->
        <div class="chatbot-input">
            <form id="chatbot-form" class="d-flex">
                <input type="text"
                       id="chatbot-input"
                       class="form-control"
                       placeholder="Ketik pesan Anda..."
                       maxlength="500"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary ms-2" id="chatbot-send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.chatbot-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chatbot-toggle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #da3544, #c02d3a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(218, 53, 68, 0.3);
    transition: all 0.3s ease;
    position: relative;
}

.chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(218, 53, 68, 0.4);
}

.chatbot-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4757;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.chatbot-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-header {
    background: linear-gradient(135deg, #da3544, #c02d3a);
    color: white;
    padding: 15px;
}

.chatbot-avatar {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2ecc71;
}

.chatbot-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f8f9fa;
    max-height: 300px;
    scroll-behavior: smooth;
}

.chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

.chatbot-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.chatbot-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chatbot-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.message {
    margin-bottom: 15px;
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bot-message .message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.user-message .message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.message-bubble {
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 18px;
    word-wrap: break-word;
}

.bot-message .message-bubble {
    background: white;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 4px;
}

.user-message .message-bubble {
    background: #da3544;
    color: white;
    border-bottom-right-radius: 4px;
}

.message-time {
    margin-top: 5px;
    color: #6c757d;
    font-size: 11px;
}

.chatbot-quick-actions {
    padding: 10px 15px;
    border-top: 1px solid #e9ecef;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.quick-btn {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 12px;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.quick-btn:hover {
    background: #da3544;
    color: white;
    border-color: #da3544;
}

.chatbot-input {
    padding: 15px;
    border-top: 1px solid #e9ecef;
    background: white;
}

.chatbot-input .form-control {
    border-radius: 20px;
    border: 1px solid #dee2e6;
    padding: 8px 15px;
}

.chatbot-input .form-control:focus {
    border-color: #da3544;
    box-shadow: 0 0 0 0.2rem rgba(218, 53, 68, 0.25);
}

.chatbot-input .btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 10px 15px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 18px;
    border-bottom-left-radius: 4px;
    max-width: 60px;
}

.typing-dot {
    width: 6px;
    height: 6px;
    background: #6c757d;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-8px); }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .chatbot-window {
        width: 320px;
        height: 450px;
        bottom: 70px;
        right: -10px;
    }

    .chatbot-toggle {
        width: 55px;
        height: 55px;
        font-size: 22px;
    }
}

@media (max-width: 480px) {
    .chatbot-window {
        width: 300px;
        height: 400px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('chatbot-widget');
    const toggle = document.getElementById('chatbot-toggle');
    const window = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');
    const quickActions = document.getElementById('chatbot-quick-actions');

    let isOpen = false;
    let sessionId = localStorage.getItem('chatbot_session_id') || generateSessionId();
    let isProcessing = false;

    // Check if chatbot is available
    checkChatbotAvailability();

    // Load conversation history
    loadConversationHistory();

    function generateSessionId() {
        const id = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('chatbot_session_id', id);
        return id;
    }

    function checkChatbotAvailability() {
        fetch('/chatbot/status', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                widget.style.display = 'block';
            }
        })
        .catch(error => {
            console.log('Chatbot not available');
        });
    }

    // Toggle chatbot
    toggle.addEventListener('click', function() {
        if (isOpen) {
            closeChatbot();
        } else {
            openChatbot();
        }
    });

    // Close chatbot
    closeBtn.addEventListener('click', closeChatbot);

    function openChatbot() {
        window.style.display = 'block';
        isOpen = true;
        input.focus();
        hideQuickActions();
        scrollToBottom();
    }

    function closeChatbot() {
        window.style.display = 'none';
        isOpen = false;
    }

    function hideQuickActions() {
        // Hide quick actions after first interaction
        setTimeout(() => {
            if (messages.children.length > 1) {
                quickActions.style.display = 'none';
            }
        }, 1000);
    }

    // Send message
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage(input.value.trim());
    });

    // Quick action buttons
    quickActions.addEventListener('click', function(e) {
        if (e.target.classList.contains('quick-btn') || e.target.closest('.quick-btn')) {
            const btn = e.target.closest('.quick-btn') || e.target;
            const message = btn.dataset.message;
            if (message) {
                sendMessage(message);
            }
        }
    });

    function sendMessage(message) {
        if (!message || isProcessing) return;

        isProcessing = true;
        input.value = '';
        input.disabled = true;

        // Add user message
        addMessage(message, 'user');

        // Hide quick actions
        quickActions.style.display = 'none';

        // Show typing indicator
        showTypingIndicator();

        // Send to server
        fetch('/chatbot/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                message: message,
                session_id: sessionId
            })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();

            if (data.success) {
                addMessage(data.message, 'bot');
            } else {
                addMessage(data.error || 'Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            addMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.', 'bot');
        })
        .finally(() => {
            isProcessing = false;
            input.disabled = false;
            input.focus();
        });
    }

    function addMessage(content, sender) {
        const time = new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;

        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">
                    <p class="mb-0">${content}</p>
                </div>
                <small class="message-time">${time}</small>
            </div>
        `;

        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'message bot-message';
        typingDiv.innerHTML = `
            <div class="message-content">
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;

        messages.appendChild(typingDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    function hideTypingIndicator() {
        const typing = document.getElementById('typing-indicator');
        if (typing) {
            typing.remove();
        }
    }

    function scrollToBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    function loadConversationHistory() {
        const savedHistory = localStorage.getItem('chatbot_history_' + sessionId);
        if (savedHistory) {
            try {
                const history = JSON.parse(savedHistory);

                // Clear welcome message if we have history
                if (history.length > 0) {
                    messages.innerHTML = '';
                    quickActions.style.display = 'none';
                }

                // Restore messages
                history.forEach(msg => {
                    addMessageToDOM(msg.content, msg.sender, msg.timestamp);
                });

                scrollToBottom();
            } catch (e) {
                console.error('Error loading chat history:', e);
            }
        }
    }

    function saveMessageToHistory(content, sender) {
        try {
            const savedHistory = localStorage.getItem('chatbot_history_' + sessionId);
            let history = savedHistory ? JSON.parse(savedHistory) : [];

            history.push({
                content: content,
                sender: sender,
                timestamp: new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                })
            });

            // Keep only last 50 messages
            if (history.length > 50) {
                history = history.slice(-50);
            }

            localStorage.setItem('chatbot_history_' + sessionId, JSON.stringify(history));
        } catch (e) {
            console.error('Error saving chat history:', e);
        }
    }

    function addMessageToDOM(content, sender, timestamp = null) {
        const time = timestamp || new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;

        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">
                    <p class="mb-0">${content}</p>
                </div>
                <small class="message-time">${time}</small>
            </div>
        `;

        messages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Update addMessage function to use new functions
    function addMessage(content, sender) {
        addMessageToDOM(content, sender);
        saveMessageToHistory(content, sender);
    }

    // Clear history function
    function clearHistory() {
        localStorage.removeItem('chatbot_history_' + sessionId);
        messages.innerHTML = `
            <div class="message bot-message">
                <div class="message-content">
                    <div class="message-bubble">
                        <p class="mb-1">👋 Halo! Saya AI Assistant CekPenyewa.com.</p>
                        <p class="mb-0">Ada yang bisa saya bantu?</p>
                    </div>
                    <small class="message-time">Baru saja</small>
                </div>
            </div>
        `;
        quickActions.style.display = 'flex';
        scrollToBottom();
    }

    // Add clear history button (optional)
    window.clearChatHistory = clearHistory;
});
</script>
