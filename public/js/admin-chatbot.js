class AdminChatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.currentConversationId = null;
        this.pollInterval = null;
        this.conversations = new Map();
        this.currentView = 'inbox'; // 'inbox' or 'chat'
        this.init();
    }

    init() {
        console.log('[ADMIN CHATBOT] Initializing admin chatbot');
        this.toggle = document.getElementById('adminChatbotToggle');
        this.window = document.getElementById('adminChatbotWindow');
        this.close = document.getElementById('adminChatbotClose');
        this.input = document.getElementById('adminChatbotInput');
        this.send = document.getElementById('adminChatbotSend');
        this.messages = document.getElementById('adminChatbotMessages');
        
        // Inbox elements
        this.conversationsList = document.getElementById('conversationsList');
        this.chatView = document.getElementById('chatView');
        this.chatMessages = document.getElementById('chatMessages');
        this.backToInbox = document.getElementById('backToInbox');
        this.chatUserName = document.getElementById('chatUserName');
        this.chatUserStatus = document.getElementById('chatUserStatus');

        // Check if all required elements exist
        if (!this.toggle || !this.window || !this.input || !this.send || !this.messages) {
            console.error('[ADMIN CHATBOT] Missing required elements, aborting initialization');
            return;
        }

        this.bindEvents();
        this.loadActiveConversations();
        this.startPolling();
    }

    bindEvents() {
        this.toggle.addEventListener('click', () => this.toggleChat());
        this.close.addEventListener('click', () => this.closeChat());
        this.send.addEventListener('click', () => this.sendMessage());
        this.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
        this.backToInbox.addEventListener('click', () => this.showInboxView());
    }

    toggleChat() {
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }

    openChat() {
        this.window.classList.add('active');
        this.isOpen = true;
        
        // Hide pulse animation when opened
        const pulse = this.toggle.querySelector('.chatbot-pulse');
        if (pulse) pulse.style.display = 'none';

        // Show inbox view by default
        this.showInboxView();
        
        // Clear notification badge
        this.clearNotificationBadge();
    }

    closeChat() {
        this.window.classList.remove('active');
        this.isOpen = false;
        
        // Show pulse animation when closed
        const pulse = this.toggle.querySelector('.chatbot-pulse');
        if (pulse) pulse.style.display = 'block';
    }

    showInboxView() {
        this.currentView = 'inbox';
        this.conversationsList.style.display = 'block';
        this.chatView.style.display = 'none';
        this.loadActiveConversations();
    }

    showChatView(sessionId, userName) {
        this.currentView = 'chat';
        this.currentConversationId = sessionId;
        this.conversationsList.style.display = 'none';
        this.chatView.style.display = 'flex';
        
        // Set user name and status
        this.chatUserName.textContent = userName;
        this.chatUserStatus.textContent = 'Online';
        
        // Load conversation
        this.loadConversationHistory(sessionId);
        this.markConversationAsRead(sessionId);
        
        // Focus input
        this.input.focus();
    }

    async loadActiveConversations() {
        try {
            const response = await fetch('/api/admin/agent-conversation/active', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.displayConversationsList(data.conversations);
                
                // Update notification badge
                const totalUnread = data.conversations.reduce((sum, conv) => sum + (conv.unread_count || 0), 0);
                this.updateNotificationBadge(totalUnread);
            }
        } catch (error) {
            console.error('Error loading conversations:', error);
            this.conversationsList.innerHTML = `
                <div style="padding: 20px; text-align: center; color: #666;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                    Error loading conversations
                </div>
            `;
        }
    }

    displayConversationsList(conversations) {
        if (conversations.length === 0) {
            this.conversationsList.innerHTML = `
                <div style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    <i class="fas fa-inbox" style="font-size: 24px; margin-bottom: 10px; display: block; color: #ccc;"></i>
                    No conversations yet
                </div>
            `;
            return;
        }

        let html = '';
        conversations.forEach(conv => {
            const userName = `User ${conv.user_session.substring(0, 8)}`;
            const lastMessage = conv.last_message ? conv.last_message.substring(0, 40) + '...' : 'No messages';
            const unreadCount = conv.unread_count || 0;
            const timeAgo = this.timeAgo(conv.last_activity);
            
            html += `
                <div class="conversation-item" data-session-id="${conv.session_id}" data-user-name="${userName}" 
                     style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; display: flex; align-items: center; transition: background-color 0.2s;"
                     onmouseover="this.style.backgroundColor='#f8f9fa'" 
                     onmouseout="this.style.backgroundColor='transparent'"
                     onclick="window.adminChatbot.openConversation('${conv.session_id}', '${userName}')">
                    
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #007bff; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0;">
                        <i class="fas fa-user" style="color: white; font-size: 16px;"></i>
                    </div>
                    
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <div style="font-weight: ${unreadCount > 0 ? 'bold' : 'normal'}; font-size: 14px; color: #333;">${userName}</div>
                            <div style="font-size: 12px; color: #666;">${timeAgo}</div>
                        </div>
                        <div style="font-size: 13px; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${lastMessage}
                        </div>
                    </div>
                    
                    ${unreadCount > 0 ? `
                        <div style="width: 20px; height: 20px; border-radius: 50%; background: #dc3545; color: white; font-size: 11px; display: flex; align-items: center; justify-content: center; margin-left: 8px;">
                            ${unreadCount > 99 ? '99+' : unreadCount}
                        </div>
                    ` : ''}
                </div>
            `;
        });

        this.conversationsList.innerHTML = html;
    }

    openConversation(sessionId, userName) {
        this.showChatView(sessionId, userName);
    }

    timeAgo(dateString) {
        const now = new Date();
        const date = new Date(dateString);
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'now';
        if (seconds < 3600) return Math.floor(seconds / 60) + 'm';
        if (seconds < 86400) return Math.floor(seconds / 3600) + 'h';
        return Math.floor(seconds / 86400) + 'd';
    }

    async loadConversationHistory(sessionId) {
        try {
            const response = await fetch(`/api/admin/agent-conversation/${sessionId}/messages`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.displayMessages(data.messages);
            }
        } catch (error) {
            console.error('Error loading conversation history:', error);
            this.chatMessages.innerHTML = `
                <div style="padding: 20px; text-align: center; color: #666;">
                    Error loading conversation history
                </div>
            `;
        }
    }

    displayMessages(messages) {
        this.chatMessages.innerHTML = '';
        
        // Filter out any [SYSTEM] messages that might have slipped through
        const filteredMessages = messages.filter(message => {
            return !message.message.includes('[SYSTEM]') && 
                   !message.message.includes('User escalated') &&
                   !message.message.includes('Previous conversation:');
        });
        
        if (filteredMessages.length === 0) {
            this.chatMessages.innerHTML = `
                <div style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    Start the conversation by typing a message
                </div>
            `;
            return;
        }

        filteredMessages.forEach(message => {
            this.addMessageToChat(
                message.message,
                message.sender_type === 'admin',
                message.created_at
            );
        });
        
        this.scrollToBottom();
    }

    addMessageToChat(text, isAdmin = false, timestamp = null) {
        // Check for duplicate messages (prevent polling duplicates)
        if (timestamp) {
            const existingMessages = this.chatMessages.querySelectorAll('.message');
            for (let msg of existingMessages) {
                const msgContent = msg.querySelector('.message-content').innerHTML;
                const msgTimestamp = msg.getAttribute('data-timestamp');
                if (msgContent.includes(text) && msgTimestamp === timestamp) {
                    console.log('[ADMIN CHATBOT] Skipping duplicate message:', text);
                    return; // Skip duplicate message
                }
            }
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isAdmin ? 'user' : 'bot'}`;
        
        // Add timestamp attribute for duplicate checking
        if (timestamp) {
            messageDiv.setAttribute('data-timestamp', timestamp);
        }
        
        const time = timestamp ? new Date(timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) 
                                : new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        // Create message structure with user avatar for bot messages
        const avatarHtml = '<div class="message-avatar"><i class="fas fa-user"></i></div>';
        
        messageDiv.innerHTML = `
            ${!isAdmin ? avatarHtml : ''}
            <div class="message-content">
                ${text}
                <div class="message-time" style="font-size: 11px; color: #999; margin-top: 5px;">${time}</div>
            </div>
        `;
        
        this.chatMessages.appendChild(messageDiv);
        this.scrollToBottom();
    }

    async sendMessage() {
        console.log('[ADMIN CHATBOT] Sending admin message');
        const message = this.input.value.trim();
        if (!message || !this.currentConversationId) return;

        // Add message to chat immediately
        this.addMessageToChat(message, true);
        this.input.value = '';

        try {
            const response = await fetch('/api/admin/agent-conversation/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_id: this.currentConversationId,
                    message: message
                })
            });

            const data = await response.json();
            
            if (!data.success) {
                console.error('Error sending message:', data.message);
                // You could add error handling here, like showing an error message
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }

    async markConversationAsRead(sessionId) {
        try {
            await fetch(`/api/admin/agent-conversation/${sessionId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            });
            console.log('[ADMIN CHATBOT] Marked conversation as read:', sessionId);
        } catch (error) {
            console.error('Error marking conversation as read:', error);
        }
    }

    scrollToBottom() {
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
    }

    startPolling() {
        // Poll for new messages every 3 seconds
        this.pollInterval = setInterval(() => {
            if (this.currentView === 'inbox') {
                this.loadActiveConversations();
            } else if (this.currentConversationId) {
                this.checkForNewMessages();
            }
        }, 3000);
    }

    async checkForNewMessages() {
        try {
            const response = await fetch(`/api/admin/agent-conversation/${this.currentConversationId}/new-messages`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                // Filter out any [SYSTEM] messages from new messages
                const filteredMessages = data.messages.filter(msg => {
                    return !msg.message.includes('[SYSTEM]') && 
                           !msg.message.includes('User escalated') &&
                           !msg.message.includes('Previous conversation:');
                });
                
                filteredMessages.forEach(msg => {
                    this.addMessageToChat(msg.message, msg.sender_type === 'admin', msg.created_at);
                });
                
                // Mark new messages as read if chat is open and we had filtered messages
                if (this.isOpen && filteredMessages.length > 0) {
                    this.markConversationAsRead(this.currentConversationId);
                }
            }
        } catch (error) {
            console.error('Error checking for new messages:', error);
        }
    }

    updateNotificationBadge(count) {
        let badge = this.toggle.querySelector('.notification-badge');
        
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('div');
                badge.className = 'notification-badge';
                badge.style.cssText = `
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: #dc3545;
                    color: white;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    font-size: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                `;
                this.toggle.appendChild(badge);
            }
            badge.textContent = count > 99 ? '99+' : count;
        } else if (badge) {
            badge.remove();
        }
    }

    clearNotificationBadge() {
        const badge = this.toggle.querySelector('.notification-badge');
        if (badge) {
            badge.remove();
        }
    }

    destroy() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }
    }
}

// Initialize admin chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if admin chatbot elements exist (more reliable check)
    if (document.getElementById('adminChatbotToggle')) {
        console.log('Initializing Admin Chatbot...');
        window.adminChatbot = new AdminChatbot();
    }
});

// Cleanup when page unloads
window.addEventListener('beforeunload', function() {
    if (window.adminChatbot) {
        window.adminChatbot.destroy();
    }
});