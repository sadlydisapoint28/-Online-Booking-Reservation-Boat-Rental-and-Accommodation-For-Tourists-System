<?php
require_once('../../../../php/config/connect.php');
require_once('../../../../php/classes/Auth.php');
require_once('../../../../php/classes/Security.php');

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../../../php/pages/login admin/login.php');
    exit;
}

// Get notifications
$notifications = [];
try {
    $query = "SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5";
    $stmt = $pdo->query($query);
    $notifications = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching notifications: " . $e->getMessage());
}

// Get statistics
$stats = [
    'total_bookings' => 0,
    'total_revenue' => 0,
    'active_boats' => 0,
    'pending_bookings' => 0,
    'today_bookings' => 0,
    'upcoming_bookings' => 0
];

// Get total bookings
$total_bookings = $pdo->query("SELECT COUNT(*) FROM boat_reservations")->fetchColumn();

// Get total revenue
$total_revenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM boat_reservations WHERE payment_status = 'fully_paid'")->fetchColumn();

// Get active boats
$active_boats = $pdo->query("SELECT COUNT(*) FROM boats WHERE status = 'available'")->fetchColumn();

// Get pending bookings
$pending_bookings = $pdo->query("SELECT COUNT(*) FROM boat_reservations WHERE booking_status = 'pending'")->fetchColumn();

// Get today's bookings
$query = "SELECT COUNT(*) as count FROM boat_reservations WHERE DATE(booking_date) = CURDATE()";
$stmt = $pdo->query($query);
$stats['today_bookings'] = $stmt->fetch()['count'];

// Get upcoming bookings (next 7 days)
$query = "SELECT COUNT(*) as count FROM boat_reservations WHERE booking_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$stmt = $pdo->query($query);
$stats['upcoming_bookings'] = $stmt->fetch()['count'];

// Get recent bookings
$recent_bookings = $pdo->query("
    SELECT br.*, u.full_name, b.boat_name 
    FROM boat_reservations br 
    JOIN users u ON br.user_id = u.user_id 
    JOIN boats b ON br.boat_id = b.id 
    ORDER BY br.created_at DESC 
    LIMIT 5
")->fetchAll();

// Get monthly revenue data for chart
$monthly_revenue = $pdo->query("
    SELECT 
        DATE_FORMAT(booking_date, '%Y-%m') as month,
        SUM(total_amount) as total
    FROM boat_reservations 
    WHERE payment_status = 'fully_paid'
    GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
          ORDER BY month DESC 
    LIMIT 12
")->fetchAll();

$revenue_data = [];
$labels = [];
foreach ($monthly_revenue as $row) {
    $revenue_data[] = $row['total'];
    $labels[] = date('M Y', strtotime($row['month']));
}
$revenue_data = array_reverse($revenue_data);
$labels = array_reverse($labels);

// Get popular boats
$popular_boats = $pdo->query("
    SELECT b.boat_name, COUNT(br.id) as booking_count 
    FROM boats b 
    LEFT JOIN boat_reservations br ON b.id = br.boat_id 
    GROUP BY b.id 
    ORDER BY booking_count DESC 
    LIMIT 5
")->fetchAll();

// Get weather data
try {
    // Check if weather API is enabled
    $weather_enabled = false; // Set this to true when you have a valid API key
    $weather = null;
    
    if ($weather_enabled) {
        $weather_api_key = 'YOUR_OPENWEATHERMAP_API_KEY'; // Replace with your actual API key
        $city = 'Manila'; // Default city
        $weather_url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$weather_api_key}&units=metric";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $weather_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200 && $response) {
            $weather_data = json_decode($response, true);
            if ($weather_data && isset($weather_data['main'], $weather_data['weather'][0], $weather_data['wind'])) {
                $weather = [
                    'temperature' => round($weather_data['main']['temp']),
                    'humidity' => $weather_data['main']['humidity'],
                    'wind_speed' => $weather_data['wind']['speed'],
                    'description' => $weather_data['weather'][0]['description'],
                    'icon' => $weather_data['weather'][0]['icon']
                ];
            }
        }
    }
} catch (Exception $e) {
    error_log("Error fetching weather data: " . $e->getMessage());
    $weather = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Boat Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .notification-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            width: 320px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }
        .notification-dropdown.active {
            display: block;
        }
        .quick-action {
            transition: all 0.3s ease;
        }
        .quick-action:hover {
            transform: scale(1.05);
        }
        .search-bar {
            transition: all 0.3s ease;
        }
        .search-bar:focus {
            transform: scale(1.02);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
        <div class="flex-1 overflow-auto custom-scrollbar">
        <!-- Top Navigation -->
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>
                        <!-- Search Bar -->
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Search..." 
                                   class="search-bar pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Quick Actions -->
                        <div class="flex space-x-2">
                            <button class="quick-action p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="quick-action p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                        <!-- Notifications -->
                        <div class="relative">
                            <button id="notificationBtn" class="flex items-center text-gray-600 hover:text-gray-800">
                                <i class="fas fa-bell text-xl"></i>
                                <?php if (count($notifications) > 0): ?>
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        <?php echo count($notifications); ?>
                                    </span>
                                <?php endif; ?>
                    </button>
                            <div id="notificationDropdown" class="notification-dropdown">
                                <div class="p-4 border-b">
                                    <h3 class="font-semibold">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <?php if (empty($notifications)): ?>
                                        <div class="p-4 text-gray-500">No new notifications</div>
                                    <?php else: ?>
                                        <?php foreach ($notifications as $notification): ?>
                                            <div class="p-4 hover:bg-gray-50 border-b">
                                                <p class="text-sm text-gray-800"><?php echo htmlspecialchars($notification['message']); ?></p>
                                                <p class="text-xs text-gray-500 mt-1"><?php echo date('M d, Y H:i', strtotime($notification['created_at'])); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4 text-center">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View all notifications</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </header>

            <main class="p-6">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
                            <p class="text-gray-600">Here's what's happening with your boat rental business today.</p>
                        </div>
                        <div class="flex space-x-4">
                            <button class="quick-action px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i> New Booking
                            </button>
                            <button class="quick-action px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-ship mr-2"></i> Add Boat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Weather Widget -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Weather Conditions</h3>
                    <?php if ($weather): ?>
                        <div class="flex items-center justify-between">
                    <div class="flex items-center">
                                <img src="http://openweathermap.org/img/w/<?php echo $weather['icon']; ?>.png" 
                                     alt="<?php echo $weather['description']; ?>" 
                                     class="w-16 h-16">
                                <div class="ml-4">
                                    <p class="text-2xl font-semibold"><?php echo $weather['temperature']; ?>°C</p>
                                    <p class="text-gray-600 capitalize"><?php echo $weather['description']; ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Humidity: <?php echo $weather['humidity']; ?>%</p>
                                <p class="text-sm text-gray-600">Wind: <?php echo $weather['wind_speed']; ?> m/s</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-cloud text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">Weather information is currently unavailable</p>
                            <?php if (!$weather_enabled): ?>
                                <p class="text-sm text-gray-400 mt-1">To enable weather updates, please configure your OpenWeatherMap API key</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Bookings -->
                    <div class="stat-card p-6 card-hover">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600">Total Bookings</h3>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $total_bookings; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="stat-card p-6 card-hover">
                    <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>
                                <p class="text-2xl font-bold text-gray-800">₱<?php echo number_format($total_revenue, 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Boats -->
                    <div class="stat-card p-6 card-hover">
                    <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100">
                                <i class="fas fa-ship text-purple-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600">Active Boats</h3>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $active_boats; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Bookings -->
                    <div class="stat-card p-6 card-hover">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100">
                                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-600">Pending Bookings</h3>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $pending_bookings; ?></p>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Charts and Recent Bookings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Revenue Chart -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Revenue</h3>
                    <canvas id="revenueChart" height="300"></canvas>
                </div>

                <!-- Recent Bookings -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Bookings</h3>
                    <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                    <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($recent_bookings as $booking): ?>
                                        <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#<?php echo $booking['id']; ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($booking['boat_name']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php echo $booking['payment_status'] === 'fully_paid' ? 'bg-green-100 text-green-800' : 
                                                        ($booking['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-gray-100 text-gray-800'); ?>">
                                                    <?php echo ucfirst($booking['payment_status']); ?>
                                                </span>
                                            </td>
                                </tr>
                                    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

                <!-- Popular Boats -->
                <div class="mt-8 bg-white rounded-xl shadow-lg p-6 card-hover">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Boats</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($popular_boats as $boat): ?>
                            <div class="bg-gray-50 rounded-lg p-4 card-hover">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($boat['boat_name']); ?></h4>
                                        <p class="text-sm text-gray-600"><?php echo $boat['booking_count']; ?> bookings</p>
                                    </div>
                                    <div class="p-2 bg-blue-100 rounded-full">
                                        <i class="fas fa-ship text-blue-600"></i>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
        </main>
        </div>
    </div>

    <script>
        // Notification Dropdown Toggle
        document.getElementById('notificationBtn').addEventListener('click', function() {
            document.getElementById('notificationDropdown').classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#notificationBtn') && !event.target.closest('#notificationDropdown')) {
                document.getElementById('notificationDropdown').classList.remove('active');
            }
        });

        // Search functionality
        document.querySelector('.search-bar').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            // Add your search logic here
            console.log('Searching for:', searchTerm);
        });

        // Quick action buttons
        document.querySelectorAll('.quick-action').forEach(button => {
            button.addEventListener('click', function() {
                // Add your quick action logic here
                console.log('Quick action clicked:', this.querySelector('i').className);
            });
        });

        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: <?php echo json_encode($revenue_data); ?>,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    </script>
</body>
</html> 