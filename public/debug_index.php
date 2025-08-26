<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

try {
    require_once __DIR__."/../vendor/autoload.php";
    echo "Autoload successful\n";
    
    $app = require_once __DIR__."/../bootstrap/app.php";
    echo "App bootstrap successful\n";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel created\n";
    
    $request = Illuminate\Http\Request::capture();
    echo "Request captured\n";
    
    $response = $kernel->handle($request);
    echo "Request handled\n";
    
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} catch (Error $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n"; 
    echo "Line: " . $e->getLine() . "\n";
}
?>
