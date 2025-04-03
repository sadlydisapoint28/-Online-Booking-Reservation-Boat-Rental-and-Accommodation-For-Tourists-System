<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

// Set session cookie parameters for better security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
ini_set('session.cookie_lifetime', 1800); // 30 minutes

session_start();

$auth = new Auth($pdo);
$security = new Security($pdo);

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login admin/login.php');
    exit;
}

// Get client IP
$clientIP = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

// Check if IP is blocked
if ($security->isIPBlocked($clientIP)) {
    header('Location: ../login admin/login.php?error=ip_blocked');
    exit;
}

// Generate CSRF token
$csrf_token = $security->generateCSRFToken();

// Handle admin approval actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $action = $_POST['action'];
        $user_id = (int)$_POST['user_id'];
        
        if ($action === 'approve_admin') {
            if ($auth->isAdminLimitReached()) {
                $_SESSION['error_message'] = "Cannot approve more admins. Maximum limit of 3 admins has been reached.";
            } else if ($auth->approveAdmin($user_id)) {
                $_SESSION['success_message'] = "Admin access approved successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to approve admin access.";
            }
        } elseif ($action === 'reject_admin') {
            if ($auth->rejectAdmin($user_id)) {
                $_SESSION['success_message'] = "Admin access request rejected.";
            } else {
                $_SESSION['error_message'] = "Failed to reject admin access request.";
            }
        }
        
        // Redirect to refresh the page
        header("Location: admin.php");
        exit();
    }
}

// Get pending admin requests
$pending_admins = $auth->getPendingAdminRequests();
$pending_count = count($pending_admins);

// Get recent bookings
$recentBookings = $pdo->query("
    SELECT br.*, u.full_name as customer_name, b.boat_name
    FROM boat_reservations br
    JOIN users u ON br.user_id = u.id
    JOIN boats b ON br.boat_id = b.id
    ORDER BY br.created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Get booking statistics
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total_bookings,
        SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
        SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
        SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed_bookings
    FROM boat_reservations
")->fetch(PDO::FETCH_ASSOC);

// Redirect to admin dashboard after successful login
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ../../Dashboards/User Dashboard/Admin Dashboard/index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-body">
    <div class="flex h-screen overflow-hidden">
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-800 ml-4">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Create Admin Button -->
                        <a href="create_admin.php" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-user-plus mr-2"></i> Create Admin
                        </a>
                        
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button id="notification-btn" class="p-1 text-gray-400 rounded-full hover:bg-gray-100 focus:outline-none relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <?php if ($pending_count > 0): ?>
                                <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs">
                                    <?php echo $pending_count; ?>
                                </span>
                                <?php endif; ?>
                            </button>
                            
                            <!-- Notification dropdown -->
                            <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <div class="px-4 py-2 border-b">
                                    <h3 class="text-sm font-semibold text-gray-700">Admin Requests</h3>
                                </div>
                                
                                <?php if ($pending_count > 0): ?>
                                <div class="max-h-72 overflow-y-auto">
                                    <?php foreach ($pending_admins as $admin): ?>
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b last:border-b-0">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($admin['full_name']); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($admin['email']); ?></p>
                                                <p class="text-xs text-blue-500 mt-1">Requested admin access</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <form method="POST" action="">
                                                    <input type="hidden" name="user_id" value="<?php echo $admin['user_id']; ?>">
                                                    <input type="hidden" name="action" value="approve_admin">
                                                    <button type="submit" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="user_id" value="<?php echo $admin['user_id']; ?>">
                                                    <input type="hidden" name="action" value="reject_admin">
                                                    <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    No pending admin requests
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="border-l pl-4">
                            <span class="text-sm font-medium text-gray-900">Admin</span>
                            <button id="user-menu-button" class="ml-2 bg-gray-800 text-white p-1 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                                <span class="sr-only">Open user menu</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <div class="ml-64 p-8">
                <!-- Top Bar -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
                    <div class="flex items-center gap-4">
                        <button class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                        <div class="relative">
                            <button class="flex items-center gap-2 text-gray-600 hover:text-gray-800">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_name']); ?>&background=6366f1&color=fff" alt="Admin" class="w-8 h-8 rounded-full">
                                <span><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Bookings</p>
                                <h3 class="text-2xl font-bold text-gray-800"><?php echo $stats['total_bookings']; ?></h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Users</p>
                                <h3 class="text-2xl font-bold text-gray-800"><?php echo $stats['total_users']; ?></h3>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Boats</p>
                                <h3 class="text-2xl font-bold text-gray-800"><?php echo $stats['total_boats']; ?></h3>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-ship text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Revenue</p>
                                <h3 class="text-2xl font-bold text-gray-800">₱<?php echo number_format($stats['revenue'], 2); ?></h3>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings Table -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Recent Bookings</h3>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                            View All
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-gray-200">
                                    <th class="pb-4 text-gray-500">Booking ID</th>
                                    <th class="pb-4 text-gray-500">Customer</th>
                                    <th class="pb-4 text-gray-500">Boat</th>
                                    <th class="pb-4 text-gray-500">Date</th>
                                    <th class="pb-4 text-gray-500">Status</th>
                                    <th class="pb-4 text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                <?php foreach ($recentBookings as $booking): ?>
                                <tr class="border-b border-gray-100">
                                    <td class="py-4">#<?php echo str_pad($booking['booking_id'], 6, '0', STR_PAD_LEFT); ?></td>
                                    <td class="py-4"><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                    <td class="py-4"><?php echo htmlspecialchars($booking['boat_name']); ?></td>
                                    <td class="py-4"><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></td>
                                    <td class="py-4">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo $booking['booking_status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo ucfirst($booking['booking_status']); ?>
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <button class="text-indigo-600 hover:text-indigo-800 mr-2">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-indigo-600 hover:text-indigo-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Show success or error message
        <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo $_SESSION['success_message']; ?>',
            showConfirmButton: false,
            timer: 3000
        });
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $_SESSION['error_message']; ?>',
            showConfirmButton: true
        });
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        // Toggle sidebar on mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.lg\\:flex-shrink-0');
            sidebar.classList.toggle('hidden');
        });
        
        // Toggle notification dropdown
        const notificationBtn = document.getElementById('notification-btn');
        const notificationDropdown = document.getElementById('notification-dropdown');
        
        if (notificationBtn && notificationDropdown) {
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationDropdown.contains(e.target) && e.target !== notificationBtn) {
                    notificationDropdown.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
