<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../php/config/connect.php');
require_once('../php/classes/Auth.php');
require_once('../php/classes/Security.php');

session_start();

// Initialize Auth and Security classes
$auth = new Auth($pdo);
$security = new Security($pdo);

// Handle signup form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Personal Information
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $middle_initial = filter_input(INPUT_POST, 'middle_initial', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_STRING) ?? '';
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $zip_code = filter_input(INPUT_POST, 'zip_code', FILTER_SANITIZE_STRING);
    
    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['signup_error'] = "Passwords do not match";
    } else {
        // Attempt to register user with additional information
        if ($auth->registerUser($username, $email, $password, $first_name, $middle_initial, $last_name, $suffix, $phone, $address, $city, $state, $zip_code)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: loginup_admin.php");
            exit();
        } else {
            $_SESSION['signup_error'] = "Registration failed. Username or email may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - Boat Rental System</title>
    <link rel="stylesheet" href="/Online Booking Reservation Boat Rentals and Accommodation/Loginup Admin/loginup_admin.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-group input {
            padding: 0.75rem 2.5rem;
            width: 100%;
            border: 1px solid #D1D5DB;
            border-radius: 0.375rem;
            background-color: white;
            transition: all 0.2s ease-in-out;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 1px #3B82F6;
        }

        .form-group label {
            position: absolute;
            left: 2.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            transition: all 0.2s ease-in-out;
            pointer-events: none;
            font-size: 0.875rem;
        }

        .form-group input:focus ~ label,
        .form-group input:not(:placeholder-shown) ~ label {
            top: 0;
            left: 0.75rem;
            font-size: 0.75rem;
            color: #3B82F6;
            background-color: white;
            padding: 0 0.25rem;
        }

        .form-group i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            transition: all 0.2s ease-in-out;
        }

        .form-group input:focus ~ i {
            color: #3B82F6;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: translateY(-50%) scale(1);
            }
            50% {
                transform: translateY(-50%) scale(1.1);
            }
            100% {
                transform: translateY(-50%) scale(1);
            }
        }
    </style>
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

    <div class="w-full max-w-2xl bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-blue-100">
        <!-- Close button -->
        <button id="closeModal" class="absolute -top-2 -right-2 text-white hover:text-gray-200 transition-colors duration-300 w-8 h-8 flex items-center justify-center rounded-full bg-gray-800 hover:bg-gray-700">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm font-semibold" id="step1-indicator">1</div>
                    <div class="ml-4 text-sm font-medium text-blue-700">Personal Info</div>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold" id="step2-indicator">2</div>
                    <div class="ml-4 text-sm font-medium text-gray-500">Contact Info</div>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold" id="step3-indicator">3</div>
                    <div class="ml-4 text-sm font-medium text-gray-500">Account</div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute top-0 left-0 h-1 bg-gray-200 w-full"></div>
                <div class="absolute top-0 left-0 h-1 bg-blue-500 w-1/3 transition-all duration-300" id="progress-bar"></div>
            </div>
        </div>

        <div class="text-center mb-8">
            <i class="fas fa-user-plus text-blue-500 text-5xl mb-4"></i>
            <h2 class="text-3xl font-bold text-blue-700">Create Account</h2>
            <p class="text-gray-600 mt-2">Join our boat rental community</p>
        </div>

        <div class="flex justify-between mb-4">
            <div class="flex space-x-4">
                <a href="../login.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </a>
                <a href="../signup.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-300">
                    <i class="fas fa-user-plus mr-2"></i>
                    Sign Up
                </a>
                <a href="loginup_admin.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-300">
                    <i class="fas fa-user-shield mr-2"></i>
                    Admin
                </a>
            </div>
        </div>

        <?php if(isset($_SESSION['signup_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $_SESSION['signup_error']; ?></span>
        </div>
        <?php unset($_SESSION['signup_error']); endif; ?>

        <form method="POST" action="signup.php" class="space-y-6" id="signup-form">
            <!-- Step 1: Personal Information -->
            <div class="form-step" id="step1">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <input type="text" name="first_name" id="first_name" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="first_name">First Name</label>
                            <i class="fas fa-user"></i>
                        </div>

                        <div class="form-group">
                            <input type="text" name="middle_initial" id="middle_initial" maxlength="1"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="middle_initial">Middle Initial</label>
                            <i class="fas fa-user"></i>
                        </div>

                        <div class="form-group">
                            <input type="text" name="last_name" id="last_name" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="last_name">Last Name</label>
                            <i class="fas fa-user"></i>
                        </div>

                        <div class="form-group">
                            <input type="text" name="suffix" id="suffix"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="suffix">Suffix (Optional)</label>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" class="next-step bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors duration-300">
                        Next <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Contact Information -->
            <div class="form-step hidden" id="step2">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <input type="email" name="email" id="email" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="email">Email Address</label>
                            <i class="fas fa-envelope"></i>
                        </div>

                        <div class="form-group">
                            <input type="tel" name="phone" id="phone" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="phone">Phone Number</label>
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Address Information</h3>
                    <div class="space-y-4">
                        <div class="form-group">
                            <input type="text" name="address" id="address" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="address">Street Address</label>
                            <i class="fas fa-home"></i>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <input type="text" name="city" id="city" required 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                    placeholder=" ">
                                <label for="city">City</label>
                                <i class="fas fa-building"></i>
                            </div>

                            <div class="form-group">
                                <input type="text" name="state" id="state" required 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                    placeholder=" ">
                                <label for="state">State</label>
                                <i class="fas fa-map-marker-alt"></i>
                            </div>

                            <div class="form-group">
                                <input type="text" name="zip_code" id="zip_code" required 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                    placeholder=" ">
                                <label for="zip_code">ZIP Code</label>
                                <i class="fas fa-map-pin"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6 space-x-4">
                    <button type="button" class="prev-step bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition-colors duration-300 flex-1">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button type="button" class="next-step bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors duration-300 flex-1">
                        Next <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Account Information -->
            <div class="form-step hidden" id="step3">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <input type="text" name="username" id="username" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="username">Username</label>
                            <i class="fas fa-user-circle"></i>
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" id="password" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="password">Password</label>
                            <i class="fas fa-lock"></i>
                            <div class="mt-1">
                                <div class="h-1 w-full bg-gray-200 rounded-full">
                                    <div class="h-1 w-0 bg-red-500 rounded-full transition-all duration-300" id="password-strength-bar"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1" id="password-strength-text">Password strength: <span class="text-red-500">Weak</span></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="password" name="confirm_password" id="confirm_password" required 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder=" ">
                            <label for="confirm_password">Confirm Password</label>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="terms" id="terms" required
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms and Conditions</a>
                        </label>
                    </div>
                </div>

                <div class="flex justify-between mt-6 space-x-4">
                    <button type="button" class="prev-step bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition-colors duration-300 flex-1">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button type="submit" 
                        class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors duration-300">
                        <i class="fas fa-user-plus mr-2"></i> Create Account
                    </button>
                </div>
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="loginup_admin.php" class="font-medium text-blue-600 hover:text-blue-500">Sign in</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all form steps
            const steps = document.querySelectorAll('.form-step');
            const progressBar = document.getElementById('progress-bar');
            let currentStep = 0;

            // Function to show a specific step
            function showStep(stepIndex) {
                // Hide all steps
                steps.forEach(step => {
                    step.style.display = 'none';
                });

                // Show the current step
                steps[stepIndex].style.display = 'block';

                // Update progress bar
                const progress = ((stepIndex + 1) / steps.length) * 100;
                progressBar.style.width = `${progress}%`;

                // Update step indicators
                document.querySelectorAll('[id^="step"]-indicator').forEach((indicator, index) => {
                    if (index <= stepIndex) {
                        indicator.classList.remove('bg-gray-300', 'text-gray-600');
                        indicator.classList.add('bg-blue-500', 'text-white');
                    } else {
                        indicator.classList.remove('bg-blue-500', 'text-white');
                        indicator.classList.add('bg-gray-300', 'text-gray-600');
                    }
                });
            }

            // Next button click handler
            document.querySelectorAll('.next-step').forEach(button => {
                button.addEventListener('click', function() {
                    // Get required fields in current step
                    const currentStepElement = steps[currentStep];
                    const requiredFields = currentStepElement.querySelectorAll('[required]');
                    let isValid = true;

                    // Check if all required fields are filled
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    if (isValid) {
                        if (currentStep < steps.length - 1) {
                            currentStep++;
                            showStep(currentStep);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Required Fields',
                            text: 'Please fill in all required fields before proceeding.'
                        });
                    }
                });
            });

            // Back button click handler
            document.querySelectorAll('.prev-step').forEach(button => {
                button.addEventListener('click', function() {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            // Show first step initially
            showStep(0);

            // Password strength indicator
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text').querySelector('span');

            password.addEventListener('input', function() {
                const value = this.value;
                let strength = 0;
                let color = 'red';
                let text = 'Weak';

                if (value.length >= 8) strength += 25;
                if (value.match(/[a-z]/) && value.match(/[A-Z]/)) strength += 25;
                if (value.match(/\d/)) strength += 25;
                if (value.match(/[^a-zA-Z\d]/)) strength += 25;

                if (strength >= 75) {
                    color = 'green';
                    text = 'Strong';
                } else if (strength >= 50) {
                    color = 'yellow';
                    text = 'Medium';
                }

                strengthBar.style.width = `${strength}%`;
                strengthBar.className = `h-1 w-${strength} bg-${color}-500 rounded-full transition-all duration-300`;
                strengthText.textContent = text;
                strengthText.className = `text-${color}-500`;
            });

            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });

            // Form validation
            document.getElementById('signup-form').addEventListener('submit', function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Mismatch',
                        text: 'Please make sure your passwords match!'
                    });
                }
            });

            // Close button functionality
            const closeBtn = document.getElementById('closeModal');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    // Confirm before leaving
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Your progress will be lost if you leave.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, leave',
                        cancelButtonText: 'Stay'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'loginup_admin.php';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html> 