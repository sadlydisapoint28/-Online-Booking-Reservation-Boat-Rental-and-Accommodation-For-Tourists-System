<?php
class Security {
    private $pdo;
    private $maxAttempts = 5;
    private $lockoutTime = 1800; // 30 minutes in seconds
    private $tokenExpiry = 1800; // 30 minutes
    
    public function __construct($pdo = null) {
        if ($pdo) {
            $this->pdo = $pdo;
        } else {
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
                
                $this->pdo = new PDO($dsn, $user, $pass, $options);
                
                // Create the login_attempts table if it doesn't exist
                $this->createLoginAttemptsTable();
            } catch (PDOException $e) {
                error_log("Database connection error: " . $e->getMessage());
                throw new Exception("Database connection failed");
            }
        }
    }
    
    private function createLoginAttemptsTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS login_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ip_address VARCHAR(45) NOT NULL,
                attempts INT NOT NULL DEFAULT 1,
                last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX (ip_address)
            )";
            
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            error_log("Table creation error: " . $e->getMessage());
        }
    }
    
    public function isIPBlocked($ip) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as attempts, MAX(attempt_time) as last_attempt 
                FROM login_attempts 
                WHERE ip_address = ? AND success = 0
            ");
            $stmt->execute([$ip]);
            $result = $stmt->fetch();

            if ($result['attempts'] >= $this->maxAttempts) {
                $lastAttempt = strtotime($result['last_attempt']);
                if (time() - $lastAttempt < $this->lockoutTime) {
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("IP block check error: " . $e->getMessage());
            return false;
        }
    }
    
    public function checkRateLimit($ip) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as attempts 
                FROM login_attempts 
                WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            $stmt->execute([$ip]);
            $result = $stmt->fetch();

            return $result['attempts'] < $this->maxAttempts;
        } catch (PDOException $e) {
            error_log("Rate limit check error: " . $e->getMessage());
            return true;
        }
    }
    
    public function logLoginAttempt($ip, $email, $success) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO login_attempts (ip_address, email, success, attempt_time)
                VALUES (?, ?, ?, NOW())
            ");
            return $stmt->execute([$ip, $email, $success ? 1 : 0]);
        } catch (PDOException $e) {
            error_log("Login attempt logging error: " . $e->getMessage());
            return false;
        }
    }
    
    public function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public function validatePassword($password) {
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
        return strlen($password) >= 8 
            && preg_match('/[A-Z]/', $password) 
            && preg_match('/[a-z]/', $password) 
            && preg_match('/[0-9]/', $password);
    }
    
    public function generateToken() {
        return bin2hex(random_bytes(32));
    }
    
    public function verifyToken($token, $storedToken) {
        return hash_equals($storedToken, $token);
    }
    
    public function checkSessionTimeout() {
        $timeout = 30 * 60; // 30 minutes
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            session_unset();
            session_destroy();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function preventXSS($data) {
        if (is_array($data)) {
            return array_map([$this, 'preventXSS'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    public function validatePhone($phone) {
        // Basic phone number validation (can be customized based on your needs)
        return preg_match('/^[0-9]{10,15}$/', $phone);
    }
    
    public function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    
    public function validateTime($time) {
        $t = DateTime::createFromFormat('H:i', $time);
        return $t && $t->format('H:i') === $time;
    }
    
    public function validateBookingDate($date) {
        $bookingDate = new DateTime($date);
        $today = new DateTime();
        return $bookingDate >= $today;
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function generateRememberToken() {
        return md5(uniqid(mt_rand(), true));
    }
    
    public function storeRememberToken($userId, $token, $expires) {
        $stmt = $this->pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $token, $expires]);
    }
    
    public function validateRememberToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM remember_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }
    
    public function validatePasswordStrength($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        
        if (!preg_match("/[A-Z]/", $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        
        if (!preg_match("/[a-z]/", $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        
        if (!preg_match("/[0-9]/", $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        if (!preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)) {
            $errors[] = "Password must contain at least one special character";
        }
        
        return $errors;
    }
    
    /**
     * Get client IP address
     * 
     * @return string The client IP address
     */
    public function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP from shared internet
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP from proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // Direct IP
            return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
    }
} 