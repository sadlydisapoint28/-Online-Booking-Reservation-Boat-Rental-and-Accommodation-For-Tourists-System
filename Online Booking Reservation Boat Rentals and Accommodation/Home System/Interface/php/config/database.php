<?php
// Database connection configuration
$host = 'localhost';
$db = 'booking_system';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Create DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Set PDO options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Try to establish connection
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Log error and terminate
    error_log("Database connection error: " . $e->getMessage());
    die("Could not connect to the database. Please try again later.");
} 