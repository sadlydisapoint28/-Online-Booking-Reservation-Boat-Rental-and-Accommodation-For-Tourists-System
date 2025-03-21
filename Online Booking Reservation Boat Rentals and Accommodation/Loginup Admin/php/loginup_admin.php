<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../php/config/connect.php');
require_once('../php/classes/Auth.php');
require_once('../php/classes/Security.php');

// Set session cookie parameters for better security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
ini_set('session.cookie_lifetime', 1800); // 30 minutes

session_start();

// Initialize Auth and Security classes
$auth = new Auth($pdo);
$security = new Security($pdo);

// Get client IP
$clientIP = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

// Check if IP is blocked
if ($security->isIPBlocked($clientIP)) {
    $_SESSION['login_error'] = "Your IP address has been blocked due to multiple failed attempts. Please try again later.";
    echo "login_error";
    exit();
}

// Check if user is already logged in
if($auth->isLoggedIn()) {
    echo "login_success";
    exit();
}

// Check if admin is already logged in
if($auth->isAdminLoggedIn()) {
    echo "login_success";
    exit();
}

// Display success message if registration was successful
if(isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check rate limiting
    if (!$security->checkRateLimit($clientIP)) {
        $_SESSION['login_error'] = "Too many attempts. Please try again later.";
        header("Location: loginup_admin.php");
        exit();
    }
    
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !$security->validateCSRFToken($_POST['csrf_token'])) {
        $_SESSION['login_error'] = "Invalid request. Please try again.";
        header("Location: loginup_admin.php");
        exit();
    }
    
    if(isset($_POST['user_login'])) {
        $email = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $rememberMe = isset($_POST['remember_me']);

        $result = $auth->loginUser($email, $password);
        
        if ($result['success']) {
            if ($rememberMe) {
                $token = $security->generateRememberToken();
                $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                $security->storeRememberToken($_SESSION['user_id'], $token, $expires);
                setcookie('remember_token', $token, strtotime('+30 days'), '/', '', true, true);
            }
            
            $_SESSION['login_success'] = true;
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_email'] = $email;
            $_SESSION['last_activity'] = time();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Login successful! Redirecting...',
                'redirect' => '../User Dashboard/userdashboard.php'
            ]);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
            exit();
        }
    } elseif(isset($_POST['admin_login'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $rememberMe = isset($_POST['remember_me']);

        $result = $auth->loginAdmin($email, $password);
        
        if ($result['success']) {
            if ($rememberMe) {
                $token = $security->generateRememberToken();
                $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                $security->storeRememberToken($_SESSION['admin_id'], $token, $expires);
                setcookie('remember_token', $token, strtotime('+30 days'), '/', '', true, true);
            }
            
            $_SESSION['login_success'] = true;
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $email;
            $_SESSION['last_activity'] = time();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Login successful! Redirecting...',
                'redirect' => '../AdminDashboard/index.php'
            ]);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
            exit();
        }
    }
}

// Generate CSRF token for forms
$csrf_token = $security->generateCSRFToken();

// Check for remember me token
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $user = $security->validateRememberToken($token);
    
    if ($user) {
        $stmt = $pdo->prepare("SELECT role FROM admin WHERE id = ?");
        $stmt->execute([$user['user_id']]);
        $admin = $stmt->fetch();
        
        if ($admin) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['admin_logged_in'] = true;
            header("Location: ../AdminDashboard/index.php");
            exit();
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_email'] = $user['email'];
            header("Location: ../User Dashboard/userdashboard.php");
            exit();
        }
    } else {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// If this is an AJAX request for popup content, only return the relevant form
if(isset($_GET['type'])) {
    // Set proper headers for AJAX response
    header('Content-Type: text/html');
    header('Cache-Control: no-cache, must-revalidate');
    
    try {
        if($_GET['type'] === 'user') {
            // User Login Form
            ?>
            <div class="space-y-8">
                <div class="text-center mb-8">
                    <i class="fas fa-user-circle text-blue-500 text-5xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-blue-700">Welcome Back!</h3>
                    <p class="text-gray-600 mt-2">Sign in to your account</p>
                </div>
                <form method="POST" action="loginup_admin.php" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="relative">
                        <input type="email" name="username" id="username" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="username" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Email
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="password" name="password" id="password" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 peer"
                            placeholder=" ">
                        <label for="password" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Password
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 text-lg peer-focus:text-blue-500"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember_me" id="remember_me" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" name="user_login" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account? 
                            <a href="signup.php" class="font-medium text-blue-600 hover:text-blue-500">Sign up</a>
                        </p>
                    </div>
                </form>
            </div>
            <?php
        } else if($_GET['type'] === 'admin') {
            // Admin Login Form
            ?>
            <div class="space-y-8">
                <div class="text-center">
                    <div class="inline-block p-3 rounded-full bg-indigo-100 mb-4">
                        <i class="fas fa-user-shield text-indigo-500 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-indigo-700">Welcome Back!</h3>
                    <p class="text-gray-600 mt-2">Sign in to admin dashboard</p>
                </div>
                <form method="POST" action="loginup_admin.php" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="relative">
                        <input type="email" name="email" id="email" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer"
                            placeholder=" ">
                        <label for="email" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Admin Email
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 text-lg peer-focus:text-indigo-500"></i>
                        </div>
                    </div>

                    <div class="relative">
                        <input type="password" name="password" id="password" required 
                            class="block w-full px-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 peer"
                            placeholder=" ">
                        <label for="password" 
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-9">
                            Password
                        </label>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 text-lg peer-focus:text-indigo-500"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember_me" id="remember_me" 
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" name="admin_login" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
            <?php
        } else {
            throw new Exception('Invalid login type specified');
        }
    } catch (Exception $e) {
        error_log("Error loading login form: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
    exit();
}
?>
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      class="w-full max-w-6xl bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-12 relative z-10 border border-blue-100"
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
                src="https://images.unsplash.com/photo-1567899378494-47b22a2ae96a?w=800&q=80"
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
                src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&q=80"
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

      <!-- Login Modal -->
      <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 relative">
          <!-- Close button area -->
          <button id="closeModal" class="absolute right-0 top-0 w-12 h-12 cursor-pointer"></button>

          <div class="p-8">
            <!-- Form Content -->
            <div id="loginContent">
              <!-- Content will be loaded here -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Get all the necessary elements
        const selectionView = document.getElementById("selection-view");
        const loginModal = document.getElementById("loginModal");
        const closeBtn = document.getElementById("closeModal");
        const userBtn = document.getElementById("user-btn");
        const adminBtn = document.getElementById("admin-btn");
        const loginContent = document.getElementById("loginContent");

        // Handle browser back button
        window.addEventListener('popstate', function(event) {
          if (loginModal.classList.contains('flex')) {
            loginModal.classList.add('hidden');
            loginModal.classList.remove('flex');
            selectionView.classList.remove('hidden');
          } else {
            window.location.href = '../php/pages/interface.php';
          }
        });

        // Show user login view by default when clicking user button
        userBtn.addEventListener("click", function() {
          selectionView.classList.add("hidden");
          loadLoginContent("user");
          // Add history state
          history.pushState({ modal: 'user' }, '', '');
        });

        // Show admin login view
        adminBtn.addEventListener("click", function() {
          selectionView.classList.add("hidden");
          loadLoginContent("admin");
          // Add history state
          history.pushState({ modal: 'admin' }, '', '');
        });

        // Hide modal
        function hideModal() {
          loginModal.classList.add("hidden");
          loginModal.classList.remove("flex");
          selectionView.classList.remove("hidden");
          // Add history state
          history.pushState({ modal: null }, '', '');
        }

        // Load login content
        async function loadLoginContent(type) {
          try {
            const response = await fetch(`loginup_admin.php?type=${type}`, {
              method: 'GET',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
              }
            });
            
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const content = await response.text();
            
            if (content.includes('Direct access not permitted')) {
              throw new Error('Direct access not permitted');
            }
            
            loginContent.innerHTML = content;
            loginModal.classList.remove("hidden");
            loginModal.classList.add("flex");
          } catch (error) {
            console.error("Detailed error:", error);
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to load login form. Please try again.",
              footer: error.message
            });
          }
        }

        // Event listeners
        closeBtn.addEventListener("click", hideModal);
        loginModal.addEventListener("click", (e) => {
          if (e.target === loginModal) hideModal();
        });

        // Handle form submissions
        document.addEventListener("submit", async function(e) {
          if (e.target.matches("form")) {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
              const response = await fetch("loginup_admin.php", {
                method: "POST",
                body: formData,
                credentials: 'same-origin'
              });
              
              const result = await response.json();
              
              if (result.success) {
                Swal.fire({
                  title: 'Success!',
                  text: result.message,
                  icon: 'success',
                  timer: 1500,
                  showConfirmButton: false
                }).then(() => {
                  window.location.href = result.redirect;
                });
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Login Failed",
                  text: result.message || "Invalid email or password. Please try again."
                });
              }
            } catch (error) {
              console.error("Login error:", error);
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "An error occurred during login. Please try again."
              });
            }
          }
        });
      });
    </script>
    <?php if(isset($_SESSION['login_success'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: 'You have successfully logged in!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3B82F6',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
    <?php 
    unset($_SESSION['login_success']); // Clear the success message after showing
    endif; 
    ?>
    <?php if(isset($_SESSION['login_error'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: '<?php echo $_SESSION['login_error']; ?>',
                icon: 'error',
                confirmButtonColor: '#3B82F6'
            });
        });
    </script>
    <?php 
    unset($_SESSION['login_error']); // Clear the error message after showing
    endif; 
    ?>
  </body>
</html>
