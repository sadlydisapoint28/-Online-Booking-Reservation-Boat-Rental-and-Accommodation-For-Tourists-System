<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

session_start();

$auth = new Auth($pdo);
$security = new Security($pdo);

// Handle password reset request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    try {
        if (empty($email)) {
            $error = 'Please enter your email address';
        } else {
            // Generate reset token
            $token = $security->generateResetToken();
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store reset token in database
            $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
            if ($stmt->execute([$token, $expires, $email])) {
                // Send reset email (in production, use proper email service)
                $reset_link = "http://localhost/Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/php/pages/login%20user/reset_password.php?token=" . $token;
                $to = $email;
                $subject = "Password Reset Request";
                $message = "Click the following link to reset your password: " . $reset_link;
                $headers = "From: noreply@carles-tourism.com";

                mail($to, $subject, $message, $headers);

                $_SESSION['success'] = 'Password reset instructions have been sent to your email.';
                header('Location: login.php');
                exit;
            } else {
                $error = 'Email not found';
            }
        }
    } catch (Exception $e) {
        $error = 'An error occurred. Please try again.';
    }
}

// Handle password reset with token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
    $stmt->execute([$token]);
    if (!$stmt->fetch()) {
        $error = 'Invalid or expired reset token';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Carles Tourism</title>
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
            <h2 class="text-3xl font-bold text-blue-800 mb-3">Reset Password</h2>
            <p class="text-blue-600 text-lg">Enter your email to receive reset instructions</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message bg-red-50 text-red-600 p-4 rounded-lg mb-6 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['token'])): ?>
            <!-- Reset Password Form -->
            <form method="POST" action="" class="space-y-5">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <div class="form-group">
                    <label class="block text-sm font-medium text-blue-700 mb-2">New Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-3 text-blue-400"></i>
                        <input type="password" name="password" required class="pl-10 pr-10 w-full px-4 py-3 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="••••••••">
                        <button type="button" class="toggle-password absolute right-3 top-3 text-blue-400 hover:text-blue-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="block text-sm font-medium text-blue-700 mb-2">Confirm New Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-3 text-blue-400"></i>
                        <input type="password" name="confirm_password" required class="pl-10 pr-10 w-full px-4 py-3 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="••••••••">
                        <button type="button" class="toggle-password absolute right-3 top-3 text-blue-400 hover:text-blue-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg mt-4 flex items-center justify-center gap-2">
                    <i class="fas fa-key"></i>
                    Reset Password
                </button>
            </form>
        <?php else: ?>
            <!-- Request Reset Form -->
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
                    Send Reset Instructions
                </button>
            </form>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Login
            </a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html> 