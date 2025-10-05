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
                // Check if admin has an active conversation or should see queue
                if (data.active_conversation) {
                    // Admin has an active conversation - show it
                    this.displayActiveConversation(data.active_conversation, data.queue_count);
                } else {
                    // No active conversation - show queue option
                    this.displayQueueOption(data.queue_count);
                }
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

    displayActiveConversation(conversation, queueCount) {
        const userName = `User ${conversation.user_session.substring(0, 8)}`;
        const lastMessage = conversation.last_message ? conversation.last_message.substring(0, 40) + '...' : 'No messages';
        const unreadCount = conversation.unread_count || 0;
        const timeAgo = this.timeAgo(conversation.last_activity);
        
        this.conversationsList.innerHTML = `
            <div style="padding: 15px; background: #e3f2fd; border-bottom: 2px solid #007bff;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h6 style="margin: 0; color: #0056b3;">
                        <i class="fas fa-comments"></i> Active Conversation
                    </h6>
                    <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;">
                        ${queueCount} in queue
                    </span>
                </div>
            </div>
            
            <div class="conversation-item" data-session-id="${conversation.session_id}" data-user-name="${userName}" 
                 style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; display: flex; align-items: center; transition: background-color 0.2s; background: #f8f9fa;"
                 onmouseover="this.style.backgroundColor='#e9ecef'" 
                 onmouseout="this.style.backgroundColor='#f8f9fa'"
                 onclick="window.adminChatbot.openConversation('${conversation.session_id}', '${userName}')">
                
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #28a745; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0;">
                    <i class="fas fa-user" style="color: white; font-size: 16px;"></i>
                </div>
                
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <div style="font-weight: bold; font-size: 14px; color: #333;">${userName}</div>
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
    }

    displayQueueOption(queueCount) {
        if (queueCount === 0) {
            this.conversationsList.innerHTML = `
                <div style="padding: 40px 20px; text-align: center; color: #666; font-size: 14px;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; color: #ccc;"></i>
                    <h5 style="color: #999; margin-bottom: 10px;">No Users Waiting</h5>
                    <p style="color: #aaa; font-size: 13px;">When users request to talk to an agent, they will appear here.</p>
                </div>
            `;
        } else {
            this.conversationsList.innerHTML = `
                <div style="padding: 30px 20px; text-align: center;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: #007bff; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="fas fa-users" style="color: white; font-size: 36px;"></i>
                    </div>
                    <h5 style="color: #333; margin-bottom: 10px;">
                        <strong>${queueCount}</strong> User${queueCount > 1 ? 's' : ''} Waiting
                    </h5>
                    <p style="color: #666; font-size: 13px; margin-bottom: 20px;">
                        Users are waiting to talk to an agent
                    </p>
                    <button onclick="window.adminChatbot.acceptNextUser()" 
                            style="background: #28a745; color: white; border: none; padding: 12px 30px; border-radius: 25px; font-size: 14px; font-weight: bold; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                        <i class="fas fa-phone-alt"></i> Accept Next User
                    </button>
                </div>
            `;
        }
    }

    displayConversationsList(conversations) {
        // This method is deprecated - now using displayActiveConversation and displayQueueOption
        console.warn('displayConversationsList is deprecated');
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
            await fetch('/api/admin/agent-conversation/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_id: sessionId
                })
            });
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

    async acceptNextUser() {
        try {
            const response = await fetch('/api/admin/agent-conversation/accept-next', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                // Open the new conversation
                const userName = `User ${data.user_session.substring(0, 8)}`;
                this.showChatView(data.session_id, userName);
                
                // Show success message in chat
                this.addMessageToChat('You are now connected with this user.', true);
            } else {
                alert(data.message || 'Failed to accept user');
            }
        } catch (error) {
            console.error('Error accepting next user:', error);
            alert('Error accepting user. Please try again.');
        }
    }

    async completeAndNext() {
        if (!this.currentConversationId) {
            alert('No active conversation');
            return;
        }

        if (!confirm('Are you sure you want to complete this conversation and move to the next user?')) {
            return;
        }

        try {
            const response = await fetch('/api/admin/agent-conversation/complete-and-next', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_id: this.currentConversationId
                })
            });

            const data = await response.json();
            
            if (data.success) {
                if (data.has_next) {
                    // Open the next conversation
                    const userName = `User ${data.user_session.substring(0, 8)}`;
                    this.showChatView(data.session_id, userName);
                    
                    // Show message
                    this.addMessageToChat('Connected to next user in queue.', true);
                } else {
                    // No more users in queue
                    alert('Conversation completed. No more users in queue.');
                    this.showInboxView();
                }
            } else {
                alert(data.message || 'Failed to complete conversation');
            }
        } catch (error) {
            console.error('Error completing conversation:', error);
            alert('Error completing conversation. Please try again.');
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