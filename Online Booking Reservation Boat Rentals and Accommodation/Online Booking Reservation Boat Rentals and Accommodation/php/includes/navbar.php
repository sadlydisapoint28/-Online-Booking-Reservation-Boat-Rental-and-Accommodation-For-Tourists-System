<nav>
    <ul>
        <li><a href="../pages/interface.php">Home</a></li>
        <li><a href="../pages/booking.php">Book a Boat</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="../pages/profile.php">Your Profile</a></li>
            <li><a href="../pages/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="../pages/login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
