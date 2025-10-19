<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat Debug</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .result { margin: 10px 0; padding: 10px; background: #f5f5f5; }
        .error { background: #ffebee; color: #c62828; }
        .success { background: #e8f5e8; color: #2e7d32; }
        button { padding: 10px 15px; margin: 5px; }
        input, textarea { width: 300px; padding: 5px; margin: 5px; }
        textarea { height: 60px; }
    </style>
</head>
<body>
    <h1>Admin Chat System Debug</h1>
    
    <!-- Step 1: Create Test Escalation -->
    <div class="section">
        <h2>Step 1: Create Test Escalation</h2>
        <button id="createEscalation">Create Test Escalation</button>
        <div id="escalationResult" class="result"></div>
    </div>
    
    <!-- Step 2: List Escalations -->
    <div class="section">
        <h2>Step 2: List Current Escalations</h2>
        <button id="listEscalations">List Escalations</button>
        <div id="listResult" class="result"></div>
    </div>
    
    <!-- Step 3: Send Admin Response -->
    <div class="section">
        <h2>Step 3: Send Admin Response</h2>
        <div>
            <label>Session ID:</label>
            <input type="text" id="sessionId" value="escalation_test_123" placeholder="Session ID">
        </div>
        <div>
            <label>Admin Message:</label>
            <textarea id="adminMessage" placeholder="Type your admin response...">Hello! I'm here to help you with your concern.</textarea>
        </div>
        <button id="sendResponse">Send Admin Response</button>
        <div id="responseResult" class="result"></div>
    </div>
    
    <!-- Step 4: View Conversation -->
    <div class="section">
        <h2>Step 4: View Conversation</h2>
        <button id="viewConversation">View Conversation</button>
        <div id="conversationResult" class="result"></div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Helper function to make API calls
        async function apiCall(url, method = 'GET', body = null) {
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            if (method !== 'GET') {
                headers['Content-Type'] = 'application/json';
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const options = {
                method,
                headers,
                credentials: 'same-origin'
            };
            
            if (body) {
                options.body = JSON.stringify(body);
            }
            
            const response = await fetch(url, options);
            const data = await response.text();
            
            try {
                return {
                    status: response.status,
                    ok: response.ok,
                    data: JSON.parse(data)
                };
            } catch (e) {
                return {
                    status: response.status,
                    ok: response.ok,
                    data: { raw: data, parseError: e.message }
                };
            }
        }
        
        // Display result
        function displayResult(elementId, result) {
            const element = document.getElementById(elementId);
            const className = result.ok ? 'result success' : 'result error';
            element.className = className;
            element.innerHTML = `
                <p><strong>Status:</strong> ${result.status}</p>
                <pre>${JSON.stringify(result.data, null, 2)}</pre>
            `;
        }
        
        // Step 1: Create Test Escalation
        document.getElementById('createEscalation').addEventListener('click', async () => {
            const result = await apiCall('/api/live-chat/escalate', 'POST', {
                user_message: 'Hello, I need help with my concern. This is a test escalation.',
                language: 'en'
            });
            displayResult('escalationResult', result);
            
            if (result.ok && result.data.session_id) {
                document.getElementById('sessionId').value = result.data.session_id;
            }
        });
        
        // Step 2: List Escalations
        document.getElementById('listEscalations').addEventListener('click', async () => {
            const result = await apiCall('/api/admin/live-chat/escalations-test');
            displayResult('listResult', result);
        });
        
        // Step 3: Send Admin Response
        document.getElementById('sendResponse').addEventListener('click', async () => {
            const sessionId = document.getElementById('sessionId').value;
            const message = document.getElementById('adminMessage').value;
            
            if (!sessionId || !message) {
                displayResult('responseResult', {
                    status: 400,
                    ok: false,
                    data: { error: 'Please provide both session ID and message' }
                });
                return;
            }
            
            // Try test endpoint first
            let result = await apiCall('/api/admin/live-chat/respond-simple', 'POST', {
                session_id: sessionId,
                message: message
            });
            
            if (!result.ok) {
                // Try test endpoint as fallback
                result = await apiCall('/api/admin/live-chat/respond-test', 'POST', {
                    session_id: sessionId,
                    message: message
                });
            }
            
            if (!result.ok) {
                // Try authenticated endpoint as last resort
                result = await apiCall('/api/admin/live-chat/respond', 'POST', {
                    session_id: sessionId,
                    message: message
                });
            }
            
            displayResult('responseResult', result);
        });
        
        // Step 4: View Conversation
        document.getElementById('viewConversation').addEventListener('click', async () => {
            const sessionId = document.getElementById('sessionId').value;
            
            if (!sessionId) {
                displayResult('conversationResult', {
                    status: 400,
                    ok: false,
                    data: { error: 'Please provide session ID' }
                });
                return;
            }
            
            const result = await apiCall(`/api/live-chat/conversation/${sessionId}`);
            displayResult('conversationResult', result);
        });
    </script>
</body>
</html>
