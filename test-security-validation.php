<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔒 Security Validation Test\n";
echo "=========================\n\n";

try {
    $rule = new App\Rules\NoMaliciousContent();
    
    // Test 1: Malicious Script Content
    echo "🔍 Test 1: Testing XSS Script Content...\n";
    $failed = false;
    $rule->validate('test_field', '<script>alert(1)</script>', function($message) use (&$failed) {
        echo "✅ SUCCESS: Security rule blocked malicious content\n";
        echo "   Message: $message\n";
        $failed = true;
    });
    
    if (!$failed) {
        echo "❌ ERROR: Security rule did not block malicious content!\n";
    }
    
    echo "\n";
    
    // Test 2: Normal Content
    echo "🔍 Test 2: Testing Normal Content...\n";
    $failed = false;
    $rule->validate('test_field', 'normal text content', function($message) use (&$failed) {
        echo "❌ ERROR: Security rule blocked normal content\n";
        echo "   Message: $message\n";
        $failed = true;
    });
    
    if (!$failed) {
        echo "✅ SUCCESS: Security rule allowed normal content\n";
    }
    
    echo "\n";
    
    // Test 3: SQL Injection
    echo "🔍 Test 3: Testing SQL Injection...\n";
    $failed = false;
    $rule->validate('test_field', "SELECT * FROM users WHERE id = 1", function($message) use (&$failed) {
        echo "✅ SUCCESS: Security rule blocked SQL injection\n";
        echo "   Message: $message\n";
        $failed = true;
    });
    
    if (!$failed) {
        echo "❌ ERROR: Security rule did not block SQL injection!\n";
    }
    
    echo "\n";
    
    // Test 4: JavaScript Protocol
    echo "🔍 Test 4: Testing JavaScript Protocol...\n";
    $failed = false;
    $rule->validate('test_field', 'javascript:void(0)', function($message) use (&$failed) {
        echo "✅ SUCCESS: Security rule blocked JavaScript protocol\n";
        echo "   Message: $message\n";
        $failed = true;
    });
    
    if (!$failed) {
        echo "❌ ERROR: Security rule did not block JavaScript protocol!\n";
    }
    
    echo "\n";
    
    // Test 5: Event Handlers
    echo "🔍 Test 5: Testing Event Handlers...\n";
    $failed = false;
    $rule->validate('test_field', '<img src="x" onerror="alert(1)">', function($message) use (&$failed) {
        echo "✅ SUCCESS: Security rule blocked event handlers\n";
        echo "   Message: $message\n";
        $failed = true;
    });
    
    if (!$failed) {
        echo "❌ ERROR: Security rule did not block event handlers!\n";
    }
    
    echo "\n";
    
    // Test 6: Valid Email
    echo "🔍 Test 6: Testing Valid Email...\n";
    $failed = false;
    $rule->validate('email_field', 'user@example.com', function($message) use (&$failed) {
        echo "❌ ERROR: Security rule blocked valid email\n";
        echo "   Message: $message\n";
        $failed = true;
    });
    
    if (!$failed) {
        echo "✅ SUCCESS: Security rule allowed valid email\n";
    }
    
    echo "\n🎉 Security validation test completed!\n";
    echo "===========================================\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}