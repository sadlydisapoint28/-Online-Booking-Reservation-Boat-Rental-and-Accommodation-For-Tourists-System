<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

session_start();

$auth = new Auth($pdo);
$security = new Security($pdo);

// Handle password recovery form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
        if ($user) {
    // Generate recovery token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Store recovery token
    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $token, $expires]);
    
    // Send recovery email
            $resetLink = "http://localhost/Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/php/pages/login%20user/reset_password.php?token=" . $token;
    
    $to = $email;
            $subject = "Password Recovery - Carles Tourism";
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
            $headers .= "From: noreply@carles-tourism.com\r\n";
    
    if (mail($to, $subject, $message, $headers)) {
                $_SESSION['success'] = 'Password recovery instructions have been sent to your email.';
                header('Location: login.php');
                exit;
    } else {
                $error = 'Failed to send recovery email. Please try again later.';
    }
} else {
            // Don't reveal if email exists or not for security
            $_SESSION['success'] = 'If an account exists with this email, you will receive password recovery instructions.';
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-blue-400 to-cyan-300 p-4 font-body">
    <!-- Animated background elements -->
    <div class="ocean-bg">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="bubble bubble1"></div>
        <div class="bubble bubble2"></div>
        <div class="bubble bubble3"></div>
        <div class="bubble bubble4"></div>
    </div>

    <div class="w-full max-w-md bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-blue-100">
        <!-- Close Button -->
        <a href="../../php/pages/interface.php" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <i class="fas fa-times text-xl"></i>
        </a>

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-blue-800 mb-3">Password Recovery</h2>
            <p class="text-blue-600 text-lg">Enter your email to receive reset instructions</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message bg-red-50 text-red-600 p-4 rounded-lg mb-6 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5">
            <div class="form-group">
                <label class="block text-sm font-medium text-blue-700 mb-2">Email Address</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-3 text-blue-400"></i>
                    <input type="email" name="email" required class="pl-10 w-full px-4 py-3 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="you@example.com">
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg mt-4 flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i>
                Send Recovery Instructions
            </button>

            <div class="text-center mt-6">
                <a href="login.php" class="text-sm text-blue-600 hover:text-blue-800">Back to Login</a>
            </div>
        </form>
    </div>

    <style>
        .ocean-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
            animation: wave 20s linear infinite;
        }
        .wave1 {
            bottom: 0;
            opacity: 0.5;
            animation-delay: -5s;
        }
        .wave2 {
            bottom: 10px;
            opacity: 0.3;
            animation-delay: -2s;
        }
        .bubble {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s infinite;
        }
        .bubble1 {
            width: 80px;
            height: 80px;
            left: 10%;
            top: 20%;
            animation-delay: 0s;
        }
        .bubble2 {
            width: 60px;
            height: 60px;
            left: 30%;
            top: 40%;
            animation-delay: 2s;
        }
        .bubble3 {
            width: 40px;
            height: 40px;
            left: 50%;
            top: 60%;
            animation-delay: 4s;
        }
        .bubble4 {
            width: 100px;
            height: 100px;
            left: 70%;
            top: 30%;
            animation-delay: 6s;
        }
        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</body>
</html> 