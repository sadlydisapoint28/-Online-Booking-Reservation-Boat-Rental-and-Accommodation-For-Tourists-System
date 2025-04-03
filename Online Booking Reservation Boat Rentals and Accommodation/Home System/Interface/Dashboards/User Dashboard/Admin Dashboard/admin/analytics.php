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

// Get current page for active menu state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Admin Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Include sidebar -->
        <?php include('includes/sidebar.php'); ?>

        <!-- Main content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-gray-800">Analytics</h1>
                <p class="text-gray-600">View detailed analytics and insights</p>
            </div>

            <!-- Analytics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Total Bookings</h3>
                        <span class="text-blue-500 bg-blue-100 rounded-full p-2">
                            <i class="fas fa-calendar-check"></i>
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-3xl font-bold text-gray-800">2,451</span>
                        <span class="text-green-500 text-sm ml-2">+15.3%</span>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
                        <span class="text-green-500 bg-green-100 rounded-full p-2">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-3xl font-bold text-gray-800">₱184,593</span>
                        <span class="text-green-500 text-sm ml-2">+8.2%</span>
                    </div>
                </div>

                <!-- Active Boats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Active Boats</h3>
                        <span class="text-purple-500 bg-purple-100 rounded-full p-2">
                            <i class="fas fa-ship"></i>
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-3xl font-bold text-gray-800">18</span>
                        <span class="text-green-500 text-sm ml-2">+2 new</span>
                    </div>
                </div>

                <!-- Customer Satisfaction -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Customer Satisfaction</h3>
                        <span class="text-yellow-500 bg-yellow-100 rounded-full p-2">
                            <i class="fas fa-star"></i>
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-3xl font-bold text-gray-800">4.8</span>
                        <span class="text-green-500 text-sm ml-2">+0.3</span>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Booking Trends Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Trends</h3>
                    <canvas id="bookingTrendsChart" height="300"></canvas>
                </div>

                <!-- Revenue Distribution Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Distribution</h3>
                    <canvas id="revenueDistributionChart" height="300"></canvas>
                </div>
            </div>

            <!-- Additional Metrics -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Popular Boats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Boats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="path/to/boat1.jpg" alt="Boat 1" class="w-10 h-10 rounded-full">
                                <span class="ml-3 text-sm font-medium text-gray-700">Luxury Yacht A</span>
                            </div>
                            <span class="text-sm text-gray-500">89 bookings</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="path/to/boat2.jpg" alt="Boat 2" class="w-10 h-10 rounded-full">
                                <span class="ml-3 text-sm font-medium text-gray-700">Speed Boat X</span>
                            </div>
                            <span class="text-sm text-gray-500">76 bookings</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="path/to/boat3.jpg" alt="Boat 3" class="w-10 h-10 rounded-full">
                                <span class="ml-3 text-sm font-medium text-gray-700">Fishing Boat B</span>
                            </div>
                            <span class="text-sm text-gray-500">65 bookings</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Reviews</h3>
                    <div class="space-y-4">
                        <div class="border-b pb-4">
                            <div class="flex items-center mb-2">
                                <span class="text-yellow-400">★★★★★</span>
                                <span class="ml-2 text-sm text-gray-600">John D.</span>
                            </div>
                            <p class="text-sm text-gray-600">"Amazing experience! The boat was perfect and service was excellent."</p>
                        </div>
                        <div class="border-b pb-4">
                            <div class="flex items-center mb-2">
                                <span class="text-yellow-400">★★★★☆</span>
                                <span class="ml-2 text-sm text-gray-600">Sarah M.</span>
                            </div>
                            <p class="text-sm text-gray-600">"Great day out on the water. Would definitely recommend!"</p>
                        </div>
                    </div>
                </div>

                <!-- Peak Hours -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Peak Booking Hours</h3>
                    <canvas id="peakHoursChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Initialization -->
    <script>
        // Booking Trends Chart
        const bookingTrendsCtx = document.getElementById('bookingTrendsChart').getContext('2d');
        new Chart(bookingTrendsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Bookings',
                    data: [65, 78, 90, 85, 95, 110],
                    borderColor: '#4F46E5',
                    tension: 0.4
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Revenue Distribution Chart
        const revenueDistributionCtx = document.getElementById('revenueDistributionChart').getContext('2d');
        new Chart(revenueDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Luxury Yachts', 'Speed Boats', 'Fishing Boats', 'Others'],
                datasets: [{
                    data: [40, 30, 20, 10],
                    backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#6B7280']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Peak Hours Chart
        const peakHoursCtx = document.getElementById('peakHoursChart').getContext('2d');
        new Chart(peakHoursCtx, {
            type: 'bar',
            data: {
                labels: ['8AM', '10AM', '12PM', '2PM', '4PM', '6PM'],
                datasets: [{
                    label: 'Bookings',
                    data: [20, 35, 45, 40, 50, 25],
                    backgroundColor: '#4F46E5'
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
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html> 