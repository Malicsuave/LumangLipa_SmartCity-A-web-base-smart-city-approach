<?php

require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AdminChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\LiveChatController;

echo "Testing Live Chat System with Existing Database Structure\n";
echo "========================================================\n\n";

try {
    // Test 1: Create an escalation
    echo "Test 1: Creating a test escalation...\n";
    
    $controller = new LiveChatController();
    
    // Create a mock request
    $request = new Request();
    $request->merge([
        'user_message' => 'I am not satisfied with the AI response. I need human help!'
    ]);
    
    // Simulate IP address
    $request->server->set('REMOTE_ADDR', '192.168.1.100');
    
    $response = $controller->escalateToAdmin($request);
    $responseData = json_decode($response->getContent(), true);
    
    if ($responseData['success']) {
        echo "✓ Escalation created successfully!\n";
        echo "  Session ID: " . $responseData['session_id'] . "\n";
        echo "  Message: " . $responseData['message'] . "\n\n";
        
        $sessionId = $responseData['session_id'];
        
        // Test 2: Check if escalation appears in admin dashboard
        echo "Test 2: Checking admin escalations...\n";
        $escalationsResponse = $controller->getActiveEscalations();
        $escalationsData = json_decode($escalationsResponse->getContent(), true);
        
        if ($escalationsData['success']) {
            echo "✓ Admin escalations retrieved successfully!\n";
            echo "  Number of escalations: " . count($escalationsData['escalations']) . "\n";
            
            if (!empty($escalationsData['escalations'])) {
                $firstEscalation = $escalationsData['escalations'][0];
                echo "  First escalation session: " . $firstEscalation['session_id'] . "\n";
                echo "  User IP: " . $firstEscalation['user_ip'] . "\n";
                echo "  Last message: " . $firstEscalation['last_message'] . "\n\n";
            }
        } else {
            echo "✗ Failed to retrieve escalations: " . $escalationsData['error'] . "\n";
        }
        
        // Test 3: Send a user message
        echo "Test 3: Sending user message...\n";
        $messageRequest = new Request();
        $messageRequest->merge([
            'session_id' => $sessionId,
            'message' => 'Hello admin, I really need help with my request.'
        ]);
        $messageRequest->server->set('REMOTE_ADDR', '192.168.1.100');
        
        $messageResponse = $controller->sendUserMessage($messageRequest);
        $messageData = json_decode($messageResponse->getContent(), true);
        
        if ($messageData['success']) {
            echo "✓ User message sent successfully!\n\n";
        } else {
            echo "✗ Failed to send user message: " . $messageData['error'] . "\n";
        }
        
        // Test 4: Get conversation history
        echo "Test 4: Getting conversation history...\n";
        $historyResponse = $controller->getConversationHistory($sessionId);
        $historyData = json_decode($historyResponse->getContent(), true);
        
        if ($historyData['success']) {
            echo "✓ Conversation history retrieved successfully!\n";
            echo "  Number of messages: " . count($historyData['messages']) . "\n";
            
            foreach ($historyData['messages'] as $index => $message) {
                echo "  Message " . ($index + 1) . " (" . $message['sender_type'] . "): " . substr($message['message'], 0, 50) . "...\n";
            }
        } else {
            echo "✗ Failed to retrieve conversation history: " . $historyData['error'] . "\n";
        }
        
    } else {
        echo "✗ Failed to create escalation: " . $responseData['error'] . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed!\n";
