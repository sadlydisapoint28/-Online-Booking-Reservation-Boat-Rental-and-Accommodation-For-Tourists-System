<?php
require_once '../../../../php/config/connect.php';
require_once '../../../../php/classes/Auth.php';
require_once '../../../../php/classes/Security.php';
require_once '../../../../php/config/database.php';

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../../../php/pages/login admin/login.php');
    exit();
}

// Use the existing PDO connection
$conn = $pdo;

// Get date range for reports
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$boat_type = isset($_GET['boat_type']) ? $_GET['boat_type'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

try {
    // Get total revenue
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(total_amount), 0) as total
        FROM bookings 
        WHERE status = 'completed' 
        AND booking_date BETWEEN ? AND ?
    ");
    $stmt->execute([$startDate, $endDate]);
    $totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get total bookings
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total
        FROM bookings 
        WHERE booking_date BETWEEN ? AND ?
    ");
    $stmt->execute([$startDate, $endDate]);
    $totalBookings = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get average booking value
    $stmt = $conn->prepare("
        SELECT COALESCE(AVG(total_amount), 0) as average
        FROM bookings 
        WHERE status = 'completed' 
        AND booking_date BETWEEN ? AND ?
    ");
    $stmt->execute([$startDate, $endDate]);
    $avgBookingValue = $stmt->fetch(PDO::FETCH_ASSOC)['average'];

    // Get monthly revenue data for chart
    $stmt = $conn->prepare("
        SELECT 
            DATE_FORMAT(booking_date, '%Y-%m') as month,
            SUM(total_amount) as revenue
        FROM bookings 
        WHERE status = 'completed'
        AND booking_date BETWEEN ? AND ?
        GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
        ORDER BY month
    ");
    $stmt->execute([$startDate, $endDate]);
    $monthlyRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get top boats by revenue
    $stmt = $conn->prepare("
        SELECT 
            b.boat_name,
            COUNT(br.id) as total_bookings,
            SUM(br.total_amount) as total_revenue
        FROM boats b
        LEFT JOIN bookings br ON b.id = br.boat_id
        WHERE br.booking_date BETWEEN ? AND ?
        GROUP BY b.id
        ORDER BY total_revenue DESC
        LIMIT 5
    ");
    $stmt->execute([$startDate, $endDate]);
    $topBoats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get booking status distribution
    $stmt = $conn->prepare("
        SELECT 
            status as booking_status,
            COUNT(*) as count
        FROM bookings
        WHERE booking_date BETWEEN ? AND ?
        GROUP BY status
    ");
    $stmt->execute([$startDate, $endDate]);
    $bookingStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get payment status distribution
    $stmt = $conn->prepare("
        SELECT 
            payment_status,
            COUNT(*) as count
        FROM bookings
        WHERE booking_date BETWEEN ? AND ?
        GROUP BY payment_status
    ");
    $stmt->execute([$startDate, $endDate]);
    $paymentStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get boat types for filter
    $stmt = $conn->prepare("SELECT DISTINCT type FROM boats ORDER BY type");
    $stmt->execute();
    $boat_types = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    error_log("Reports error: " . $e->getMessage());
    $error = "Error loading reports";
}

// Get booking statistics
$stmt = $conn->prepare("
    SELECT COUNT(*) as total_bookings,
           SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
           SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
           SUM(total_amount) as total_revenue
    FROM bookings
    WHERE booking_date BETWEEN ? AND ?
");
$stmt->execute([$startDate, $endDate]);
$stats = $stmt->fetch();

// Bookings by boat type
$stmt = $conn->prepare("
    SELECT bt.type, COUNT(*) as count
    FROM bookings b
    JOIN boats bt ON b.boat_id = bt.id
    WHERE b.booking_date BETWEEN ? AND ?
    GROUP BY bt.type
");
$stmt->execute([$startDate, $endDate]);
$bookings_by_type = $stmt->fetchAll();

// Peak booking times
$stmt = $conn->prepare("
    SELECT HOUR(booking_date) as hour, COUNT(*) as count
    FROM bookings
    WHERE booking_date BETWEEN ? AND ?
    GROUP BY HOUR(booking_date)
    ORDER BY count DESC
    LIMIT 5
");
$stmt->execute([$startDate, $endDate]);
$peak_times = $stmt->fetchAll();

// Popular destinations
$stmt = $conn->prepare("
    SELECT destination, COUNT(*) as count
    FROM bookings
    WHERE booking_date BETWEEN ? AND ?
    GROUP BY destination
    ORDER BY count DESC
    LIMIT 5
");
$stmt->execute([$startDate, $endDate]);
$popular_destinations = $stmt->fetchAll();

// Bookings by customer type
$stmt = $conn->prepare("
    SELECT c.type, COUNT(*) as count
    FROM bookings b
    JOIN customers c ON b.customer_id = c.id
    WHERE b.booking_date BETWEEN ? AND ?
    GROUP BY c.type
");
$stmt->execute([$startDate, $endDate]);
$bookings_by_customer_type = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Carles Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.active {
                width: 250px;
            }
            .main-content.active {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
                        <button class="md:hidden text-gray-500" id="sidebarToggle">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Reports Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <!-- Date Range Filter -->
                <div class="bg-white shadow rounded-lg p-4 mb-6">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" value="<?php echo $startDate; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" value="<?php echo $endDate; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Boat Type</label>
                            <select name="boat_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Types</option>
                                <?php foreach ($boat_types as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>" 
                                        <?php echo $boat_type === $type ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="self-end">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-gray-500 text-sm">Total Revenue</h3>
                                <p class="text-2xl font-semibold text-gray-900">₱<?php echo number_format($totalRevenue, 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-calendar-check text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-gray-500 text-sm">Total Bookings</h3>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo number_format($totalBookings); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-gray-500 text-sm">Average Booking Value</h3>
                                <p class="text-2xl font-semibold text-gray-900">₱<?php echo number_format($avgBookingValue, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Revenue Chart -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue</h3>
                        <canvas id="revenueChart" height="300"></canvas>
                    </div>

                    <!-- Top Boats Chart -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Boats by Revenue</h3>
                        <canvas id="topBoatsChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Booking Status -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Status Distribution</h3>
                        <canvas id="bookingStatusChart" height="300"></canvas>
                    </div>

                    <!-- Payment Status -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Status Distribution</h3>
                        <canvas id="paymentStatusChart" height="300"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthlyRevenue, 'month')); ?>,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: <?php echo json_encode(array_column($monthlyRevenue, 'revenue')); ?>,
                    borderColor: 'rgb(79, 70, 229)',
                    tension: 0.1,
                    fill: true,
                    backgroundColor: 'rgba(79, 70, 229, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Top Boats Chart
        const topBoatsCtx = document.getElementById('topBoatsChart').getContext('2d');
        new Chart(topBoatsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($topBoats, 'boat_name')); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode(array_column($topBoats, 'total_revenue')); ?>,
                    backgroundColor: 'rgba(79, 70, 229, 0.8)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Booking Status Chart
        const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
        new Chart(bookingStatusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($bookingStatus, 'booking_status')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($bookingStatus, 'count')); ?>,
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',  // green
                        'rgba(234, 179, 8, 0.8)',  // yellow
                        'rgba(239, 68, 68, 0.8)'   // red
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Payment Status Chart
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        new Chart(paymentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($paymentStatus, 'payment_status')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($paymentStatus, 'count')); ?>,
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',  // green
                        'rgba(234, 179, 8, 0.8)',  // yellow
                        'rgba(239, 68, 68, 0.8)'   // red
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    </script>
</body>
</html> 