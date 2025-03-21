<?php
class Auth {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function loginUser($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                return [
                    'success' => true,
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'full_name' => $user['full_name']
                    ]
                ];
            }
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ];
        }
    }
    
    public function loginAdmin($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE email = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_role'] = $admin['role'] ?? 'admin';
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                return [
                    'success' => true,
                    'admin' => [
                        'id' => $admin['id'],
                        'email' => $admin['email'],
                        'full_name' => $admin['full_name'],
                        'role' => $admin['role'] ?? 'admin'
                    ]
                ];
            }
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        } catch (PDOException $e) {
            error_log("Admin login error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ];
        }
    }
    
    public function registerUser($name, $email, $password, $phone = null, $address = null) {
        try {
            // Check if email already exists
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return false;
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$name, $email, $hashedPassword, $phone, $address]);
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
    }
    
    public function checkSessionTimeout() {
        $timeout = 30 * 60; // 30 minutes
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            $this->logout();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: /Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/Loginup%20Admin/loginup_admin.php");
            exit();
        }
        $this->checkSessionTimeout();
    }
    
    public function requireAdmin() {
        if (!$this->isAdminLoggedIn()) {
            header("Location: /Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/Loginup%20Admin/loginup_admin.php");
            exit();
        }
        $this->checkSessionTimeout();
    }
    
    public function requireSuperAdmin() {
        if (!$this->isAdminLoggedIn() || $_SESSION['admin_role'] !== 'super_admin') {
            header("Location: /Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/Admin%20Dashboard/index.php");
            exit();
        }
        $this->checkSessionTimeout();
    }
} 