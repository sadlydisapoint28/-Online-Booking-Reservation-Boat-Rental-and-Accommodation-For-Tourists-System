<?php
// Initialize session
session_start();

// Include auth and security classes
require_once '../../php/classes/Auth.php';
require_once '../../php/classes/Security.php';

// Initialize database connection
try {
    $host = 'localhost';
    $db = 'booking_system';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Could not connect to the database. Please try again later.");
}

// Create auth instance with database connection
$auth = new Auth($pdo);
$security = new Security();

// Check if user is logged in
$auth->requireLogin();

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? 0;

// Check if user is an admin and redirect to admin dashboard
$stmt = $pdo->prepare("SELECT user_type FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_type = $stmt->fetchColumn();

if ($user_type === 'admin') {
    header("Location: Admin%20Dashboard/admin/dashboard.php");
    exit();
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND user_type = 'customer'");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// If user not found in users table, check customers table
if (!$user) {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Convert customer data to match user format
        $user['user_id'] = $user['id'];
        $user['full_name'] = $user['name'];
        $user['user_type'] = $user['type'];
    }
}

// Get available boats
$stmt = $pdo->prepare("
    SELECT * FROM boats 
    WHERE status = 'available' 
    ORDER BY created_at DESC
");
$stmt->execute();
$available_boats = $stmt->fetchAll();

// Get user's upcoming bookings
$stmt = $pdo->prepare("
    SELECT b.*, bt.boat_name, bt.price_per_hour as price, bt.capacity 
    FROM bookings b 
    JOIN boats bt ON b.boat_id = bt.id 
    WHERE b.customer_id = ? AND b.booking_date >= CURDATE()
    ORDER BY b.booking_date ASC
    LIMIT 5
");
$stmt->execute([$user_id]);
$upcoming_bookings = $stmt->fetchAll();

// Get user's past bookings
$stmt = $pdo->prepare("
    SELECT b.*, bt.boat_name, bt.price_per_hour as price 
    FROM bookings b 
    JOIN boats bt ON b.boat_id = bt.id 
    WHERE b.customer_id = ? AND b.booking_date < CURDATE()
    ORDER BY b.booking_date DESC
    LIMIT 5
");
$stmt->execute([$user_id]);
$past_bookings = $stmt->fetchAll();

// Get all user's bookings for calendar (without limit)
$stmt = $pdo->prepare("
    SELECT b.*, bt.boat_name
    FROM bookings b 
    JOIN boats bt ON b.boat_id = bt.id 
    WHERE b.customer_id = ?
    ORDER BY b.booking_date ASC
");
$stmt->execute([$user_id]);
$calendar_bookings = $stmt->fetchAll();

// Get active reservations (boats currently reserved by the user)
$stmt = $pdo->prepare("
    SELECT b.*, bt.boat_name, bt.price_per_hour as price, bt.capacity 
    FROM bookings b 
    JOIN boats bt ON b.boat_id = bt.id 
    WHERE b.customer_id = ? AND b.status = 'confirmed'
    ORDER BY b.booking_date ASC
");
$stmt->execute([$user_id]);
$active_reservations = $stmt->fetchAll();

// Format calendar events for JSON
$calendar_events = [];
foreach ($calendar_bookings as $booking) {
    $calendar_events[] = [
        'id' => $booking['id'],
        'title' => $booking['boat_name'],
        'start' => $booking['booking_date'],
        'end' => date('Y-m-d', strtotime($booking['booking_date'] . ' +1 day')),
        'status' => $booking['status'],
        'allDay' => true,
        'className' => $booking['status'] === 'completed' ? 'bg-green-500' : 
                      ($booking['status'] === 'pending' ? 'bg-yellow-500' : 
                       ($booking['status'] === 'confirmed' ? 'bg-blue-500' : 'bg-red-500'))
    ];
}
$calendar_events_json = json_encode($calendar_events);
?>

<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boat Rental Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .main-content {
            transition: all 0.3s ease;
        }
        .main-content.expanded {
            margin-left: -200px;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .boat-card {
            transition: all 0.3s ease;
        }
        .boat-card:hover {
            transform: scale(1.02);
        }

        /* Dark theme styles */
        .dark {
            color-scheme: dark;
        }
        .dark body {
            background-color: #1a1a1a;
            color: #e5e5e5;
        }
        .dark .bg-white {
            background-color: #2d2d2d;
        }
        .dark .text-gray-800 {
            color: #e5e5e5;
        }
        .dark .text-gray-600 {
            color: #a3a3a3;
        }
        .dark .bg-gray-100 {
            background-color: #1a1a1a;
        }
        .dark .border-gray-200 {
            border-color: #404040;
        }
        .dark .bg-gray-50 {
            background-color: #333333;
        }
        .dark .text-gray-500 {
            color: #a3a3a3;
        }
        .dark .text-gray-900 {
            color: #e5e5e5;
        }
        .dark .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        }
        .dark .hover\:text-gray-700:hover {
            color: #e5e5e5;
        }
        .dark .bg-blue-100 {
            background-color: #1e3a8a;
        }
        .dark .text-blue-600 {
            color: #60a5fa;
        }
        .dark .bg-green-100 {
            background-color: #065f46;
        }
        .dark .text-green-600 {
            color: #34d399;
        }
        .dark .bg-purple-100 {
            background-color: #5b21b6;
        }
        .dark .text-purple-600 {
            color: #a78bfa;
        }
        .dark .bg-blue-600 {
            background-color: #1e40af;
        }
        .dark .bg-blue-700 {
            background-color: #1e3a8a;
        }
        .dark .text-blue-200 {
            color: #bfdbfe;
        }
        .dark .w-px {
            background-color: #404040;
        }
        .dark .border-b {
            border-color: #404040;
        }
        .dark .divide-y {
            border-color: #404040;
        }
        .dark .divide-gray-200 {
            border-color: #404040;
        }
        .dark .bg-green-100 {
            background-color: #065f46;
        }
        .dark .bg-yellow-100 {
            background-color: #854d0e;
        }
        .dark .bg-red-100 {
            background-color: #991b1b;
        }
        .dark .text-green-800 {
            color: #34d399;
        }
        .dark .text-yellow-800 {
            color: #fbbf24;
        }
        .dark .text-red-800 {
            color: #f87171;
        }
        .dark .text-blue-900 {
            color: #60a5fa;
        }
        .dark .text-red-900 {
            color: #f87171;
        }
        .dark .text-green-900 {
            color: #34d399;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-600 text-white w-64 flex-shrink-0">
            <div class="p-4">
                <div class="flex items-center space-x-4">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo $user['email'] ?? 'user'; ?>" 
                         alt="Profile" 
                         class="w-12 h-12 rounded-full">
                    <div>
                        <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($user['full_name'] ?? $user['name'] ?? 'User'); ?></h2>
                        <p class="text-sm text-blue-200"><?php echo htmlspecialchars($user['email'] ?? 'No email'); ?></p>
                        <p class="text-xs text-blue-300"><?php echo ucfirst($user['user_type'] ?? 'Customer'); ?></p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-8">
                <a href="#dashboard" class="flex items-center px-6 py-3 bg-blue-700 text-white">
                    <i class="fas fa-home w-6"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#available-boats" class="flex items-center px-6 py-3 hover:bg-blue-700 text-white">
                    <i class="fas fa-ship w-6"></i>
                    <span>View Available Boats</span>
                </a>
                <a href="#book-boat" class="flex items-center px-6 py-3 hover:bg-blue-700 text-white">
                    <i class="fas fa-bookmark w-6"></i>
                    <span>Book a Boat</span>
                </a>
                <a href="#calendar" class="flex items-center px-6 py-3 hover:bg-blue-700 text-white">
                    <i class="fas fa-calendar-alt w-6"></i>
                    <span>View Calendar</span>
                </a>
                <a href="#bookings" class="flex items-center px-6 py-3 hover:bg-blue-700 text-white">
                    <i class="fas fa-calendar-check w-6"></i>
                    <span>My Bookings</span>
                </a>
                <a href="#reservations" class="flex items-center px-6 py-3 hover:bg-blue-700 text-white">
                    <i class="fas fa-ticket-alt w-6"></i>
                    <span>Reservations in Boats</span>
                </a>
                <a href="#profile" class="flex items-center px-6 py-3 hover:bg-blue-700 text-white">
                    <i class="fas fa-user w-6"></i>
                    <span>Manage Profile</span>
                </a>
                <a href="../php/pages/logout.php" class="flex items-center px-6 py-3 hover:bg-blue-700 text-white">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

    <!-- Main Content -->
        <div class="main-content flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="text-2xl font-semibold text-gray-800">Welcome, <?php echo htmlspecialchars($user['full_name'] ?? $user['name'] ?? 'User'); ?>!</h1>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo ($user['user_type'] ?? '') === 'vip' ? 'bg-purple-100 text-purple-800' : (($user['user_type'] ?? '') === 'group' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                            <?php echo ucfirst($user['user_type'] ?? 'Regular'); ?> Customer
                        </span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:block"></i>
                        </button>
                        
                        <!-- Date and Time -->
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div id="time" class="text-lg font-semibold text-gray-800"></div>
                                <div id="date" class="text-sm text-gray-600"></div>
                            </div>
                            <div class="w-px h-8 bg-gray-300"></div>
                            <div id="calendar" class="text-center">
                                <div id="calendar-day" class="text-2xl font-bold text-blue-600"></div>
                                <div id="calendar-month" class="text-sm text-gray-600"></div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <button class="text-gray-500 hover:text-gray-700 relative">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Tab Content Sections -->
                <!-- Dashboard Tab (default view) -->
                <div id="dashboard" class="tab-content">
                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <a href="#book-boat" class="bg-white rounded-lg shadow p-6 card-hover">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-ship text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-gray-800 font-semibold">Book a Boat</h3>
                                    <p class="text-sm text-gray-600">Find and book your perfect boat</p>
                                </div>
                            </div>
                        </a>
                        <a href="#bookings" class="bg-white rounded-lg shadow p-6 card-hover">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <i class="fas fa-calendar-check text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-gray-800 font-semibold">My Bookings</h3>
                                    <p class="text-sm text-gray-600">View and manage your bookings</p>
                                </div>
                            </div>
                        </a>
                        <a href="#calendar" class="bg-white rounded-lg shadow p-6 card-hover">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                    <i class="fas fa-calendar-alt text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-gray-800 font-semibold">View Calendar</h3>
                                    <p class="text-sm text-gray-600">See your booking schedule</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Available Boats -->
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Available Boats</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                            <?php foreach ($available_boats as $boat): ?>
                            <div class="boat-card bg-white rounded-lg shadow overflow-hidden">
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-ship text-4xl text-gray-400"></i>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($boat['boat_name'] ?? 'Unnamed Boat'); ?></h3>
                                    <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($boat['description'] ?? 'No description available'); ?></p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-users text-gray-500 mr-2"></i>
                                            <span class="text-sm text-gray-600">Capacity: <?php echo $boat['capacity'] ?? 0; ?> persons</span>
                                        </div>
                                        <span class="text-lg font-semibold text-blue-600">$<?php echo number_format($boat['price_per_hour'] ?? 0, 2); ?></span>
                                    </div>
                                    <a href="#book-boat" data-boat-id="<?php echo $boat['id']; ?>" class="book-now-btn mt-4 block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Upcoming Bookings Summary -->
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6 border-b flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">Upcoming Bookings</h2>
                            <a href="#bookings" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($upcoming_bookings)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No upcoming bookings found</td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach ($upcoming_bookings as $booking): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-ship text-gray-400"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($booking['boat_name'] ?? 'Unknown Boat'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('M d, Y', strtotime($booking['booking_date'] ?? 'now')); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                $<?php echo number_format($booking['total_amount'] ?? 0, 2); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo ($booking['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                                                    (($booking['status'] ?? '') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-red-100 text-red-800'); ?>">
                                                <?php echo ucfirst($booking['status'] ?? 'Unknown'); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View Details</a>
                                            <?php if (($booking['status'] ?? '') === 'pending'): ?>
                                            <a href="#" class="text-red-600 hover:text-red-900">Cancel</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Available Boats Tab -->
                <div id="available-boats" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">All Available Boats</h2>
                            <p class="text-gray-600 mt-1">Browse our collection of available boats for rent.</p>
                        </div>
                        
                        <!-- Search and Filter -->
                        <div class="p-6 bg-gray-50 border-b">
                            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                                <div class="flex-1">
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                    <input type="text" id="search" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Search boats by name or description...">
                                </div>
                                <div>
                                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                                    <select id="capacity" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Any capacity</option>
                                        <option value="2">2+ persons</option>
                                        <option value="4">4+ persons</option>
                                        <option value="6">6+ persons</option>
                                        <option value="8">8+ persons</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                                    <select id="price" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Any price</option>
                                        <option value="100">Up to $100</option>
                                        <option value="200">Up to $200</option>
                                        <option value="500">Up to $500</option>
                                        <option value="1000">Up to $1,000</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Boat Listings -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                            <?php foreach ($available_boats as $boat): ?>
                            <div class="boat-card bg-white rounded-lg shadow overflow-hidden">
                                <img src="<?php echo htmlspecialchars($boat['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($boat['name']); ?>"
                                     class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($boat['name']); ?></h3>
                                    <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($boat['description']); ?></p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-users text-gray-500 mr-2"></i>
                                            <span class="text-sm text-gray-600">Capacity: <?php echo $boat['capacity']; ?> persons</span>
                                        </div>
                                        <span class="text-lg font-semibold text-blue-600">$<?php echo number_format($boat['price'], 2); ?></span>
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="text-sm text-gray-600">4.5/5 (12 reviews)</span>
                                    </div>
                                    <div class="mt-4 flex space-x-2">
                                        <a href="#book-boat" data-boat-id="<?php echo $boat['id']; ?>" class="book-now-btn flex-1 bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                                            Book Now
                                        </a>
                                        <button class="boat-details-btn bg-gray-200 text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-300 transition duration-300" data-boat-id="<?php echo $boat['id']; ?>">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Book a Boat Tab -->
                <div id="book-boat" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Book a Boat</h2>
                            <p class="text-gray-600 mt-1">Complete the form below to book your boat rental.</p>
                        </div>
                        
                        <div class="p-6">
                            <form id="booking-form" class="space-y-6">
                                <input type="hidden" id="boat_id" name="boat_id" value="">
                                
                                <!-- Selected Boat Preview (initially hidden) -->
                                <div id="selected-boat-preview" class="hidden mb-6 border rounded-lg overflow-hidden">
                                    <div class="p-4 bg-blue-50 border-b flex items-center justify-between">
                                        <h3 class="font-medium text-blue-800" id="selected-boat-name">Boat Name</h3>
                                        <button type="button" id="change-boat-btn" class="text-sm text-blue-600 hover:text-blue-800">
                                            Change Boat
                                        </button>
                                    </div>
                                    <div class="p-4 flex items-center">
                                        <img id="selected-boat-image" src="" alt="Boat" class="w-24 h-24 object-cover rounded-lg">
                                        <div class="ml-4">
                                            <div class="flex items-center mt-1">
                                                <i class="fas fa-users text-gray-500 mr-2"></i>
                                                <span class="text-sm text-gray-600" id="selected-boat-capacity">Capacity: 0 persons</span>
                                            </div>
                                            <div class="flex items-center mt-1">
                                                <i class="fas fa-tag text-gray-500 mr-2"></i>
                                                <span class="text-sm text-gray-600" id="selected-boat-price">$0.00 per day</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Boat Selection (initially shown) -->
                                <div id="boat-selection" class="mb-6">
                                    <label for="boat_select" class="block text-sm font-medium text-gray-700 mb-1">Select a Boat</label>
                                    <select id="boat_select" name="boat_select" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">-- Select a boat --</option>
                                        <?php foreach ($available_boats as $boat): ?>
                                        <option value="<?php echo $boat['id']; ?>" 
                                                data-name="<?php echo htmlspecialchars($boat['name']); ?>"
                                                data-price="<?php echo $boat['price']; ?>"
                                                data-capacity="<?php echo $boat['capacity']; ?>"
                                                data-image="<?php echo htmlspecialchars($boat['image_url']); ?>">
                                            <?php echo htmlspecialchars($boat['name']); ?> - $<?php echo number_format($boat['price'], 2); ?> per day
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Booking Date</label>
                                        <input type="date" id="booking_date" name="booking_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div>
                                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (days)</label>
                                        <input type="number" id="duration" name="duration" min="1" max="30" value="1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="number_of_people" class="block text-sm font-medium text-gray-700 mb-1">Number of People</label>
                                        <input type="number" id="number_of_people" name="number_of_people" min="1" value="1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                        <p class="text-sm text-red-600 hidden" id="capacity-warning">Warning: Exceeds boat capacity</p>
                                    </div>
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                                        <input type="tel" id="contact_phone" name="contact_phone" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-1">Special Requests</label>
                                    <textarea id="special_requests" name="special_requests" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                                
                                <!-- Booking Summary -->
                                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                    <h3 class="font-medium text-gray-800 mb-2">Booking Summary</h3>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Base Price:</span>
                                        <span class="font-medium" id="base-price">$0.00</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Duration:</span>
                                        <span id="duration-display">1 day(s)</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-medium" id="subtotal">$0.00</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Tax (10%):</span>
                                        <span id="tax-amount">$0.00</span>
                                    </div>
                                    <div class="flex justify-between py-2 text-lg font-bold">
                                        <span>Total:</span>
                                        <span id="total-price">$0.00</span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-4">
                                    <button type="button" id="cancel-booking" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Confirm Booking
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bookings Tab -->
                <div id="bookings" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">My Bookings</h2>
                            <p class="text-gray-600 mt-1">View and manage all your boat bookings.</p>
                        </div>
                        
                        <!-- Booking Tabs -->
                        <div class="border-b">
                            <div class="flex">
                                <button id="upcoming-tab-btn" class="px-6 py-3 border-b-2 border-blue-500 text-blue-600 font-medium">Upcoming</button>
                                <button id="past-tab-btn" class="px-6 py-3 text-gray-500 hover:text-gray-700">Past</button>
                            </div>
                        </div>
                        
                        <!-- Upcoming Bookings Table -->
                        <div id="upcoming-bookings-tab" class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($upcoming_bookings as $booking): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="<?php echo htmlspecialchars($booking['image_url']); ?>" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($booking['boat_name']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                $<?php echo number_format($booking['price'], 2); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo $booking['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                                    ($booking['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-red-100 text-red-800'); ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View Details</a>
                                            <?php if ($booking['status'] === 'pending'): ?>
                                            <a href="#" class="text-red-600 hover:text-red-900">Cancel</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Past Bookings Table (Initially Hidden) -->
                        <div id="past-bookings-tab" class="overflow-x-auto hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($past_bookings)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No past bookings found</td>
                                    </tr>
                                    <?php else: ?>
                                    <?php foreach ($past_bookings as $booking): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-ship text-gray-400"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($booking['boat_name'] ?? 'Unknown Boat'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('M d, Y', strtotime($booking['booking_date'] ?? 'now')); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                $<?php echo number_format($booking['total_amount'] ?? 0, 2); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo ($booking['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                                                    'bg-red-100 text-red-800'; ?>">
                                                <?php echo ucfirst($booking['status'] ?? 'Unknown'); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View Details</a>
                                            <?php if (($booking['status'] ?? '') === 'completed'): ?>
                                            <a href="#" class="text-green-600 hover:text-green-900">Leave Review</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Calendar View Tab -->
                <div id="calendar" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Booking Calendar</h2>
                            <p class="text-gray-600 mt-1">View your scheduled bookings on the calendar.</p>
                        </div>
                        
                        <div class="p-6">
                            <!-- Calendar Legend -->
                            <div class="flex flex-wrap gap-4 mb-6">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600">Completed</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600">Pending</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600">Active</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-gray-600">Cancelled</span>
                                </div>
                            </div>
                            
                            <!-- Calendar Container -->
                            <div id="booking-calendar" class="h-96 md:h-[600px]"></div>
                        </div>
                    </div>
                </div>

                <!-- More tabs will be added in subsequent edits -->
            </div>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        });

        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        // Check for saved theme preference
        if (localStorage.getItem('theme') === 'dark' || 
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }

        // Theme toggle click handler
        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            
            // Update icon visibility
            const moonIcon = themeToggle.querySelector('.fa-moon');
            const sunIcon = themeToggle.querySelector('.fa-sun');
            
            if (html.classList.contains('dark')) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                moonIcon.classList.remove('hidden');
                sunIcon.classList.add('hidden');
            }
        });

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            
            // Time
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            });
            document.getElementById('time').textContent = timeString;
            
            // Date
            const dateString = now.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            document.getElementById('date').textContent = dateString;
            
            // Calendar
            document.getElementById('calendar-day').textContent = now.getDate();
            document.getElementById('calendar-month').textContent = now.toLocaleString('en-US', { month: 'short' });
        }

        // Update every second
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Initialize theme icon visibility
        const moonIcon = themeToggle.querySelector('.fa-moon');
        const sunIcon = themeToggle.querySelector('.fa-sun');
        if (html.classList.contains('dark')) {
            moonIcon.classList.add('hidden');
            sunIcon.classList.remove('hidden');
        } else {
            moonIcon.classList.remove('hidden');
            sunIcon.classList.add('hidden');
        }
    </script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('booking-calendar');
            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: <?php echo $calendar_events_json; ?>,
                    eventClick: function(info) {
                        alert('Booking: ' + info.event.title + '\nStatus: ' + info.event.extendedProps.status);
                    }
                });
                calendar.render();
            }

            // Tab switching functionality
            const tabs = document.querySelectorAll('.dashboard-tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs and tab contents
                    tabs.forEach(t => t.classList.remove('bg-blue-700'));
                    tabContents.forEach(content => content.classList.add('hidden'));
                    
                    // Add active class to clicked tab
                    tab.classList.add('bg-blue-700');
                    
                    // Show corresponding tab content
                    const targetId = tab.getAttribute('href').substring(1);
                    document.getElementById(targetId).classList.remove('hidden');
                    
                    // Render calendar if calendar tab is active
                    if(targetId === 'calendar' && calendar) {
                        setTimeout(() => calendar.updateSize(), 100);
                    }
                });
            });

            // Get the hash from the URL
            const hash = window.location.hash || '#dashboard';
            
            // Activate the tab corresponding to the hash
            const activeTab = document.querySelector(`a[href="${hash}"]`);
            if (activeTab) {
                activeTab.click();
            } else {
                // Default to dashboard tab
                document.querySelector('a[href="#dashboard"]').click();
            }
        });
    </script>
</body>
</html>
