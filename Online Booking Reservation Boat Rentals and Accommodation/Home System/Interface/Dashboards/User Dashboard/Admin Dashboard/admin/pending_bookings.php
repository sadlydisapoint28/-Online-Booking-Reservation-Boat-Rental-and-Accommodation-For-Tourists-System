<?php
require_once('../../../../php/config/connect.php');
require_once('../../../../php/classes/Auth.php');
require_once('../../../../php/classes/Security.php');

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../../../../php/pages/login admin/login.php');
    exit;
}

// Get pending bookings
$query = "SELECT br.*, u.full_name as customer_name, b.boat_name 
          FROM boat_reservations br 
          JOIN users u ON br.user_id = u.user_id 
          JOIN boats b ON br.boat_id = b.id 
          WHERE br.booking_status = 'pending'
          ORDER BY br.created_at DESC";
$pending_bookings = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Bookings - Boat Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-indigo-900 to-purple-900 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Boat Rental</h1>
                <p class="text-sm text-indigo-200">Admin Dashboard</p>
            </div>
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <!-- Main Menu -->
                    <a href="dashboard.php" class="flex items-center px-6 py-3 text-indigo-200 hover:bg-indigo-800 rounded-lg transition-colors duration-200">
                        <i class="fas fa-home mr-3"></i>
                        Dashboard
                    </a>
                    
                    <!-- Bookings Section -->
                    <div class="mt-4">
                        <p class="px-6 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Bookings</p>
                        <a href="bookings.php" class="flex items-center px-6 py-3 text-indigo-200 hover:bg-indigo-800 rounded-lg transition-colors duration-200">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            All Bookings
                        </a>
                        <a href="pending_bookings.php" class="flex items-center px-6 py-3 text-white bg-indigo-800 rounded-lg">
                            <i class="fas fa-clock mr-3"></i>
                            Pending
                        </a>
                        <a href="completed_bookings.php" class="flex items-center px-6 py-3 text-indigo-200 hover:bg-indigo-800 rounded-lg transition-colors duration-200">
                            <i class="fas fa-check-circle mr-3"></i>
                            Completed
                        </a>
                    </div>

                    <!-- Rest of the menu items... -->
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">Pending Bookings</h2>
                </div>
            </header>

            <main class="p-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($booking = $pending_bookings->fetch()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#<?php echo $booking['id']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($booking['boat_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="text-green-600 hover:text-green-900 mr-3" onclick="approveBooking(<?php echo $booking['id']; ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" onclick="rejectBooking(<?php echo $booking['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function approveBooking(bookingId) {
            if (confirm('Are you sure you want to approve this booking?')) {
                // Add AJAX call to approve booking
                console.log('Approving booking:', bookingId);
            }
        }

        function rejectBooking(bookingId) {
            if (confirm('Are you sure you want to reject this booking?')) {
                // Add AJAX call to reject booking
                console.log('Rejecting booking:', bookingId);
            }
        }
    </script>
</body>
</html> 