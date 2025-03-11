<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Type Selection</title>
    <link rel="stylesheet" href="/Online Booking Reservation Boat Rentals and Accommodation/Loginup Admin/loginup_admin.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-blue-400 to-cyan-300 p-4 font-body"
  >
    <!-- Animated background elements -->
    <div class="ocean-bg">
      <div class="wave wave1"></div>
      <div class="wave wave2"></div>
      <div class="bubble bubble1"></div>
      <div class="bubble bubble2"></div>
      <div class="bubble bubble3"></div>
      <div class="bubble bubble4"></div>
    </div>

    <div
      class="w-full max-w-4xl bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-blue-100"
      id="main-container"
    >
      <!-- User Type Selection -->
      <div id="selection-view" class="w-full">
        <h2
          class="text-4xl font-bold text-center mb-10 text-blue-800 tracking-tight"
        >
          Choose Your Experience
        </h2>

        <div class="grid md:grid-cols-2 gap-10">
          <!-- User Card -->
          <div
            class="card overflow-hidden border-0 hover:transform hover:scale-105 transition-all duration-300 bg-white rounded-xl shadow-lg"
          >
            <div
              class="h-56 bg-gradient-to-r from-blue-500 to-cyan-400 relative overflow-hidden"
            >
              <img
                src="https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&q=80"
                alt="User experience"
                class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity duration-300"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-blue-900 to-transparent opacity-60"
              ></div>
              <i
                class="fas fa-ship absolute bottom-6 right-6 text-white text-5xl drop-shadow-lg"
              ></i>
              <div class="absolute bottom-6 left-6 text-white">
                <span
                  class="bg-blue-600 text-xs uppercase tracking-wider py-1 px-2 rounded-full shadow-md"
                  >For Customers</span
                >
              </div>
            </div>
            <div class="card-header p-6 bg-gradient-to-r from-blue-50 to-white">
              <h3
                class="text-2xl font-bold text-blue-700 flex items-center gap-3"
              >
                <i class="fas fa-user-circle text-blue-500"></i>
                User Portal
              </h3>
              <p class="text-blue-600 mt-2 font-medium">
                Book boats and accommodations for your perfect waterfront
                vacation
              </p>
            </div>
            <div class="card-content p-6">
              <ul class="space-y-3 text-gray-600">
                <li class="flex items-center gap-3">
                  <i class="fas fa-anchor text-blue-500"></i>
                  <span>Browse available boats and waterfront stays</span>
                </li>
                <li class="flex items-center gap-3">
                  <i class="fas fa-calendar-alt text-blue-500"></i>
                  <span>Easy booking with visual calendars</span>
                </li>
                <li class="flex items-center gap-3">
                  <i class="fas fa-user-plus text-blue-500"></i>
                  <span>Simple signup with minimal information</span>
                </li>
              </ul>
            </div>
            <div class="card-footer p-6 pt-2">
              <button
                id="user-btn"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2"
              >
                <i class="fas fa-sign-in-alt"></i>
                Continue as User
              </button>
            </div>
          </div>

          <!-- Admin Card -->
          <div
            class="card overflow-hidden border-0 hover:transform hover:scale-105 transition-all duration-300 bg-white rounded-xl shadow-lg"
          >
            <div
              class="h-56 bg-gradient-to-r from-indigo-700 to-purple-600 relative overflow-hidden"
            >
              <img
                src="https://images.unsplash.com/photo-1564069114553-7215e1ff1890?w=800&q=80"
                alt="Admin dashboard"
                class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity duration-300"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-indigo-900 to-transparent opacity-60"
              ></div>
              <i
                class="fas fa-building absolute bottom-6 right-6 text-white text-5xl drop-shadow-lg"
              ></i>
              <div class="absolute bottom-6 left-6 text-white">
                <span
                  class="bg-indigo-600 text-xs uppercase tracking-wider py-1 px-2 rounded-full shadow-md"
                  >Staff Only</span
                >
              </div>
            </div>
            <div
              class="card-header p-6 bg-gradient-to-r from-indigo-50 to-white"
            >
              <h3
                class="text-2xl font-bold text-indigo-700 flex items-center gap-3"
              >
                <i class="fas fa-user-shield text-indigo-500"></i>
                Administrator
              </h3>
              <p class="text-indigo-600 mt-2 font-medium">
                Manage bookings, listings, and system settings
              </p>
            </div>
            <div class="card-content p-6">
              <ul class="space-y-3 text-gray-600">
                <li class="flex items-center gap-3">
                  <i class="fas fa-lock text-indigo-500"></i>
                  <span>Secure admin portal access</span>
                </li>
                <li class="flex items-center gap-3">
                  <i class="fas fa-edit text-indigo-500"></i>
                  <span>Manage boat and accommodation listings</span>
                </li>
                <li class="flex items-center gap-3">
                  <i class="fas fa-chart-line text-indigo-500"></i>
                  <span>View and manage bookings and reports</span>
                </li>
              </ul>
            </div>
            <div class="card-footer p-6 pt-2">
              <button
                id="admin-btn"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2"
              >
                <i class="fas fa-sign-in-alt"></i>
                Continue as Administrator
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- User Signup Form -->
      <div id="user-signup-view" class="hidden">
        <div class="mb-8 text-center">
          <h2 class="text-3xl font-bold text-blue-800 mb-3">
            Create Your User Account
          </h2>
          <p class="text-blue-600 text-lg">
            Sign up to start booking your ocean adventures
          </p>
        </div>
        <form
          id="user-signup-form"
          class="space-y-5 max-w-md mx-auto bg-blue-50 p-8 rounded-xl shadow-md"
        >
          <div class="form-group">
            <label class="block text-sm font-medium text-blue-700 mb-2"
              >Full Name</label
            >
            <div class="relative">
              <i class="fas fa-user absolute left-3 top-3 text-blue-400"></i>
              <input
                type="text"
                name="name"
                placeholder="John Doe"
                class="w-full pl-10 px-4 py-3 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                required
              />
            </div>
          </div>
          <div class="form-group">
            <label class="block text-sm font-medium text-blue-700 mb-2"
              >Email</label
            >
            <div class="relative">
              <i
                class="fas fa-envelope absolute left-3 top-3 text-blue-400"
              ></i>
              <input
                type="email"
                name="email"
                placeholder="you@example.com"
                class="w-full pl-10 px-4 py-3 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                required
              />
            </div>
          </div>
          <div class="form-group">
            <label class="block text-sm font-medium text-blue-700 mb-2"
              >Password</label
            >
            <div class="relative">
              <i class="fas fa-lock absolute left-3 top-3 text-blue-400"></i>
              <input
                type="password"
                name="password"
                placeholder="••••••••"
                class="w-full pl-10 px-4 py-3 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                required
              />
              <p class="text-xs text-blue-600 mt-2 flex items-center">
                <i class="fas fa-info-circle mr-1"></i>
                Password must be at least 8 characters long
              </p>
            </div>
          </div>
          <button
            type="submit"
            class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg mt-4 flex items-center justify-center gap-2"
          >
            <i class="fas fa-user-plus"></i>
            Create Account
          </button>
        </form>
        <div class="mt-8 text-center">
          <button
            id="back-to-selection-from-signup"
            class="text-blue-600 hover:text-blue-800 underline flex items-center gap-2 mx-auto font-medium"
          >
            <i class="fas fa-arrow-left"></i>
            Back to selection
          </button>
        </div>
      </div>

      <!-- Admin Login Form -->
      <div id="admin-login-view" class="hidden">
        <div class="mb-8 text-center">
          <h2 class="text-3xl font-bold text-indigo-800 mb-3">
            Administrator Access
          </h2>
          <p class="text-indigo-600 text-lg">
            Secure login for system administrators
          </p>
        </div>
        <div class="flex justify-center">
          <div
            class="w-full max-w-md bg-gradient-to-b from-indigo-50 to-blue-50 p-8 rounded-xl shadow-lg border border-indigo-100"
          >
            <div class="flex justify-center mb-6">
              <div
                class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center shadow-inner"
              >
                <i class="fas fa-shield-alt text-indigo-600 text-4xl"></i>
              </div>
            </div>
            <form id="admin-login-form" class="space-y-5">
              <div class="form-group">
                <label class="block text-sm font-medium text-indigo-700 mb-2"
                  >Username</label
                >
                <div class="relative">
                  <input
                    type="text"
                    name="username"
                    placeholder="Enter your username"
                    class="pl-10 w-full px-4 py-3 border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    required
                  />
                  <i
                    class="fas fa-user absolute left-3 top-3 text-indigo-400"
                  ></i>
                </div>
              </div>
              <div class="form-group">
                <label class="block text-sm font-medium text-indigo-700 mb-2"
                  >Password</label
                >
                <div class="relative">
                  <input
                    type="password"
                    id="admin-password"
                    name="password"
                    placeholder="Enter your password"
                    class="pl-10 pr-10 w-full px-4 py-3 border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    required
                  />
                  <i
                    class="fas fa-lock absolute left-3 top-3 text-indigo-400"
                  ></i>
                  <button
                    type="button"
                    id="toggle-password"
                    class="absolute right-3 top-3 text-indigo-400 hover:text-indigo-600 transition-colors duration-200"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              <button
                type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg mt-4 flex items-center justify-center gap-2"
              >
                <i class="fas fa-sign-in-alt"></i>
                Login to Dashboard
              </button>
            </form>
            <div class="mt-6 text-center border-t border-indigo-100 pt-6">
              <p
                class="text-sm text-indigo-600 flex items-center justify-center gap-2"
              >
                <i class="fas fa-lock"></i>
                Secure access for authorized personnel only
              </p>
            </div>
          </div>
        </div>
        <div class="mt-8 text-center">
          <button
            id="back-to-selection-from-admin"
            class="text-indigo-600 hover:text-indigo-800 underline flex items-center gap-2 mx-auto font-medium"
          >
            <i class="fas fa-arrow-left"></i>
            Back to selection
          </button>
        </div>
      </div>
    </div>

    <style>
      /* Base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  .font-body {
    font-family: "Poppins", sans-serif;
  }
  
  /* Card styling */
  .card {
    display: flex;
    flex-direction: column;
    border-radius: 1rem;
    transition: all 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-5px);
  }
  
  .card-header,
  .card-content,
  .card-footer {
    width: 100%;
  }
  
  /* Animation for view transitions */
  .fade-in {
    animation: fadeIn 0.5s ease-in-out;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Form styling */
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  /* Error message styling */
  .error-message {
    color: #e53e3e;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  /* Success message styling */
  .success-message {
    color: #38a169;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  /* Ocean background animations */
  .ocean-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
  }
  
  .wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="%23FFFFFF"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="%23FFFFFF"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%23FFFFFF"/></svg>');
    background-size: 1200px 100px;
    animation: wave 10s linear infinite;
  }
  
  .wave1 {
    opacity: 0.3;
    animation-delay: 0s;
    bottom: 0;
  }
  
  .wave2 {
    opacity: 0.2;
    animation-delay: -5s;
    bottom: 10px;
    animation-duration: 15s;
  }
  
  @keyframes wave {
    0% {
      background-position-x: 0;
    }
    100% {
      background-position-x: 1200px;
    }
  }
  
  .bubble {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(1px);
    animation: float 8s ease-in-out infinite;
  }
  
  .bubble1 {
    width: 80px;
    height: 80px;
    left: 10%;
    bottom: 20%;
    animation-delay: 0s;
  }
  
  .bubble2 {
    width: 50px;
    height: 50px;
    left: 20%;
    bottom: 40%;
    animation-delay: 2s;
  }
  
  .bubble3 {
    width: 70px;
    height: 70px;
    right: 15%;
    bottom: 30%;
    animation-delay: 4s;
  }
  
  .bubble4 {
    width: 40px;
    height: 40px;
    right: 25%;
    bottom: 60%;
    animation-delay: 6s;
  }
  
  @keyframes float {
    0% {
      transform: translateY(0) rotate(0);
    }
    50% {
      transform: translateY(-100px) rotate(180deg);
    }
    100% {
      transform: translateY(0) rotate(360deg);
    }
  }
  
  /* Button hover effects */
  button:hover {
    transform: translateY(-2px);
    box-shadow:
      0 10px 15px -3px rgba(0, 0, 0, 0.1),
      0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }
  
  /* Input focus effects */
  input:focus {
    transform: translateY(-1px);
  }
  
  /* Mix blend mode for image overlays -ramos */
  .mix-blend-overlay {
    mix-blend-mode: overlay;
  }
    </style>

    <script>
      // Get the button and input elements
      document.addEventListener("DOMContentLoaded", function () {
  // Toggle user dropdown
  const userDropdownToggle = document.getElementById("userDropdownToggle");
  const userDropdown = document.getElementById("userDropdown");

  if (userDropdownToggle && userDropdown) {
    userDropdownToggle.addEventListener("click", function () {
      userDropdown.classList.toggle("show");
    });
  }

  // Close dropdown when clicking outside
  document.addEventListener("click", function (event) {
    if (
      userDropdown &&
      userDropdownToggle &&
      !userDropdownToggle.contains(event.target) &&
      !userDropdown.contains(event.target)
    ) {
      userDropdown.classList.remove("show");
    }
  });

  // Toggle sidebar collapse
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("collapsed");
    });
  }

  // Tab navigation
  const tabCards = document.querySelectorAll(".tab-card");
  const tabPanes = document.querySelectorAll(".tab-pane");
  const sidebarLinks = document.querySelectorAll(".sidebar-link");

  function setActiveTab(tabId) {
    // Remove active class from all tabs and panes
    tabCards.forEach((card) => card.classList.remove("active"));
    tabPanes.forEach((pane) => pane.classList.remove("active"));
    sidebarLinks.forEach((link) => link.classList.remove("active"));

    // Add active class to selected tab and pane
    const selectedCard = document.querySelector(
      `.tab-card[data-tab="${tabId}"]`,
    );
    const selectedPane = document.getElementById(`${tabId}-tab`);
    const selectedLink = document.querySelector(
      `.sidebar-link[data-tab="${tabId}"]`,
    );

    if (selectedCard) selectedCard.classList.add("active");
    if (selectedPane) selectedPane.classList.add("active");
    if (selectedLink) selectedLink.classList.add("active");
  }

  // Add click event to tab cards
  tabCards.forEach((card) => {
    card.addEventListener("click", function () {
      const tabId = this.getAttribute("data-tab");
      setActiveTab(tabId);
    });
  });

  // Add click event to sidebar links
  sidebarLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const tabId = this.getAttribute("data-tab");
      setActiveTab(tabId);
    });
  });

  // Booking steps navigation
  const nextToStep2 = document.getElementById("next-to-step-2");
  const nextToStep3 = document.getElementById("next-to-step-3");
  const nextToStep4 = document.getElementById("next-to-step-4");
  const backToStep1 = document.getElementById("back-to-step-1");
  const backToStep2 = document.getElementById("back-to-step-2");
  const backToStep3 = document.getElementById("back-to-step-3");
  const completeBooking = document.getElementById("complete-booking");

  const bookingSteps = document.querySelectorAll(".booking-step");
  const progressSteps = document.querySelectorAll(".progress-step");
  const progressBarFill = document.querySelector(".progress-bar-fill");

  function setActiveStep(stepNumber) {
    // Hide all steps
    bookingSteps.forEach((step) => step.classList.remove("active"));

    // Show active step
    const activeStep = document.getElementById(`booking-step-${stepNumber}`);
    if (activeStep) activeStep.classList.add("active");

    // Update progress steps
    progressSteps.forEach((step, index) => {
      if (index < stepNumber) {
        step.classList.add("active");
      } else {
        step.classList.remove("active");
      }
    });

    // Update progress bar
    if (progressBarFill) {
      progressBarFill.style.width = `${((stepNumber - 1) / 3) * 100}%`;
    }
  }

  // Add click events to navigation buttons
  if (nextToStep2)
    nextToStep2.addEventListener("click", () => setActiveStep(2));
  if (nextToStep3)
    nextToStep3.addEventListener("click", () => setActiveStep(3));
  if (nextToStep4)
    nextToStep4.addEventListener("click", () => setActiveStep(4));
  if (backToStep1)
    backToStep1.addEventListener("click", () => setActiveStep(1));
  if (backToStep2)
    backToStep2.addEventListener("click", () => setActiveStep(2));
  if (backToStep3)
    backToStep3.addEventListener("click", () => setActiveStep(3));

  if (completeBooking) {
    completeBooking.addEventListener("click", function () {
      alert("Booking completed successfully!");
      setActiveTab("calendar");
    });
  }

  // Tutorial overlay
  const showTutorial = document.getElementById("showTutorial");
  const tutorialOverlay = document.getElementById("tutorialOverlay");
  const closeTutorial = document.getElementById("closeTutorial");
  const skipTutorial = document.getElementById("skipTutorial");
  const nextTutorial = document.getElementById("nextTutorial");
  const tutorialDots = document.querySelectorAll(".tutorial-dot");

  let currentTutorialStep = 0;
  const totalTutorialSteps = tutorialDots.length;

  function showTutorialStep(step) {
    // Update dots
    tutorialDots.forEach((dot, index) => {
      dot.classList.toggle("active", index === step);
    });

    currentTutorialStep = step;

    // If last step, change button text
    if (nextTutorial) {
      if (step === totalTutorialSteps - 1) {
        nextTutorial.innerHTML = 'Get Started <i class="fas fa-check"></i>';
      } else {
        nextTutorial.innerHTML = 'Next <i class="fas fa-chevron-right"></i>';
      }
    }
  }

  if (showTutorial && tutorialOverlay) {
    showTutorial.addEventListener("click", function () {
      tutorialOverlay.classList.add("show");
      tutorialOverlay.style.display = "flex";
      showTutorialStep(0);
    });
  }

  if (closeTutorial && tutorialOverlay) {
    closeTutorial.addEventListener("click", function () {
      tutorialOverlay.classList.remove("show");
      tutorialOverlay.style.display = "none";
    });
  }

  if (skipTutorial && tutorialOverlay) {
    skipTutorial.addEventListener("click", function () {
      tutorialOverlay.classList.remove("show");
      tutorialOverlay.style.display = "none";
    });
  }

  if (nextTutorial) {
    nextTutorial.addEventListener("click", function () {
      if (currentTutorialStep < totalTutorialSteps - 1) {
        showTutorialStep(currentTutorialStep + 1);
      } else {
        // Last step, close tutorial
        if (tutorialOverlay) {
          tutorialOverlay.classList.remove("show");
          tutorialOverlay.style.display = "none";
        }

        // Show welcome message
        const welcomeMessage = document.getElementById("welcomeMessage");
        if (welcomeMessage) {
          welcomeMessage.classList.add("show");
          welcomeMessage.style.display = "block";
        }
      }
    });
  }

  // Welcome message
  const welcomeMessage = document.getElementById("welcomeMessage");
  const closeWelcome = document.getElementById("closeWelcome");

  if (closeWelcome && welcomeMessage) {
    closeWelcome.addEventListener("click", function () {
      welcomeMessage.classList.remove("show");
      welcomeMessage.style.display = "none";
    });
  }

  // Toggle help tooltips
  const toggleHelp = document.getElementById("toggleHelp");

  if (toggleHelp) {
    toggleHelp.addEventListener("click", function () {
      const isShowingHelp = this.classList.toggle("active");

      if (isShowingHelp) {
        this.querySelector("span").textContent = "Hide Help";
      } else {
        this.querySelector("span").textContent = "Show Help";
      }
    });
  }
});

    </script>

  </body>
</html>
