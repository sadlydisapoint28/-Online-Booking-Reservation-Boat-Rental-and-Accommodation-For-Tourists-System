<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

// Set headers for JSON response
header('Content-Type: application/json');

// Get raw POST data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Validate data
if (!isset($data['name']) || !isset($data['email']) || !isset($data['password']) || 
    !isset($data['gender']) || !isset($data['nationality']) || !isset($data['address']) || 
    !isset($data['phone_number']) || !isset($data['age']) || !isset($data['first_name']) || 
    !isset($data['last_name'])) {
    $response['message'] = 'Missing required fields';
    echo json_encode($response);
    exit;
}

// Sanitize inputs
$name = filter_var($data['name'], FILTER_SANITIZE_STRING);
$first_name = filter_var($data['first_name'], FILTER_SANITIZE_STRING);
$last_name = filter_var($data['last_name'], FILTER_SANITIZE_STRING);
$middle_initial = filter_var($data['middle_initial'] ?? '', FILTER_SANITIZE_STRING);
$suffix = filter_var($data['suffix'] ?? '', FILTER_SANITIZE_STRING);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = $data['password'];
$gender = filter_var($data['gender'], FILTER_SANITIZE_STRING);
$nationality = filter_var($data['nationality'], FILTER_SANITIZE_STRING);
$address = filter_var($data['address'], FILTER_SANITIZE_STRING);
$phone_number = filter_var($data['phone_number'], FILTER_SANITIZE_STRING);
$age = filter_var($data['age'], FILTER_SANITIZE_NUMBER_INT);

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Invalid email format';
    echo json_encode($response);
    exit;
}

// Validate age
if (!is_numeric($age) || $age < 18) {
    $response['message'] = 'Admin must be at least 18 years old';
    echo json_encode($response);
    exit;
}

// Validate phone number (Philippines format +63XXXXXXXXXX or 09XXXXXXXXX)
if (!preg_match('/^(\+63|09)\d{9,10}$/', $phone_number)) {
    $response['message'] = 'Invalid Philippines phone number format';
    echo json_encode($response);
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn() > 0;
    
    if ($emailExists) {
        $response['message'] = 'Email already exists';
        echo json_encode($response);
        exit;
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new admin into database
    $stmt = $pdo->prepare("INSERT INTO users (full_name, first_name, last_name, middle_initial, suffix, email, password, gender, nationality, address, phone_number, age, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'admin')");
    $result = $stmt->execute([$name, $first_name, $last_name, $middle_initial, $suffix, $email, $hashedPassword, $gender, $nationality, $address, $phone_number, $age]);
    
    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Admin account created successfully';
    } else {
        $response['message'] = 'Failed to create admin account';
    }
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    error_log('Admin creation error: ' . $e->getMessage());
}

echo json_encode($response);
exit; 