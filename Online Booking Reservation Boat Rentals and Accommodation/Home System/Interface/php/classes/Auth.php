<?php
class Auth {
    private $pdo;
    
    public function __construct($pdo = null) {
        if ($pdo) {
            $this->pdo = $pdo;
        } else {
            // Get PDO from global if not provided
            global $pdo;
            if (!$pdo) {
                throw new Exception("No database connection provided");
            }
            $this->pdo = $pdo;
        }
    }
    
    public function loginUser($email, $password) {
        try {
            // First check if user exists in users table
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND user_type = 'customer'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['user_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
                // Redirect to user dashboard
                header("Location: ../../Dashboards/User%20Dashboard/userdashboard.php");
                exit();
                
                return [
                    'success' => true,
                    'user' => [
                        'id' => $user['user_id'],
                        'name' => $user['full_name'],
                        'email' => $user['email'],
                        'type' => $user['user_type']
                    ]
                ];
            }
            
            // If not found in users table, check customers table
            $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE email = ?");
            $stmt->execute([$email]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($customer) {
                // For customers table, we'll use a default password hash for testing
                $default_password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password: password
                
                if (password_verify($password, $default_password_hash)) {
                    // Set session variables
                    $_SESSION['user_id'] = $customer['id'];
                    $_SESSION['user_email'] = $customer['email'];
                    $_SESSION['user_name'] = $customer['name'];
                    $_SESSION['user_type'] = $customer['type'];
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['last_activity'] = time();
                    
                    // Redirect to user dashboard
                    header("Location: ../../Dashboards/User%20Dashboard/userdashboard.php");
                    exit();
                    
                    return [
                        'success' => true,
                        'user' => [
                            'id' => $customer['id'],
                            'name' => $customer['name'],
                            'email' => $customer['email'],
                            'type' => $customer['type']
                        ]
                    ];
                }
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
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND user_type = 'admin'");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['user_id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['user_id'] = $admin['user_id'];
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['user_name'] = $admin['full_name'];
                $_SESSION['user_type'] = $admin['user_type'];
                $_SESSION['user_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
                // Redirect to admin dashboard
                header("Location: ../../Dashboards/User%20Dashboard/Admin%20Dashboard/admin/dashboard.php");
                exit();
                
                return [
                    'success' => true,
                    'admin_id' => $admin['user_id'],
                    'admin_name' => $admin['full_name']
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
    
    public function registerUser($data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (full_name, email, password, phone_number, address, user_type) VALUES (?, ?, ?, ?, ?, 'customer')");
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            return $stmt->execute([
                $data['name'],
                $data['email'],
                $hashedPassword,
                $data['phone'],
                $data['address']
            ]);
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
    }
    
    public function isAdmin() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    public function logout() {
        session_destroy();
        return true;
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
            header("Location: ../../Admin%20and%20User%20Loginup/loginup_admin.php");
            exit();
        }
        
        // Check if user is an admin and redirect to admin dashboard if needed
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
            header("Location: ../../Dashboards/User%20Dashboard/Admin%20Dashboard/admin/dashboard.php");
            exit();
        }
        
        $this->checkSessionTimeout();
    }
    
    public function requireAdmin() {
        if (!$this->isAdmin()) {
            header("Location: ../../Admin%20and%20User%20Loginup/loginup_admin.php");
            exit();
        }
        $this->checkSessionTimeout();
    }
    
    public function requireSuperAdmin() {
        if (!$this->isAdmin() || $_SESSION['admin_role'] !== 'super_admin') {
            header("Location: ../../Dashboards/User%20Dashboard/Admin%20Dashboard/admin/dashboard.php");
            exit();
        }
        $this->checkSessionTimeout();
    }
    
    /**
     * Get all pending admin requests
     * 
     * @return array Array of pending admin requests
     */
    public function getPendingAdminRequests() {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_type = 'admin_pending' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if the admin limit has been reached
     * 
     * @return bool True if admin limit reached, false otherwise
     */
    public function isAdminLimitReached() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE user_type = 'admin'");
        $stmt->execute();
        $adminCount = (int)$stmt->fetchColumn();
        
        // Maximum of 3 admins allowed
        return $adminCount >= 3;
    }
    
    /**
     * Approve an admin request
     * 
     * @param int $user_id The user ID to approve
     * @return bool True on success, false on failure
     */
    public function approveAdmin($user_id) {
        try {
            // Check if admin limit is reached
            if ($this->isAdminLimitReached()) {
                return false;
            }
            
            $stmt = $this->pdo->prepare("UPDATE users SET user_type = 'admin' WHERE user_id = ? AND user_type = 'admin_pending'");
            $stmt->execute([$user_id]);
            
            if ($stmt->rowCount() > 0) {
                // Get user email
                $email_stmt = $this->pdo->prepare("SELECT email, full_name FROM users WHERE user_id = ?");
                $email_stmt->execute([$user_id]);
                $user = $email_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    // Send approval email
                    $email = new Email();
                    $subject = "Admin Access Approved";
                    $body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                            <h2 style='color: #2563EB;'>Admin Access Approved</h2>
                            <p>Dear {$user['full_name']},</p>
                            <p>Your request for admin access to the Boat Rentals system has been approved.</p>
                            <p>You can now log in using your email and password to access the admin dashboard.</p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='http://{$_SERVER['HTTP_HOST']}/Home%20System/Interface/php/pages/login%20admin/login.php' style='background-color: #2563EB; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Go to Admin Login</a>
                            </div>
                            <hr style='border: 1px solid #eee; margin: 20px 0;'>
                            <p style='color: #666; font-size: 12px;'>This is an automated message, please do not reply to this email.</p>
                        </div>
                    ";
                    $email->send($user['email'], $subject, $body);
                }
                
                // Delete the notification
                $notif_stmt = $this->pdo->prepare("DELETE FROM notifications WHERE user_id = ? AND type = 'admin_request'");
                $notif_stmt->execute([$user_id]);
                
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Admin approval error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Reject an admin request
     * 
     * @param int $user_id The user ID to reject
     * @return bool True on success, false on failure
     */
    public function rejectAdmin($user_id) {
        try {
            // Get user email before updating
            $email_stmt = $this->pdo->prepare("SELECT email, full_name FROM users WHERE user_id = ? AND user_type = 'admin_pending'");
            $email_stmt->execute([$user_id]);
            $user = $email_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Update user type to regular customer
                $stmt = $this->pdo->prepare("UPDATE users SET user_type = 'customer' WHERE user_id = ? AND user_type = 'admin_pending'");
                $stmt->execute([$user_id]);
                
                if ($stmt->rowCount() > 0) {
                    // Send rejection email
                    $email = new Email();
                    $subject = "Admin Access Request Update";
                    $body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                            <h2 style='color: #2563EB;'>Admin Access Request Update</h2>
                            <p>Dear {$user['full_name']},</p>
                            <p>Your request for admin access to the Boat Rentals system was not approved at this time.</p>
                            <p>You can still use the system as a regular user with your current account.</p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='http://{$_SERVER['HTTP_HOST']}/Home%20System/Interface/php/pages/login%20user/login.php' style='background-color: #2563EB; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Go to Login</a>
                            </div>
                            <hr style='border: 1px solid #eee; margin: 20px 0;'>
                            <p style='color: #666; font-size: 12px;'>This is an automated message, please do not reply to this email.</p>
                        </div>
                    ";
                    $email->send($user['email'], $subject, $body);
                    
                    // Delete the notification
                    $notif_stmt = $this->pdo->prepare("DELETE FROM notifications WHERE user_id = ? AND type = 'admin_request'");
                    $notif_stmt->execute([$user_id]);
                    
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Admin rejection error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get count of pending admin requests
     * 
     * @return int Number of pending admin requests
     */
    public function countPendingAdminRequests() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE user_type = 'admin_pending'");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
} 