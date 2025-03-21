<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Initialize Auth and Security classes
$auth = new Auth($pdo);
$security = new Security($pdo);

// Get client IP
$clientIP = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

// Check if IP is blocked
if ($security->isIPBlocked($clientIP)) {
    $_SESSION['register_error'] = "Your IP address has been blocked due to multiple failed attempts. Please try again later.";
    header("Location: register.php");
    exit();
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check rate limiting
    if (!$security->checkRateLimit($clientIP)) {
        $_SESSION['register_error'] = "Too many attempts. Please try again later.";
        header("Location: register.php");
        exit();
    }
    
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !$security->validateCSRFToken($_POST['csrf_token'])) {
        $_SESSION['register_error'] = "Invalid request. Please try again.";
        header("Location: register.php");
        exit();
    }

    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $middle_initial = filter_input(INPUT_POST, 'middle_initial', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_STRING);
    $phone_prefix = filter_input(INPUT_POST, 'phone_prefix', FILTER_SANITIZE_STRING);
    $cellphone = filter_input(INPUT_POST, 'cellphone', FILTER_SANITIZE_STRING);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $date_of_birth = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

    // Validate required fields
    if (empty($first_name) || empty($last_name)) {
        $_SESSION['register_error'] = "First name and last name are required.";
        header("Location: register.php");
        exit();
    }

    // Validate and format phone number
    if (empty($cellphone)) {
        $_SESSION['register_error'] = "Cellphone number is required.";
        header("Location: register.php");
        exit();
    }

    // Format phone number based on prefix
    if ($country === 'PH') {
        // Remove any non-digit characters from the phone number
        $cellphone = preg_replace('/[^0-9]/', '', $cellphone);
        
        // Validate phone number length (should be 10 digits for Philippines)
        if (strlen($cellphone) !== 10) {
            $_SESSION['register_error'] = "Invalid phone number format. Please enter a valid 10-digit number.";
            header("Location: register.php");
            exit();
        }

        // Format the complete phone number
        $formatted_phone = $phone_prefix . $cellphone;
    } else {
        // For other countries, just combine prefix and number
        $formatted_phone = $phone_prefix . $cellphone;
    }

    // Validate country
    if (empty($country)) {
        $_SESSION['register_error'] = "Please select your country.";
        header("Location: register.php");
        exit();
    }

    // Validate date of birth
    if (empty($date_of_birth)) {
        $_SESSION['register_error'] = "Date of birth is required.";
        header("Location: register.php");
        exit();
    }

    // Validate age (must be at least 18 years old)
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age = $today->diff($dob)->y;
    if ($age < 18) {
        $_SESSION['register_error'] = "You must be at least 18 years old to register.";
        header("Location: register.php");
        exit();
    }

    // Combine name fields
    $full_name = $first_name;
    if (!empty($middle_initial)) {
        $full_name .= " " . $middle_initial;
    }
    $full_name .= " " . $last_name;
    if (!empty($suffix)) {
        $full_name .= " " . $suffix;
    }

    // Validate password match
        if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Invalid email format.";
        header("Location: register.php");
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['register_error'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert new user with additional fields
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, cellphone, country, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $hashed_password, $formatted_phone, $country, $date_of_birth]);

                        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: ../login user/login.php");
                        exit();
            } catch (PDOException $e) {
        $_SESSION['register_error'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit();
    }
}

// Generate CSRF token for form
$csrf_token = $security->generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200" id="progress-text">
                    Step 1 of 3
                </span>
                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200" id="progress-percentage">
                    33%
                </span>
            </div>
            <div class="flex h-2 mb-4 overflow-hidden bg-blue-200 rounded">
                <div class="transition-all duration-500 ease-out bg-blue-500" style="width: 33%" id="progress-bar"></div>
            </div>
        </div>

        <div class="text-center mb-8">
            <div class="inline-block p-3 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-user-plus text-blue-500 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-blue-700">Create Account</h3>
            <p class="text-gray-600 mt-2">Join us for your perfect waterfront vacation</p>
        </div>

        <!-- Step Indicators -->
        <div class="flex justify-between mb-8 relative">
            <div class="w-full absolute top-1/2 transform -translate-y-1/2">
                <div class="h-1 bg-gray-200">
                    <div class="h-1 bg-blue-500 transition-all duration-500" style="width: 33%" id="step-progress"></div>
                </div>
            </div>
            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center z-10 transition-all duration-300" id="step1">
                <span class="text-white font-bold">1</span>
            </div>
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center z-10 transition-all duration-300" id="step2">
                <span class="text-gray-500 font-bold">2</span>
            </div>
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center z-10 transition-all duration-300" id="step3">
                <span class="text-gray-500 font-bold">3</span>
            </div>
        </div>

        <?php if(isset($_SESSION['register_error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-shake" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['register_error']; ?></span>
            </div>
            <?php unset($_SESSION['register_error']); ?>
            <?php endif; ?>

        <form method="POST" action="register.php" class="space-y-6" id="registrationForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <!-- Section 1: Personal Information -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6 form-section active">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">1</div>
                    <h4 class="text-lg font-semibold text-blue-700">Personal Information</h4>
                </div>
                <div class="space-y-4">
                    <div class="relative">
                        <input type="text" name="first_name" id="first_name" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="first_name" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            First Name
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="text" name="middle_initial" id="middle_initial" 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="middle_initial" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Middle Initial
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="text" name="last_name" id="last_name" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="last_name" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Last Name
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="text" name="suffix" id="suffix" 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="suffix" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Suffix (Jr., Sr., III, etc.)
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="date" name="date_of_birth" id="date_of_birth" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="date_of_birth" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Date of Birth
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Contact Details -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6 form-section">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">2</div>
                    <h4 class="text-lg font-semibold text-blue-700">Contact Details</h4>
                </div>
                <div class="space-y-4">
                    <div class="relative">
                        <select name="country" id="country" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                            <option value=""></option>
                            <option value="PH">Philippines</option>
                            <option value="US">United States</option>
                            <option value="GB">United Kingdom</option>
                            <option value="CA">Canada</option>
                            <option value="AU">Australia</option>
                            <option value="SG">Singapore</option>
                            <option value="JP">Japan</option>
                            <option value="KR">South Korea</option>
                            <option value="CN">China</option>
                            <option value="HK">Hong Kong</option>
                            <option value="TW">Taiwan</option>
                            <option value="MY">Malaysia</option>
                            <option value="ID">Indonesia</option>
                            <option value="TH">Thailand</option>
                            <option value="VN">Vietnam</option>
                        </select>
                        <label for="country" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Select a country
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-globe text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="flex">
                            <select name="phone_prefix" id="phone_prefix" required 
                                class="w-24 px-2 py-3 text-base border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="+63">+63</option>
                                <option value="09">09</option>
                            </select>
                            <input type="tel" name="cellphone" id="cellphone" required 
                                class="block w-full px-10 py-3 text-base border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cellphone Number">
                        </div>
                        <div class="absolute inset-y-0 left-24 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400 text-lg"></i>
                        </div>
                    </div>

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
            </div>

            <!-- Section 3: Account Details -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6 form-section">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">3</div>
                    <h4 class="text-lg font-semibold text-blue-700">Account Details</h4>
                </div>
                <div class="space-y-4">
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
                    </div>

                    <div class="relative">
                        <input type="password" name="confirm_password" id="confirm_password" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="confirm_password" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Confirm Password
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" id="prevBtn" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all duration-300 hidden">
                        <i class="fas fa-arrow-left mr-2"></i>Previous
                    </button>
                    <button type="button" id="nextBtn" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-300">
                        Next<i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="w-full px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-300 hidden">
                        <i class="fas fa-check-circle mr-2"></i>Complete Registration
                    </button>
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
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
            animation: wave 20s linear infinite;
        }

        .wave1 {
            bottom: 0;
            opacity: 0.3;
        }

        .wave2 {
            bottom: 10px;
            opacity: 0.2;
            animation-delay: -5s;
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
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const sections = document.querySelectorAll('.form-section');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const progressPercentage = document.getElementById('progress-percentage');
            const stepProgress = document.getElementById('step-progress');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const stepIndicators = [
                document.getElementById('step1'),
                document.getElementById('step2'),
                document.getElementById('step3')
            ];

            let currentSection = 0;

            // Initialize form
            sections[currentSection].classList.add('active');
            updateProgress();

            // Next button click handler
            nextBtn.addEventListener('click', () => {
                if (validateSection(currentSection)) {
                    sections[currentSection].classList.remove('active');
                    currentSection++;
                    sections[currentSection].classList.add('active');
                    updateProgress();
                }
            });

            // Previous button click handler
            prevBtn.addEventListener('click', () => {
                sections[currentSection].classList.remove('active');
                currentSection--;
                sections[currentSection].classList.add('active');
                updateProgress();
            });

            // Update progress indicators
            function updateProgress() {
                const progress = ((currentSection + 1) / sections.length) * 100;
                progressBar.style.width = `${progress}%`;
                stepProgress.style.width = `${progress}%`;
                progressText.textContent = `Step ${currentSection + 1} of ${sections.length}`;
                progressPercentage.textContent = `${Math.round(progress)}%`;

                // Update step indicators
                stepIndicators.forEach((step, index) => {
                    if (index <= currentSection) {
                        step.classList.remove('bg-gray-200');
                        step.classList.add('bg-blue-500');
                        step.querySelector('span').classList.remove('text-gray-500');
                        step.querySelector('span').classList.add('text-white');
                    } else {
                        step.classList.remove('bg-blue-500');
                        step.classList.add('bg-gray-200');
                        step.querySelector('span').classList.remove('text-white');
                        step.querySelector('span').classList.add('text-gray-500');
                    }
                });

                // Show/hide buttons
                prevBtn.style.display = currentSection === 0 ? 'none' : 'block';
                nextBtn.style.display = currentSection === sections.length - 1 ? 'none' : 'block';
                submitBtn.style.display = currentSection === sections.length - 1 ? 'block' : 'none';
            }

            // Validate current section
            function validateSection(sectionIndex) {
                const currentFields = sections[sectionIndex].querySelectorAll('input[required], select[required]');
                let isValid = true;

                currentFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.classList.add('border-red-500');
                        showTooltip(field, 'This field is required');
                    } else {
                        field.classList.remove('border-red-500');
                        hideTooltip(field);
                    }
                });

                if (!isValid) {
                    sections[sectionIndex].classList.add('animate-shake');
                    setTimeout(() => {
                        sections[sectionIndex].classList.remove('animate-shake');
                    }, 500);
                }

                return isValid;
            }

            // Show tooltip
            function showTooltip(element, message) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltiptext';
                tooltip.textContent = message;
                element.parentElement.classList.add('tooltip');
                element.parentElement.appendChild(tooltip);
            }

            // Hide tooltip
            function hideTooltip(element) {
                const tooltip = element.parentElement.querySelector('.tooltiptext');
                if (tooltip) {
                    element.parentElement.classList.remove('tooltip');
                    tooltip.remove();
                }
            }

            // Password match validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords don't match");
                    showTooltip(confirmPassword, "Passwords don't match");
                } else {
                    confirmPassword.setCustomValidity('');
                    hideTooltip(confirmPassword);
                }
            }
            
            password.addEventListener('change', validatePassword);
            confirmPassword.addEventListener('keyup', validatePassword);
        });
    </script>
</body>
</html>
