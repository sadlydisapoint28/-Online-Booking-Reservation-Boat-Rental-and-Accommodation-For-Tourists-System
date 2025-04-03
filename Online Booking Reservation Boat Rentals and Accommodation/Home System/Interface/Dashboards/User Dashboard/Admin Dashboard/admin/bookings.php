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

// Get all bookings with user and boat details
$query = "SELECT br.*, u.full_name, u.email, u.phone_number, b.boat_name, b.boat_type 
        FROM boat_reservations br
          JOIN users u ON br.user_id = u.user_id 
        JOIN boats b ON br.boat_id = b.id
          ORDER BY br.created_at DESC";
$bookings = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Boat Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                        <h2 class="text-xl font-semibold text-gray-800">Manage Bookings</h2>
                        <!-- Search Bar -->
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Search bookings..." 
                                   class="search-bar pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Quick Actions -->
                        <div class="flex space-x-2">
                            <button class="quick-action p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full">
                                <i class="fas fa-filter"></i>
                            </button>
                            <button class="quick-action p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full">
                                <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
                </div>
            </header>

            <main class="p-6">
                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boat Type</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Types</option>
                                <option value="speed_boat">Speed Boat</option>
                                <option value="yacht">Yacht</option>
                                <option value="fishing_boat">Fishing Boat</option>
                                <option value="pontoon">Pontoon</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

            <!-- Bookings Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($bookings as $booking): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#<?php echo $booking['id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($booking['full_name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($booking['email']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($booking['boat_name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($booking['boat_type']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱<?php echo number_format($booking['total_amount'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo $booking['payment_status'] === 'fully_paid' ? 'bg-green-100 text-green-800' : 
                                                    ($booking['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo ucfirst($booking['payment_status']); ?>
                                    </span>
                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex space-x-2">
                                                <button class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                            </button>
                                                <button class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-check"></i>
                                        </button>
                                                <button class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-times"></i>
                                        </button>
                                            </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </main>
            </div>
    </div>

    <script>
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
    </script>
</body>
</html> 