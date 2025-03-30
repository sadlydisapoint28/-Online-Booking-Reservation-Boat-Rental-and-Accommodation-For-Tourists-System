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
            __DIR__ . '/../../../../database_schema.sql',
            __DIR__ . '/../../../sql/database_schema.sql',
            __DIR__ . '/../sql/database_schema.sql'
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
            // Create essential tables manually if schema file not found
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `users` (
                  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
                  `full_name` VARCHAR(100) NOT NULL,
                  `email` VARCHAR(100) NOT NULL UNIQUE,
                  `password` VARCHAR(255) NOT NULL,
                  `phone_number` VARCHAR(20),
                  `address` TEXT,
                  `user_type` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
                  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                
                CREATE TABLE IF NOT EXISTS `boats` (
                  `boat_id` INT AUTO_INCREMENT PRIMARY KEY,
                  `boat_name` VARCHAR(100) NOT NULL,
                  `description` TEXT,
                  `capacity` INT NOT NULL,
                  `price_per_hour` DECIMAL(10,2) NOT NULL,
                  `price_per_day` DECIMAL(10,2) NOT NULL,
                  `image_url` VARCHAR(255),
                  `status` ENUM('available', 'maintenance', 'booked') DEFAULT 'available',
                  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                
                CREATE TABLE IF NOT EXISTS `bookings` (
                  `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
                  `user_id` INT NOT NULL,
                  `boat_id` INT NOT NULL,
                  `check_in_date` DATE NOT NULL,
                  `check_out_date` DATE NOT NULL,
                  `check_in_time` TIME NOT NULL,
                  `check_out_time` TIME NOT NULL,
                  `total_price` DECIMAL(10,2) NOT NULL,
                  `additional_services` TEXT,
                  `booking_status` ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
                  `payment_status` ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
                  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                
                CREATE TABLE IF NOT EXISTS `login_attempts` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `ip_address` VARCHAR(45) NOT NULL,
                  `attempts` INT NOT NULL DEFAULT 1,
                  `last_attempt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  INDEX (`ip_address`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
            
            // Insert default admin user
            $stmt = $pdo->prepare("INSERT INTO `users` (`full_name`, `email`, `password`, `user_type`) VALUES (?, ?, ?, ?)");
            $stmt->execute(['Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin']);
            
            // Insert sample customer
            $stmt = $pdo->prepare("INSERT INTO `users` (`full_name`, `email`, `password`, `phone_number`, `address`, `user_type`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute(['John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123-456-7890', '123 Main St, Anytown, USA', 'customer']);
        }
    }

} catch (PDOException $e) {
    error_log('Connection Error: ' . $e->getMessage());
    die('Database connection failed. Please try again later.');
}
?>
