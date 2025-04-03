<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

// Set session cookie parameters for better security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
ini_set('session.cookie_lifetime', 1800); // 30 minutes

session_start();

$auth = new Auth($pdo);
$security = new Security($pdo);

// Get client IP
$clientIP = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

// Check if IP is blocked
if ($security->isIPBlocked($clientIP)) {
    $error = "Your IP address has been blocked due to multiple failed attempts. Please try again later.";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check rate limiting
    if (!$security->checkRateLimit($clientIP)) {
        $error = "Too many attempts. Please try again later.";
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = "Please fill in all fields";
        } else {
            try {
                // Debug information
                error_log("Login attempt for email: " . $email);
                
                // Check if user exists and is an admin
                $stmt = $pdo->prepare("SELECT user_id, full_name, password, user_type FROM users WHERE email = ? AND user_type = 'admin'");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                // Debug information
                error_log("User found: " . ($user ? "Yes" : "No"));
                if ($user) {
                    error_log("Password verify result: " . (password_verify($password, $user['password']) ? "True" : "False"));
                }

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['user_id'];
                    $_SESSION['admin_name'] = $user['full_name'];
                    $_SESSION['last_activity'] = time();
                    
                    // Log successful login
                    $security->logLoginAttempt($clientIP, $email, true);
                    
                    error_log("Login successful, redirecting to dashboard");
                    header('Location: ../../../Dashboards/User Dashboard/Admin Dashboard/admin/dashboard.php');
                    exit;
                } else {
                    $error = "Invalid email or password";
                    // Log failed login
                    $security->logLoginAttempt($clientIP, $email, false);
                    error_log("Login failed: Invalid credentials");
                }
            } catch (PDOException $e) {
                $error = "Database error. Please try again later.";
                error_log("Login error: " . $e->getMessage());
            }
        }
    }
}

// Generate CSRF token
$csrf_token = $security->generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-image: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .input-field {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .input-field:focus {
            border-left: 4px solid #3b82f6;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }
        .wave-background {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 15vh;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="%231e3c72" fill-opacity="0.5" d="M0,288L48,272C96,256,192,224,288,213.3C384,203,480,213,576,202.7C672,192,768,160,864,165.3C960,171,1056,213,1152,213.3C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-repeat: no-repeat;
            z-index: -1;
        }
        .wave-background-2 {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 10vh;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="%232a5298" fill-opacity="0.7" d="M0,32L48,48C96,64,192,96,288,106.7C384,117,480,107,576,90.7C672,75,768,53,864,69.3C960,85,1056,139,1152,149.3C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-repeat: no-repeat;
            z-index: -1;
        }
        .floating-dots {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(#ffffff33 1px, transparent 1px);
            background-size: 30px 30px;
            z-index: -1;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(27, 40, 72, 0.4);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="floating-dots"></div>
    <div class="wave-background"></div>
    <div class="wave-background-2"></div>
    
    <div class="w-full max-w-md glass-card rounded-3xl overflow-hidden p-8 relative z-10 transform transition-all duration-300 hover:scale-[1.01]">
        <div class="text-center mb-8">
            <div class="mb-4 flex justify-center">
                <div class="w-20 h-20 bg-indigo-700 rounded-2xl flex items-center justify-center shadow-lg transform rotate-12">
                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center transform -rotate-12">
                        <i class="fas fa-user-shield text-3xl text-indigo-700"></i>
                    </div>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">Administrator Portal</h2>
            <p class="text-indigo-600 mt-2 font-light">Secure access to manage Carles Tourism</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-6" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="space-y-1">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-indigo-500"></i>
                    </div>
                    <input type="email" name="email" id="email" required
                        class="input-field focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-4 py-3 rounded-xl shadow-sm border-gray-200"
                        placeholder="admin@admin.com"
                        autocomplete="email">
                </div>
            </div>

            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-indigo-500"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="input-field focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 py-3 rounded-xl shadow-sm border-gray-200"
                        placeholder="••••••••"
                        autocomplete="current-password">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" id="togglePassword" class="text-indigo-500 hover:text-indigo-700 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="btn-gradient w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Access Dashboard
                </button>
            </div>
        </form>

        <div class="mt-8 text-center flex flex-col space-y-2">
            <button type="button" id="createAdminBtn" class="text-indigo-600 hover:text-indigo-500 flex justify-center items-center">
                <i class="fas fa-user-plus mr-2"></i> Create Administrator Account
            </button>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between text-sm">
            <a href="../../pages/interface.php" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to Home
            </a>
            <a href="javascript:void(0);" onclick="window.location.href='../../../Admin and User Loginup/loginup_admin.php'" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <i class="fas fa-times mr-1"></i>
                Close
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            
            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }

            // Form validation
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                if (!email || !password) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please fill in all fields'
                    });
                }
            });
            
            // Create Admin Account button click handler
            document.getElementById('createAdminBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Create Admin Account',
                    html:
                        // Step indicators
                        '<div class="step-indicators-row mb-4" style="display: flex; justify-content: space-between; align-items: center; position: relative;">' +
                        '<div class="step active" data-step="1" style="display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; width: 33.333%;">' +
                        '<div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background-color: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-bottom: 8px;">1</div>' +
                        '<div class="step-label" style="font-size: 0.8rem; color: #2563eb; font-weight: 600;">Personal Info</div>' +
                        '</div>' +
                        '<div class="step" data-step="2" style="display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; width: 33.333%;">' +
                        '<div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background-color: #e5e7eb; color: #6b7280; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-bottom: 8px;">2</div>' +
                        '<div class="step-label" style="font-size: 0.8rem; color: #6b7280; font-weight: 500;">Contact Info</div>' +
                        '</div>' +
                        '<div class="step" data-step="3" style="display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; width: 33.333%;">' +
                        '<div class="step-circle" style="width: 30px; height: 30px; border-radius: 50%; background-color: #e5e7eb; color: #6b7280; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-bottom: 8px;">3</div>' +
                        '<div class="step-label" style="font-size: 0.8rem; color: #6b7280; font-weight: 500;">Set Password</div>' +
                        '</div>' +
                        '</div>' +
                        
                        // Progress bar
                        '<div class="progress-container mb-4" style="background-color: #e5e7eb; border-radius: 9999px; height: 8px; width: 100%; overflow: hidden;">' +
                        '<div class="progress-bar" id="admin-progress" style="height: 100%; background-color: #2563eb; border-radius: 9999px; width: 33.33%; transition: width 0.3s ease;"></div>' +
                        '</div>' +

                        // Step 1 - Personal Info
                        '<div id="admin-step1" class="admin-step active" style="display: block;">' +
                        '<div class="mb-3">' +
                        '<label for="admin-first-name" class="block text-sm font-medium text-gray-700 text-left mb-1">First Name</label>' +
                        '<input id="admin-first-name" class="swal2-input" placeholder="Enter first name">' +
                        '</div>' +
                        '<div class="mb-3">' +
                        '<label for="admin-last-name" class="block text-sm font-medium text-gray-700 text-left mb-1">Last Name</label>' +
                        '<input id="admin-last-name" class="swal2-input" placeholder="Enter last name">' +
                        '</div>' +
                        '<div class="mb-3 grid grid-cols-2 gap-3">' +
                        '<div>' +
                        '<label for="admin-middle-initial" class="block text-sm font-medium text-gray-700 text-left mb-1">Middle Initial</label>' +
                        '<input id="admin-middle-initial" class="swal2-input" placeholder="M" maxlength="1">' +
                        '</div>' +
                        '<div>' +
                        '<label for="admin-suffix" class="block text-sm font-medium text-gray-700 text-left mb-1">Suffix</label>' +
                        '<select id="admin-suffix" class="swal2-input">' +
                        '<option value="">None</option>' +
                        '<option value="Jr.">Jr.</option>' +
                        '<option value="Sr.">Sr.</option>' +
                        '<option value="I">I</option>' +
                        '<option value="II">II</option>' +
                        '<option value="III">III</option>' +
                        '<option value="IV">IV</option>' +
                        '<option value="V">V</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="mb-3">' +
                        '<label for="admin-email" class="block text-sm font-medium text-gray-700 text-left mb-1">Email Address</label>' +
                        '<input id="admin-email" class="swal2-input" placeholder="Enter email">' +
                        '</div>' +
                        '<div class="mb-3">' +
                        '<label for="admin-gender" class="block text-sm font-medium text-gray-700 text-left mb-1">Gender</label>' +
                        '<select id="admin-gender" class="swal2-input">' +
                        '<option value="">Select Gender</option>' +
                        '<option value="male">Male</option>' +
                        '<option value="female">Female</option>' +
                        '<option value="other">Other</option>' +
                        '</select>' +
                        '</div>' +
                        '<div class="mb-3">' +
                        '<label for="admin-nationality" class="block text-sm font-medium text-gray-700 text-left mb-1">Nationality</label>' +
                        '<select id="admin-nationality" class="swal2-input">' +
                        '<option value="">Select Nationality</option>' +
                        '<option value="Filipino">Filipino</option>' +
                        '<option value="American">American</option>' +
                        '<option value="Chinese">Chinese</option>' +
                        '<option value="Japanese">Japanese</option>' +
                        '<option value="Korean">Korean</option>' +
                        '<option value="Other">Other</option>' +
                        '</select>' +
                        '</div>' +
                        '<div class="mb-3 text-right">' +
                        '<button type="button" onclick="adminNextStep(1, 2)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Next <i class="fas fa-arrow-right ml-1"></i></button>' +
                        '</div>' +
                        '</div>' +

                        // Step 2 - Contact Info
                        '<div id="admin-step2" class="admin-step" style="display: none;">' +
                        '<div class="mb-3">' +
                        '<label for="admin-age" class="block text-sm font-medium text-gray-700 text-left mb-1">Age</label>' +
                        '<input id="admin-age" type="number" min="18" max="100" class="swal2-input" placeholder="Enter age (minimum 18)">' +
                        '</div>' +
                        '<div class="mb-3">' +
                        '<label for="admin-address" class="block text-sm font-medium text-gray-700 text-left mb-1">Address</label>' +
                        '<input id="admin-address" class="swal2-input" placeholder="Enter address">' +
                        '</div>' +
                        '<div class="mb-3">' +
                        '<label for="admin-phone" class="block text-sm font-medium text-gray-700 text-left mb-1">Phone Number (Philippine format)</label>' +
                        '<input id="admin-phone" class="swal2-input" placeholder="e.g. 09XXXXXXXXX or +63XXXXXXXXXX">' +
                        '</div>' +
                        '<div class="mb-3 flex justify-between">' +
                        '<button type="button" onclick="adminPrevStep(2, 1)" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition"><i class="fas fa-arrow-left mr-1"></i> Previous</button>' +
                        '<button type="button" onclick="adminNextStep(2, 3)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Next <i class="fas fa-arrow-right ml-1"></i></button>' +
                        '</div>' +
                        '</div>' +

                        // Step 3 - Set Password
                        '<div id="admin-step3" class="admin-step" style="display: none;">' +
                        '<div class="mb-3">' +
                        '<label for="admin-password" class="block text-sm font-medium text-gray-700 text-left mb-1">Password</label>' +
                        '<input id="admin-password" type="password" class="swal2-input" placeholder="Enter password">' +
                        '</div>' +
                        '<div class="mb-3">' +
                        '<label for="admin-confirm-password" class="block text-sm font-medium text-gray-700 text-left mb-1">Confirm Password</label>' +
                        '<input id="admin-confirm-password" type="password" class="swal2-input" placeholder="Confirm password">' +
                        '</div>' +
                        '<div class="mb-3 flex justify-between">' +
                        '<button type="button" onclick="adminPrevStep(3, 2)" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition"><i class="fas fa-arrow-left mr-1"></i> Previous</button>' +
                        '<button type="button" id="adminSubmitBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"><i class="fas fa-check mr-1"></i> Create Account</button>' +
                        '</div>' +
                        '</div>',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Close',
                    cancelButtonColor: '#ef4444',
                    didOpen: () => {
                        // Add functions for step navigation
                        window.adminNextStep = function(currentStep, nextStep) {
                            const isValid = validateAdminStep(currentStep);
                            
                            if (isValid) {
                                document.getElementById('admin-step' + currentStep).style.display = 'none';
                                document.getElementById('admin-step' + nextStep).style.display = 'block';
                                
                                // Update step indicators
                                document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');
                                document.querySelector(`.step[data-step="${currentStep}"] .step-circle`).style.backgroundColor = '#10b981';
                                document.querySelector(`.step[data-step="${currentStep}"] .step-circle`).style.color = 'white';
                                document.querySelector(`.step[data-step="${currentStep}"] .step-label`).style.color = '#10b981';
                                
                                document.querySelector(`.step[data-step="${nextStep}"]`).classList.add('active');
                                document.querySelector(`.step[data-step="${nextStep}"] .step-circle`).style.backgroundColor = '#2563eb';
                                document.querySelector(`.step[data-step="${nextStep}"] .step-circle`).style.color = 'white';
                                document.querySelector(`.step[data-step="${nextStep}"] .step-label`).style.color = '#2563eb';
                                document.querySelector(`.step[data-step="${nextStep}"] .step-label`).style.fontWeight = '600';
                                
                                // Update progress bar
                                const progressBar = document.getElementById('admin-progress');
                                switch(nextStep) {
                                    case 2:
                                        progressBar.style.width = '66.66%';
                                        break;
                                    case 3:
                                        progressBar.style.width = '100%';
                                        break;
                                }
                            }
                        };
                        
                        window.adminPrevStep = function(currentStep, prevStep) {
                            document.getElementById('admin-step' + currentStep).style.display = 'none';
                            document.getElementById('admin-step' + prevStep).style.display = 'block';
                            
                            // Update step indicators
                            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
                            document.querySelector(`.step[data-step="${currentStep}"] .step-circle`).style.backgroundColor = '#e5e7eb';
                            document.querySelector(`.step[data-step="${currentStep}"] .step-circle`).style.color = '#6b7280';
                            document.querySelector(`.step[data-step="${currentStep}"] .step-label`).style.color = '#6b7280';
                            document.querySelector(`.step[data-step="${currentStep}"] .step-label`).style.fontWeight = '500';
                            
                            // Update progress bar
                            const progressBar = document.getElementById('admin-progress');
                            switch(prevStep) {
                                case 1:
                                    progressBar.style.width = '33.33%';
                                    break;
                                case 2:
                                    progressBar.style.width = '66.66%';
                                    break;
                            }
                        };
                        
                        window.validateAdminStep = function(step) {
                            let isValid = true;
                            
                            switch(step) {
                                case 1:
                                    // Validate personal info
                                    const first_name = document.getElementById('admin-first-name').value;
                                    const last_name = document.getElementById('admin-last-name').value;
                                    const middle_initial = document.getElementById('admin-middle-initial').value;
                                    const suffix = document.getElementById('admin-suffix').value;
                                    const email = document.getElementById('admin-email').value;
                                    const gender = document.getElementById('admin-gender').value;
                                    const nationality = document.getElementById('admin-nationality').value;
                                    
                                    if (!first_name || !last_name || !email || !gender || !nationality) {
                                        Swal.showValidationMessage('Please fill in all required fields in this step');
                                        isValid = false;
                                    }
                                    
                                    if (email && !email.includes('@')) {
                                        Swal.showValidationMessage('Please enter a valid email address');
                                        isValid = false;
                                    }
                                    break;
                                    
                                case 2:
                                    // Validate contact info
                                    const age = document.getElementById('admin-age').value;
                                    const address = document.getElementById('admin-address').value;
                                    const phone = document.getElementById('admin-phone').value;
                                    
                                    if (!age || !address || !phone) {
                                        Swal.showValidationMessage('Please fill in all fields in this step');
                                        isValid = false;
                                    }
                                    
                                    if (age && (age < 18 || age > 100)) {
                                        Swal.showValidationMessage('Admin must be at least 18 years old');
                                        isValid = false;
                                    }
                                    
                                    const phoneRegex = /^(\+63|09)\d{9,10}$/;
                                    if (phone && !phoneRegex.test(phone)) {
                                        Swal.showValidationMessage('Please enter a valid Philippine phone number format (09XXXXXXXXX or +63XXXXXXXXXX)');
                                        isValid = false;
                                    }
                                    break;
                            }
                            
                            return isValid;
                        }
                        
                        // Handle form submission when the "Create Account" button is clicked
                        document.getElementById('adminSubmitBtn').addEventListener('click', function() {
                            // Gather all data from all steps
                            const first_name = document.getElementById('admin-first-name').value;
                            const last_name = document.getElementById('admin-last-name').value;
                            const middle_initial = document.getElementById('admin-middle-initial').value;
                            const suffix = document.getElementById('admin-suffix').value;
                            
                            // Combine name parts into full_name for display
                            let full_name = first_name;
                            if (middle_initial) {
                                full_name += ' ' + middle_initial + '.';
                            }
                            full_name += ' ' + last_name;
                            if (suffix) {
                                full_name += ' ' + suffix;
                            }
                            
                            const email = document.getElementById('admin-email').value;
                            const gender = document.getElementById('admin-gender').value;
                            const nationality = document.getElementById('admin-nationality').value;
                            const age = document.getElementById('admin-age').value;
                            const address = document.getElementById('admin-address').value;
                            const phone_number = document.getElementById('admin-phone').value;
                            const password = document.getElementById('admin-password').value;
                            const confirmPassword = document.getElementById('admin-confirm-password').value;
                            
                            // Validate password fields
                            if (!password || !confirmPassword) {
                                Swal.showValidationMessage('Please enter and confirm your password');
                                return;
                            }
                            
                            if (password !== confirmPassword) {
                                Swal.showValidationMessage('Passwords do not match');
                                return;
                            }
                            
                            // Send data to server
                            fetch('create_admin.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    name: full_name,
                                    first_name: first_name,
                                    last_name: last_name,
                                    middle_initial: middle_initial,
                                    suffix: suffix,
                                    email: email,
                                    gender: gender,
                                    nationality: nationality,
                                    address: address,
                                    age: age,
                                    phone_number: phone_number,
                                    password: password
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.close();
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Admin account created successfully',
                                        icon: 'success',
                                        confirmButtonColor: '#4caf50'
                                    });
                                } else {
                                    Swal.showValidationMessage(data.message || 'Failed to create admin account');
                                }
                            })
                            .catch(error => {
                                Swal.showValidationMessage('Error: ' + error.message);
                            });
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Admin account created successfully',
                            icon: 'success',
                            confirmButtonColor: '#4caf50'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html> 