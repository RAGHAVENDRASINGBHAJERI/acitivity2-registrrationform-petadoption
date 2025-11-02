<?php
require_once 'config.php';

try {
    // Check if pet_type column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM registrations LIKE 'pet_type'");
    if ($stmt->rowCount() == 0) {
        // Add pet_type column and copy data from course column
        $pdo->exec("ALTER TABLE registrations ADD COLUMN pet_type VARCHAR(50) NOT NULL DEFAULT ''");
        $pdo->exec("UPDATE registrations SET pet_type = course");
        $pdo->exec("ALTER TABLE registrations DROP COLUMN course");
        echo "Database migrated successfully! Column 'course' renamed to 'pet_type'.";
    } else {
        echo "Database already migrated.";
    }
} catch(PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>