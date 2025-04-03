<?php
require_once 'public/includes/config.php';

try {
    // Check if database exists
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'business_db'");
    $dbExists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dbExists) {
        die("Database already exists. To reinstall, please drop the database first.\n");
    }

    // Read schema file
    $schema = file_get_contents('public/database/schema.sql');
    if (!$schema) {
        throw new Exception("Could not read schema file");
    }

    // Execute schema SQL
    $pdo->exec($schema);

    echo "Database created successfully!\n";
    echo "Admin user created with credentials:\n";
    echo "Email: admin@example.com\n";
    echo "Password: password\n";

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
?>