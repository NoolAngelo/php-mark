<?php
// This script migrates existing plain text passwords to hashed passwords
// Run this ONCE after implementing the new security system

require_once 'config/database.php';
require_once 'includes/security.php';

echo "Password Migration Script\n";
echo "=========================\n\n";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Get all users with plain text passwords (assuming they exist)
    $query = "SELECT id, email, password FROM members";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $users = $stmt->fetchAll();
    $migrated = 0;
    
    foreach ($users as $user) {
        // Check if password is already hashed (bcrypt hashes start with $2y$)
        if (!password_get_info($user['password'])['algo']) {
            // This is likely a plain text password, hash it
            $hashedPassword = Security::hashPassword($user['password']);
            
            $updateQuery = "UPDATE members SET password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            
            if ($updateStmt->execute([$hashedPassword, $user['id']])) {
                echo "Migrated password for user: " . $user['email'] . "\n";
                $migrated++;
            } else {
                echo "Failed to migrate password for user: " . $user['email'] . "\n";
            }
        } else {
            echo "Password already hashed for user: " . $user['email'] . "\n";
        }
    }
    
    echo "\nMigration completed. Total passwords migrated: " . $migrated . "\n";
    echo "You should delete this file after running it for security.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
