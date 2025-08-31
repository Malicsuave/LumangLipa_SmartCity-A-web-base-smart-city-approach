<!DOCTYPE html>
<html>
<head>
    <title>Test Chat Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .chat-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .chat-header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        
        .conversation-messages {
            height: 400px;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: #fafafa;
        }
        
        .message {
            max-width: 85%;
            padding: 10px 14px;
            border-radius: 16px;
            line-height: 1.4;
            font-size: 13px;
            word-wrap: break-word;
            position: relative;
        }
        
        .message.user {
            background: #e3f2fd;
            color: #1565c0;
            align-self: flex-start;
            border-bottom-left-radius: 6px;
            margin-right: auto;
            margin-left: 0;
        }
        
        .message.admin {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 6px;
            border-right: 3px solid #007bff;
            margin-left: auto;
            margin-right: 0;
        }
        
        .message-timestamp {
            font-size: 10px;
            opacity: 0.7;
            margin-top: 4px;
        }
        
        .message.user .message-timestamp {
            text-align: left;
        }
        
        .message.admin .message-timestamp {
            text-align: right;
        }
        
        .input-area {
            padding: 15px;
            background: white;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 10px;
        }
        
        .input-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .input-area button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            Live Chat Layout Test - Admin View
        </div>
        
        <div class="conversation-messages">
            <div class="message user">
                Hello admin, I need help with my request.
                <div class="message-timestamp">14:30</div>
            </div>
            
            <div class="message admin">
                Hi! I'm here to help. What can I assist you with?
                <div class="message-timestamp">14:31</div>
            </div>
            
            <div class="message user">
                I am not satisfied with the AI response. I need human help!
                <div class="message-timestamp">14:32</div>
            </div>
            
            <div class="message admin">
                I understand. Let me help you with that. Can you tell me more about what you need?
                <div class="message-timestamp">14:33</div>
            </div>
            
            <div class="message user">
                I need to get a barangay clearance but the system is confusing.
                <div class="message-timestamp">14:34</div>
            </div>
            
            <div class="message admin">
                No problem! I can guide you through the process step by step.
                <div class="message-timestamp">14:35</div>
            </div>
        </div>
        
        <div class="input-area">
            <input type="text" placeholder="Type your response here..." />
            <button>Send</button>
        </div>
    </div>
    
    <div style="margin-top: 20px; text-align: center; color: #666;">
        <p><strong>Expected Layout:</strong></p>
        <p>• User messages (blue) should appear on the LEFT</p>
        <p>• Admin messages (green) should appear on the RIGHT</p>
    </div>
</body>
</html>
