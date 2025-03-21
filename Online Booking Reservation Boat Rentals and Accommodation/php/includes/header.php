<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Add any common PHP functions or variables
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-left">
        <a href="index.php" class="logo">
            <img src="assets/images/logo.png" alt="Carles Tourism Logo" height="40">
        </a>
    </div>
    
    <button class="mobile-menu-btn" aria-label="Toggle Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-right">
        <div class="nav-links">
            <a href="#home" class="nav-link active">Home</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#services" class="nav-link">Services</a>
            <a href="#beaches" class="nav-link">Beaches</a>
            <a href="#gallery" class="nav-link">Gallery</a>
            <a href="#contact" class="nav-link">Contact</a>
        </div>
        <div class="nav-buttons">
            <a href="booking.php" class="btn-book">Book Now</a>
            <a href="login.php" class="btn-login">Login</a>
        </div>
    </div>
</nav>

