<?php
require_once('../../config/connect.php');
require_once('../../classes/Auth.php');
require_once('../../classes/Security.php');

session_start();

$auth = new Auth($pdo);
$security = new Security($pdo);

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../login admin/login.php');
    exit;
}

// Fetch all bookings with user and boat details
$stmt = $pdo->query("
    SELECT b.*, u.full_name as user_name, bt.name as boat_name 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN boats bt ON b.boat_id = bt.id 
    ORDER BY b.created_at DESC
");
$bookings = $stmt->fetchAll();

// Fetch statistics
$stats = [
    'total_bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
    'total_users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'total_boats' => $pdo->query("SELECT COUNT(*) FROM boats")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(total_amount) FROM bookings")->fetchColumn()
];
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
</head>
<body class="bg-gray-100 font-body">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-indigo-800 text-white transition-transform duration-300 transform">
        <div class="flex items-center justify-between p-4 border-b border-indigo-700">
            <h1 class="text-xl font-bold">Admin Panel</h1>
            <button class="lg:hidden" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center gap-3 p-2 rounded-lg bg-indigo-700 hover:bg-indigo-600 transition-colors">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-ship"></i>
                        Boats
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-calendar-check"></i>
                        Bookings
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>

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
        <?php foreach ($bookings as $booking): ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-4">#<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($booking['user_name']); ?></td>
                            <td class="py-4"><?php echo htmlspecialchars($booking['boat_name']); ?></td>
                            <td class="py-4"><?php echo date('M d, Y', strtotime($booking['date'])); ?></td>
                            <td class="py-4">
                                <span class="px-2 py-1 text-xs rounded-full <?php echo $booking['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
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

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.fixed');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html>
