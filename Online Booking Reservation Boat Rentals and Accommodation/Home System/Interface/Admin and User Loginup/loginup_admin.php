<?php
$basePath = realpath(dirname(__FILE__) . '/..');
require_once($basePath . '/php/config/connect.php');
require_once($basePath . '/php/classes/Auth.php');
require_once($basePath . '/php/classes/Security.php');

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
<body class="min-h-screen flex items-center justify-center p-4 font-body relative">
    <!-- Ocean background with waves -->
    <div class="ocean">
        <div class="wave"></div>
        <div class="wave"></div>
    </div>

    <style>
        body {
            background: linear-gradient(180deg, #0ea5e9, #0284c7);
            margin: 0;
            overflow-x: hidden;
        }

        .ocean {
            height: 5%;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            overflow-x: hidden;
        }

        .wave {
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 88.7'%3E%3Cpath d='M800 56.9c-155.5 0-204.9-50-405.5-49.9-200 0-250 49.9-394.5 49.9v31.8h800v-.2-31.6z' fill='%23ffffff22'/%3E%3C/svg%3E");
            position: absolute;
            width: 200%;
            height: 100%;
            animation: wave 10s -3s linear infinite;
            transform: translate3d(0, 0, 0);
            opacity: 0.8;
        }

        .wave:nth-of-type(2) {
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 88.7'%3E%3Cpath d='M800 56.9c-155.5 0-204.9-50-405.5-49.9-200 0-250 49.9-394.5 49.9v31.8h800v-.2-31.6z' fill='%23ffffff33'/%3E%3C/svg%3E");
            animation: wave 18s linear reverse infinite;
            opacity: 0.5;
        }

        .wave:nth-of-type(3) {
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 88.7'%3E%3Cpath d='M800 56.9c-155.5 0-204.9-50-405.5-49.9-200 0-250 49.9-394.5 49.9v31.8h800v-.2-31.6z' fill='%23ffffff44'/%3E%3C/svg%3E");
            animation: wave 20s -1s linear infinite;
            opacity: 0.3;
        }

        @keyframes wave {
            0% {transform: translateX(0);}
            50% {transform: translateX(-25%);}
            100% {transform: translateX(-50%);}
        }

        /* Ocean color overlay */
        .ocean::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(0deg, #0ea5e944 0%, transparent 80%);
            z-index: 1;
        }

        /* Make sure the main content stays above the waves */
        #main-container {
            position: relative;
            z-index: 10;
        }
    </style>

    <!-- Return to Homepage Button -->
    <a href="../php/pages/interface.php" class="absolute top-6 left-6 bg-white rounded-full py-2 px-4 flex items-center gap-2 shadow-md hover:shadow-lg">
        <i class="fas fa-home"></i>
        <span>Back to Website</span>
    </a>

    <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-hidden p-6 relative z-10 border border-blue-100" id="main-container">
        <!-- Header with Logo -->
        <div class="text-center mb-6">
            <img src="../img/timbook-carles-tourism.png" alt="Carles Tourism Logo" class="h-24 mx-auto mb-2" onerror="this.src='../img/default-logo.png'">
            <h1 class="text-3xl font-bold text-blue-800">Welcome to Carles Tourism</h1>
            <p class="text-blue-600">Discover the beauty of Isla de Gigantes</p>
        </div>
        
        <!--  Type Selection -->
        <div class="w-full">
            <h2 class="text-2xl font-bold text-center mb-6 text-blue-800">Choose Your Experience</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- User Card -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-48 bg-blue-500 relative overflow-hidden rounded-t-lg">
                        <img src="../img/gigantes.png" alt="User experience" class="w-full h-full object-cover" onerror="this.src='../img/default-user-banner.jpg'">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900 to-transparent opacity-60"></div>
                        <i class="fas fa-ship absolute bottom-4 right-4 text-white text-4xl"></i>
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="text-xl font-bold text-blue-700 flex items-center gap-2">
                            <i class="fas fa-user-circle"></i>
                            User Portal
                        </h3>
                        <p class="text-blue-600 mt-1">Book boats and accommodations for your island adventure</p>
                        
                        <ul class="text-sm text-gray-600 mt-4 space-y-3">
                            <li class="flex items-center gap-3">
                                <div class="w-5 text-center flex-shrink-0">
                                    <i class="fas fa-anchor text-blue-500"></i>
                                </div>
                                <div class="flex-1">Browse boats and stays</div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 text-center flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-blue-500"></i>
                                </div>
                                <div class="flex-1">Easy booking</div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 text-center flex-shrink-0">
                                    <i class="fas fa-user-plus text-blue-500"></i>
                                </div>
                                <div class="flex-1">Simple signup</div>
                            </li>
                        </ul>
                        
                        <a href="../php/pages/login user/login.php" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 mt-auto">
                            <i class="fas fa-sign-in-alt"></i>
                            Continue as User
                        </a>
                    </div>
                </div>

                <!-- Admin Card -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-48 bg-indigo-600 relative overflow-hidden rounded-t-lg">
                        <img src="../img/background system.jpg" alt="Admin dashboard" class="w-full h-full object-cover" onerror="this.src='../img/default-admin-banner.jpg'">
                        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900 to-transparent opacity-60"></div>
                        <i class="fas fa-user-shield absolute bottom-4 right-4 text-white text-4xl"></i>
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <h3 class="text-xl font-bold text-indigo-700 flex items-center gap-2">
                            <i class="fas fa-user-shield"></i>
                            Administrator
                        </h3>
                        <p class="text-indigo-600 mt-1">Manage bookings, listings, and system settings</p>
                        
                        <ul class="text-sm text-gray-600 mt-4 space-y-3">
                            <li class="flex items-center gap-3">
                                <div class="w-5 text-center flex-shrink-0">
                                    <i class="fas fa-lock text-indigo-500"></i>
                                </div>
                                <div class="flex-1">Secure admin access</div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 text-center flex-shrink-0">
                                    <i class="fas fa-edit text-indigo-500"></i>
                                </div>
                                <div class="flex-1">Manage listings</div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 text-center flex-shrink-0">
                                    <i class="fas fa-chart-line text-indigo-500"></i>
                                </div>
                                <div class="flex-1">Booking reports</div>
                            </li>
                        </ul>
                        
                        <a href="../php/pages/login admin/login.php" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 mt-auto">
                            <i class="fas fa-sign-in-alt"></i>
                            Continue as Administrator
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-6 text-center text-gray-500 text-sm">
                <p>© 2025 Carles Tourism | All Rights Reserved</p>
            </div>
        </div>
    </div>

    <script src="loginup_admin.js"></script>
</body>
</html> 