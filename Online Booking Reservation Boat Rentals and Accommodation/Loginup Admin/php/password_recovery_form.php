<?php
// This file should only be included, not accessed directly
if (!defined('INCLUDED')) {
    die('Direct access not permitted');
}
?>
<form id="password-recovery-form" class="space-y-5 animate-fade-in" method="POST">
    <input type="hidden" name="password_recovery" value="1">
    
    <div class="form-group transform transition-all duration-300 hover:scale-[1.02]">
        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
        <div class="relative">
            <i class="fas fa-envelope absolute left-3 top-3 text-gray-400 transition-colors duration-200"></i>
            <input type="email" name="email" placeholder="you@example.com" 
                   class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                   required />
        </div>
    </div>
    
    <div class="form-group flex justify-center">
        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
    </div>
    
    <button type="submit" 
            class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 transform hover:scale-[1.02] active:scale-[0.98]">
        <i class="fas fa-paper-plane"></i>
        Send Recovery Link
    </button>
    
    <div class="mt-4 text-center">
        <p class="text-gray-600">
            Remember your password? 
            <button type="button" id="back-to-login" class="text-blue-600 hover:text-blue-800 font-medium underline transition-colors duration-200">
                Back to Login
            </button>
        </p>
    </div>
</form>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

.animate-slide-in {
    animation: slideIn 0.5s ease-out forwards;
}

.animate-pulse {
    animation: pulse 2s infinite;
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

.input-focus {
    animation: pulse 0.5s ease-out;
}

.error-message {
    color: #EF4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    animation: fadeIn 0.3s ease-out;
}

.success-message {
    color: #10B981;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    animation: fadeIn 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('password-recovery-form');
    const emailInput = form.querySelector('input[type="email"]');
    
    // Email validation
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Show error message
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        let errorDiv = formGroup.querySelector('.error-message');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            formGroup.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;
        input.classList.add('border-red-500');
        input.classList.add('input-focus');
        
        setTimeout(() => {
            input.classList.remove('input-focus');
        }, 500);
    }
    
    // Remove error message
    function removeError(input) {
        const formGroup = input.closest('.form-group');
        const errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.classList.remove('border-red-500');
    }
    
    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset previous errors
        removeError(emailInput);
        
        // Validate email
        if (!validateEmail(emailInput.value)) {
            showError(emailInput, 'Please enter a valid email address');
            return;
        }
        
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
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitButton.disabled = true;
        
        try {
            const response = await fetch('password_recovery.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Password recovery instructions have been sent to your email.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    // Switch back to login form
                    document.getElementById('back-to-login').click();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: result.message || 'Failed to send recovery email. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#3B82F6'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred. Please try again.',
                icon: 'error',
                confirmButtonColor: '#3B82F6'
            });
        } finally {
            // Reset button state
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        }
    });
    
    // Back to login button
    document.getElementById('back-to-login').addEventListener('click', function() {
        const loginContent = document.getElementById('loginContent');
        loginContent.classList.add('animate-slide-in');
        setTimeout(() => {
            loadLoginContent('user');
        }, 300);
    });
});
</script> 