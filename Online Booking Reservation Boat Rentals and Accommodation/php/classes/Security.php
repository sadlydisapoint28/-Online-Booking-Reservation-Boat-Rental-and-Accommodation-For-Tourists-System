<?php
class Security {
    private $pdo;
    private $maxLoginAttempts = 5;
    private $lockoutTime = 1800; // 30 minutes
    private $tokenExpiry = 1800; // 30 minutes
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function isIPBlocked($ip) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$ip, $this->lockoutTime]);
        return $stmt->fetchColumn() >= $this->maxLoginAttempts;
    }
    
    public function checkRateLimit($ip) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 60 SECOND)");
        $stmt->execute([$ip]);
        return $stmt->fetchColumn() < 5; // 5 attempts per minute
    }
    
    public function logLoginAttempt($ip, $success) {
        $stmt = $this->pdo->prepare("INSERT INTO login_attempts (ip_address, success, attempt_time) VALUES (?, ?, NOW())");
        return $stmt->execute([$ip, $success]);
    }
    
    public function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = md5(uniqid(mt_rand(), true));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }
    
    public function validateCSRFToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time'])) {
            return false;
        }

        if (time() - $_SESSION['csrf_token_time'] > $this->tokenExpiry) {
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
            return false;
        }

        return $_SESSION['csrf_token'] === $token;
    }
    
    public function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public function validatePassword($password) {
        return strlen($password) >= 8 && 
               preg_match('/[0-9]/', $password) && 
               preg_match('/[a-zA-Z]/', $password);
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function checkSessionTimeout() {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $this->tokenExpiry)) {
            session_unset();
            session_destroy();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
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
} 