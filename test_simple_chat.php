<?php

// Simple test to check database connection and model
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing database connection and AdminChatMessage model...\n";

try {
    // Test database connection
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    
    // Test table structure
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM admin_chat_messages");
    echo "✓ Table exists with columns: ";
    foreach ($columns as $column) {
        echo $column->Field . " ";
    }
    echo "\n";
    
    // Test model creation
    $message = new App\Models\AdminChatMessage();
    echo "✓ AdminChatMessage model loaded successfully\n";
    
    // Test creating a record
    $testMessage = App\Models\AdminChatMessage::create([
        'conversation_id' => 'test_escalation_' . time(),
        'sender_id' => 'test_user',
        'sender_type' => 'user',
        'message' => 'This is a test escalation message'
    ]);
    
    echo "✓ Test message created with ID: " . $testMessage->id . "\n";
    
    // Test scopes
    $conversations = App\Models\AdminChatMessage::escalations()->count();
    echo "✓ Escalations scope works: found " . $conversations . " escalation conversations\n";
    
    echo "\nAll tests passed! Live chat system is ready.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
