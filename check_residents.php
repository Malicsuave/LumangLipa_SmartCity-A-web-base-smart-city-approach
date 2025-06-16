<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lumanglipa', 'root', '');
    
    echo "=== CHECKING FOR DELETED RESIDENTS ===\n";
    
    // Check all residents including deleted
    $stmt = $pdo->query("SELECT COUNT(*) FROM residents");
    $total_residents = $stmt->fetchColumn();
    echo "Total residents in table: $total_residents\n";
    
    if ($total_residents > 0) {
        $stmt = $pdo->query("SELECT resident_id, first_name, last_name, email, deleted_at FROM residents ORDER BY created_at DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['deleted_at'] ? "DELETED ({$row['deleted_at']})" : "ACTIVE";
            echo "Resident: {$row['resident_id']} - {$row['first_name']} {$row['last_name']} ({$row['email']}) - $status\n";
        }
    }
    
    echo "\n=== PHOTO FILES FOUND ===\n";
    $photoDir = 'storage/app/public/residents/photos';
    if (is_dir($photoDir)) {
        $files = scandir($photoDir);
        $photoFiles = array_filter($files, function($file) {
            return !in_array($file, ['.', '..']) && pathinfo($file, PATHINFO_EXTENSION) === 'jpg';
        });
        
        echo "Photo files found: " . count($photoFiles) . "\n";
        foreach ($photoFiles as $file) {
            echo "- $file\n";
        }
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
