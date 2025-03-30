<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/database.php';
require_once '../../classes/Auth.php';
require_once '../../classes/Security.php';
require_once '../../classes/Email.php';

// Set session cookie parameters for better security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
ini_set('session.cookie_lifetime', 1800); // 30 minutes

session_start();

// Initialize classes
$auth = new Auth($pdo);
$security = new Security($pdo);
$email = new Email();

// Get client IP
$clientIP = $security->getClientIP();

// Check if IP is blocked
if ($security->isIPBlocked($clientIP)) {
    $_SESSION['register_error'] = "Your IP address has been blocked due to multiple failed attempts. Please try again later.";
    header("Location: register.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get client IP for logging
        $clientIP = $security->getClientIP();

        // Get form data
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $middle_initial = $_POST['middle_initial'] ?? '';
        $suffix = $_POST['suffix'] ?? '';
        
        // Combine name parts into full_name
        $full_name = trim($first_name);
        if (!empty($middle_initial)) {
            $full_name .= ' ' . $middle_initial;
        }
        $full_name .= ' ' . trim($last_name);
        if (!empty($suffix)) {
            $full_name .= ' ' . $suffix;
        }
        
        $email_address = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $age = $_POST['age'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $nationality = $_POST['nationality'] ?? '';
        $address = $_POST['address'] ?? '';
        $drink_preference = $_POST['drink_preference'] ?? '';
        
        // Get boat data if provided
        $boat_name = $_POST['boat_name'] ?? '';
        $boat_type = $_POST['boat_type'] ?? '';
        $boat_capacity = $_POST['boat_capacity'] ?? '';
        $boat_description = $_POST['boat_description'] ?? '';

        // Validate passwords match
        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match");
        }

        // Validate email format
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Validate age
        if (!is_numeric($age) || $age < 18) {
            throw new Exception("You must be at least 18 years old to register");
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email_address]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Email already registered");
        }

        // Generate verification token
        $verification_token = bin2hex(random_bytes(32));
        $verification_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Determine user type (customer or admin_pending)
        $user_type = isset($_POST['request_admin']) && $_POST['request_admin'] == 'yes' ? 'admin_pending' : 'customer';

        // Insert user into database with verification token
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone_number, date_of_birth, age, gender, nationality, address, drink_preference, user_type, verification_token, verification_expires, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([$full_name, $email_address, $hashed_password, $phone_number, $date_of_birth, $age, $gender, $nationality, $address, $drink_preference, $user_type, $verification_token, $verification_expires]);

        // Get user ID
        $user_id = $pdo->lastInsertId();

        // If boat information is provided, insert it into the boats table
        if (!empty($boat_name) && !empty($boat_type)) {
            try {
                $boat_capacity = !empty($boat_capacity) ? $boat_capacity : 0;
                $boat_stmt = $pdo->prepare("INSERT INTO boats (user_id, boat_name, boat_type, capacity, description, status) VALUES (?, ?, ?, ?, ?, 'available')");
                $boat_stmt->execute([$user_id, $boat_name, $boat_type, $boat_capacity, $boat_description]);
            } catch (PDOException $e) {
                // Log boat insertion error but continue with registration
                error_log("Boat registration error: " . $e->getMessage());
            }
        }

        // If admin request, notify existing admins
        if ($user_type === 'admin_pending') {
            // Create notification in the database
            try {
                $notif_stmt = $pdo->prepare("INSERT INTO notifications (user_id, type, message, created_at) VALUES (?, 'admin_request', ?, NOW())");
                $notif_stmt->execute([$user_id, "$full_name has requested admin access"]);
                
                // Get all current admins to email them
                $admin_stmt = $pdo->prepare("SELECT email FROM users WHERE user_type = 'admin'");
                $admin_stmt->execute();
                $admins = $admin_stmt->fetchAll(PDO::FETCH_COLUMN);
                
                // Send email to all admins
                foreach ($admins as $admin_email) {
                    $admin_subject = "New Admin Access Request";
                    $admin_body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                            <h2 style='color: #2563EB;'>New Admin Request</h2>
                            <p>$full_name ($email_address) has requested admin access to the Boat Rentals system.</p>
                            <p>Please log in to the admin dashboard to review and approve/reject this request.</p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='http://{$_SERVER['HTTP_HOST']}/Home%20System/Interface/php/pages/admin/admin.php' style='background-color: #2563EB; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Go to Admin Dashboard</a>
                            </div>
                            <hr style='border: 1px solid #eee; margin: 20px 0;'>
                            <p style='color: #666; font-size: 12px;'>This is an automated message, please do not reply to this email.</p>
                        </div>
                    ";
                    $email->send($admin_email, $admin_subject, $admin_body);
                }
            } catch (PDOException $e) {
                // Log notification error but continue with registration
                error_log("Admin notification error: " . $e->getMessage());
            }
        }

        // Send verification email
        $verification_link = "http://" . $_SERVER['HTTP_HOST'] . "/Home%20System/Interface/php/pages/verify%20email/verify.php?token=" . $verification_token;
        
        $email_subject = "Verify Your Email Address";
        $email_body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #2563EB;'>Welcome to Boat Rentals!</h2>
                <p>Dear {$full_name},</p>
                <p>Thank you for registering with us. Please verify your email address by clicking the button below:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$verification_link}' style='background-color: #2563EB; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Verify Email Address</a>
                </div>
                <p>Or copy and paste this link in your browser:</p>
                <p style='color: #666; word-break: break-all;'>{$verification_link}</p>
                <p>This verification link will expire in 24 hours.</p>
                <p>If you didn't create an account, you can safely ignore this email.</p>
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                <p style='color: #666; font-size: 12px;'>This is an automated message, please do not reply to this email.</p>
            </div>
        ";

        $email->send($email_address, $email_subject, $email_body);

        // Log successful registration
        $security->logActivity($clientIP, 'registration', 'success', 'User registered successfully');

        // Check if this is a direct form submission or AJAX
        if (isset($_POST['form_submitted']) && $_POST['form_submitted'] === 'true') {
            // Direct form submission - redirect with success message
            $_SESSION['success_message'] = 'Registration successful! Please check your email to verify your account.';
            header("Location: register.php?form_submitted=true&success=" . urlencode('Please check your email to verify your account.'));
            exit;
        } else {
            // AJAX submission - return JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true, 
            'message' => 'Registration successful! Please check your email to verify your account.'
        ]);
        exit;
        }

    } catch (PDOException $e) {
        // Log error
        $security->logActivity($clientIP, 'registration', 'error', 'Database error: ' . $e->getMessage());
        
        // Check if this is a direct form submission or AJAX
        if (isset($_POST['form_submitted']) && $_POST['form_submitted'] === 'true') {
            // Direct form submission - redirect with error
            $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
            header("Location: register.php?form_submitted=true&error=" . urlencode('Database error: ' . $e->getMessage()));
            exit;
        } else {
            // AJAX submission - return JSON
        header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
        }
    } catch (Exception $e) {
        // Log error
        $security->logActivity($clientIP, 'registration', 'error', 'General error: ' . $e->getMessage());
        
        // Check if this is a direct form submission or AJAX
        if (isset($_POST['form_submitted']) && $_POST['form_submitted'] === 'true') {
            // Direct form submission - redirect with error
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: register.php?form_submitted=true&error=" . urlencode($e->getMessage()));
            exit;
        } else {
            // AJAX submission - return JSON
        header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
        }
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
    <title>User Registration - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f1f8fc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%2393c5fd' fill-opacity='0.1'%3E%3Cpath d='M0 38.59l2.83-2.83 1.41 1.41L1.41 40H0v-1.41zM0 1.4l2.83 2.83 1.41-1.41L1.41 0H0v1.41zM38.59 40l-2.83-2.83 1.41-1.41L40 38.59V40h-1.41zM40 1.41l-2.83 2.83-1.41-1.41L38.59 0H40v1.41zM20 18.6l2.83-2.83 1.41 1.41L21.41 20l2.83 2.83-1.41 1.41L20 21.41l-2.83 2.83-1.41-1.41L18.59 20l-2.83-2.83 1.41-1.41L20 18.59z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .register-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 10px;
            z-index: 1000;
            overflow-y: auto;
        }
        .register-card {  
            background: linear-gradient(145deg, #ffffff, #f8fbff);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            border: 1px solid #e0e7ff;
            margin-top: 10px;
            max-height: 90vh;
            overflow-y: auto;
            max-width: 95%;
        }
        .card-accent {
            position: absolute;
            height: 100%;
            width: 12px;
            left: 0;
            top: 0;
            background: linear-gradient(to bottom, #4f46e5, #818cf8, #93c5fd);
        }
        .step-indicators-row {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 0.75rem 0 1.25rem;
            background-color: #f0f5ff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.15);
            padding: 15px 20px;
            z-index: 5;
            border: 2px solid #c7d2fe;
            opacity: 1;
        }
        .step-indicators-row::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 15%;
            right: 15%;
            height: 4px;
            background-color: #cbd5e1;
            transform: translateY(-50%);
            z-index: 0;
            border-radius: 2px;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            width: 33.333%;
        }
        .step-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 1.35rem;
            position: relative;
            transition: all 0.5s ease;
            margin-bottom: 0.5rem;
            color: white;
            background-color: #818cf8;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
            border: 3px solid #fff;
        }
        .step.active .step-circle {
            background-color: #4f46e5;
            transform: scale(1.15);
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.5);
        }
        .step.completed .step-circle {
            background-color: #10b981;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
        }
        .step-circle::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid transparent;
            top: 0;
            left: 0;
            transition: all 0.5s ease;
        }
        .step.active .step-circle::after {
            border-color: #4f46e5;
            transform: scale(1.2);
            opacity: 0.5;
        }
        .step.completed .step-circle::after {
            border-color: #10b981;
            transform: scale(1.2);
            opacity: 0.5;
        }
        .step-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #4b5563;
            transition: all 0.3s ease;
            text-align: center;
            margin-top: 5px;
        }
        .step.active .step-label {
            color: #4338ca;
            font-weight: 800;
            transform: scale(1.05);
            text-shadow: 0 1px 2px rgba(79, 70, 229, 0.2);
        }
        .step.completed .step-label {
            color: #059669;
            font-weight: 700;
        }
        .progress-container {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 20px 1.25rem 20px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #d1d5db;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, #6366f1, #818cf8);
            border-radius: 8px;
            transition: width 0.5s ease;
            box-shadow: 0 1px 3px rgba(99, 102, 241, 0.4);
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        .form-step {
            display: none;
            animation: fadeIn 0.5s;
            padding: 0.5rem 0.5rem;
        }
        .form-step.active {
            display: block;
        }
        .input-field {
            position: relative;
            margin-bottom: 0.85rem;
        }
        .input-field label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 600;
            color: #4b5563;
            font-size: 0.85rem;
        }
        .input-field input, 
        .input-field select, 
        .input-field textarea {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Quicksand', sans-serif;
            font-size: 0.9rem;
            background-color: #fff;
            transition: all 0.2s;
        }
        .input-field input:focus, 
        .input-field select:focus, 
        .input-field textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            outline: none;
        }
        .input-icon {
            position: absolute;
            top: 2.15rem;
            left: 0.9rem;
            color: #6366f1;
            font-size: 0.9rem;
        }
        .btn-prev, .btn-next {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s;
            cursor: pointer;
        }
        .btn-prev {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }
        .btn-prev:hover {
            background-color: #e5e7eb;
        }
        .btn-next {
            background-color: #6366f1;
            color: white;
        }
        .btn-next:hover {
            background-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(99, 102, 241, 0.25);
        }
        .btn-submit {
            background: linear-gradient(to right, #10b981, #059669);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.925rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .step-title {
            color: #4f46e5;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        .step-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 70%;
            height: 3px;
            background: linear-gradient(to right, #6366f1, #818cf8);
            border-radius: 2px;
        }
        .decoration-dot {
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(129, 140, 248, 0.1) 0%, rgba(79, 70, 229, 0.05) 50%, transparent 70%);
            z-index: -1;
        }
        .dot-1 {
            top: -75px;
            right: -75px;
        }
        .dot-2 {
            bottom: -75px;
            left: -75px;
        }
        .link-muted {
            color: #64748b;
            font-size: 0.875rem;
            transition: color 0.3s;
        }
        .link-muted:hover {
            color: #4f46e5;
        }
        .link-divider {
            color: #cbd5e1;
        }
        .text-center.mb-4 {
            margin-bottom: 1rem;
        }
        .text-xl {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4338ca;
        }
        .text-indigo-500 {
            color: #6366f1;
        }
        .space-y-5 {
            margin-top: 0;
        }
        .p-8 {
            padding: 1.5rem;
        }
        #error-message {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .flex.justify-end, .flex.justify-between {
            margin-top: 1.25rem;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="register-wrapper">
        <div class="register-card w-full max-w-2xl p-8 relative">
            <div class="card-accent"></div>
            <div class="decoration-dot dot-1"></div>
            <div class="decoration-dot dot-2"></div>
            
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Join Carles Tourism</h2>
                <p class="text-indigo-500 text-sm mt-1">Create your account</p>
            </div>

            <div id="error-message" class="hidden bg-red-50 border-l-4 border-red-500 text-red-600 p-4 rounded-md mb-6" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle"></i>
            </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium" id="error-text"></p>
        </div>
            </div>
        </div>

        <!-- Step Indicators -->
            <div class="step-indicators-row">
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Personal Info</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Boat Details</div>
            </div>
                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Set Password</div>
            </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar" id="registration-progress" style="width: 33.33%"></div>
            </div>

            <form id="registerForm" method="POST" action="" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <!-- Step 1: Personal Information -->
                <div class="form-step active" id="step1">
                    <h3 class="step-title">Personal Information</h3>
                    
                    <div class="input-field">
                        <label for="first_name">First Name</label>
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="first_name" id="first_name" required placeholder="Enter your first name">
        </div>

                    <div class="input-field">
                        <label for="last_name">Last Name</label>
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="last_name" id="last_name" required placeholder="Enter your last name">
            </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-field">
                            <label for="middle_initial">Middle Initial</label>
                            <i class="fas fa-signature input-icon"></i>
                            <input type="text" name="middle_initial" id="middle_initial" maxlength="1" placeholder="M">
                </div>

                        <div class="input-field">
                            <label for="suffix">Suffix</label>
                            <i class="fas fa-id-card input-icon"></i>
                            <select name="suffix" id="suffix">
                                <option value="">None</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-field">
                        <label for="email">Email Address</label>
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" id="email" required placeholder="email@example.com">
                    </div>

                    <div class="input-field">
                        <label for="phone_number">Phone Number (Philippine format)</label>
                        <i class="fas fa-mobile-alt input-icon"></i>
                        <div class="flex items-center">
                            <select id="phone_prefix" class="w-24 h-10 pl-2 pr-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="09">09</option>
                                <option value="+63">+63</option>
                            </select>
                            <input type="text" name="phone_number" id="phone_number_input" class="flex-1 h-10 pl-3 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="9123456789" maxlength="10" pattern="[0-9]{9,10}" required>
                            <input type="hidden" id="full_phone_number" name="phone_number">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: 09XXXXXXXXX or +63XXXXXXXXXX</p>
                    </div>

                    <div class="input-field">
                        <label for="date_of_birth">Date of Birth</label>
                        <i class="fas fa-calendar input-icon"></i>
                        <input type="date" name="date_of_birth" id="date_of_birth" required>
                    </div>

                    <div class="input-field">
                        <label for="age">Age</label>
                        <i class="fas fa-birthday-cake input-icon"></i>
                        <input type="number" name="age" id="age" required min="18" max="100" placeholder="Your current age">
            </div>

                    <div class="input-field">
                        <label for="drink_preference">SelectDrink</label>
                        <i class="fas fa-glass-martini-alt input-icon"></i>
                        <select name="drink_preference" id="drink_preference">
                            <option value="">Select Drink Preference</option>
                            <option value="gin">Gin  joke lang Tubig</option>
                            <option value="tanduay">Tanduay</option>
                            <option value="emperador">Emperador</option>
                            <option value="red_horse">Red Horse</option>
                            <option value="san_miguel">San Miguel</option>
                            <option value="none">None</option>
                        </select>
                </div>

                    <div class="input-field">
                        <label for="gender">Gender</label>
                        <i class="fas fa-venus-mars input-icon"></i>
                        <select name="gender" id="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="non_binary">Non-binary</option>
                            <option value="other">Other</option>
                            <option value="prefer_not_to_say">Prefer not to say</option>
                            <option value="crossaint">Crossaint</option>
                            <option value="email">Email</option>
                            <option value="mechanic">Mechanic</option>
                            <option value="wallmart_bag">Wallmart Bag</option>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="nationality">Nationality</label>
                        <i class="fas fa-globe input-icon"></i>
                        <select name="nationality" id="nationality" required>
                            <option value="">Select Nationality</option>
                            <option value="Filipino">Filipino</option>
                            <option value="American">American</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Japanese">Japanese</option>
                            <option value="Korean">Korean</option>
                            <option value="British">British</option>
                            <option value="Australian">Australian</option>
                            <option value="Canadian">Canadian</option>
                            <option value="Other">Other</option>
                            </select>
                        </div>

                    <div class="input-field">
                        <label for="address">Address</label>
                        <i class="fas fa-home input-icon"></i>
                        <input type="text" name="address" id="address" required placeholder="123 Main St, City, Province">
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" class="btn-next" onclick="nextStep(1, 2)">
                            Continue to Boat Details <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        </div>
                    </div>
                
                <!-- Step 2: Boat Information -->
                <div class="form-step" id="step2">
                    <h3 class="step-title">Boat Information (Optional)</h3>
                    
                    <div class="input-field">
                        <label for="boat_type">Boat Type</label>
                        <i class="fas fa-ship input-icon"></i>
                        <select name="boat_type" id="boat_type">
                            <option value="">Select Boat Type</option>
                            <option value="speedboat">Speedboat</option>
                            <option value="yacht">Yacht</option>
                            <option value="fishing_boat">Fishing Boat</option>
                            <option value="pontoon">Pontoon</option>
                            <option value="sailboat">Sailboat</option>
                            <option value="other">Other</option>
                        </select>
            </div>

                    <div class="input-field">
                        <label for="boat_name">Boat Name</label>
                        <i class="fas fa-tag input-icon"></i>
                        <input type="text" name="boat_name" id="boat_name" placeholder="e.g. Sea Breeze, Island Explorer">
                            </div>

                    <div class="input-field">
                        <label for="boat_capacity">Boat Capacity (Number of People)</label>
                        <i class="fas fa-users input-icon"></i>
                        <input type="number" name="boat_capacity" id="boat_capacity" min="1" max="100" placeholder="e.g. 10">
                    </div>

                    <div class="input-field">
                        <label for="boat_description">Boat Description</label>
                        <i class="fas fa-align-left input-icon"></i>
                        <textarea name="boat_description" id="boat_description" rows="3" placeholder="Describe your boat and its features" class="pt-3"></textarea>
                </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" class="btn-prev" onclick="prevStep(2, 1)">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Personal Info
                    </button>
                        <button type="button" class="btn-next" onclick="nextStep(2, 3)">
                            Continue to Set Password <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

                <!-- Step 3: Set Password -->
                <div class="form-step" id="step3">
                    <h3 class="step-title">Set Password</h3>
                    
                    <div class="input-field">
                        <label for="password">Password</label>
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="password" required placeholder="Create a strong password">
    </div>

                    <div class="input-field">
                        <label for="confirm_password">Confirm Password</label>
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm your password">
            </div>
            
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="request_admin" name="request_admin" value="yes" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="request_admin" class="ml-2 block text-sm text-gray-700">
                                Request Admin Access
                        </label>
                </div>
                        <p class="text-xs text-gray-500 mt-1">Check this if you want to request administrator privileges. An existing admin will need to approve your request. <strong>Note: The system is limited to a maximum of 3 admin accounts.</strong></p>
            </div>
            
                    <div class="flex justify-between mt-6">
                        <button type="button" class="btn-prev" onclick="prevStep(3, 2)">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Boat Details
                    </button>
                        <button type="submit" id="submit-btn" class="btn-submit">
                            <i class="fas fa-user-plus mr-2"></i> Complete Registration
                    </button>
                </div>
            </div>
        </form>

            <div class="mt-8 text-center flex justify-center space-x-4">
                <a href="../interface.php" class="link-muted flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Home
                </a>
                <span class="link-divider">|</span>
                <a href="../login user/login.php" class="link-muted flex items-center">
                    <i class="fas fa-sign-in-alt mr-1"></i>
                    Already have an account? Login
                </a>
        </div>
    </div>
    </div>

    <script>
        // Make step indicators clickable
        document.addEventListener('DOMContentLoaded', function() {
            // Add click events to step indicators
            document.querySelectorAll('.step').forEach(function(step) {
                step.addEventListener('click', function() {
                    const stepNum = parseInt(this.getAttribute('data-step'));
                    const currentStep = parseInt(document.querySelector('.form-step.active').id.replace('step', ''));
                    
                    // Only allow clicking on completed steps or the next available step
                    if (stepNum < currentStep || (stepNum === currentStep + 1 && validateStep(currentStep))) {
                        // Hide current step
                        document.getElementById('step' + currentStep).classList.remove('active');
                        
                        // Show clicked step
                        document.getElementById('step' + stepNum).classList.add('active');
                        
                        // Update progress
                        updateProgress(stepNum);
                        
                        // Scroll to top
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            });
            
            // Initialize steps visibility
            updateProgress(1);
        });
        
        // Validate the current step
        function validateStep(stepNum) {
            if (stepNum === 1) {
                // Validate personal info
                const firstName = document.getElementById('first_name').value.trim();
                const lastName = document.getElementById('last_name').value.trim();
                const email = document.getElementById('email').value.trim();
                const phoneInput = document.getElementById('phone_number_input').value.trim();
                const dob = document.getElementById('date_of_birth').value;
                const age = document.getElementById('age').value;
                const gender = document.getElementById('gender').value;
                const address = document.getElementById('address').value.trim();
                
                if (!firstName || !lastName || !email || !phoneInput || !dob || !age || !gender || !address) {
                    showError('Please fill in all required fields before proceeding.');
                    return false;
                }
                
                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showError('Please enter a valid email address.');
                    return false;
                }
                
                // Phone validation
                if (phoneInput.length < 9) {
                    showError('Please enter a valid phone number.');
                    return false;
                }
                
                // Update the full phone number
                updatePhoneNumber();
                
                // Age validation
                if (parseInt(age) < 18) {
                    showError('You must be at least 18 years old to register.');
                    return false;
                }
                
                return true;
            }
            
            return true; // Other steps don't need validation (boat info is optional)
        }
        
        // Simple flag to track if a form submission is in progress
        let isSubmitting = false;
        
        // Update progress bar and step indicators
        function updateProgress(stepNum) {
            const totalSteps = 3;
            const progressPercent = ((stepNum - 1) / (totalSteps - 1)) * 100;
            document.getElementById('registration-progress').style.width = progressPercent + '%';
            
            // Update step indicators
            document.querySelectorAll('.step').forEach(function(step) {
                const stepNumber = parseInt(step.getAttribute('data-step'));
                step.classList.remove('active', 'completed');
                
                if (stepNumber === stepNum) {
                    step.classList.add('active');
                } else if (stepNumber < stepNum) {
                    step.classList.add('completed');
                    const circle = step.querySelector('.step-circle');
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                } else {
                    const circle = step.querySelector('.step-circle');
                    circle.innerHTML = stepNumber;
                }
            });
        }

        // Display error message
        function showError(message) {
            const errorBox = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            errorText.textContent = message;
            errorBox.classList.remove('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorBox.classList.add('hidden');
            }, 5000);
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Switch between steps
        function nextStep(currentStep, nextStep) {
            // Validate current step
            if (currentStep === 1) {
                if (!validateStep(1)) {
                    return false;
                }
            } else if (currentStep === 2) {
                // Boat info is optional, no validation required
            }
            
            // Hide current step
            document.getElementById('step' + currentStep).classList.remove('active');
            
            // Show next step
            document.getElementById('step' + nextStep).classList.add('active');
            
            // Update progress
            updateProgress(nextStep);
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            return true;
        }

        function prevStep(currentStep, prevStep) {
            // Hide current step
            document.getElementById('step' + currentStep).classList.remove('active');
            
            // Show previous step
            document.getElementById('step' + prevStep).classList.add('active');
            
            // Update progress
            updateProgress(prevStep);
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Calculate age from date of birth
        document.getElementById('date_of_birth').addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            document.getElementById('age').value = age;
        });
        
        // Handle phone number format
        function updatePhoneNumber() {
            const prefix = document.getElementById('phone_prefix').value;
            const input = document.getElementById('phone_number_input').value.replace(/\D/g, '');
            
            // Remove the first digit if it's 0 and prefix is +63
            let phoneDigits = input;
            if (prefix === '+63' && input.startsWith('0')) {
                phoneDigits = input.substring(1);
            }
            
            // Combine prefix and number
            const fullNumber = prefix + phoneDigits;
            document.getElementById('full_phone_number').value = fullNumber;
            
            return fullNumber;
        }
        
        // Set up event listeners for phone number
        document.getElementById('phone_prefix').addEventListener('change', updatePhoneNumber);
        document.getElementById('phone_number_input').addEventListener('input', updatePhoneNumber);
        
        // Initialize the phone number field
        updatePhoneNumber();

        // Fallback for direct form submission without AJAX
        // This adds a query parameter that will show if form was directly submitted
        if (window.location.search.includes('form_submitted=true')) {
            // Check URL for error or success messages
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                showError(decodeURIComponent(urlParams.get('error')));
            } else if (urlParams.has('success')) {
                Swal.fire({
                    title: 'Registration Successful!',
                    text: decodeURIComponent(urlParams.get('success')),
                    icon: 'success',
                    confirmButtonColor: '#4f46e5',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../login user/login.php';
                    }
                });
            }
        }

        // Form submission handling
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            // Only prevent default if we haven't already started submitting
            if (!isSubmitting) {
                e.preventDefault();
                
                // Make sure the phone number is formatted correctly
                updatePhoneNumber();
                
                // Validate password step
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                if (!password || !confirmPassword) {
                    showError('Please enter a password and confirm it.');
                    return false;
                }
                
                if (password !== confirmPassword) {
                    showError('Passwords do not match.');
                    return false;
                }
                
                if (password.length < 8) {
                    showError('Password must be at least 8 characters long.');
                    return false;
                }
                
                // Show loading state
                const submitBtn = document.getElementById('submit-btn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                
                // Set the flag that we're submitting
                isSubmitting = true;
                
                // Add a hidden field to indicate this is a direct submission
                const formSubmittedInput = document.createElement('input');
                formSubmittedInput.type = 'hidden';
                formSubmittedInput.name = 'form_submitted';
                formSubmittedInput.value = 'true';
                this.appendChild(formSubmittedInput);
                
                // Submit the form directly
                    setTimeout(() => {
                    this.submit();
                    }, 500);
            }
        });
    </script>
</body>
</html>
