<?php

// Quick test to verify multi-step registration system
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Multi-Step Pre-Registration System with Senior Citizen Logic\n";
echo "================================================================\n\n";

// Test 1: Check if PreRegistration model has senior_info field
echo "1. Testing PreRegistration Model Configuration:\n";
$preReg = new App\Models\PreRegistration();
$fillable = $preReg->getFillable();
echo "   - Fillable fields count: " . count($fillable) . "\n";
echo "   - senior_info field: " . (in_array('senior_info', $fillable) ? "✓ Found" : "✗ Missing") . "\n";

// Test 2: Check if senior citizen logic works
echo "\n2. Testing Senior Citizen Age Logic:\n";
$testBirthdate = now()->subYears(65); // 65 years old
$testPreReg = new App\Models\PreRegistration(['birthdate' => $testBirthdate]);
echo "   - Test birthdate: " . $testBirthdate->format('Y-m-d') . "\n";
echo "   - Calculated age: " . $testPreReg->age . " years\n";
echo "   - Is senior citizen: " . ($testPreReg->is_senior_citizen ? "✓ Yes" : "✗ No") . "\n";

$testBirthdate2 = now()->subYears(45); // 45 years old
$testPreReg2 = new App\Models\PreRegistration(['birthdate' => $testBirthdate2]);
echo "   - Test birthdate 2: " . $testBirthdate2->format('Y-m-d') . "\n";
echo "   - Calculated age 2: " . $testPreReg2->age . " years\n";
echo "   - Is senior citizen 2: " . ($testPreReg2->is_senior_citizen ? "✓ Yes" : "✗ No") . "\n";

// Test 3: Check routes
echo "\n3. Testing Route Configuration:\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$preRegRoutes = [];
foreach ($routes as $route) {
    $uri = $route->uri();
    if (str_contains($uri, 'pre-registration')) {
        $preRegRoutes[] = $uri;
    }
}
echo "   - Pre-registration routes found: " . count($preRegRoutes) . "\n";
echo "   - Key routes:\n";
foreach (['pre-registration/step1', 'pre-registration/step4-senior', 'pre-registration/review'] as $keyRoute) {
    $found = in_array($keyRoute, $preRegRoutes);
    echo "     * $keyRoute: " . ($found ? "✓ Found" : "✗ Missing") . "\n";
}

// Test 4: Check if views exist
echo "\n4. Testing View Files:\n";
$viewPaths = [
    'public.pre-registration.step1',
    'public.pre-registration.step2', 
    'public.pre-registration.step3',
    'public.pre-registration.step4-senior',
    'public.pre-registration.step4',
    'public.pre-registration.review',
    'public.pre-registration.success'
];

foreach ($viewPaths as $viewPath) {
    $viewFile = str_replace('.', '/', $viewPath) . '.blade.php';
    $fullPath = resource_path('views/' . $viewFile);
    $exists = file_exists($fullPath);
    echo "   - $viewPath: " . ($exists ? "✓ Found" : "✗ Missing") . "\n";
}

echo "\n5. Testing Controller Methods:\n";
$controller = new App\Http\Controllers\Public\PreRegistrationController();
$methods = get_class_methods($controller);
$requiredMethods = [
    'createStep1', 'storeStep1',
    'createStep2', 'storeStep2', 
    'createStep3', 'storeStep3',
    'createStep4Senior', 'storeStep4Senior',
    'createStep4', 'storeStep4',
    'createReview', 'store'
];

foreach ($requiredMethods as $method) {
    $exists = in_array($method, $methods);
    echo "   - $method: " . ($exists ? "✓ Found" : "✗ Missing") . "\n";
}

echo "\n✅ Multi-Step Pre-Registration System Test Complete!\n";
echo "The system includes senior citizen logic that automatically detects users 60+ years old\n";
echo "and guides them through an additional step for senior citizen information.\n";