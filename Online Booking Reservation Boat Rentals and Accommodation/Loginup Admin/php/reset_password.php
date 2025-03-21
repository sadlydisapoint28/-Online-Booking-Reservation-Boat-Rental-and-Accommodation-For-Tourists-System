<?php
require_once('../php/config/connect.php');
require_once('../php/classes/Auth.php');
require_once('../php/classes/Security.php');

$auth = new Auth($pdo);
$security = new Security($pdo);

$token = $_GET['token'] ?? '';
$valid = false;
$error = '';

if ($token) {
    // Validate token
    $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW() AND used = 0");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();
    
    if ($reset) {
        $valid = true;
    } else {
        $error = 'Invalid or expired password reset link.';
    }
} else {
    $error = 'No password reset token provided.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password
    $errors = $security->validatePasswordStrength($password);
    
    if (empty($errors) && $password === $confirm_password) {
        // Update password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $reset['user_id']]);
        
        // Mark token as used
        $stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
        $stmt->execute([$token]);
        
        $_SESSION['success'] = 'Password has been reset successfully. You can now login with your new password.';
        header('Location: interface.php');
        exit;
    } else {
        if (!empty($errors)) {
            $error = implode('<br>', $errors);
        } else {
            $error = 'Passwords do not match.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Boat Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        .form-group {
            position: relative;
            overflow: hidden;
        }
        
        .form-group::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, #3B82F6, #60A5FA);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        
        .form-group:hover::after {
            transform: scaleX(1);
        }
        
        .input-focus {
            animation: pulse 0.5s ease-out;
        }
        
        .error-message {
            color: #EF4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            animation: fadeIn 0.3s ease-out;
        }
        
        .success-message {
            color: #10B981;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            animation: fadeIn 0.3s ease-out;
        }
        
        .password-strength {
            height: 4px;
            margin-top: 8px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-blue-400 to-cyan-300 p-4 font-body">
    <div class="w-full max-w-md bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-blue-100 animate-fade-in">
        <div class="text-center mb-8">
            <i class="fas fa-lock text-4xl text-blue-500 mb-4"></i>
            <h1 class="text-2xl font-bold text-gray-800">Reset Password</h1>
            <p class="text-gray-600 mt-2">Enter your new password below</p>
        </div>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 animate-fade-in">
            <span class="block sm:inline"><?php echo $error; ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($valid): ?>
        <form id="reset-password-form" class="space-y-6" method="POST">
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="Enter new password">
                    <button type="button" id="toggle-password" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength bg-gray-200">
                    <div class="password-strength-bar"></div>
                </div>
                <div id="password-requirements" class="text-xs text-gray-500 mt-1"></div>
            </div>
            
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                    <input type="password" name="confirm_password" id="confirm_password" required
                           class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="Confirm new password">
                    <button type="button" id="toggle-confirm-password" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="fas fa-key"></i>
                Reset Password
            </button>
        </form>
        <?php endif; ?>
        
        <div class="mt-6 text-center">
            <a href="interface.php" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Login
            </a>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reset-password-form');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const togglePassword = document.getElementById('toggle-password');
        const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
        const strengthBar = document.querySelector('.password-strength-bar');
        const requirements = document.getElementById('password-requirements');
        
        // Toggle password visibility
        function togglePasswordVisibility(input, button) {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            button.querySelector('i').classList.toggle('fa-eye');
            button.querySelector('i').classList.toggle('fa-eye-slash');
        }
        
        togglePassword.addEventListener('click', () => togglePasswordVisibility(passwordInput, togglePassword));
        toggleConfirmPassword.addEventListener('click', () => togglePasswordVisibility(confirmPasswordInput, toggleConfirmPassword));
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = [];
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[!@#$%^&*()\-_=+{};:,<.>]/.test(password)) strength++;
            
            if (password.length < 8) requirements.push("At least 8 characters");
            if (!/[A-Z]/.test(password)) requirements.push("One uppercase letter");
            if (!/[a-z]/.test(password)) requirements.push("One lowercase letter");
            if (!/[0-9]/.test(password)) requirements.push("One number");
            if (!/[!@#$%^&*()\-_=+{};:,<.>]/.test(password)) requirements.push("One special character");
            
            return { strength, requirements };
        }
        
        // Update password strength indicator
        function updatePasswordStrength(password) {
            const { strength, requirements } = checkPasswordStrength(password);
            
            // Update strength bar
            strengthBar.style.width = `${(strength / 5) * 100}%`;
            if (strength <= 2) {
                strengthBar.style.backgroundColor = '#EF4444';
            } else if (strength <= 4) {
                strengthBar.style.backgroundColor = '#F59E0B';
            } else {
                strengthBar.style.backgroundColor = '#10B981';
            }
            
            // Update requirements text
            if (requirements.length > 0) {
                requirements.textContent = requirements.join(", ");
            } else {
                requirements.textContent = "Password strength: Strong";
            }
        }
        
        passwordInput.addEventListener('input', function() {
            updatePasswordStrength(this.value);
        });
        
        // Form validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (password !== confirmPassword) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Passwords do not match',
                    icon: 'error',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }
            
            const { strength } = checkPasswordStrength(password);
            if (strength < 5) {
                Swal.fire({
                    title: 'Weak Password',
                    text: 'Please ensure your password meets all requirements',
                    icon: 'warning',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }
            
            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            submitButton.disabled = true;
            
            // Submit form
            form.submit();
        });
    });
    </script>
</body>
</html> 