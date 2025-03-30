<?php
/**
 * Email class for sending emails
 */
class Email {
    /**
     * Send an email
     *
     * @param string $to The recipient email address
     * @param string $subject The email subject
     * @param string $body The email content (HTML)
     * @param array $attachments Optional array of attachments
     * @return bool True if email sent successfully, false otherwise
     */
    public function send($to, $subject, $body, $attachments = []) {
        try {
            // Set up email headers
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Carles Tourism <info@carles-tourism.com>" . "\r\n";
            $headers .= "Reply-To: info@carles-tourism.com" . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // For production use, consider using a mail service like PHPMailer or similar
            // For development, we'll log emails and return success
            $logMessage = "Email would be sent to: $to\nSubject: $subject\nBody: $body\nHeaders: $headers\n\n";
            error_log($logMessage, 3, __DIR__ . "/../logs/emails.log");
            
            // Uncomment the line below to actually send emails in production
            // return mail($to, $subject, $body, $headers);
            
            // For development just return true
            return true;
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send a verification email
     *
     * @param string $to The recipient email address
     * @param string $name The recipient's name
     * @param string $token The verification token
     * @return bool True if email sent successfully, false otherwise
     */
    public function sendVerification($to, $name, $token) {
        $subject = "Verify Your Carles Tourism Account";
        
        $verificationUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/Home%20System/Interface/php/pages/verify.php?token=' . $token;
        
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #2563EB;'>Welcome to Carles Tourism!</h2>
                <p>Hello $name,</p>
                <p>Thank you for registering. Please verify your email address to activate your account.</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='$verificationUrl' style='background-color: #2563EB; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Verify Email Address</a>
                </div>
                <p>If the button above doesn't work, you can copy and paste this link into your browser:</p>
                <p><a href='$verificationUrl'>$verificationUrl</a></p>
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                <p style='color: #666; font-size: 12px;'>If you didn't create this account, please ignore this email.</p>
            </div>
        ";
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Send a password reset email
     *
     * @param string $to The recipient email address
     * @param string $name The recipient's name
     * @param string $token The reset token
     * @return bool True if email sent successfully, false otherwise
     */
    public function sendPasswordReset($to, $name, $token) {
        $subject = "Reset Your Carles Tourism Password";
        
        $resetUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/Home%20System/Interface/php/pages/reset_password.php?token=' . $token;
        
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #2563EB;'>Password Reset Request</h2>
                <p>Hello $name,</p>
                <p>We received a request to reset your password. Click the button below to create a new password:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='$resetUrl' style='background-color: #2563EB; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;'>Reset Password</a>
                </div>
                <p>If the button above doesn't work, you can copy and paste this link into your browser:</p>
                <p><a href='$resetUrl'>$resetUrl</a></p>
                <hr style='border: 1px solid #eee; margin: 20px 0;'>
                <p style='color: #666; font-size: 12px;'>If you didn't request a password reset, please ignore this email or contact support if you have concerns.</p>
            </div>
        ";
        
        return $this->send($to, $subject, $body);
    }
} 