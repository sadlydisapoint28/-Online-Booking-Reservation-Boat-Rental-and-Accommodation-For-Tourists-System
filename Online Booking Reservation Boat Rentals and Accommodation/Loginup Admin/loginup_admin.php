<?php
require_once('../php/config/connect.php');
require_once('../php/classes/Auth.php');
require_once('../php/classes/Security.php');

session_start();

$auth = new Auth($pdo);
$security = new Security($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Carles Tourism</title>
    <link rel="stylesheet" href="loginup_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-blue-400 to-cyan-300 p-4 font-body">
    <!-- Animated background elements -->
    <div class="ocean-bg">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="bubble bubble1"></div>
        <div class="bubble bubble2"></div>
        <div class="bubble bubble3"></div>
        <div class="bubble bubble4"></div>
    </div>

    <div class="w-full max-w-4xl bg-white bg-opacity-95 rounded-xl shadow-2xl overflow-hidden p-8 relative z-10 border border-blue-100" id="main-container">
        <!-- User Type Selection -->
        <div id="selection-view" class="w-full">
            <h2 class="text-4xl font-bold text-center mb-10 text-blue-800 tracking-tight">Choose Your Experience</h2>
            
            <div class="grid md:grid-cols-2 gap-10">
                <!-- User Card -->
                <div class="card overflow-hidden border-0 hover:transform hover:scale-105 transition-all duration-300 bg-white rounded-xl shadow-lg">
                    <div class="h-56 bg-gradient-to-r from-blue-500 to-cyan-400 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&q=80" alt="User experience" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900 to-transparent opacity-60"></div>
                        <i class="fas fa-ship absolute bottom-6 right-6 text-white text-5xl drop-shadow-lg"></i>
                        <div class="absolute bottom-6 left-6 text-white">
                            <span class="bg-blue-600 text-xs uppercase tracking-wider py-1 px-2 rounded-full shadow-md">For Customers</span>
                        </div>
                    </div>
                    <div class="card-header p-6 bg-gradient-to-r from-blue-50 to-white">
                        <h3 class="text-2xl font-bold text-blue-700 flex items-center gap-3">
                            <i class="fas fa-user-circle text-blue-500"></i>
                            User Portal
                        </h3>
                        <p class="text-blue-600 mt-2 font-medium">Book boats and accommodations for your perfect waterfront vacation</p>
                    </div>
                    <div class="card-content p-6">
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center gap-3">
                                <i class="fas fa-anchor text-blue-500"></i>
                                <span>Browse available boats and waterfront stays</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                <span>Easy booking with visual calendars</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-user-plus text-blue-500"></i>
                                <span>Simple signup with minimal information</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer p-6 pt-2">
                        <button onclick="showUserLogin()" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-sign-in-alt"></i>
                            Continue as User
                        </button>
                    </div>
                </div>

                <!-- Admin Card -->
                <div class="card overflow-hidden border-0 hover:transform hover:scale-105 transition-all duration-300 bg-white rounded-xl shadow-lg">
                    <div class="h-56 bg-gradient-to-r from-indigo-700 to-purple-600 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1564069114553-7215e1ff1890?w=800&q=80" alt="Admin dashboard" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900 to-transparent opacity-60"></div>
                        <i class="fas fa-building absolute bottom-6 right-6 text-white text-5xl drop-shadow-lg"></i>
                        <div class="absolute bottom-6 left-6 text-white">
                            <span class="bg-indigo-600 text-xs uppercase tracking-wider py-1 px-2 rounded-full shadow-md">Staff Only</span>
                        </div>
                    </div>
                    <div class="card-header p-6 bg-gradient-to-r from-indigo-50 to-white">
                        <h3 class="text-2xl font-bold text-indigo-700 flex items-center gap-3">
                            <i class="fas fa-user-shield text-indigo-500"></i>
                            Administrator
                        </h3>
                        <p class="text-indigo-600 mt-2 font-medium">Manage bookings, listings, and system settings</p>
                    </div>
                    <div class="card-content p-6">
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center gap-3">
                                <i class="fas fa-lock text-indigo-500"></i>
                                <span>Secure admin portal access</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-edit text-indigo-500"></i>
                                <span>Manage boat and accommodation listings</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-chart-line text-indigo-500"></i>
                                <span>View and manage bookings and reports</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer p-6 pt-2">
                        <button onclick="showAdminLogin()" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-sign-in-alt"></i>
                            Continue as Administrator
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showUserLogin() {
            window.location.href = '../php/pages/login user/login.php';
        }

        function showAdminLogin() {
            window.location.href = '../php/pages/login admin/login.php';
        }
    </script>
</body>
</html> 