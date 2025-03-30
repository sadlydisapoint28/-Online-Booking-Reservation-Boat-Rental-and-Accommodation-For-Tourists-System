<?php
require_once('../config/connect.php'); // Include database connection
session_start(); // Start the session

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $feedback = $_POST['feedback'];

    // Insert feedback into the database
    $stmt = $pdo->prepare("INSERT INTO feedback (user_id, feedback) VALUES (?, ?)");
    if ($stmt->execute([$user_id, $feedback])) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: Could not submit feedback.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
</head>
<body>
    <h1>Leave Feedback</h1>
    <form method="POST" action="">
        <textarea name="feedback" placeholder="Enter your feedback here..." required></textarea>
        <br>
        <input type="submit" value="Submit Feedback">
    </form>
</body>
</html>
