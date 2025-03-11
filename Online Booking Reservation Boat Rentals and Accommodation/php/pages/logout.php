<?php
session_start(); // Start the session
session_destroy(); // Destroy the session
header("Location: ../pages/interface.php"); // Redirect to the interface page
exit();
?>
