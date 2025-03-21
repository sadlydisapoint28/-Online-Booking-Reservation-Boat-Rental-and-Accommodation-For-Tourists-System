<?php
// Database connection settings
$host = 'localhost';
$dbname = 'booking_db';
$username = 'root';
$password = '';

try {
    // First connect without database to create it if it doesn't exist
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
    // Now connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create admin table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create boats table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS boats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        capacity INT NOT NULL,
        image_url VARCHAR(255),
        status ENUM('available', 'maintenance', 'booked') DEFAULT 'available',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Create bookings table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        boat_id INT NOT NULL,
        date DATE NOT NULL,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
        total_price DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (boat_id) REFERENCES boats(id) ON DELETE CASCADE
    )");
    
    // Create login_attempts table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL,
        success BOOLEAN NOT NULL,
        attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create remember_tokens table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS remember_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at TIMESTAMP NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    
    // Create a default admin user if none exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin");
    if ($stmt->fetchColumn() == 0) {
        $default_admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO admin (username, password, email, full_name) VALUES ('admin', '$default_admin_password', 'admin@example.com', 'Administrator')");
    }
    
    // Create a default user account if none exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $default_user_password = password_hash('user123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (username, password, email, full_name) VALUES ('user', '$default_user_password', 'user@example.com', 'Default User')");
    }
    
    // Insert some sample boats if none exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM boats");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO boats (name, description, price, capacity, image_url) VALUES 
            ('Luxury Yacht', 'A beautiful luxury yacht perfect for special occasions', 500.00, 10, 'images/yacht.jpg'),
            ('Speed Boat', 'Fast and exciting speed boat for thrill seekers', 300.00, 6, 'images/speedboat.jpg'),
            ('Fishing Boat', 'Perfect for fishing trips and family outings', 250.00, 8, 'images/fishingboat.jpg')");
    }
    
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}
?>
