<?php
require_once('../config/connect.php'); // Include database connection
require_once('../classes/Security.php');
require_once('../classes/Auth.php');

session_start(); // Start the session

$security = new Security($pdo);
$auth = new Auth($pdo);

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header("Location: ../login/login.php");
    exit();
}

// Validate CSRF token
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !$security->validateCSRFToken($_POST['csrf_token'])) {
        die(json_encode(['success' => false, 'message' => 'Invalid request. Please try again.']));
    }

    $boat_id = filter_input(INPUT_POST, 'boat_id', FILTER_VALIDATE_INT);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $user_id = $_SESSION['user_id'];

    $errors = [];

    // Validate input
    if (!$boat_id) {
        $errors[] = "Invalid boat selection.";
    }

    if (!$date) {
        $errors[] = "Invalid date selection.";
    } else {
        $selected_date = strtotime($date);
        $current_date = strtotime('today');
        
        if ($selected_date < $current_date) {
            $errors[] = "Cannot book for past dates.";
        }
    }

    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Check if boat exists and is available
            $stmt = $pdo->prepare("SELECT * FROM boats WHERE id = ? AND status = 'available'");
            $stmt->execute([$boat_id]);
            $boat = $stmt->fetch();

            if (!$boat) {
                $errors[] = "Selected boat is not available.";
            } else {
                // Check if boat is already booked for the date
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE boat_id = ? AND date = ? AND status != 'cancelled'");
                $stmt->execute([$boat_id, $date]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = "This boat is already booked for the selected date.";
                } else {
                    // Calculate total price (can be extended for different pricing rules)
                    $total_price = $boat['price'];

                    // Insert booking
                    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, boat_id, date, total_price, status) VALUES (?, ?, ?, ?, 'pending')");
                    if ($stmt->execute([$user_id, $boat_id, $date, $total_price])) {
                        // Update boat status
                        $stmt = $pdo->prepare("UPDATE boats SET status = 'booked' WHERE id = ?");
                        $stmt->execute([$boat_id]);

                        // Commit transaction
                        $pdo->commit();

                        echo json_encode([
                            'success' => true,
                            'message' => "Booking successful!",
                            'booking' => [
                                'boat_name' => $boat['name'],
                                'date' => $date,
                                'price' => $total_price,
                                'status' => 'pending'
                            ]
                        ]);
                    } else {
                        throw new Exception("Failed to create booking.");
                    }
                }
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            error_log("Booking error: " . $e->getMessage());
            $errors[] = "An error occurred. Please try again later.";
        }
    }

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode("\n", $errors)
        ]);
    }
    exit();
}

// If not POST request, redirect to home
header("Location: ../index.php");
exit();
?>
