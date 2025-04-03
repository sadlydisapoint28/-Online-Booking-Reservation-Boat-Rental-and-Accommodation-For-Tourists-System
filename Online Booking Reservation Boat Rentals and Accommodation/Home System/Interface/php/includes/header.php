<?php
session_start();

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';

// Get user info if logged in
$user_info = null;
if ($is_logged_in) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_info = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user info: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carles Tourism</title>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../Interface/css/interface.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../../php/pages/interface.php">
                <img src="../../img/carleslogomunicipality.png" alt="Carles Logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'interface.php' ? 'active' : ''; ?>" href="../../php/pages/interface.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'islands.php' ? 'active' : ''; ?>" href="../../php/pages/sections/islands.php">Islands</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'boat-rentals.php' ? 'active' : ''; ?>" href="../../php/pages/sections/boat-rentals.php">Boats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'destinations.php' ? 'active' : ''; ?>" href="../../php/pages/sections/destinations.php">Destinations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>" href="../../php/pages/sections/services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>" href="../../php/pages/sections/gallery.php">Gallery</a>
                    </li>
                    <?php if ($is_logged_in): ?>
                        <?php if ($user_type === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../php/pages/admin/dashboard.php">Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../../php/pages/sections/profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../php/pages/sections/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../../php/pages/sections/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../php/pages/sections/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary" href="../../php/pages/sections/booking.php">Book Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> 