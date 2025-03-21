<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boat_rental_db";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Log the error (in a production environment, you'd want to log this to a file)
    error_log("Database connection error: " . $e->getMessage());
    
    // Show a user-friendly error message
    die("We're experiencing technical difficulties. Please try again later.");
}
?> 