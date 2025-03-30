<?php
require_once('../../config/connect.php');
require_once('../../classes/Security.php');

$security = new Security($pdo);

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        // Check if token exists and is not expired
        $stmt = $pdo->prepare("SELECT user_id, email FROM users WHERE verification_token = ? AND verification_expires > NOW() AND is_verified = 0");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Update user as verified
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL, verification_expires = NULL WHERE user_id = ?");
            $stmt->execute([$user['user_id']]);
            
            // Log successful verification
            $security->logActivity($_SERVER['REMOTE_ADDR'], 'email_verification', 'success', 'User email verified successfully');
            
            $_SESSION['verification_success'] = true;
            $_SESSION['verification_email'] = $user['email'];
        } else {
            $_SESSION['verification_error'] = "Invalid or expired verification link.";
        }
    } catch (PDOException $e) {
        $_SESSION['verification_error'] = "An error occurred during verification.";
        $security->logActivity($_SERVER['REMOTE_ADDR'], 'email_verification', 'error', 'Database error: ' . $e->getMessage());
    }
} else {
    $_SESSION['verification_error'] = "No verification token provided.";
}

header("Location: ../login user/login.php");
exit; 