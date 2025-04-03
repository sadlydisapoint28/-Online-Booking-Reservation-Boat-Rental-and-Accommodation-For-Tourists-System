<?php
require_once '../../config/database.php';
require_once '../../classes/Auth.php';
require_once '../../classes/Security.php';
require_once '../../classes/Email.php';

// Set session cookie parameters for better security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();

// Initialize classes
$auth = new Auth($pdo);
$security = new Security($pdo);
$email = new Email();

// Check if already logged in as admin
if (!$auth->isAdmin()) {
    header("Location: ../../pages/login admin/login.php");
    exit;
}

// Check if admin limit is reached
$admin_limit_reached = $auth->isAdminLimitReached();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
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
            throw new Exception("Admin must be at least 18 years old");
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email_address]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Email already registered");
        }
        
        // Check if admin limit is reached
        if ($auth->isAdminLimitReached()) {
            throw new Exception("Maximum admin limit (3) has been reached");
        }

        // Generate verification token
        $verification_token = bin2hex(random_bytes(32));
        $verification_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert admin into database
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone_number, date_of_birth, age, gender, nationality, address, user_type, verification_token, verification_expires, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'admin', ?, ?, 1)");
        $stmt->execute([$full_name, $email_address, $hashed_password, $phone_number, $date_of_birth, $age, $gender, $nationality, $address, $verification_token, $verification_expires]);

        // Get user ID
        $user_id = $pdo->lastInsertId();

        // Log successful admin creation
        $security->logActivity($_SERVER['REMOTE_ADDR'], 'admin_creation', 'success', 'Admin account created: ' . $email_address);

        // Set success message
        $_SESSION['success_message'] = 'Admin account created successfully.';
        
        // Redirect back to admin page
        header("Location: admin.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .input-field {
            @apply mb-4;
        }
        .input-field label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }
        .input-field input, .input-field select {
            @apply w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500;
        }
        .btn-primary {
            @apply bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
            <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                <div class="max-w-md mx-auto">
                    <div class="text-center">
                        <h1 class="text-2xl font-semibold text-gray-900">Create Admin Account</h1>
                        <p class="mt-2 text-gray-600">Add a new administrator to the system</p>
                    </div>
                    
                    <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    
                    <?php if ($admin_limit_reached): ?>
                    <div class="mt-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">
                            <strong>Warning:</strong> Maximum admin limit (3) has been reached. 
                            You must remove an existing admin before adding a new one.
                        </span>
                    </div>
                    <?php else: ?>
                    
                    <form id="adminForm" method="POST" class="mt-8 space-y-6">
                        <div class="rounded-md shadow-sm -space-y-px">
                            <h2 class="font-medium text-lg text-gray-900 mb-4">Personal Information</h2>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="input-field">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" required>
                                </div>
                                
                                <div class="input-field">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="input-field">
                                    <label for="middle_initial">Middle Initial</label>
                                    <input type="text" id="middle_initial" name="middle_initial">
                                </div>
                                
                                <div class="input-field">
                                    <label for="suffix">Suffix</label>
                                    <input type="text" id="suffix" name="suffix" placeholder="Optional">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="input-field">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="non_binary">Non-binary</option>
                                        <option value="other">Other</option>
                                        <option value="prefer_not_to_say">Prefer not to say</option>
                                    </select>
                                </div>
                                
                                <div class="input-field">
                                    <label for="nationality">Nationality</label>
                                    <input type="text" id="nationality" name="nationality" required>
                                </div>
                            </div>
                            
                            <h2 class="font-medium text-lg text-gray-900 mt-8 mb-4">Contact Information</h2>
                            
                            <div class="input-field">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="input-field">
                                <label for="phone_number">Phone Number (Philippine format)</label>
                                <div class="flex items-center">
                                    <select id="phone_prefix" class="w-24 h-10 pl-2 pr-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="09">09</option>
                                        <option value="+63">+63</option>
                                    </select>
                                    <input type="text" id="phone_number_input" class="flex-1 h-10 pl-3 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="9123456789" maxlength="10" pattern="[0-9]{9,10}" required>
                                    <input type="hidden" id="full_phone_number" name="phone_number">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Format: 09XXXXXXXXX or +63XXXXXXXXXX</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="input-field">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" required>
                                </div>
                                
                                <div class="input-field">
                                    <label for="age">Age</label>
                                    <input type="number" id="age" name="age" min="18" readonly>
                                </div>
                            </div>
                            
                            <div class="input-field">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" required>
                            </div>
                            
                            <h2 class="font-medium text-lg text-gray-900 mt-8 mb-4">Security</h2>
                            
                            <div class="input-field">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" minlength="8" required>
                            </div>
                            
                            <div class="input-field">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <a href="admin.php" class="text-indigo-600 hover:text-indigo-500">
                                Back to Dashboard
                            </a>
                            <button type="submit" class="btn-primary">
                                Create Admin Account
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
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
        
        // Form validation
        document.getElementById('adminForm').addEventListener('submit', function(e) {
            // Make sure the phone number is formatted correctly
            updatePhoneNumber();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Passwords do not match'
                });
                return false;
            }
            
            const age = parseInt(document.getElementById('age').value);
            if (isNaN(age) || age < 18) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Admin must be at least 18 years old'
                });
                return false;
            }
        });
        
        // Show success message if set
        <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo $_SESSION['success_message']; ?>'
        });
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html> 