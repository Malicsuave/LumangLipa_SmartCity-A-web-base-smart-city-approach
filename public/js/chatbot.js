class BarangayChatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.questionHistory = [];
        this.satisfactionCheckShown = false;
        this.agentEscalationOffered = false;
        this.userSession = this.generateUserSession();
        
        // Agent mode properties
        this.isAgentMode = false;
        this.agentSessionId = null;
        this.agentPollInterval = null;
        this.lastMessageCheck = null;
        this.queuePosition = null;
        this.queueStatus = 'waiting'; // 'waiting', 'active', 'completed'
        this.queueStatusElement = null;
        
        this.init();
        this.knowledgeBase = this.initKnowledgeBase();
    }

    init() {
        console.log('[USER CHATBOT] Initializing user chatbot');
        this.toggle = document.getElementById('chatbotToggle');
        this.window = document.getElementById('chatbotWindow');
        this.close = document.getElementById('chatbotClose');
        this.input = document.getElementById('chatbotInput');
        this.send = document.getElementById('chatbotSend');
        this.messages = document.getElementById('chatbotMessages');

        // Check if all required elements exist
        if (!this.toggle || !this.window || !this.input || !this.send || !this.messages) {
            console.error('[USER CHATBOT] Missing required elements, aborting initialization');
            return;
        }

        this.bindEvents();
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
        this.input.focus();
        
        // Hide pulse animation when opened
        const pulse = this.toggle.querySelector('.chatbot-pulse');
        if (pulse) pulse.style.display = 'none';

        // If strict mode and no AI key, show unavailable message once
        const body = document.body;
        const strict = body.getAttribute('data-chatbot-strict') === '1';
        const hasKey = body.getAttribute('data-chatbot-has-key') === '1';
        if (strict && !hasKey && !this._notifiedNoAI) {
            this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
            this._notifiedNoAI = true;
        }
    }

    closeChat() {
        this.window.classList.remove('active');
        this.isOpen = false;
        
        // Show pulse animation when closed
        const pulse = this.toggle.querySelector('.chatbot-pulse');
        if (pulse) pulse.style.display = 'block';
    }

    sendMessage() {
        const message = this.input.value.trim();
        if (!message || this.isTyping) return;

        // If in agent mode, send to agent instead
        if (this.isAgentMode && this.agentSessionId) {
            this.addMessage(message, 'user', null);
            this.input.value = '';
            this.sendMessageToAgent(message);
            return;
        }

        const body = document.body;
        const strict = body.getAttribute('data-chatbot-strict') === '1';
        const hasKey = body.getAttribute('data-chatbot-has-key') === '1';
        if (strict && !hasKey) {
            this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
            return;
        }

        // Track question for satisfaction checking
        this.trackQuestion(message);

        this.addMessage(message, 'user');
        this.input.value = '';
        this.send.disabled = true;
        
        this.showTyping();
        // Try real AI via backend first; on failure, fall back to local rule-based responses
        this.askAI(message)
            .then(aiText => {
                this.hideTyping();
                if (aiText) {
                    // Check if backend wants to trigger frontend service options
                    if (aiText.startsWith('TRIGGER_SERVICE_OPTIONS:')) {
                        const serviceType = aiText.replace('TRIGGER_SERVICE_OPTIONS:', '');
                        this.addMessage(this.getServiceOptions(serviceType), 'bot');
                    } else if (aiText === 'TRIGGER_BARANGAY_ID_OPTIONS') {
                        // Show specific Barangay ID options
                        const barangayIdResponse = `What would you like to know about Barangay ID?\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>\n    <button class="quick-action-btn" data-action="get-barangay-id">📋 Get Barangay ID</button>\n</div>`;
                        this.addMessage(barangayIdResponse, 'bot');
                    } else {
                        this.addMessage(aiText, 'bot');
                        // Check if we should offer agent escalation
                        this.checkSatisfactionAndOfferAgent(message, aiText);
                    }
                } else {
                    this.processMessage(message);
                }
            })
            .catch((err) => {
                this.hideTyping();
                
                // Handle different error types with appropriate messages
                if (err && err.message === 'strict_mode_active') {
                    this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
                } else if (err && err.message && err.message.includes('AI service')) {
                    this.addMessage(err.message, 'bot');
                } else if (err && err.message === 'I can only help with barangay-related questions.') {
                    this.addMessage('I can only help with barangay-related questions. Please ask about documents, office hours, complaints, or other barangay services.', 'bot');
                } else if (err && err.message && err.message.length > 10) {
                    // If we have a meaningful error message from the server
                    this.addMessage(err.message, 'bot');
                } else {
                    // Fallback to local processing for other errors
                    this.processMessage(message);
                }
            })
            .finally(() => {
                this.send.disabled = false;
            });
    }

    // Call backend Hugging Face proxy
    async askAI(message) {
        try {
            const payload = {
                message: message,
                language: this.detectLanguage(message),
                context: 'public'
            };
            
            const res = await fetch('/api/chatbot/chat', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });
            
            // Always try to parse JSON response
            let data = null;
            try {
                data = await res.json();
            } catch (parseError) {
                console.error('Failed to parse response:', parseError);
                throw new Error('Invalid response format');
            }
            
            if (res.ok && data && data.success && data.response) {
                return data.response;
            }
            
            // Handle error responses with proper messages
            if (data && data.strict) {
                throw new Error('strict_mode_active');
            }
            
            if (data && data.message) {
                throw new Error(data.message);
            }
            
            // Handle HTTP error status
            if (!res.ok) {
                const errorMsg = data?.error || `Server error (${res.status})`;
                throw new Error(errorMsg);
            }
            
            return '';
        } catch (e) {
            console.error('AI request failed:', e.message);
            throw e;
        }
    }

    detectLanguage(text) {
        const t = (text || '').toLowerCase();
        const filipinoHints = ['po', 'opo', 'barangay', 'dokumento', 'serbisyo', 'oras', 'reklamo', 'saan', 'nasaan'];
        return filipinoHints.some(w => t.includes(w)) ? 'tl' : 'en';
    }

    addMessage(content, sender, timestamp = null) {
        // Check for duplicate messages in agent mode (to prevent duplicates from polling)
        if (this.isAgentMode && sender === 'bot' && timestamp) {
            const existingMessages = this.messages.querySelectorAll('.message.bot');
            for (let msg of existingMessages) {
                const msgContent = msg.querySelector('.message-content').innerHTML;
                const msgTimestamp = msg.getAttribute('data-timestamp');
                if (msgContent === content && msgTimestamp === timestamp) {
                    return; // Skip duplicate message
                }
            }
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        // Add timestamp attribute for duplicate checking
        if (timestamp) {
            messageDiv.setAttribute('data-timestamp', timestamp);
        }
        
        // Only add avatar for bot messages, not for user messages
        if (sender === 'bot') {
            const avatar = document.createElement('div');
            avatar.className = 'message-avatar';
            avatar.innerHTML = '<i class="fas fa-robot"></i>';
            messageDiv.appendChild(avatar);
        }
        
        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        messageContent.innerHTML = content;
        
        messageDiv.appendChild(messageContent);
        
        this.messages.appendChild(messageDiv);
        
        // Add event listeners to any buttons in the message
        this.attachButtonListeners(messageDiv);
        
        this.scrollToBottom();
    }

    attachButtonListeners(messageElement) {
        const buttons = messageElement.querySelectorAll('.quick-action-btn');
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Block quick actions if strict mode and no AI key
                const body = document.body;
                const strict = body.getAttribute('data-chatbot-strict') === '1';
                const hasKey = body.getAttribute('data-chatbot-has-key') === '1';
                if (strict && !hasKey) {
                    this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
                    return;
                }
                
                const buttonText = button.textContent.trim();
                
                // Add user message for the button clicked
                this.addMessage(buttonText, 'user');
                
                // Handle service choice buttons
                const service = button.getAttribute('data-service');
                const method = button.getAttribute('data-method');
                const action = button.getAttribute('data-action');
                
                if (service && method) {
                    this.showTyping();
                    setTimeout(() => {
                        this.hideTyping();
                        const response = this.handleServiceChoice(service, method);
                        this.addMessage(response, 'bot');
                    }, 1000);
                    return;
                }

                // Handle specific actions
                if (action) {
                    this.showTyping();
                    setTimeout(() => {
                        this.hideTyping();
                        let response = '';
                        switch(action) {
                            case 'barangay-id':
                                response = this.generateResponse('barangay id requirements');
                                break;
                            case 'what-is-barangay-id':
                                response = this.getBarangayIdInfo();
                                break;
                            case 'get-barangay-id':
                                response = this.getBarangayIdOptions();
                                break;
                            case 'document-types':
                                response = this.getDocumentTypesInfo();
                                break;
                            case 'emergency':
                                response = this.generateResponse('emergency');
                                break;
                            case 'complaint-types':
                                response = this.generateResponse('complaint types');
                                break;
                            case 'requirements':
                                response = this.getGeneralRequirements();
                                break;
                            default:
                                response = this.generateResponse(action);
                        }
                        this.addMessage(response, 'bot');
                    }, 1000);
                    return;
                }
                
                // Handle other button actions
                const onclickAttr = button.getAttribute('onclick');
                if (onclickAttr) {
                    try {
                        if (onclickAttr.includes('window.open')) {
                            const urlMatch = onclickAttr.match(/window\.open\('([^']+)'/);
                            if (urlMatch) {
                                window.open(urlMatch[1], '_blank');
                                this.addMessage('Opening the document request system for you! You can now proceed with your online application.', 'bot');
                            }
                        } else if (onclickAttr.includes('addMessage')) {
                            const messageMatch = onclickAttr.match(/addMessage\('([^']+)',\s*'user'\)/);
                            const processMatch = onclickAttr.match(/processMessage\('([^']+)'\)/);
                            if (messageMatch && processMatch) {
                                const processMsg = processMatch[1];
                                this.showTyping();
                                setTimeout(() => {
                                    this.hideTyping();
                                    this.processMessage(processMsg);
                                }, 1000);
                            }
                        } else if (onclickAttr.includes('sendQuickMessage')) {
                            const messageMatch = onclickAttr.match(/sendQuickMessage\('([^']+)'\)/);
                            if (messageMatch) {
                                const message = messageMatch[1];
                                this.showTyping();
                                setTimeout(() => {
                                    this.hideTyping();
                                    this.processMessage(message.toLowerCase());
                                }, 1000);
                            }
                        }
                    } catch (error) {
                        console.error('Error executing button action:', error);
                        this.addMessage('Sorry, there was an error processing your request. Please try again or contact our office directly.', 'bot');
                    }
                }
            });
        });
    }

    getGeneralRequirements() {
        return `📋 **General Requirements for All Documents:**\n\n**Basic Requirements:**\n• Valid Barangay ID\n• Government-issued ID (Driver's License, Passport, etc.)\n• Cedula (Community Tax Certificate)\n• Proof of residency (Utility bill, lease contract)\n\n**For Specific Documents:**\n• **Indigency:** Income certification, Medical needs\n• **Business:** Business plan, Location clearance\n• **Good Moral:** Character references\n\n**Important Notes:**\n• All documents must be clear copies\n• Photos/scans should be readable\n• Bring originals for verification\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/request-document', '_blank')">📋 Apply Now</button>\n</div>`;
    }

    getBarangayIdInfo() {
        return `🆔 What is Barangay ID?\n\n        <br><br>    \n\nBarangay ID is your official identification as a resident of Barangay Lumanglipa.\n\n<br><br>\n\nPurpose:\n<br> <br>\n• Proof of residency in Lumanglipa Barangay\n<br>\n• Required for all barangay services and concerns\n<br>\n• Access to documents, health services, and complaints\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" data-action="get-barangay-id">📋 Get Barangay ID</button>\n</div>`;
    }

    getBarangayIdOptions() {
        return `🆔 Get Barangay ID\n        <br>\n\nHow would you like to get your Barangay ID?\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" data-service="barangay-id" data-method="online">📱 Online Application</button>\n    <button class="quick-action-btn" data-service="barangay-id" data-method="walkin">🚶 Walk-in Application</button>\n</div>`;
    }

    showTyping() {
        this.isTyping = true;
        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `\n            <div class="typing-dots">\n                <div class="typing-dot"></div>\n                <div class="typing-dot"></div>\n                <div class="typing-dot"></div>\n            </div>\n        `;
        this.messages.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideTyping() {
        this.isTyping = false;
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    processMessage(message) {
        const response = this.generateResponse(message.toLowerCase());
        this.addMessage(response, 'bot');
    }

    generateResponse(message) {
        // Check for greetings
        if (message.match(/^(hi|hello|hey|good morning|good afternoon|good evening)/i)) {
            return "Hello! Welcome to Barangay Lumanglipa. I'm here to help you with information about our services. What would you like to know about?";
        }

        // Check knowledge base
        for (const [keywords, response] of this.knowledgeBase) {
            if (keywords.some(keyword => message.includes(keyword))) {
                return response;
            }
        }

        // Check if inquiry is related to barangay services
        const barangayRelatedKeywords = [
            'barangay', 'document', 'clearance', 'certificate', 'residency', 'indigency',
            'health', 'medical', 'clinic', 'doctor', 'medicine', 'complaint', 'problem',
            'issue', 'concern', 'report', 'captain', 'councilor', 'official', 'office',
            'hours', 'schedule', 'contact', 'address', 'location', 'phone', 'email',
            'registration', 'register', 'resident', 'id', 'identification', 'service',
            'assistance', 'help', 'application', 'request', 'file', 'submit', 'process',
            'emergency', 'urgent', 'mediation', 'conciliation', 'dispute', 'lumanglipa',
            'mataas na kahoy', 'batangas', 'purok', 'secretary', 'requirements'
        ];

        const isBarangayRelated = barangayRelatedKeywords.some(keyword => 
            message.toLowerCase().includes(keyword.toLowerCase())
        );

        if (!isBarangayRelated) {
            return `Thank you for reaching out! 😊\n<br><br>\nUnfortunately, the inquiry you've made is outside the scope of our current services.\n<br><br>\nIf there's anything else we can help you with or if you have questions related to our services offerings, feel free to ask!\n<br><br>\nWe're here to assist you the best we can.`;
        }

        // Default response for barangay-related but unmatched queries
        return `I understand you're asking about \"${message}\". For specific inquiries about barangay services, you can:\n        \n        📞 Call us at (043) 123-4567\n        📧 Email: info@lumanglipa.gov.ph\n        🏢 Visit our office: Mon-Fri, 8:00 AM - 5:00 PM\n        \n        Is there anything else about our standard services I can help you with?`;
    }

    // Enhanced method to handle service choices
    handleServiceChoice(service, method) {
        if (method === 'online') {
            return this.getOnlineGuide(service);
        } else if (method === 'walkin') {
            return this.getWalkInGuide(service);
        }
        return this.getServiceOptions(service);
    }

    getServiceOptions(service) {
        const serviceKey = service.toLowerCase().replace(' services', '').replace(' filing', '').replace('document services', 'document').trim();
        
        return `How would you like to proceed with <strong>${service}</strong>?\n        \n        <div class="quick-actions" style="margin-top: 15px;">\n            <button class="quick-action-btn" data-service="${serviceKey}" data-method="online">📱 Online Service</button>\n            <button class="quick-action-btn" data-service="${serviceKey}" data-method="walkin">🚶 Walk-in Service</button>\n        </div>`;
    }

    getOnlineGuide(service) {
        const guides = {
            'document': `Online Document Request\n            <br><br>\n\nWhat you need:\n<br><br>\n\n• Barangay ID\n<br><br>\n\n1-3 Business Days for Processing\n<br><br>\n\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/request-document', '_blank')">🚀 Request Document Now</button>\n    <button class="quick-action-btn" data-action="barangay-id">❓ Barangay ID</button>\n</div>`,

            'barangay-id': `📱 Online Barangay ID Application\n            <br><br>\n\nWhat you need:\n<br><br>\n\n\n• Any ID\n<br>\n• Proof of Residency\n<br><br>\n\nProcessing: 1-3 business days after admin approval\n<br><br>\n\nClick \"Register Now\" to submit your pre-application\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="window.open('/pre-registration', '_blank')">📝 Register Now</button>\n    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>\n</div>`,

            'health': `Online Health Services\n            <br><br>\n\nWhat you need:\n<br><br>\n\n• Barangay ID\n<br><br>\n\nAvailable Services: Medical consultation, Health certificates, BP monitoring\n<br><br>\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/health/request', '_blank')">🩺 Request Health Service</button>\n    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>\n</div>`,

            'complaint': `Online Complaint Filing\n            <br><br>\n\nWhat you need:\n<br><br>\n\n• Barangay ID\n<br><br>\n\n1-3 Business Days for Processing\n\n<br><br>\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/complaints/file', '_blank')">📋 File Complaint Now</button>\n    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>\n</div>`
        };

        return guides[service] || this.getServiceOptions(service);
    }

    getWalkInGuide(service) {
        const guides = {
            'document': `🚶 Walk-in Document Request\n\n            <br>\n            <br>\n\nLocation: Barangay Hall of Lumanglipa, located in Purok 1\n\n<br>\n<br>\n\nWhat to Bring:\n<br>\n• ✅ Barangay ID (or any valid ID if you don't have Barangay ID yet)\n\n<br>\n<br>\nProcess:\n\n1. Go to Barangay Hall in Purok 1\n<br>\n2. Find the Secretary\n<br>\n3. Tell them what document you need\n<br>\n4. Fill up the form\n<br>\n5. Pay ₱50 for the document\n<br>\n6. Get your document same day\n<br>\n<br>\n\nOffice Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM\n<br>\nProcessing: Same day\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="chatbot.addMessage('office location', 'user'); chatbot.processMessage('contact location')">📍 Office Location</button>\n    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>\n</div>`,

            'health': `🚶 Walk-in Health Services\n\n            <br>\n            <br>\n\nLocation: Barangay Hall, Purok 1\n\n<br>\n<br>\n\nWhat to Bring:\n<br>\n• ✅ Barangay ID (or any valid ID if you don't have Barangay ID yet)\n\n<br>\n<br>\nProcess:\n\n1. Go to Health Center in Purok 1\n<br>\n2. Tell the barangay health workers what service you need\n\n<br>\n<br>\n\nOffice Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>\n</div>`,

            'complaint': `� Walk-in Complaint Filing\n\n            <br>\n            <br>\n\nLocation: Barangay Hall of Lumanglipa, Purok 1\n\n<br>\n<br>\n\nWhat to Bring:\n<br>\n• ✅ Barangay ID (or any valid ID if you don't have Barangay ID yet)\n\n<br>\n<br>\nProcess:\n\n1. Go to Barangay Hall in Purok 1\n<br>\n2. Find the secretary or Barangay Captain\n<br>\n3. Explain your complaint\n<br>\n4. Fill up the complaint form\n<br>\n5. Submit any evidence you have\n<br>\n<br>\n\nOffice Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM\n<br>\nResponse: 1-3 business days\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>\n</div>`,

            'barangay-id': `🚶 Walk-in Barangay ID Application\n<br>\n<br>\n\nWhat to Bring:\n<br>\n\n• ✅ Any ID  \n<br>\n• ✅ Proof of Residency \n\n<br>\n<br>\nProcess:\n<br>\n<br>\n1. Go to secretary and ask for registration of barangay ID\n<br>\n2. Submit the ID and proof of residency\n<br>\n\n3. Wait for the processing of your barangay ID\n\n\n<br>\n<br>\nOffice Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM\n\n<br>\n<br>\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" onclick="chatbot.addMessage('office location', 'user'); chatbot.processMessage('contact location')">📍 Office Location</button>\n    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>\n</div>`
        };

        return guides[service] || this.getServiceOptions(service);
    }

    initKnowledgeBase() {
        return new Map([
            [['document', 'document request', 'documents', 'clearance', 'certificate', 'residency', 'indigency', 'request', 'filing', 'services', 'service', 'dokumento', 'documento', 'sertipiko', 'clearanse', 'patunay', 'papeles', 'papel', 'barangay clearance', 'certificate of residency'], 
             this.getServiceOptions('Document Services')],
            
            [['health', 'health services', 'medical', 'clinic', 'medicine', 'doctor', 'kalusugan', 'medisina', 'doktor'],
             this.getServiceOptions('Health Services')],
            
            [['complaint', 'file complaint', 'problem', 'issue', 'concern', 'report', 'reklamo', 'problema', 'hinaing'],
             this.getServiceOptions('Complaint Filing')],

            [['barangay id', 'id card', 'resident id', 'identification'],
             `What would you like to know about Barangay ID?\n\n<div class="quick-actions" style="margin-top: 15px;">\n    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>\n    <button class="quick-action-btn" data-action="get-barangay-id">📋 Get Barangay ID</button>\n</div>`],

            [['lost id', 'replace id', 'id replacement'],
             `🔄 **Lost/Damaged Barangay ID Replacement:**\n             \n             **Requirements:**\n             • Affidavit of Loss (if lost)\n             • Valid government ID\n             • 2x2 ID picture (2 pieces)\n             • Police report (for lost ID)\n             • Replacement fee: ₱150\n             \n             **Processing Time:** 3-5 business days\n             **Temporary ID:** Available for urgent needs (₱20)\n             \n             <div class="quick-actions" style="margin-top: 15px;">\n                 <button class="quick-action-btn" onclick="chatbot.addMessage('temporary id', 'user'); chatbot.processMessage('temporary id')">⚡ Temporary ID Info</button>\n             </div>`],

            [['emergency', 'urgent', '911', 'emergency contact'],
             `🚨 **Emergency Contacts:**\n             \n             **Barangay Emergency Hotline:** (043) 123-4567\n             **Police:** 117 or (043) 456-7890\n             **Fire Department:** 116 or (043) 456-7891\n             **Medical Emergency:** 911 or (043) 456-7892\n             \n             **Barangay Emergency Response:**\n             • Available 24/7\n             • First aid response\n             • Disaster coordination\n             • Security concerns\n             \n             **For non-life threatening health issues:**\n             Walk-in to Barangay Health Center during office hours.`],

            [['track complaint', 'complaint status', 'follow up'],
             `📊 **Track Your Complaint:**\n             \n             **Online Tracking:**\n             • Use your tracking number at our complaint portal\n             • Receive SMS/email updates automatically\n             \n             **Walk-in Inquiry:**\n             • Bring your claim stub to the office\n             • Ask for status update at Complaints desk\n             \n             **Response Timeline:**\n             • Acknowledgment: Within 24 hours\n             • Initial action: 3-5 business days\n             • Resolution: 7-14 business days (depending on complexity)\n             \n             <div class="quick-actions" style="margin-top: 15px;">\n                 <button class="quick-action-btn" onclick="window.open('${window.location.origin}/complaints/track', '_blank')">🔍 Track Online</button>\n             </div>`],

            [['mediation', 'conciliation', 'dispute resolution'],
             `⚖️ **Barangay Mediation Services:**\n             \n             **Free Conciliation Services:**\n             • Neighbor disputes\n             • Property boundary issues\n             • Minor civil conflicts\n             • Family disputes\n             \n             **Process:**\n             1. File complaint\n             2. Summon both parties\n             3. Mediation session\n             4. Agreement drafting\n             5. Legal documentation\n             \n             **Benefits:**\n             • Free service\n             • Faster resolution\n             • Preserve relationships\n             • Avoid court proceedings\n             \n             **Schedule:** Every Tuesday and Thursday, 2:00 PM - 5:00 PM`],

            [['complaint types', 'what complaints'],
             `📋 **Types of Complaints We Handle:**\n             \n             **Public Order & Safety:**\n             • Noise disturbance\n             • Public nuisance\n             • Illegal activities\n             • Safety hazards\n             \n             **Property & Civil Issues:**\n             • Boundary disputes\n             • Right of way issues\n             • Property damage\n             • Rental disputes\n             \n             **Infrastructure:**\n             • Poor road conditions\n             • Drainage problems\n             • Street lighting\n             • Water supply issues\n             \n             **Environmental:**\n             • Improper waste disposal\n             • Water pollution\n             • Air quality concerns\n             \n             **Note:** Criminal cases should be reported directly to police.`],
            
            [['contact', 'phone', 'address', 'location', 'office'],
             `📍 Office Location:\n             <br>\n             \n             Purok 1, Lumanglipa, Mataas na Kahoy, Batangas\n             <br>\n             <br>\n             \n             \n             Office Hours:\n             <br>\n            <br>\n             Monday-Friday: 8:00 AM - 5:00 PM\n             <br>\n             Saturday: 8:00 AM - 12:00 PM\n             <br>\n             Sunday: Closed`],
            
            [['schedule', 'hours', 'time', 'open', 'closed'],
             `🕐 **Office Schedule:**\n             \n             **Regular Hours:**\n             • Monday-Friday: 8:00 AM - 5:00 PM\n             • Saturday: 8:00 AM - 12:00 PM\n             • Sunday: Closed\n             \n             **Lunch Break:** 12:00 PM - 1:00 PM\n             **Emergency Services:** 24/7 available`],
            
            [['officials', 'captain', 'councilor', 'barangay official'],
             `👥 **Barangay Officials:**\n             \n             Our dedicated officials serve the community. You can learn more about them on our <a href="${window.location.origin}/about" target="_blank">About Page</a>.\n             \n             **How to reach officials:**\n             • Schedule appointment at the office\n             • Attend monthly barangay assembly\n             • Submit written concerns`],
            
            [['registration', 'register', 'new resident', 'move'],
             `📋 **New Resident Registration:**\n             \n             Welcome to Barangay Lumanglipa! To register as a new resident:\n             \n             **Requirements:**\n             • Transfer Certificate/Clearance from previous barangay\n             • Valid ID\n             • Proof of address\n             \n             **Start here:** <a href="${window.location.origin}/pre-registration" target="_blank">Registration Form</a>`],
            
            [['thank', 'thanks', 'salamat'],
             `You're welcome! Is there anything else you'd like to know about Barangay Lumanglipa services? I'm here to help! 😊`],
            
            [['bye', 'goodbye', 'see you'],
             `Thank you for contacting Barangay Lumanglipa! Have a great day and feel free to reach out anytime you need assistance. 👋`]
        ]);
    }

    scrollToBottom() {
        this.messages.scrollTop = this.messages.scrollHeight;
    }

    // Generate unique user session
    generateUserSession() {
        return 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    // Track questions for satisfaction checking
    trackQuestion(question) {
        const normalizedQuestion = question.toLowerCase().trim();
        this.questionHistory.push({
            question: normalizedQuestion,
            timestamp: Date.now()
        });

        // Keep only last 10 questions
        if (this.questionHistory.length > 10) {
            this.questionHistory = this.questionHistory.slice(-10);
        }
    }

    // Check if user satisfaction should be assessed and offer agent
    checkSatisfactionAndOfferAgent(question, response) {
        if (this.agentEscalationOffered) return;

        const normalizedQuestion = question.toLowerCase().trim();
        
        // Count similar questions in recent history
        const recentQuestions = this.questionHistory.filter(h => 
            Date.now() - h.timestamp < 300000 // Last 5 minutes
        );

        const similarQuestions = recentQuestions.filter(h => 
            this.calculateSimilarity(h.question, normalizedQuestion) > 0.7
        ).length;

        // If user asked similar question 3 times, offer agent
        if (similarQuestions >= 3 && !this.satisfactionCheckShown) {
            this.satisfactionCheckShown = true;
            setTimeout(() => {
                this.offerAgentEscalation();
            }, 2000);
        }
    }

    // Calculate similarity between two strings
    calculateSimilarity(str1, str2) {
        const words1 = str1.split(' ');
        const words2 = str2.split(' ');
        const intersection = words1.filter(word => words2.includes(word));
        const union = [...new Set([...words1, ...words2])];
        return intersection.length / union.length;
    }

    // Offer agent escalation
    offerAgentEscalation() {
        const message = `
            <div style="padding: 15px; border: 2px solid #ffc107; border-radius: 10px; background: #fff3cd; margin: 10px 0;">
                <h5 style="color: #856404; margin: 0 0 10px 0;">🤔 Need More Help?</h5>
                <p style="color: #856404; margin: 0 0 15px 0;">
                    I notice you've asked similar questions. Are you satisfied with my responses, or would you like to talk to a barangay staff member?
                </p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button class="quick-action-btn" onclick="window.barangayChatbot.handleSatisfactionResponse('satisfied')" 
                            style="background: #28a745; color: white;">✅ I'm Satisfied</button>
                    <button class="quick-action-btn" onclick="window.barangayChatbot.handleSatisfactionResponse('agent')" 
                            style="background: #007bff; color: white;">💬 Talk to Agent</button>
                </div>
            </div>
        `;
        this.addMessage(message, 'bot');
    }

    // Handle satisfaction response
    handleSatisfactionResponse(response) {
        if (response === 'satisfied') {
            this.addMessage('Great! I\'m glad I could help. Feel free to ask if you have any other questions!', 'bot');
        } else if (response === 'agent') {
            this.escalateToAgent();
        }
    }

    // Escalate to human agent
    async escalateToAgent() {
        this.agentEscalationOffered = true;
        
        this.addMessage('Connecting you to a barangay staff member. Please wait...', 'bot');
        this.showTyping();

        try {
            const response = await fetch('/api/agent-conversation/escalate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_session: this.userSession,
                    conversation_history: this.getConversationHistory(),
                    escalation_reason: 'User requested agent after repeated questions'
                })
            });

            const data = await response.json();
            
            this.hideTyping();
            
            if (data.success) {
                // Show queue position
                if (data.queue_status === 'waiting') {
                    this.addMessage(`
                        <div style="padding: 15px; border: 2px solid #ffc107; border-radius: 10px; background: #fff3cd; margin: 10px 0;">
                            <h5 style="color: #856404; margin: 0 0 10px 0;">⏳ You're in the Queue</h5>
                            <p style="color: #856404; margin: 0;">
                                You are <strong>#${data.queue_position}</strong> in line to talk to an agent.
                                Please wait while an agent becomes available.
                            </p>
                            <div id="queueStatusIndicator" style="margin-top: 10px; padding: 8px; background: white; border-radius: 5px; text-align: center; font-weight: bold;">
                                Queue Position: <span id="queuePositionNumber">${data.queue_position}</span>
                            </div>
                        </div>
                    `, 'bot');
                    
                    this.queuePosition = data.queue_position;
                    this.queueStatus = 'waiting';
                } else if (data.queue_status === 'active') {
                    this.addMessage(`
                        <div style="padding: 15px; border: 2px solid #28a745; border-radius: 10px; background: #d4edda; margin: 10px 0;">
                            <h5 style="color: #155724; margin: 0 0 10px 0;">✅ Connected to Agent</h5>
                            <p style="color: #155724; margin: 0;">
                                You're now connected to a barangay staff member. They will respond to your messages.
                                Please continue asking your questions here.
                            </p>
                        </div>
                    `, 'bot');
                    
                    this.queueStatus = 'active';
                }
                
                // Change the chatbot behavior to agent mode
                this.switchToAgentMode(data.session_id);
            } else {
                this.addMessage('Sorry, unable to connect to an agent right now. Please try again later or contact the barangay office directly.', 'bot');
            }
        } catch (error) {
            this.hideTyping();
            console.error('Error escalating to agent:', error);
            this.addMessage('Sorry, there was an error connecting to an agent. Please try again later.', 'bot');
        }
    }

    // Switch to agent conversation mode
    switchToAgentMode(sessionId) {
        this.agentSessionId = sessionId;
        this.isAgentMode = true;
        
        // Initialize last message check time to current time to avoid loading old messages
        this.lastMessageCheck = new Date().toISOString();
        
        // Start polling for agent responses
        this.startAgentPolling();
        
        // Update UI to show agent mode
        const header = this.window.querySelector('.chatbot-header h4');
        if (header) {
            header.innerHTML = '💬 Talking to Agent';
        }
    }

    // Start polling for agent responses
    startAgentPolling() {
        if (this.agentPollInterval) {
            clearInterval(this.agentPollInterval);
        }
        
        this.agentPollInterval = setInterval(() => {
            // Check queue status for updates (waiting -> active -> completed)
            this.checkQueueStatus();
            
            // Only check for messages if conversation is active
            if (this.queueStatus === 'active') {
                this.checkForAgentMessages();
            }
        }, 3000);
    }

    // Check queue status and update position
    async checkQueueStatus() {
        if (!this.agentSessionId) return;

        try {
            const response = await fetch(`/api/agent-conversation/${this.agentSessionId}/queue-status`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                // Check if conversation was completed by admin
                if (data.queue_status === 'completed' && this.queueStatus !== 'completed') {
                    this.queueStatus = 'completed';
                    this.addMessage(`
                        <div style="padding: 15px; border: 2px solid #17a2b8; border-radius: 10px; background: #d1ecf1; margin: 10px 0;">
                            <h5 style="color: #0c5460; margin: 0 0 10px 0;">✓ Conversation Ended</h5>
                            <p style="color: #0c5460; margin: 0;">
                                The agent has ended this conversation. Thank you for contacting us!
                            </p>
                            <p style="color: #0c5460; margin: 10px 0 0 0; font-size: 13px;">
                                If you have more questions, feel free to start a new conversation.
                            </p>
                        </div>
                    `, 'bot');
                    
                    // Update header
                    const header = this.window.querySelector('.chatbot-header h4');
                    if (header) {
                        header.innerHTML = '💬 Barangay Chatbot';
                    }
                    
                    // Stop polling
                    if (this.agentPollInterval) {
                        clearInterval(this.agentPollInterval);
                        this.agentPollInterval = null;
                    }
                    
                    // Reset agent mode after a delay to allow user to see the message
                    setTimeout(() => {
                        this.isAgentMode = false;
                        this.agentSessionId = null;
                    }, 2000);
                    
                    return; // Exit early
                }
                
                // Check if status changed from waiting to active
                if (this.queueStatus === 'waiting' && data.queue_status === 'active') {
                    this.queueStatus = 'active';
                    this.addMessage(`
                        <div style="padding: 15px; border: 2px solid #28a745; border-radius: 10px; background: #d4edda; margin: 10px 0;">
                            <h5 style="color: #155724; margin: 0 0 10px 0;">✅ Agent Connected!</h5>
                            <p style="color: #155724; margin: 0;">
                                An agent is now available. You can start chatting!
                            </p>
                        </div>
                    `, 'bot');
                    
                    // Update header
                    const header = this.window.querySelector('.chatbot-header h4');
                    if (header) {
                        header.innerHTML = '💬 Talking to Agent';
                    }
                } else if (data.queue_status === 'waiting' && data.queue_position !== this.queuePosition) {
                    // Update queue position
                    this.queuePosition = data.queue_position;
                    const positionElement = document.getElementById('queuePositionNumber');
                    if (positionElement) {
                        positionElement.textContent = data.queue_position;
                    }
                }
            }
        } catch (error) {
            console.error('Error checking queue status:', error);
        }
    }

    // Check for new agent messages
    async checkForAgentMessages() {
        if (!this.agentSessionId) return;

        try {
            // Build URL with since parameter if we have a last check time
            let url = `/api/agent-conversation/${this.agentSessionId}/new-messages`;
            if (this.lastMessageCheck) {
                url += `?since=${encodeURIComponent(this.lastMessageCheck)}`;
            }

            const response = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    if (msg.sender_type === 'admin') {
                        this.addMessage(msg.message, 'bot', msg.created_at);
                    }
                });
                
                // Update last check time to the timestamp of the newest message
                const newestMessage = data.messages[data.messages.length - 1];
                this.lastMessageCheck = newestMessage.created_at;
            } else {
                // Update last check time even if no new messages
                this.lastMessageCheck = new Date().toISOString();
            }
        } catch (error) {
            console.error('Error checking for agent messages:', error);
        }
    }

    // Send user message to agent
    async sendMessageToAgent(message) {
        console.log('[USER CHATBOT] Sending message to agent:', message, 'Session:', this.agentSessionId);
        if (!this.agentSessionId) {
            console.error('[USER CHATBOT] No agent session ID available');
            this.addMessage('Error: Not connected to agent. Please try escalating again.', 'bot');
            return;
        }

        // Block sending messages if conversation is completed
        if (this.queueStatus === 'completed') {
            this.addMessage(`
                <div style="padding: 10px; border: 1px solid #17a2b8; border-radius: 5px; background: #d1ecf1; color: #0c5460;">
                    ✓ This conversation has ended. Please start a new conversation if you need more help.
                </div>
            `, 'bot');
            return;
        }

        // Block sending messages if still in queue
        if (this.queueStatus === 'waiting') {
            this.addMessage(`
                <div style="padding: 10px; border: 1px solid #ffc107; border-radius: 5px; background: #fff3cd; color: #856404;">
                    ⏳ Please wait for your turn. You are #${this.queuePosition} in line.
                </div>
            `, 'bot');
            return;
        }

        try {
            const response = await fetch('/api/agent-conversation/send-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_id: this.agentSessionId,
                    message: message,
                    user_session: this.userSession
                })
            });

            const data = await response.json();
            
            if (!data.success) {
                console.error('Failed to send message to agent:', data.message);
                
                // Check if it's a queue-related error
                if (data.queue_position) {
                    this.addMessage(`
                        <div style="padding: 10px; border: 1px solid #ffc107; border-radius: 5px; background: #fff3cd; color: #856404;">
                            ⏳ Please wait for your turn. You are #${data.queue_position} in line.
                        </div>
                    `, 'bot');
                } else {
                    this.addMessage('Message failed to send. Please try again.', 'bot');
                }
            } else {
                console.log('Message sent to agent successfully');
            }
        } catch (error) {
            console.error('Error sending message to agent:', error);
            this.addMessage('Connection error. Please check your internet and try again.', 'bot');
        }
    }

    // Get conversation history for escalation
    getConversationHistory() {
        const messages = this.messages.querySelectorAll('.message');
        return Array.from(messages).slice(-10).map(msg => {
            const sender = msg.classList.contains('user') ? 'user' : 'bot';
            const content = msg.querySelector('.message-content').textContent;
            return { sender, content };
        }).filter(msg => {
            // Exclude escalation-related bot messages that shouldn't be seen by admin
            if (msg.sender === 'bot') {
                const content = msg.content.toLowerCase();
                return !content.includes('connecting you to a barangay staff') &&
                       !content.includes('connected to agent') &&
                       !content.includes('would you like to talk to a barangay staff') &&
                       !content.includes('talk to agent') &&
                       !content.includes('need more help');
            }
            return true;
        });
    }
}

// Quick message function for action buttons
function sendQuickMessage(message) {
    const chatbot = window.barangayChatbot;
    if (chatbot) {
        chatbot.addMessage(message, 'user');
        chatbot.showTyping();
        setTimeout(() => {
            chatbot.hideTyping();
            chatbot.processMessage(message.toLowerCase());
        }, 1000);
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if user chatbot elements exist before initializing
    if (document.getElementById('chatbotToggle')) {
        console.log('[USER CHATBOT] Initializing User Chatbot...');
        window.barangayChatbot = new BarangayChatbot();
        // Make chatbot globally accessible for button callbacks
        window.chatbot = window.barangayChatbot;
    } else {
        console.log('[USER CHATBOT] Elements not found, skipping initialization');
    }
});
