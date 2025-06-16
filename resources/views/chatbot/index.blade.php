@extends('layouts.main')

@section('title', 'AI Assistant')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Chatbot Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-robot"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-0">AI Assistant CekPenyewa.com</h5>
                            <small class="opacity-75">Siap membantu Anda 24/7</small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-success" id="status-indicator">
                                <i class="fas fa-circle me-1"></i>Online
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <!-- Chat Messages -->
                    <div id="chat-messages" class="chat-messages p-4" style="height: 500px; overflow-y: auto;">
                        <!-- Welcome Message -->
                        <div class="message bot-message mb-3">
                            <div class="d-flex">
                                <div class="avatar me-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-robot fa-sm"></i>
                                    </div>
                                </div>
                                <div class="message-content">
                                    <div class="bg-light rounded-3 p-3">
                                        <p class="mb-0">
                                            👋 Halo! Saya AI Assistant CekPenyewa.com. 
                                            Saya siap membantu Anda dengan pertanyaan tentang:
                                        </p>
                                        <ul class="mb-0 mt-2">
                                            <li>Cara menggunakan platform</li>
                                            <li>Fitur-fitur yang tersedia</li>
                                            <li>Proses pendaftaran dan verifikasi</li>
                                            <li>Sistem pembayaran dan topup</li>
                                            <li>Dan pertanyaan lainnya</li>
                                        </ul>
                                    </div>
                                    <small class="text-muted">Baru saja</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="border-top p-3">
                        <form id="chat-form" class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <input type="text" 
                                       id="message-input" 
                                       class="form-control" 
                                       placeholder="Ketik pertanyaan Anda di sini..." 
                                       maxlength="1000"
                                       autocomplete="off">
                            </div>
                            <button type="submit" 
                                    id="send-button" 
                                    class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                        
                        <!-- Quick Actions -->
                        <div class="mt-3">
                            <small class="text-muted d-block mb-2">Pertanyaan cepat:</small>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-outline-primary btn-sm quick-question" 
                                        data-question="Bagaimana cara mendaftar?">
                                    Cara Daftar
                                </button>
                                <button class="btn btn-outline-primary btn-sm quick-question" 
                                        data-question="Berapa harga pencarian data?">
                                    Harga Pencarian
                                </button>
                                <button class="btn btn-outline-primary btn-sm quick-question" 
                                        data-question="Bagaimana cara topup saldo?">
                                    Cara Topup
                                </button>
                                <button class="btn btn-outline-primary btn-sm quick-question" 
                                        data-question="Apa itu rental owner?">
                                    Rental Owner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Actions -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <button id="clear-chat" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-trash me-1"></i>Hapus Riwayat
                    </button>
                </div>
                <div>
                    <small class="text-muted">
                        Powered by AI • 
                        <span id="provider-info">Claude, ChatGPT & Gemini</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">AI sedang berpikir...</p>
                <small class="text-muted">Mohon tunggu sebentar</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.chat-messages {
    background: #f8f9fa;
}

.message {
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

.user-message .message-content {
    margin-left: auto;
    max-width: 80%;
}

.user-message .bg-primary {
    color: white !important;
}

.bot-message .message-content {
    max-width: 85%;
}

.avatar {
    flex-shrink: 0;
}

.quick-question:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

#message-input:focus {
    border-color: #da3544;
    box-shadow: 0 0 0 0.2rem rgba(218, 53, 68, 0.25);
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 8px 12px;
    background: #e9ecef;
    border-radius: 12px;
    width: fit-content;
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
    30% { transform: translateY(-10px); }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let sessionId = localStorage.getItem('chatbot_session_id') || generateSessionId();
    let isProcessing = false;

    // Generate session ID
    function generateSessionId() {
        const id = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('chatbot_session_id', id);
        return id;
    }

    // Load chat history
    loadChatHistory();

    // Form submit
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    // Quick questions
    $('.quick-question').on('click', function() {
        const question = $(this).data('question');
        $('#message-input').val(question);
        sendMessage();
    });

    // Clear chat
    $('#clear-chat').on('click', function() {
        if (confirm('Yakin ingin menghapus riwayat percakapan?')) {
            clearChat();
        }
    });

    // Send message function
    function sendMessage() {
        const message = $('#message-input').val().trim();
        
        if (!message || isProcessing) return;

        isProcessing = true;
        $('#send-button').prop('disabled', true);
        $('#message-input').prop('disabled', true);

        // Add user message to chat
        addMessage(message, 'user');
        $('#message-input').val('');

        // Show typing indicator
        showTypingIndicator();

        // Send to server
        $.ajax({
            url: '/chatbot/send',
            method: 'POST',
            data: {
                message: message,
                session_id: sessionId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                hideTypingIndicator();
                
                if (response.success) {
                    addMessage(response.message, 'bot');
                    updateProviderInfo(response.metadata);
                } else {
                    addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot', true);
                }
            },
            error: function(xhr) {
                hideTypingIndicator();
                let errorMessage = 'Maaf, terjadi kesalahan sistem. Silakan coba lagi.';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                
                addMessage(errorMessage, 'bot', true);
            },
            complete: function() {
                isProcessing = false;
                $('#send-button').prop('disabled', false);
                $('#message-input').prop('disabled', false);
                $('#message-input').focus();
            }
        });
    }

    // Add message to chat
    function addMessage(content, sender, isError = false) {
        const time = new Date().toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        const messageClass = sender === 'user' ? 'user-message' : 'bot-message';
        const bgClass = sender === 'user' ? 'bg-primary text-white' : (isError ? 'bg-danger text-white' : 'bg-light');
        const avatar = sender === 'user' ? 
            '<i class="fas fa-user fa-sm"></i>' : 
            '<i class="fas fa-robot fa-sm"></i>';
        const avatarBg = sender === 'user' ? 'bg-primary text-white' : 'bg-primary text-white';

        const messageHtml = `
            <div class="message ${messageClass} mb-3">
                <div class="d-flex ${sender === 'user' ? 'justify-content-end' : ''}">
                    ${sender === 'bot' ? `
                        <div class="avatar me-3">
                            <div class="${avatarBg} rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                ${avatar}
                            </div>
                        </div>
                    ` : ''}
                    <div class="message-content">
                        <div class="${bgClass} rounded-3 p-3">
                            <p class="mb-0">${content}</p>
                        </div>
                        <small class="text-muted">${time}</small>
                    </div>
                    ${sender === 'user' ? `
                        <div class="avatar ms-3">
                            <div class="${avatarBg} rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                ${avatar}
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;

        $('#chat-messages').append(messageHtml);
        scrollToBottom();
    }

    // Show typing indicator
    function showTypingIndicator() {
        const typingHtml = `
            <div id="typing-indicator" class="message bot-message mb-3">
                <div class="d-flex">
                    <div class="avatar me-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fas fa-robot fa-sm"></i>
                        </div>
                    </div>
                    <div class="message-content">
                        <div class="typing-indicator">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#chat-messages').append(typingHtml);
        scrollToBottom();
    }

    // Hide typing indicator
    function hideTypingIndicator() {
        $('#typing-indicator').remove();
    }

    // Scroll to bottom
    function scrollToBottom() {
        const chatMessages = $('#chat-messages');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }

    // Update provider info
    function updateProviderInfo(metadata) {
        if (metadata && metadata.provider) {
            $('#provider-info').text(`Powered by ${metadata.provider}`);
        }
    }

    // Load chat history
    function loadChatHistory() {
        // Implementation for loading chat history
        // This would make an AJAX call to get previous conversations
    }

    // Clear chat
    function clearChat() {
        $.ajax({
            url: '/chatbot/clear-history',
            method: 'POST',
            data: {
                session_id: sessionId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                $('#chat-messages').find('.message:not(:first)').remove();
                sessionId = generateSessionId();
            }
        });
    }

    // Auto-focus on input
    $('#message-input').focus();
});
</script>
@endpush
