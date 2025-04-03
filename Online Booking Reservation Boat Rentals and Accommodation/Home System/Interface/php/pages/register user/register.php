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
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, date_of_birth, age, gender, nationality, address, drink_preference, user_type, verification_token, verification_expires, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([$full_name, $email_address, $hashed_password, $date_of_birth, $age, $gender, $nationality, $address, $drink_preference, $user_type, $verification_token, $verification_expires]);

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
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = $user_type;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email_address;
            $_SESSION['success_message'] = 'Registration successful! Welcome to Carles Tourism.';
            header("Location: ../../../Dashboards/User Dashboard/userdashboard.php");
            exit;
        } else {
            // AJAX submission - return JSON
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = $user_type;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email_address;
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true, 
                'message' => 'Registration successful! Welcome to Carles Tourism.'
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
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .register-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            position: relative;
        }
        .card-accent {
            position: absolute;
            height: 100%;
            width: 8px;
            left: 0;
            top: 0;
            background: linear-gradient(to bottom, #3b82f6, #60a5fa);
            border-radius: 15px 0 0 15px;
        }
        .step-indicators-row {
            display: flex;
            justify-content: space-between;
            margin: 0 0 20px 0;
            position: relative;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            width: 50%;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #60a5fa;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .step.active .step-circle {
            background: #3b82f6;
        }
        .step.completed .step-circle {
            background: #10b981;
        }
        .step-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #4b5563;
        }
        .form-step {
            display: none;
            padding: 15px;
        }
        .form-step.active {
            display: block;
        }
        .input-field {
            margin-bottom: 15px;
        }
        .input-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #4b5563;
            font-size: 0.9rem;
        }
        .input-field input,
        .input-field select,
        .input-field textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .input-field input:focus,
        .input-field select:focus,
        .input-field textarea:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }
        .btn-prev,
        .btn-next,
        .btn-submit {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-prev {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }
        .btn-next {
            background: #3b82f6;
            color: white;
        }
        .btn-submit {
            background: #10b981;
            color: white;
        }
        .btn-prev:hover {
            background: #e5e7eb;
        }
        .btn-next:hover {
            background: #2563eb;
        }
        .btn-submit:hover {
            background: #059669;
        }
        .step-title {
            color: #3b82f6;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        .grid {
            display: grid;
            gap: 15px;
        }
        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        .flex {
            display: flex;
        }
        .justify-between {
            justify-content: space-between;
        }
        .justify-end {
            justify-content: flex-end;
        }
        .mt-4 {
            margin-top: 1rem;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .text-center {
            text-align: center;
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .text-gray-500 {
            color: #6b7280;
        }
        #error-message {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
        }
        .progress-container {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin: 10px 0;
        }
        .progress-bar {
            height: 100%;
            background: #3b82f6;
            border-radius: 2px;
            transition: width 0.3s;
        }
        @media (max-width: 768px) {
            .register-wrapper {
                padding: 10px;
            }
            .register-card {
                padding: 15px;
            }
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
            .step-label {
                font-size: 0.7rem;
            }
        }
        .input-field.error input,
        .input-field.error select,
        .input-field.error textarea {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: none;
        }
        .input-field.error .error-message {
            display: block;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="register-wrapper">
        <div class="register-card">
            <div class="card-accent"></div>
            
            <!-- Updated Header with Logo -->
            <div class="text-center mb-6">
                <img src="../../../img/timbook-carles-tourism.png" alt="Carles Tourism Logo" class="h-24 mx-auto mb-2">
                <h1 class="text-3xl font-bold text-blue-800">Welcome to Carles Tourism</h1>
                <p class="text-blue-600">Experience the beauty of Carles, Iloilo</p>
            </div>

            <div id="error-message" role="alert">
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
                    <div class="step-label">Set Password</div>
            </div>
        </div>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar" id="registration-progress" style="width: 50%"></div>
            </div>

            <form id="registerForm" method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <!-- Step 1: Personal Information -->
                <div class="form-step active" id="step1">
                    <h3 class="step-title">Personal Information</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-field">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" required placeholder="Enter your first name" pattern="[A-Za-z ]+" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                        </div>
                        <div class="input-field">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" required placeholder="Enter your last name" pattern="[A-Za-z ]+" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-field">
                            <label for="middle_initial">Middle Initial</label>
                            <input type="text" name="middle_initial" id="middle_initial" maxlength="1" placeholder="M" pattern="[A-Za-z]" oninput="this.value = this.value.replace(/[^A-Za-z]/g, '')">
                        </div>
                        <div class="input-field">
                            <label for="suffix">Suffix</label>
                            <select name="suffix" id="suffix" class="w-full h-10 border rounded-md bg-white px-2">
                                <option value="None">None</option>
                                <option value="Jr">Jr.</option>
                                <option value="Sr">Sr.</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="other_suffix" name="other_suffix" placeholder="Please specify your suffix" class="w-full h-10 border rounded-md mt-2 px-2 hidden">
                </div>
            </div>

                    <div class="input-field">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" required placeholder="example@email.com">
                    </div>

                    <div class="input-field">
                        <label for="phone_number">Phone Number</label>
                        <div class="flex items-center gap-1">
                            <select id="phone_prefix" class="w-16 h-10 border rounded-md bg-white px-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="09">09</option>
                                <option value="+63">+63</option>
                            </select>
                            <input type="number" 
                                   name="phone_number" 
                                   id="phone_number_input" 
                                   class="w-48 h-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   maxlength="9" 
                                   required 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 9) this.value = this.value.slice(0,9);"
                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-field">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" 
                                   name="date_of_birth" 
                                   id="date_of_birth" 
                                   required 
                                   class="w-full h-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="input-field">
                            <label for="age">Age</label>
                            <input type="number" 
                                   name="age" 
                                   id="age" 
                                   min="18" 
                                   max="100" 
                                   class="w-full h-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-field">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" required class="w-full h-10 border rounded-md bg-white px-2">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="other_gender" name="other_gender" placeholder="Please specify your gender" class="w-full h-10 border rounded-md mt-2 px-2 hidden">
                </div>
                        <div class="input-field">
                            <label for="nationality">Nationality</label>
                            <select name="nationality" id="nationality" required class="w-full h-10 border rounded-md bg-white px-2">
                                <option value="">Select Nationality</option>
                                <option value="Filipino">Filipino</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="other_nationality" name="other_nationality" placeholder="Please specify your nationality" class="w-full h-10 border rounded-md mt-2 px-2 hidden">
                        </div>
                            </div>

                    <div class="input-field">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" required placeholder="Enter your complete address" class="w-full h-20 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 p-2"></textarea>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn-next">
                            Continue to Set Password <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                        </div>
                    </div>

                <!-- Step 2: Set Password -->
                <div class="form-step" id="step2">
                    <h3 class="step-title">Set Password</h3>
                    
                    <div class="input-field">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required placeholder="Enter at least 8 characters">
                    </div>

                    <div class="input-field">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required placeholder="Re-enter your password">
                </div>

                    <div class="input-field">
                        <label class="flex items-center">
                            <input type="checkbox" id="request_admin" name="request_admin" value="yes" class="mr-2">
                            <span>Request Admin Access</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Note: Limited to 3 admin accounts. Requires approval.</p>
                </div>

                    <div class="flex justify-between mt-4">
                        <button type="button" class="btn-prev">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-user-plus mr-2"></i> Complete Registration
                    </button>
                </div>
            </div>
        </form>

            <div class="mt-4 text-center">
                <a href="../interface.php" class="text-sm text-gray-500 hover:text-indigo-500">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Home
                </a>
                <span class="mx-2 text-gray-300">|</span>
                <a href="../login user/login.php" class="text-sm text-gray-500 hover:text-indigo-500">
                    Already have an account? Login <i class="fas fa-sign-in-alt ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add validation styles
            const style = document.createElement('style');
            style.textContent = `
                .input-field.error input,
                .input-field.error select,
                .input-field.error textarea {
                    border-color: #ef4444;
                    background-color: #fef2f2;
                }
                .error-message {
                    color: #ef4444;
                    font-size: 0.75rem;
                    margin-top: 0.25rem;
                }
            `;
            document.head.appendChild(style);

            // Function to update progress
            function updateProgress(step) {
                const progress = document.getElementById('registration-progress');
                const percentage = (step === 1) ? '50%' : '100%';
                progress.style.width = percentage;

                // Update step indicators
                document.querySelectorAll('.step').forEach((el, index) => {
                    if (index + 1 === step) {
                        el.classList.add('active');
                    } else {
                        el.classList.remove('active');
                    }
                });
            }

            // Function to show/hide steps
            function showStep(step) {
                document.querySelectorAll('.form-step').forEach(el => {
                    el.classList.remove('active');
                });
                document.getElementById('step' + step).classList.add('active');
                updateProgress(step);
            }

            // Validation function for required fields
            function validateStepFields(stepNumber) {
                const step = document.getElementById('step' + stepNumber);
                const requiredFields = step.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;
                let emptyFields = [];

                requiredFields.forEach(field => {
                    const inputField = field.closest('.input-field');
                    const errorMessage = inputField.querySelector('.error-message') || document.createElement('div');
                    
                    if (!errorMessage.classList.contains('error-message')) {
                        errorMessage.className = 'error-message';
                        inputField.appendChild(errorMessage);
                    }

                    // Clear previous error state
                    inputField.classList.remove('error');
                    errorMessage.style.display = 'none';

                    // Validate empty fields
                    if (!field.value.trim()) {
                        isValid = false;
                        inputField.classList.add('error');
                        errorMessage.textContent = 'This field is required';
                        errorMessage.style.display = 'block';
                        const label = inputField.querySelector('label');
                        emptyFields.push(label ? label.textContent : field.name);
                    }

                    // Additional validation for specific fields
                    if (field.type === 'email' && field.value.trim()) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(field.value)) {
                            isValid = false;
                            inputField.classList.add('error');
                            errorMessage.textContent = 'Please enter a valid email address';
                            errorMessage.style.display = 'block';
                        }
                    }

                    if (field.id === 'phone_number_input' && field.value.trim()) {
                        if (field.value.length !== 9) {
                            isValid = false;
                            inputField.classList.add('error');
                            errorMessage.textContent = 'Phone number must be 9 digits';
                            errorMessage.style.display = 'block';
                        }
                    }

                    // Password validation in step 2
                    if (stepNumber === 2) {
                        if (field.id === 'password' && field.value.length < 8) {
                            isValid = false;
                            inputField.classList.add('error');
                            errorMessage.textContent = 'Password must be at least 8 characters';
                            errorMessage.style.display = 'block';
                        }
                        if (field.id === 'confirm_password') {
                            const password = document.getElementById('password').value;
                            if (field.value !== password) {
                                isValid = false;
                                inputField.classList.add('error');
                                errorMessage.textContent = 'Passwords do not match';
                                errorMessage.style.display = 'block';
                            }
                        }
                    }
                });

                if (!isValid && emptyFields.length > 0) {
                    Swal.fire({
                        title: 'Required Fields Empty',
                        html: 'Please fill in the following fields:<br><br>' + emptyFields.join('<br>'),
                        icon: 'warning',
                        confirmButtonColor: '#3b82f6'
                    });
                }

                return isValid;
            }

            // Navigation functions
            function nextStep(current, next) {
                if (validateStepFields(current)) {
                    showStep(next);
                }
            }

            function prevStep(current, prev) {
                showStep(prev);
            }

            // Date of birth and age calculation
            const dobInput = document.getElementById('date_of_birth');
            const ageInput = document.getElementById('age');

            // Set max date to 18 years ago
            const today = new Date();
            const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            dobInput.max = maxDate.toISOString().split('T')[0];

            function calculateAge(birthDate) {
                const today = new Date();
                const dob = new Date(birthDate);
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                return age;
            }

            function calculateDateOfBirth(age) {
                if (!age || age < 18 || age > 100) return '';
                
                const today = new Date();
                // Set birth date to today's date minus the age in years
                let birthYear = today.getFullYear() - age;
                let birthDate = new Date(birthYear, today.getMonth(), today.getDate());
                
                // Ensure the calculated age matches the input age
                let calculatedAge = calculateAge(birthDate);
                if (calculatedAge > age) {
                    // If calculated age is too high, add one year to birth year
                    birthYear--;
                    birthDate = new Date(birthYear, today.getMonth(), today.getDate());
                }
                
                return birthDate.toISOString().split('T')[0];
            }

            // Calculate age when date of birth changes
            dobInput.addEventListener('change', function() {
                if (this.value) {
                    const age = calculateAge(this.value);
                    if (age >= 18 && age <= 100) {
                        ageInput.value = age;
                    } else {
                        Swal.fire({
                            title: 'Age Restriction',
                            text: age < 18 ? 'You must be at least 18 years old to register.' : 'Please enter a valid age (up to 100 years).',
                            icon: 'warning',
                            confirmButtonColor: '#3b82f6'
                        });
                        this.value = '';
                        ageInput.value = '';
                    }
                }
            });

            // Calculate date of birth when age changes
            ageInput.addEventListener('input', function() {
                const age = parseInt(this.value);
                if (!isNaN(age)) {
                    if (age >= 18 && age <= 100) {
                        const dob = calculateDateOfBirth(age);
                        if (dob) {
                            dobInput.value = dob;
                            // Remove any error styling
                            const inputField = this.closest('.input-field');
                            inputField.classList.remove('error');
                            const errorMessage = inputField.querySelector('.error-message');
                            if (errorMessage) {
                                errorMessage.style.display = 'none';
                            }
                        }
                    } else {
                        Swal.fire({
                            title: age < 18 ? 'Age Restriction' : 'Invalid Age',
                            text: age < 18 ? 'You must be at least 18 years old to register.' : 'Please enter an age between 18 and 100.',
                            icon: 'warning',
                            confirmButtonColor: '#3b82f6'
                        });
                        this.value = '';
                        dobInput.value = '';
                    }
                    } else {
                    dobInput.value = '';
                }
            });

            // Handle "Other" options
            function setupOtherField(selectId, otherId) {
                const select = document.getElementById(selectId);
                const otherInput = document.getElementById(otherId);
                
                if (!select || !otherInput) return;

                // Show/hide other input when select changes
                select.addEventListener('change', function() {
                    if (this.value === 'Other') {
                        otherInput.style.display = 'block';
                        otherInput.classList.remove('hidden');
                        otherInput.required = true;
                        otherInput.value = ''; // Clear previous value
                        setTimeout(() => otherInput.focus(), 0); // Focus the input
                } else {
                        otherInput.style.display = 'none';
                        otherInput.classList.add('hidden');
                        otherInput.required = false;
                        otherInput.value = '';
                    }
                });

                // Check initial state
                if (select.value === 'Other') {
                    otherInput.style.display = 'block';
                    otherInput.classList.remove('hidden');
                    otherInput.required = true;
                }
            }

            // Setup all "Other" fields
            setupOtherField('suffix', 'other_suffix');
            setupOtherField('gender', 'other_gender');
            setupOtherField('nationality', 'other_nationality');

            // Form submission handling
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate all steps before submission
                if (!validateStepFields(1) || !validateStepFields(2)) {
                    return;
                }

                // Handle "Other" options before submission
                ['suffix', 'gender', 'nationality'].forEach(field => {
                    const select = document.getElementById(field);
                    const otherInput = document.getElementById(`other_${field}`);
                    if (select && otherInput && select.value === 'Other') {
                        const otherValue = otherInput.value.trim();
                        if (otherValue) {
                            select.value = otherValue;
                        }
                    }
                });

                // Continue with form submission
                            const formData = new FormData(this);
                formData.append('form_submitted', 'true');

                fetch(form.action, {
                                method: 'POST',
                                body: formData
                            })
                .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Registration Successful!',
                            text: 'Welcome to Carles Tourism!',
                                        icon: 'success',
                            confirmButtonColor: '#3b82f6',
                            confirmButtonText: 'Continue to Dashboard'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                window.location.href = '../../../Dashboards/User Dashboard/userdashboard.php';
                                        }
                                    });
                                } else {
                                    Swal.fire({
                            title: 'Error!',
                            text: data.message,
                                        icon: 'error',
                            confirmButtonColor: '#3b82f6',
                            confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                                    icon: 'error',
                        confirmButtonColor: '#3b82f6',
                        confirmButtonText: 'OK'
                                });
                            });
            });

            // Add click event listeners to continue buttons
            document.querySelectorAll('.btn-next').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const currentStep = parseInt(this.closest('.form-step').id.replace('step', ''));
                    const nextStepNum = currentStep + 1;
                    nextStep(currentStep, nextStepNum);
                });
            });

            // Add click event listeners to back buttons
            document.querySelectorAll('.btn-prev').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const currentStep = parseInt(this.closest('.form-step').id.replace('step', ''));
                    const prevStepNum = currentStep - 1;
                    prevStep(currentStep, prevStepNum);
                });
            });

            // Initialize steps visibility
            updateProgress(1);
        });
    </script>
</body>
</html>
