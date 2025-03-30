<?php
try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS boat_rentals_db");
    echo "Database created successfully<br>";
    
    // Select the database
    $pdo->exec("USE boat_rentals_db");
    
    // Create blocked_ips table
    $pdo->exec("CREATE TABLE IF NOT EXISTS blocked_ips (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL,
        blocked_until DATETIME NOT NULL,
        reason TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "blocked_ips table created successfully<br>";
    
    // Create activity_logs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL,
        action VARCHAR(50) NOT NULL,
        status VARCHAR(20) NOT NULL,
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "activity_logs table created successfully<br>";
    
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone_number VARCHAR(20),
        date_of_birth DATE,
        user_type ENUM('admin', 'customer') DEFAULT 'customer',
        verification_token VARCHAR(64),
        verification_expires DATETIME,
        is_verified TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "users table created successfully<br>";
    
    echo "<br>All tables created successfully!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} 