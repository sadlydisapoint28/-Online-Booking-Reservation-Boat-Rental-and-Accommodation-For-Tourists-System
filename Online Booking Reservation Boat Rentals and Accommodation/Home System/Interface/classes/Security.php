<?php
class Security {
    private $pdo;

    public function __construct($pdo = null) {
        $this->pdo = $pdo;
    }

    public function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function isIPBlocked($ip) {
        if (!$this->pdo) return false;
        
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM blocked_ips WHERE ip_address = ? AND blocked_until > NOW()");
            $stmt->execute([$ip]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            // If table doesn't exist, return false
            if ($e->getCode() == '42S02') {
                return false;
            }
            throw $e;
        }
    }

    public function logActivity($ip, $action, $status, $message) {
        if (!$this->pdo) return;
        
        try {
            $stmt = $this->pdo->prepare("INSERT INTO activity_logs (ip_address, action, status, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$ip, $action, $status, $message]);
        } catch (PDOException $e) {
            // If table doesn't exist, just log to error log
            if ($e->getCode() == '42S02') {
                error_log("Activity log table not found: " . $message);
            } else {
                throw $e;
            }
        }
    }

    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
} 