<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

session_start();

$auth = new Auth($pdo);
$security = new Security($pdo);

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $result = $auth->adminLogin($email, $password);
        if ($result['success']) {
            $_SESSION['admin_id'] = $result['admin']['id'];
            $_SESSION['admin_name'] = $result['admin']['full_name'];
            echo json_encode([
                'success' => true, 
                'message' => 'Login successful! Redirecting...',
                'redirect' => '../../php/pages/admin/admin.php'
            ]);
            exit;
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Invalid email or password'
            ]);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'An error occurred. Please try again.'
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-700 via-indigo-600 to-purple-600 p-4 font-body">
    <!-- Animated background elements -->
    <div class="ocean-bg">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="bubble bubble1"></div>
        <div class="bubble bubble2"></div>
        <div class="bubble bubble3"></div>
        <div class="bubble bubble4"></div>
    </div>

    <div class="w-full max-w-md bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-indigo-100 transform transition-all duration-300 hover:scale-[1.02]">
        <!-- Remove Progress Bar and Steps -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <span class="text-xs text-gray-500"></span>
            </div>
        </div>

        <!-- Close Button -->
        <button type="button" id="closeBtn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200" onclick="handleClose()">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="mb-8 text-center">
            <div class="inline-block p-3 rounded-full bg-indigo-100 mb-4">
                <i class="fas fa-shield-alt text-indigo-600 text-4xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-indigo-800 mb-3">Admin Portal</h2>
            <p class="text-indigo-600 text-lg">Secure access for administrators</p>
        </div>

        <div id="error-message" class="hidden error-message bg-red-50 text-red-600 p-4 rounded-lg mb-6 flex items-center gap-2 transform transition-all duration-300">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text"></span>
        </div>

        <form id="loginForm" method="POST" action="" class="space-y-5">
            <div class="form-group transform transition-all duration-300">
                <div class="relative">
                    <input type="email" name="email" id="email" required 
                        class="block w-full h-[50px] px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer"
                        placeholder=" ">
                    <label for="email" 
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                        Admin Email
                    </label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400 text-lg peer-focus:text-indigo-500"></i>
                    </div>
                    <div class="error-message hidden text-red-500 text-sm mt-1 ml-2">
                        <i class="fas fa-exclamation-circle"></i>
                        This field is required
                    </div>
                </div>
            </div>
            <div class="form-group transform transition-all duration-300">
                <div class="relative">
                    <input type="password" name="password" id="password" required 
                        class="block w-full h-[50px] px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer"
                        placeholder=" ">
                    <label for="password" 
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                        Password
                    </label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400 text-lg peer-focus:text-indigo-500"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="error-message hidden text-red-500 text-sm mt-1 ml-2">
                        <i class="fas fa-exclamation-circle"></i>
                        This field is required
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-indigo-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                    Remember me
                </label>
                <button type="button" onclick="showPasswordRecovery()" 
                    class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                    Forgot Password?
                </button>
            </div>
            <div class="flex justify-between mt-6 space-x-4">
                <div></div>
                <div>
                    <button type="submit" id="submitBtn" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="../../php/pages/interface.php" 
                    class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200 hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Main Site
                </a>
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
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 45%;
            top: -100%;
            left: -50%;
            animation: wave 15s infinite linear;
        }

        .wave1 {
            animation-delay: 0s;
        }

        .wave2 {
            animation-delay: -5s;
        }

        .bubble {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s infinite ease-in-out;
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
            left: 80%;
            top: 40%;
            animation-delay: 2s;
        }

        .bubble3 {
            width: 40px;
            height: 40px;
            left: 30%;
            top: 60%;
            animation-delay: 4s;
        }

        .bubble4 {
            width: 50px;
            height: 50px;
            left: 70%;
            top: 80%;
            animation-delay: 6s;
        }

        @keyframes wave {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
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
            background: linear-gradient(to right, #4F46E5, #7C3AED);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .form-group:hover::after {
            transform: scaleX(1);
        }

        .error-message {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility with animation
            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const icon = this.querySelector('i');
                
                // Animate icon rotation
                icon.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    icon.style.transform = 'rotate(0deg)';
                }, 200);
                
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });

            // Handle form submission with loading state
            const form = document.getElementById('loginForm');
            const submitButton = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
                
                fetch('login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful!',
                            text: 'Redirecting to dashboard...',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        // Show error message with animation
                        const errorMessage = document.getElementById('error-message');
                        const errorText = document.getElementById('error-text');
                        errorText.textContent = data.message;
                        errorMessage.classList.remove('hidden');
                        
                        // Shake animation for error
                        errorMessage.classList.add('animate-shake');
                        setTimeout(() => {
                            errorMessage.classList.remove('animate-shake');
                        }, 500);
                    }
                })
                .catch(error => {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred. Please try again.'
                    });
                })
                .finally(() => {
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
                });
            });

            // Add input focus effects
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-indigo-500');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-indigo-500');
                });
            });

            // Add validation for required fields
            const inputs = form.querySelectorAll('input[required]');
            
            inputs.forEach(input => {
                // Show error message on blur if empty
                input.addEventListener('blur', function() {
                    const errorMessage = this.parentElement.querySelector('.error-message');
                    if (!this.value) {
                        this.classList.add('border-red-500');
                        errorMessage.classList.remove('hidden');
                    } else {
                        this.classList.remove('border-red-500');
                        errorMessage.classList.add('hidden');
                    }
                });

                // Hide error message when user starts typing
                input.addEventListener('input', function() {
                    const errorMessage = this.parentElement.querySelector('.error-message');
                    this.classList.remove('border-red-500');
                    errorMessage.classList.add('hidden');
                });
            });

            // Close button functionality
            function handleClose() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be redirected to the home page",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4F46E5',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/Online Booking Reservation Boat Rentals and Accommodation/php/pages/interface.php';
                    }
                });
            }

            // Add click event listener to close button
            const closeBtn = document.getElementById('closeBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleClose();
                });
            }
        });

        function showPasswordRecovery() {
            Swal.fire({
                title: 'Password Recovery',
                text: 'This feature is coming soon!',
                icon: 'info',
                confirmButtonColor: '#4F46E5'
            });
        }
    </script>
</body>
</html> 