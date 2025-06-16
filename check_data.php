<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lumanglipa', 'root', '');
    
    echo "=== DATABASE DATA SUMMARY ===\n";
    
    // Count records in each table
    $tables = [
        'users' => 'Users',
        'residents' => 'Residents',
        'households' => 'Households', 
        'family_members' => 'Family Members',
        'senior_citizens' => 'Senior Citizens',
        'access_requests' => 'Access Requests',
        'roles' => 'Roles',
        'admin_approvals' => 'Admin Approvals',
        'gads' => 'GADs',
        'user_activities' => 'User Activities',
        'activity_log' => 'Activity Log'
    ];
    
    foreach ($tables as $table => $label) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "$label: $count\n";
    }
    
    echo "\n=== USER DETAILS ===\n";
    $stmt = $pdo->query("SELECT u.name, u.email, r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "User: {$row['name']} ({$row['email']}) - Role: " . ($row['role_name'] ?? 'No Role') . "\n";
    }
    
    echo "\n=== ROLES ===\n";
    $stmt = $pdo->query("SELECT name, description FROM roles");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Role: {$row['name']} - {$row['description']}\n";
    }
    
    // Check if there are residents with soft deletes
    $stmt = $pdo->query("SELECT COUNT(*) FROM residents WHERE deleted_at IS NULL");
    $active_residents = $stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM residents WHERE deleted_at IS NOT NULL");
    $deleted_residents = $stmt->fetchColumn();
    
    echo "\n=== RESIDENTS STATUS ===\n";
    echo "Active Residents: $active_residents\n";
    echo "Deleted Residents: $deleted_residents\n";
    
    if ($active_residents > 0) {
        echo "\n=== RECENT RESIDENTS ===\n";
        $stmt = $pdo->query("SELECT resident_id, first_name, last_name, email FROM residents WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Resident: {$row['resident_id']} - {$row['first_name']} {$row['last_name']} ({$row['email']})\n";
        }
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
