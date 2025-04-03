<?php
session_start();
require_once '../../../../php/config/connect.php';
require_once '../../../../php/classes/Auth.php';
require_once '../../../../php/classes/Security.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../../../php/pages/login admin/login.php');
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Stats - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include('includes/sidebar.php'); ?>

        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-gray-800">Quick Stats</h1>
                <p class="text-gray-600">Overview of key metrics and performance indicators</p>
            </div>

            <!-- Time Period Filter -->
            <div class="mb-6">
                <select class="border border-gray-300 rounded-md px-4 py-2">
                    <option>Today</option>
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>This Month</option>
                    <option>This Year</option>
                </select>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Bookings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">New Bookings</h3>
                        <span class="text-blue-500 bg-blue-100 rounded-full p-2">
                            <i class="fas fa-calendar-plus"></i>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-3xl font-bold text-gray-800">48</span>
                            <span class="text-green-500 text-sm ml-2">+12%</span>
                        </div>
                        <div class="text-xs text-gray-500">vs last period</div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Today's Revenue</h3>
                        <span class="text-green-500 bg-green-100 rounded-full p-2">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-3xl font-bold text-gray-800">₱12,845</span>
                            <span class="text-green-500 text-sm ml-2">+8%</span>
                        </div>
                        <div class="text-xs text-gray-500">vs yesterday</div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Active Users</h3>
                        <span class="text-purple-500 bg-purple-100 rounded-full p-2">
                            <i class="fas fa-users"></i>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-3xl font-bold text-gray-800">156</span>
                            <span class="text-green-500 text-sm ml-2">+24%</span>
                        </div>
                        <div class="text-xs text-gray-500">currently online</div>
                    </div>
                </div>

                <!-- Conversion Rate -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Conversion Rate</h3>
                        <span class="text-yellow-500 bg-yellow-100 rounded-full p-2">
                            <i class="fas fa-chart-line"></i>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-3xl font-bold text-gray-800">3.2%</span>
                            <span class="text-red-500 text-sm ml-2">-0.4%</span>
                        </div>
                        <div class="text-xs text-gray-500">vs last week</div>
                    </div>
                </div>
            </div>

            <!-- Quick Insights -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Top Performing Boats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Performing Boats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">1</span>
                                <span class="ml-3 text-sm font-medium text-gray-700">Luxury Yacht A</span>
                            </div>
                            <span class="text-sm text-gray-500">₱45,000</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-8 h-8 rounded-full bg-gray-500 flex items-center justify-center text-white font-bold">2</span>
                                <span class="ml-3 text-sm font-medium text-gray-700">Speed Boat X</span>
                            </div>
                            <span class="text-sm text-gray-500">₱32,000</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold">3</span>
                                <span class="ml-3 text-sm font-medium text-gray-700">Fishing Boat B</span>
                            </div>
                            <span class="text-sm text-gray-500">₱28,500</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-500"></i>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">New booking confirmed</p>
                                <p class="text-sm text-gray-500">Luxury Yacht A - 2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user text-blue-500"></i>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">New customer registration</p>
                                <p class="text-sm text-gray-500">John Doe - 3 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-star text-yellow-500"></i>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">New review received</p>
                                <p class="text-sm text-gray-500">Speed Boat X - 4 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 