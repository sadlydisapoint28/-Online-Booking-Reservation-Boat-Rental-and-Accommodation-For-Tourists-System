<?php
require_once('../config/connect.php'); // Include database connection
session_start(); // Start the session

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch available boats from the database (example query)
$stmt = $pdo->query("SELECT * FROM boats"); // Assuming a 'boats' table exists
$boats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
</head>
<body>
    <h1>Book a Boat</h1>
    <form method="POST" action="process_booking.php"> <!-- Process booking logic in another file -->
        <label for="boat">Select a Boat:</label>
        <select name="boat_id" required>
            <?php foreach ($boats as $boat): ?>
                <option value="<?php echo $boat['id']; ?>"><?php echo htmlspecialchars($boat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="date">Select Date:</label>
        <input type="date" name="date" required>
        <br>
        <input type="submit" value="Book Now">
    </form>
</body>
</html>
