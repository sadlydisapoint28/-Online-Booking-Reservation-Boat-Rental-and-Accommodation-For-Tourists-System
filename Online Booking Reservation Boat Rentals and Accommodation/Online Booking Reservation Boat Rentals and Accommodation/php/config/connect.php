<?php
// Database connection settings
$host = 'localhost';
$dbname = 'boat_rentals_db'; // Change this to your actual database name
$username = 'root'; // Change this to your actual database username
$password = ''; // Change this to your actual database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
