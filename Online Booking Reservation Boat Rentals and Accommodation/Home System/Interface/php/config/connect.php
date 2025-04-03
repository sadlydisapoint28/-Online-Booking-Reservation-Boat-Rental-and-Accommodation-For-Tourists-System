<?php
/**
 * Database Connection
 * 
 * This file establishes a connection to the MySQL database and provides a PDO object
 * for executing queries throughout the application.
 */

$host = 'localhost';
$db = 'booking_system';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    // First connect without database
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Check if database exists, create if not
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    
    // Now connect to the specified database
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Set timezone for consistent date/time operations
    $pdo->exec("SET time_zone = '+00:00'");
    
    // Check if essential tables exist, if not include the schema file
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        // Define various possible paths for the schema file
        $schema_paths = [
            __DIR__ . '/../../../../sql/database.sql',
            __DIR__ . '/../../../sql/database.sql',
            __DIR__ . '/../sql/database.sql'
        ];
        
        $schema_loaded = false;
        foreach ($schema_paths as $path) {
            if (file_exists($path)) {
                try {
                    $schema = file_get_contents($path);
                    $pdo->exec($schema);
                    $schema_loaded = true;
                    break;
                } catch (PDOException $e) {
                    error_log('Schema loading error: ' . $e->getMessage());
                    // Continue to try other paths
                }
            }
        }
        
        if (!$schema_loaded) {
            error_log('Could not load database schema from any path');
            die('Database schema file not found. Please ensure database.sql exists in the correct location.');
        }
    }

} catch (PDOException $e) {
    error_log('Connection Error: ' . $e->getMessage());
    die('Database connection failed. Please try again later.');
}
?>
