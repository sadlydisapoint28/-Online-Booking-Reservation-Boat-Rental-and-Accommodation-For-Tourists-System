<?php
require_once('../php/config/connect.php');
require_once('../php/classes/Auth.php');
require_once('../php/classes/Security.php');

header('Content-Type: application/json');

// Initialize classes
$auth = new Auth($pdo);
$security = new Security($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password_recovery'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Don't reveal if email exists or not for security
        echo json_encode(['success' => true, 'message' => 'If an account exists with this email, you will receive password recovery instructions.']);
        exit;
    }
    
    // Generate recovery token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store recovery token
    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $token, $expires]);
    
    // Send recovery email
    $resetLink = "http://localhost/Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/php/pages/interface.php?token=" . $token;
    
    $to = $email;
    $subject = "Password Recovery - Boat Rental System";
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #3B82F6; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9fafb; }
            .button { display: inline-block; padding: 12px 24px; background: #3B82F6; color: white; text-decoration: none; border-radius: 6px; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Password Recovery</h1>
            </div>
            <div class='content'>
                <p>Hello {$user['name']},</p>
                <p>We received a request to reset your password. Click the button below to create a new password:</p>
                <p style='text-align: center;'>
                    <a href='{$resetLink}' class='button'>Reset Password</a>
                </p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, please ignore this email or contact support if you have concerns.</p>
            </div>
            <div class='footer'>
                <p>This is an automated message, please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: noreply@boatrental.com\r\n";
    
    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Password recovery instructions have been sent to your email.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send recovery email. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
} 