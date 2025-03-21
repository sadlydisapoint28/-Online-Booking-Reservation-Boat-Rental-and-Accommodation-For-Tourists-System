<?php
// This file should only be included, not accessed directly
if (!defined('INCLUDED')) {
    die('Direct access not permitted');
}
?>
<form id="admin-login-form" class="space-y-5 animate-fade-in" method="POST">
    <input type="hidden" name="admin_login" value="1">
    
    <div class="form-group transform transition-all duration-300 hover:scale-[1.02]">
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <div class="relative">
            <i class="fas fa-envelope absolute left-3 top-3 text-gray-400 transition-colors duration-200"></i>
            <input type="email" name="email" placeholder="admin@example.com" 
                   class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                   required />
        </div>
    </div>
    
    <div class="form-group transform transition-all duration-300 hover:scale-[1.02]">
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <div class="relative">
            <i class="fas fa-lock absolute left-3 top-3 text-gray-400 transition-colors duration-200"></i>
            <input type="password" id="admin-password" name="password" placeholder="••••••••" 
                   class="w-full pl-10 pr-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                   required />
            <button type="button" id="toggle-admin-password" 
                    class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div id="password-strength" class="mt-2 hidden">
            <div class="flex items-center space-x-2">
                <div class="h-1 w-1/4 bg-gray-200 rounded-full"></div>
                <div class="h-1 w-1/4 bg-gray-200 rounded-full"></div>
                <div class="h-1 w-1/4 bg-gray-200 rounded-full"></div>
                <div class="h-1 w-1/4 bg-gray-200 rounded-full"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1" id="password-requirements"></p>
        </div>
    </div>
    
    <div class="flex items-center justify-between">
        <label class="flex items-center space-x-2 cursor-pointer group">
            <input type="checkbox" name="remember_me" class="form-checkbox h-4 w-4 text-blue-600 rounded transition-colors duration-200">
            <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors duration-200">Remember me</span>
        </label>
        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">Forgot password?</a>
    </div>
    
    <div class="form-group flex justify-center">
        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
    </div>
    
    <button type="submit" 
            class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 transform hover:scale-[1.02] active:scale-[0.98]">
        <i class="fas fa-sign-in-alt"></i>
        Login to Admin Dashboard
    </button>
</form>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

.form-group {
    position: relative;
    overflow: hidden;
}

.form-group::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(to right, #3B82F6, #60A5FA);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.form-group:hover::after {
    transform: scaleX(1);
}

#password-strength .bg-gray-200 {
    transition: background-color 0.3s ease;
}

#password-strength .bg-red-500 { background-color: #EF4444; }
#password-strength .bg-yellow-500 { background-color: #F59E0B; }
#password-strength .bg-green-500 { background-color: #10B981; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('toggle-admin-password');
    const passwordInput = document.getElementById('admin-password');
    const passwordStrength = document.getElementById('password-strength');
    const passwordRequirements = document.getElementById('password-requirements');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        updatePasswordStrengthIndicator(strength);
    });
    
    function checkPasswordStrength(password) {
        let strength = 0;
        const requirements = [];
        
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[!@#$%^&*()\-_=+{};:,<.>]/.test(password)) strength++;
        
        if (password.length < 8) requirements.push("At least 8 characters");
        if (!/[A-Z]/.test(password)) requirements.push("One uppercase letter");
        if (!/[a-z]/.test(password)) requirements.push("One lowercase letter");
        if (!/[0-9]/.test(password)) requirements.push("One number");
        if (!/[!@#$%^&*()\-_=+{};:,<.>]/.test(password)) requirements.push("One special character");
        
        return { strength, requirements };
    }
    
    function updatePasswordStrengthIndicator({ strength, requirements }) {
        const indicators = passwordStrength.querySelectorAll('.h-1');
        indicators.forEach((indicator, index) => {
            if (index < strength) {
                indicator.classList.remove('bg-gray-200');
                if (strength <= 2) indicator.classList.add('bg-red-500');
                else if (strength <= 4) indicator.classList.add('bg-yellow-500');
                else indicator.classList.add('bg-green-500');
            } else {
                indicator.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
                indicator.classList.add('bg-gray-200');
            }
        });
        
        if (requirements.length > 0) {
            passwordStrength.classList.remove('hidden');
            passwordRequirements.textContent = requirements.join(", ");
        } else {
            passwordStrength.classList.add('hidden');
        }
    }
    
    // Handle form submission
    const form = document.getElementById('admin-login-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
            Swal.fire({
                title: 'Error!',
                text: 'Please verify that you are not a robot',
                icon: 'error',
                confirmButtonColor: '#3B82F6'
            });
            return;
        }
        
        const formData = new FormData(this);
        
        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        submitButton.disabled = true;
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            if (html.includes('Invalid email or password')) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid email or password',
                    icon: 'error',
                    confirmButtonColor: '#3B82F6'
                });
            } else if (html.includes('Too many attempts')) {
                Swal.fire({
                    title: 'Account Locked!',
                    text: 'Too many failed attempts. Please try again later.',
                    icon: 'error',
                    confirmButtonColor: '#3B82F6'
                });
            } else {
                Swal.fire({
                    title: 'Success!',
                    text: 'You have successfully logged in!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '../AdminDashboard/index.php';
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred. Please try again.',
                icon: 'error',
                confirmButtonColor: '#3B82F6'
            });
        })
        .finally(() => {
            // Reset button state
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        });
    });
});
</script> 