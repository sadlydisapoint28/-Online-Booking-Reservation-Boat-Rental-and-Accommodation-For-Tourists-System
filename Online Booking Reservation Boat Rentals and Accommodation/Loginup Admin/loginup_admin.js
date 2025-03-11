document.addEventListener("DOMContentLoaded", function () {
   // Elements
   const selectionView = document.getElementById("selection-view");
   const userSignupView = document.getElementById("user-signup-view");
   const adminLoginView = document.getElementById("admin-login-view");
 
   const userBtn = document.getElementById("user-btn");
   const adminBtn = document.getElementById("admin-btn");
   const backToSelectionFromSignup = document.getElementById(
     "back-to-selection-from-signup",
   );
   const backToSelectionFromAdmin = document.getElementById(
     "back-to-selection-from-admin",
   );
 
   const userSignupForm = document.getElementById("user-signup-form");
   const adminLoginForm = document.getElementById("admin-login-form");
   const togglePasswordBtn = document.getElementById("toggle-password");
   const adminPasswordInput = document.getElementById("admin-password");
 
   // View switching functions
   function showView(viewToShow) {
     // Hide all views
     selectionView.classList.add("hidden");
     userSignupView.classList.add("hidden");
     adminLoginView.classList.add("hidden");
 
     // Show the requested view
     viewToShow.classList.remove("hidden");
     viewToShow.classList.add("fade-in");
   }
 
   // Event listeners for navigation
   userBtn.addEventListener("click", function () {
     showView(userSignupView);
   });
 
   adminBtn.addEventListener("click", function () {
     showView(adminLoginView);
   });
 
   backToSelectionFromSignup.addEventListener("click", function () {
     showView(selectionView);
   });
 
   backToSelectionFromAdmin.addEventListener("click", function () {
     showView(selectionView);
   });
 
   // Toggle password visibility
   togglePasswordBtn.addEventListener("click", function () {
     const type =
       adminPasswordInput.getAttribute("type") === "password"
         ? "text"
         : "password";
     adminPasswordInput.setAttribute("type", type);
 
     // Toggle eye icon
     const eyeIcon = togglePasswordBtn.querySelector("i");
     if (type === "password") {
       eyeIcon.classList.remove("fa-eye-slash");
       eyeIcon.classList.add("fa-eye");
     } else {
       eyeIcon.classList.remove("fa-eye");
       eyeIcon.classList.add("fa-eye-slash");
     }
   });
 
   // Form submissions
   userSignupForm.addEventListener("submit", function (e) {
     e.preventDefault();
     const formData = new FormData(userSignupForm);
     const userData = {
       name: formData.get("name"),
       email: formData.get("email"),
       password: formData.get("password"),
     };
 
     console.log("User signup submitted:", userData);
 
     // Simulate API call
     simulateLoading(
       userSignupForm.querySelector('button[type="submit"]'),
       "Signing up...",
       function () {
         // Show success message
         alert("Account created successfully! You can now log in.");
         userSignupForm.reset();
         // In a real app, you might redirect to login or dashboard
       },
     );
   });
 
   adminLoginForm.addEventListener("submit", function (e) {
     e.preventDefault();
     const formData = new FormData(adminLoginForm);
     const adminData = {
       username: formData.get("username"),
       password: formData.get("password"),
     };
 
     console.log("Admin login submitted:", adminData);
 
     // Simulate API call
     simulateLoading(
       adminLoginForm.querySelector('button[type="submit"]'),
       "Authenticating...",
       function () {
         // Show error message (for demo purposes)
         const errorDiv = document.createElement("div");
         errorDiv.className =
           "p-3 bg-red-100 border border-red-300 text-red-600 rounded-md text-sm mt-4";
         errorDiv.textContent = "Invalid credentials. Please try again.";
 
         // Remove any existing error message
         const existingError = adminLoginForm.querySelector(".error-message");
         if (existingError) {
           existingError.remove();
         }
 
         adminLoginForm.insertBefore(errorDiv, adminLoginForm.firstChild);
         // In a real app, you would validate credentials and redirect to admin dashboard if valid
       },
     );
   });
 
   // Helper function to simulate loading state
   function simulateLoading(button, loadingText, callback) {
     const originalText = button.textContent;
     button.disabled = true;
     button.textContent = loadingText;
 
     setTimeout(function () {
       button.disabled = false;
       button.textContent = originalText;
       if (callback) callback();
     }, 1500);
   }
 });
 