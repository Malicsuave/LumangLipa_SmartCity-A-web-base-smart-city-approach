<!DOCTYPE html>
<html>
<head>
    <title>Admin Response Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Admin Response Test</h1>
    
    <div>
        <label>Session ID:</label>
        <input type="text" id="sessionId" value="test_session_123">
    </div>
    
    <div>
        <label>Message:</label>
        <textarea id="message" placeholder="Type your admin response..."></textarea>
    </div>
    
    <button id="sendBtn">Send Admin Response</button>
    
    <div id="result"></div>
    
    <script>
        document.getElementById('sendBtn').addEventListener('click', async function() {
            const sessionId = document.getElementById('sessionId').value;
            const message = document.getElementById('message').value;
            const resultDiv = document.getElementById('result');
            
            if (!sessionId || !message) {
                resultDiv.innerHTML = '<p style="color: red;">Please fill in both fields</p>';
                return;
            }
            
            resultDiv.innerHTML = '<p>Sending...</p>';
            
            try {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Try the simplified endpoint first
                let response = await fetch('/api/admin/live-chat/respond-simple', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        message: message
                    })
                });
                
                // If simplified fails, try test endpoint
                if (!response.ok) {
                    response = await fetch('/api/admin/live-chat/respond-test', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            session_id: sessionId,
                            message: message
                        })
                    });
                }
                
                const data = await response.json();
                
                resultDiv.innerHTML = `
                    <h3>Response (Status: ${response.status})</h3>
                    <pre>${JSON.stringify(data, null, 2)}</pre>
                `;
                
                if (data.success) {
                    resultDiv.style.color = 'green';
                } else {
                    resultDiv.style.color = 'red';
                }
                
            } catch (error) {
                resultDiv.innerHTML = `
                    <p style="color: red;">Error: ${error.message}</p>
                `;
                console.error('Error:', error);
            }
        });
    </script>
</body>
</html>
