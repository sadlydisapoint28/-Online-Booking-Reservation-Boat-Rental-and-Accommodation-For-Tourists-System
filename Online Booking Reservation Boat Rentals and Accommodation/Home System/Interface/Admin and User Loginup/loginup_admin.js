document.addEventListener("DOMContentLoaded", function () {
   // Elements
   const selectionView = document.getElementById("selection-view");
   const userLoginView = document.getElementById("user-login-view");
   const adminLoginView = document.getElementById("admin-login-view");
   const userLoginBtn = document.getElementById("user-login-btn");
   const adminBtn = document.getElementById("admin-btn");
 
   // Check if we should show login form directly
   const urlParams = new URLSearchParams(window.location.search);
   if (urlParams.get('show') === 'login') {
     showView(userLoginView);
   }
 
   // View switching functions
   function showView(viewToShow) {
     // Hide all views
     selectionView.classList.add("hidden");
     userLoginView.classList.add("hidden");
     adminLoginView.classList.add("hidden");
 
     // Show the requested view
     viewToShow.classList.remove("hidden");
     viewToShow.classList.add("fade-in");
   }
 
   // Event listeners for navigation
   if (userLoginBtn) {
     userLoginBtn.addEventListener("click", function () {
       showView(userLoginView);
     });
   }
 
   if (adminBtn) {
     adminBtn.addEventListener("click", function () {
       showView(adminLoginView);
     });
   }
 
   // Add back buttons to both login forms
   const userLoginForm = document.querySelector("#user-login-view form");
   const adminLoginForm = document.querySelector("#admin-login-view form");
 
   // Add back buttons
   const userBackBtn = document.createElement("button");
   userBackBtn.type = "button";
   userBackBtn.className = "mt-4 text-blue-600 hover:text-blue-800 underline flex items-center gap-2 mx-auto font-medium";
   userBackBtn.innerHTML = '<i class="fas fa-arrow-left"></i> Back to selection';
   userBackBtn.onclick = () => showView(selectionView);
   userLoginForm.parentNode.appendChild(userBackBtn);
 
   const adminBackBtn = document.createElement("button");
   adminBackBtn.type = "button";
   adminBackBtn.className = "mt-4 text-indigo-600 hover:text-indigo-800 underline flex items-center gap-2 mx-auto font-medium";
   adminBackBtn.innerHTML = '<i class="fas fa-arrow-left"></i> Back to selection';
   adminBackBtn.onclick = () => showView(selectionView);
   adminLoginForm.parentNode.appendChild(adminBackBtn);
 
   // Form submissions
   userLoginForm.addEventListener("submit", function (e) {
     e.preventDefault();
     const submitBtn = this.querySelector('button[type="submit"]');
     submitBtn.disabled = true;
     submitBtn.textContent = "Logging in...";
     
     // Submit the form to loginup_admin.php
     this.action = "loginup_admin.php";
     this.submit();
   });
 
   adminLoginForm.addEventListener("submit", function (e) {
     e.preventDefault();
     const submitBtn = this.querySelector('button[type="submit"]');
     submitBtn.disabled = true;
     submitBtn.textContent = "Logging in...";
     
     // Submit the form to loginup_admin.php
     this.action = "loginup_admin.php";
     this.submit();
   });
 });
 