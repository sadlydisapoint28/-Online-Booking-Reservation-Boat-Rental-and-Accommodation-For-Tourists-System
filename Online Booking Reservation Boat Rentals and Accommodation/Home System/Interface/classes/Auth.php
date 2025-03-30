<?php
class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (full_name, email, password, phone_number, date_of_birth, user_type, verification_token, verification_expires, is_verified) VALUES (?, ?, ?, ?, ?, 'customer', ?, ?, 0)");
            return $stmt->execute([
                $data['full_name'],
                $data['email'],
                $data['password'],
                $data['phone_number'],
                $data['date_of_birth'],
                $data['verification_token'],
                $data['verification_expires']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                if (!$user['is_verified']) {
                    return ['success' => false, 'message' => 'Please verify your email first'];
                }
                return ['success' => true, 'user' => $user];
            }
            return ['success' => false, 'message' => 'Invalid email or password'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'An error occurred'];
        }
    }

    public function verifyEmail($token) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE verification_token = ? AND verification_expires > NOW() AND is_verified = 0");
            $stmt->execute([$token]);
            $user = $stmt->fetch();

            if ($user) {
                $stmt = $this->pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL, verification_expires = NULL WHERE id = ?");
                $stmt->execute([$user['id']]);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
        return true;
    }
} 