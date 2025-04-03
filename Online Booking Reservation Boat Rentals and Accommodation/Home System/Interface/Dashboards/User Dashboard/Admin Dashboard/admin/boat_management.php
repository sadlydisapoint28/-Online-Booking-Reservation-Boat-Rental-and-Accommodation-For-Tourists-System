<?php
session_start();
require_once '../../../../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Handle maintenance schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_maintenance'])) {
    $boat_id = $_POST['boat_id'];
    $maintenance_type = $_POST['maintenance_type'];
    $scheduled_date = $_POST['scheduled_date'];
    $description = $_POST['description'];
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO boat_maintenance (boat_id, maintenance_type, scheduled_date, description, status)
            VALUES (?, ?, ?, ?, 'scheduled')
        ");
        $stmt->execute([$boat_id, $maintenance_type, $scheduled_date, $description]);
        
        $_SESSION['success'] = "Maintenance scheduled successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error scheduling maintenance: " . $e->getMessage();
    }
}

// Handle price update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_price'])) {
    $boat_id = $_POST['boat_id'];
    $new_price = $_POST['new_price'];
    $effective_date = $_POST['effective_date'];
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Update current price
        $stmt = $conn->prepare("UPDATE boats SET price = ? WHERE id = ?");
        $stmt->execute([$new_price, $boat_id]);
        
        // Record price history
        $stmt = $conn->prepare("
            INSERT INTO boat_price_history (boat_id, price, effective_date)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$boat_id, $new_price, $effective_date]);
        
        $conn->commit();
        $_SESSION['success'] = "Price updated successfully!";
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Error updating price: " . $e->getMessage();
    }
}

// Get all boats
try {
    $stmt = $conn->prepare("
        SELECT b.*, 
               COUNT(DISTINCT m.id) as maintenance_count,
               COUNT(DISTINCT bh.id) as booking_count
        FROM boats b
        LEFT JOIN boat_maintenance m ON b.id = m.boat_id
        LEFT JOIN bookings bh ON b.id = bh.boat_id
        GROUP BY b.id
        ORDER BY b.name
    ");
    $stmt->execute();
    $boats = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching boats: " . $e->getMessage();
}

// Get maintenance history for a specific boat
function getMaintenanceHistory($conn, $boat_id) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM boat_maintenance 
            WHERE boat_id = ? 
            ORDER BY scheduled_date DESC
        ");
        $stmt->execute([$boat_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Get price history for a specific boat
function getPriceHistory($conn, $boat_id) {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM boat_price_history 
            WHERE boat_id = ? 
            ORDER BY effective_date DESC
        ");
        $stmt->execute([$boat_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Get upcoming bookings for a specific boat
function getUpcomingBookings($conn, $boat_id) {
    try {
        $stmt = $conn->prepare("
            SELECT b.*, c.name as customer_name
            FROM bookings b
            JOIN customers c ON b.customer_id = c.id
            WHERE b.boat_id = ? 
            AND b.booking_date >= CURDATE()
            AND b.status != 'cancelled'
            ORDER BY b.booking_date ASC
        ");
        $stmt->execute([$boat_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boat Management - Boat Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
</head>
<body class="bg-gray-100 font-body">
    <div class="flex h-screen overflow-hidden">
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="px-4 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-800">Boat Management</h1>
                        <div class="flex items-center space-x-4">
                            <a href="../logout.php" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Boat List -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Boat List</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($boats as $boat): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if ($boat['image']): ?>
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                        src="<?php echo htmlspecialchars($boat['image']); ?>" 
                                                        alt="<?php echo htmlspecialchars($boat['name']); ?>">
                                                <?php endif; ?>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($boat['name']); ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo htmlspecialchars($boat['description']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($boat['type']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ₱<?php echo number_format($boat['price'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo $boat['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo ucfirst($boat['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="showMaintenanceModal(<?php echo $boat['id']; ?>)" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-tools"></i> Maintenance
                                            </button>
                                            <button onclick="showPriceModal(<?php echo $boat['id']; ?>)" 
                                                class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-dollar-sign"></i> Price
                                            </button>
                                            <button onclick="showCalendar(<?php echo $boat['id']; ?>)" 
                                                class="text-purple-600 hover:text-purple-900">
                                                <i class="fas fa-calendar"></i> Calendar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Maintenance Modal -->
                <div id="maintenanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule Maintenance</h3>
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="boat_id" id="maintenance_boat_id">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Maintenance Type</label>
                                    <select name="maintenance_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="routine">Routine Maintenance</option>
                                        <option value="repair">Repair</option>
                                        <option value="inspection">Inspection</option>
                                        <option value="cleaning">Cleaning</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                                    <input type="date" name="scheduled_date" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="hideMaintenanceModal()"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                        Cancel
                                    </button>
                                    <button type="submit" name="schedule_maintenance"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                        Schedule
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Price Update Modal -->
                <div id="priceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Price</h3>
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="boat_id" id="price_boat_id">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">New Price</label>
                                    <input type="number" name="new_price" required step="0.01"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Effective Date</label>
                                    <input type="date" name="effective_date" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="hidePriceModal()"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                        Cancel
                                    </button>
                                    <button type="submit" name="update_price"
                                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Calendar Modal -->
                <div id="calendarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Boat Availability Calendar</h3>
                                <button onclick="hideCalendar()" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Initialize FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(info, successCallback, failureCallback) {
                    // Fetch events from server
                    fetch('get_calendar_events.php?boat_id=' + currentBoatId)
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data);
                        })
                        .catch(error => {
                            failureCallback(error);
                        });
                }
            });
            calendar.render();
        });

        // Modal functions
        let currentBoatId = null;

        function showMaintenanceModal(boatId) {
            currentBoatId = boatId;
            document.getElementById('maintenance_boat_id').value = boatId;
            document.getElementById('maintenanceModal').classList.remove('hidden');
        }

        function hideMaintenanceModal() {
            document.getElementById('maintenanceModal').classList.add('hidden');
        }

        function showPriceModal(boatId) {
            currentBoatId = boatId;
            document.getElementById('price_boat_id').value = boatId;
            document.getElementById('priceModal').classList.remove('hidden');
        }

        function hidePriceModal() {
            document.getElementById('priceModal').classList.add('hidden');
        }

        function showCalendar(boatId) {
            currentBoatId = boatId;
            document.getElementById('calendarModal').classList.remove('hidden');
            calendar.refetchEvents();
        }

        function hideCalendar() {
            document.getElementById('calendarModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('maintenanceModal')) {
                hideMaintenanceModal();
            }
            if (event.target == document.getElementById('priceModal')) {
                hidePriceModal();
            }
            if (event.target == document.getElementById('calendarModal')) {
                hideCalendar();
            }
        }
    </script>
</body>
</html> 