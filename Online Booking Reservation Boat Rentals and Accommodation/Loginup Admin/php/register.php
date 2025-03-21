<?php
session_start();
require_once('../php/config/connect.php');

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    try {
        // Validate password match
        if ($password !== $confirm_password) {
            $error = "Passwords do not match";
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Email already registered";
            } else {
                // Hash password and insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password]);
                
                // Redirect to login page with success message
                $_SESSION['success'] = "Registration successful! Please login.";
                header("Location: loginup_admin.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        $error = "An error occurred. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-700 via-indigo-600 to-purple-600 p-4 font-body">
    <!-- Navigation buttons with absolute positioning -->
    <div class="absolute top-4 left-4 right-4 flex justify-between items-center z-[9999]">
        <a href="../pages/login admin/login.php" 
            class="flex items-center gap-3 px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-2xl transform hover:scale-105 border-2 border-indigo-600">
            <i class="fas fa-arrow-left text-xl"></i>
            Back to Login
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

    <div class="w-full max-w-md bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-indigo-100 transform transition-all duration-300 hover:scale-[1.02] mt-16">
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-sm text-indigo-600 font-medium">Progress: <span id="progress-text">0%</span></span>
            <div class="flex gap-2">
                <span class="text-xs text-gray-500">Step <span id="current-step">1</span> of <span id="total-steps">3</span></span>
            </div>
        </div>

        <div class="mb-8 text-center">
            <div class="inline-block p-3 rounded-full bg-indigo-100 mb-4">
                <i class="fas fa-shield-alt text-indigo-600 text-4xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-indigo-800 mb-3">Admin Registration</h2>
            <p class="text-indigo-600 text-lg">Create your administrator account</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 animate-shake" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5" id="registrationForm">
            <div class="form-group transform transition-all duration-300">
                <div class="relative">
                    <input type="text" name="name" id="name" required 
                        class="block w-full h-[50px] px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer"
                        placeholder=" ">
                    <label for="name" 
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                        Full Name
                    </label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400 text-lg peer-focus:text-indigo-500"></i>
                    </div>
                    <div class="error-message hidden text-red-500 text-sm mt-1 ml-2">
                        <i class="fas fa-exclamation-circle"></i>
                        This field is required
                    </div>
                </div>
            </div>

            <div class="form-group transform transition-all duration-300">
                <div class="relative">
                    <input type="email" name="email" id="email" required 
                        class="block w-full h-[50px] px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer"
                        placeholder=" ">
                    <label for="email" 
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                        Email Address
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

            <div class="form-group transform transition-all duration-300">
                <div class="relative">
                    <input type="password" name="confirm_password" id="confirm_password" required 
                        class="block w-full h-[50px] px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer"
                        placeholder=" ">
                    <label for="confirm_password" 
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                        Confirm Password
                    </label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400 text-lg peer-focus:text-indigo-500"></i>
                    </div>
                    <div class="error-message hidden text-red-500 text-sm mt-1 ml-2">
                        <i class="fas fa-exclamation-circle"></i>
                        This field is required
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-6 space-x-4">
                <div class="flex gap-4">
                    <button type="button" id="prevBtn" class="hidden px-6 py-2 text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Previous
                    </button>
                </div>
                <div class="flex gap-4">
                    <button type="button" id="nextBtn" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                        Next<i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <div class="flex gap-4 items-center">
                        <button type="submit" id="submitBtn" class="hidden px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fas fa-user-shield mr-2"></i>Create Account
                        </button>
                        <a href="../pages/login admin/login.php" class="hidden px-6 py-2 text-indigo-600 border-2 border-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98]" id="backBtn">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Login
                        </a>
                        <button type="button" id="closeFormBtn" class="hidden px-6 py-2 text-red-600 border-2 border-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fas fa-times mr-2"></i>Close
            </button>
                    </div>
                </div>
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

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Additional styles for the navigation overlay */
        .pointer-events-none {
            pointer-events: none;
        }
        .pointer-events-auto {
            pointer-events: auto;
        }
        
        /* Ensure buttons are always on top */
        #closeBtn, 
        .back-to-login {
            position: relative;
            z-index: 9999;
        }
        
        /* Add a subtle glow effect to make buttons more visible */
        #closeBtn {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }
        
        .back-to-login {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        /* Enhanced button styles */
        .absolute {
            position: absolute !important;
        }

        /* Add glass effect to the navigation bar */
        .from-black\/50 {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Button hover effects */
        .hover\:bg-indigo-600:hover,
        .hover\:bg-red-600:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Add margin to main content to prevent overlap */
        .mt-16 {
            margin-top: 5rem !important;
        }

        /* Enhanced button visibility */
        .absolute {
            position: absolute !important;
        }

        /* Navigation bar with glass effect */
        .bg-white\/90 {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
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

        /* Ensure content doesn't hide under the navigation */
        body {
            padding-top: 6rem !important;
        }

        /* Add glow effects to buttons */
        .bg-indigo-600 {
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.4);
        }
        
        .bg-red-600 {
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.4);
        }

        /* Navigation bar styles */
        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Button effects */
        .from-indigo-600,
        .from-red-600 {
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.5),
                        0 0 60px rgba(79, 70, 229, 0.3);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Hover animations */
        .transform.hover\:scale-105:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3),
                        0 0 30px rgba(255, 255, 255, 0.5),
                        0 0 60px rgba(79, 70, 229, 0.3);
        }

        /* Content spacing */
        body {
            padding-top: 7rem !important;
        }

        /* Container responsiveness */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Button text enhancement */
        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        /* Ensure buttons are always visible */
        .z-\[9999\] {
            z-index: 9999 !important;
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

            // Password match validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords don't match");
                    confirmPassword.classList.add('border-red-500');
                } else {
                    confirmPassword.setCustomValidity('');
                    confirmPassword.classList.remove('border-red-500');
                }
            }
            
            password.addEventListener('change', validatePassword);
            confirmPassword.addEventListener('keyup', validatePassword);

            // Add input focus effects
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-indigo-500');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-indigo-500');
                });
            });

            // Add validation for required fields
            const form = document.getElementById('registrationForm');
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

            // Validate form before submission
            form.addEventListener('submit', function(e) {
                let isValid = true;
                inputs.forEach(input => {
                    const errorMessage = input.parentElement.querySelector('.error-message');
                    if (!input.value) {
                        e.preventDefault();
                        isValid = false;
                        input.classList.add('border-red-500');
                        errorMessage.classList.remove('hidden');
                    }
                });

                if (!isValid) {
                    // Shake animation for invalid form
                    form.classList.add('animate-shake');
                    setTimeout(() => {
                        form.classList.remove('animate-shake');
                    }, 500);
                }
            });

            // Form steps functionality
            const form = document.getElementById('registrationForm');
            const formGroups = form.querySelectorAll('.form-group');
            const progressBar = document.querySelector('.bg-indigo-600');
            const progressText = document.getElementById('progress-text');
            const currentStepText = document.getElementById('current-step');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            let currentStep = 1;
            const totalSteps = formGroups.length;

            // Hide all form groups initially except the first one
            formGroups.forEach((group, index) => {
                if (index > 0) {
                    group.style.display = 'none';
                }
            });

            function updateProgress() {
                let progress = 0;
                if (currentStep === 2) {
                    progress = 33;
                } else if (currentStep === 3) {
                    progress = 66;
                } else if (currentStep === 4) {
                    progress = 100;
                }
                progressBar.style.width = `${progress}%`;
                progressText.textContent = `${progress}%`;
                currentStepText.textContent = currentStep;
            }

            function validateCurrentStep() {
                const currentGroup = formGroups[currentStep - 1];
                const currentInputs = currentGroup.querySelectorAll('input[required]');
                let isValid = true;

                // Clear all error states first
                currentInputs.forEach(input => {
                    const errorMessage = input.parentElement.querySelector('.error-message');
                    input.classList.remove('border-red-500');
                    errorMessage.classList.add('hidden');
                });

                // Validate each input
                currentInputs.forEach(input => {
                    const errorMessage = input.parentElement.querySelector('.error-message');
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('border-red-500');
                        errorMessage.classList.remove('hidden');
                        
                        // Add shake animation only to the input field, not the whole group
                        input.classList.add('animate-shake');
                        setTimeout(() => {
                            input.classList.remove('animate-shake');
                        }, 500);
                    }
                });

                return isValid;
            }

            // Show/hide navigation buttons based on steps
            function updateButtonVisibility() {
                if (currentStep === totalSteps) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                    document.getElementById('backBtn').classList.remove('hidden');
                    document.getElementById('closeFormBtn').classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                    document.getElementById('backBtn').classList.add('hidden');
                    document.getElementById('closeFormBtn').classList.add('hidden');
                }
            }

            // Update the next button click handler
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!validateCurrentStep()) {
                    const currentGroup = formGroups[currentStep - 1];
                    const emptyInputs = currentGroup.querySelectorAll('input[required]:not([value])');
                    
                    if (emptyInputs.length > 0) {
                        emptyInputs[0].focus();
                    }
                    return false;
                }

                if (currentStep < totalSteps) {
                    formGroups[currentStep - 1].style.display = 'none';
                    formGroups[currentStep].style.display = 'block';
                    currentStep++;
                    updateProgress();
                    updateButtonVisibility();
                    
                    if (currentStep > 1) {
                        prevBtn.classList.remove('hidden');
                    }
                }
            });

            // Add click handler for the close form button
            document.getElementById('closeFormBtn').addEventListener('click', function(e) {
                e.preventDefault();
                handleClose();
            });

            // Initialize button visibility
            updateButtonVisibility();

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
        });
    </script>
</body>
</html> 