<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

session_start();

// Initialize database connection
try {
    $host = 'localhost';
    $db = 'booking_system';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Could not connect to the database. Please try again later.");
}

$auth = new Auth($pdo);
$security = new Security($pdo);

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Debug log
        error_log("Login attempt for email: " . $email);
        
        $result = $auth->loginUser($email, $password);
        
        // Debug log
        error_log("Login result: " . print_r($result, true));
        
        if ($result['success']) {
            // Log successful login
            $security->logLoginAttempt($_SERVER['REMOTE_ADDR'], $email, true);
            
            // Return success response with correct dashboard path
            echo json_encode([
                'success' => true,
                'redirect' => '../../../Dashboards/User Dashboard/userdashboard.php'
            ]);
            exit;
        } else {
            // Log failed login
            $security->logLoginAttempt($_SERVER['REMOTE_ADDR'], $email, false);
            
            // Return error response
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
            exit;
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
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
    <title>User Login - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f0f9ff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%2393c5fd' fill-opacity='0.3'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .login-card {
            background: #ffffff;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
        }
        .card-decoration {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, #3b82f6, #60a5fa, #93c5fd);
        }
        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .custom-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .custom-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        .input-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #3b82f6;
        }
        .btn-primary {
            background: linear-gradient(to right, #3b82f6, #60a5fa);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #2563eb, #3b82f6);
            box-shadow: 0 6px 10px rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        .link-blue {
            color: #3b82f6;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .link-blue:hover {
            color: #2563eb;
            text-decoration: underline;
        }
        .ocean-effect {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40vh;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%233b82f6' fill-opacity='0.2' d='M0,96L48,122.7C96,149,192,203,288,202.7C384,203,480,149,576,149.3C672,149,768,203,864,208C960,213,1056,171,1152,144C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: cover;
            z-index: -1;
        }
        .floating-island {
            position: absolute;
            right: 5%;
            bottom: 20%;
            width: 150px;
            height: 100px;
            opacity: 0.7;
            z-index: -1;
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="ocean-effect"></div>
    <img src="https://cdn-icons-png.flaticon.com/512/119/119596.png" alt="Island" class="floating-island">
    
    <div class="login-card w-full max-w-md p-8">
        <div class="card-decoration"></div>
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-16 w-16 bg-blue-100 rounded-full mb-4">
                <i class="fas fa-sailboat text-3xl text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome Back!</h2>
            <p class="text-blue-500 mt-1">Login to begin your island adventure</p>
        </div>

        <div id="error-message" class="hidden bg-red-50 text-red-600 p-4 rounded-lg mb-6 border-l-4 border-red-500" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm" id="error-text"></p>
                </div>
            </div>
        </div>

        <form method="POST" action="" id="loginForm" class="space-y-5">
            <div class="input-wrapper">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" id="email" required
                    class="custom-input"
                    placeholder="Your email address"
                    autocomplete="email">
            </div>

            <div class="input-wrapper">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" id="password" required
                    class="custom-input pr-10"
                    placeholder="Your password"
                    autocomplete="current-password">
                <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <div class="mb-4">
                <button type="submit" class="btn-primary flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign in to your account
                </button>
            </div>
            
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Don't have an account? 
                    <a href="../register user/register.php" class="link-blue">
                        Register now
                    </a>
                </p>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between text-sm">
            <a href="../interface.php" class="link-blue flex items-center">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to Home
            </a>
            <a href="javascript:void(0);" onclick="window.location.href='../../../Admin and User Loginup/loginup_admin.php'" class="link-blue flex items-center">
                <i class="fas fa-times mr-1"></i>
                Close
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            // Handle form submission
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                if (!email || !password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please fill in all fields'
                    });
                    return false;
                }

                // Use AJAX to submit the form
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message before redirecting
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            text: 'Redirecting to dashboard...',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        const errorMessage = document.getElementById('error-message');
                        const errorText = document.getElementById('error-text');
                        errorText.textContent = data.message;
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.'
                    });
                });
            });
        });
    </script>
</body>
</html>
