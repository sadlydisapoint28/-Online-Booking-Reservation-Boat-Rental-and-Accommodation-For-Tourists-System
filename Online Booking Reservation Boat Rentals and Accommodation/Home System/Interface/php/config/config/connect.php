<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_booking_db"; // Palitan mo ito ng tamang database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 