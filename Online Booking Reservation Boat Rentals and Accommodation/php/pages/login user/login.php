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
        $result = $auth->userLogin($email, $password);
        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['full_name'];
            echo json_encode(['success' => true, 'redirect' => '../../UserDashboard/userdashboard.php']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-blue-400 to-cyan-300 p-4 font-body">
    <!-- Navigation buttons with absolute positioning -->
    <div class="absolute top-4 left-4 right-4 flex justify-between items-center z-[9999]">
        <a href="../../pages/interface.php" 
            class="flex items-center gap-3 px-6 py-3 bg-white text-blue-600 font-bold rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-2xl transform hover:scale-105 border-2 border-blue-600">
            <i class="fas fa-arrow-left text-xl"></i>
            Back to Home
        </a>
        <button type="button" id="closeBtn" 
            class="flex items-center gap-3 px-6 py-3 bg-white text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white transition-all duration-300 shadow-2xl transform hover:scale-105 border-2 border-red-600">
            <i class="fas fa-times text-xl"></i>
            Close
        </button>
    </div>

    <!-- Animated background elements -->
    <div class="ocean-bg">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="bubble bubble1"></div>
        <div class="bubble bubble2"></div>
        <div class="bubble bubble3"></div>
        <div class="bubble bubble4"></div>
    </div>

    <div class="w-full max-w-md bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-blue-100 transform transition-all duration-300 hover:scale-[1.02] mt-16">
        <div class="mb-8 text-center">
            <div class="inline-block p-3 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-user-circle text-blue-600 text-4xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-blue-800 mb-3">Welcome Back</h2>
            <p class="text-blue-600 text-lg">Please login to your account</p>
        </div>

        <div id="error-message" class="hidden error-message bg-red-50 text-red-600 p-4 rounded-lg mb-6 flex items-center gap-2 transform transition-all duration-300">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text"></span>
        </div>

        <form id="loginForm" method="POST" action="" class="space-y-5">
            <div class="form-group transform transition-all duration-300 hover:scale-[1.02]">
                <div class="relative">
                    <input type="email" name="email" id="email" required 
                        class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                        placeholder=" ">
                    <label for="email" 
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                        Email Address
                    </label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400 text-lg peer-focus:text-blue-500"></i>
                    </div>
                </div>
            </div>

            <div class="form-group transform transition-all duration-300 hover:scale-[1.02]">
                <div class="relative">
                    <input type="password" name="password" id="password" required 
                        class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                        placeholder=" ">
                    <label for="password" 
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                        Password
                    </label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400 text-lg peer-focus:text-blue-500"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-blue-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                    Remember me
                </label>
                <button type="button" onclick="showPasswordRecovery()" 
                    class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    Forgot Password?
                </button>
            </div>

            <button type="submit" 
                class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg mt-4 flex items-center justify-center gap-2 transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="fas fa-sign-in-alt"></i>
                Login
            </button>

            <div class="text-center mt-4">
                <p class="text-sm text-blue-600">Don't have an account? 
                    <a href="../register user/register.php" 
                        class="text-blue-800 hover:text-blue-900 font-medium transition-colors duration-200 hover:underline">
                        Register here
                    </a>
                </p>
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
            background: linear-gradient(to right, #2563EB, #3B82F6);
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

        /* Button shadows and effects */
        .shadow-2xl {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1),
                        0 0 40px rgba(255, 255, 255, 0.2);
        }

        /* Button hover animations */
        .transform.hover\:scale-105:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.3),
                        0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Add glow effects to buttons */
        .from-blue-600 {
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.4);
        }
        
        .text-red-600 {
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.4);
        }

        /* Ensure content doesn't hide under the navigation */
        body {
            padding-top: 6rem !important;
        }

        /* Add glass effect to the navigation bar */
        .from-black\/50 {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Close button functionality
            document.getElementById('closeBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be redirected to the home page",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563EB',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../pages/interface.php';
                    }
                });
            });

            // Form submission handling
            const loginForm = document.getElementById('loginForm');
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        const errorMessage = document.getElementById('error-message');
                        const errorText = document.getElementById('error-text');
                        errorText.textContent = data.message;
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

        function showPasswordRecovery() {
            window.location.href = 'password_recovery.php';
        }
    </script>
</body>
</html>
