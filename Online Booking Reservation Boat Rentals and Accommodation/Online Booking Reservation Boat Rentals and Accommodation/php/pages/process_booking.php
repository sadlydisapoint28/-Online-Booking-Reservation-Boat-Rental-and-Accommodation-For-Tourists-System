<?php
require_once('../config/connect.php'); // Include database connection
session_start(); // Start the session

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $boat_id = $_POST['boat_id'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    // Insert booking into the database
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, boat_id, date) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $boat_id, $date])) {
        echo "Booking successful!";
    } else {
        echo "Error: Could not complete the booking.";
    }
}
?>
