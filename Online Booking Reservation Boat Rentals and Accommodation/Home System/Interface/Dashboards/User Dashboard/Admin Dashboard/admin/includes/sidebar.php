<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white transition-transform duration-300 transform" id="sidebar">
    <!-- Logo and Title -->
    <div class="flex items-center justify-between p-4 border-b border-gray-800">
        <div class="flex items-center">
            <img src="../../../../assets/images/carles-logo.png" alt="Carles Logo" class="w-10 h-10 mr-2">
            <span class="text-xl font-semibold">Carles Tourism</span>
        </div>
        <button id="toggleSidebar" class="text-gray-400 hover:text-white focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Profile Section -->
    <div class="p-4 border-b border-gray-800">
        <div class="flex items-center">
            <img src="../../../../assets/images/admin-avatar.png" alt="Admin Avatar" class="w-10 h-10 rounded-full mr-3">
            <div>
                <p class="text-sm font-medium">System Admin</p>
                <p class="text-xs text-gray-400">Administrator</p>
            </div>
            <button id="profileDropdown" class="ml-auto text-gray-400 hover:text-white focus:outline-none">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
        <!-- Profile Dropdown Menu -->
        <div id="profileMenu" class="mt-2 py-2 bg-gray-800 rounded-lg shadow-lg hidden">
            <a href="profile.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                <i class="fas fa-user mr-2"></i> Profile
            </a>
            <a href="settings.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                <i class="fas fa-cog mr-2"></i> Settings
            </a>
            <hr class="my-2 border-gray-700">
            <a href="logout.php" class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-180px)]">
        <!-- GROUP 1: DASHBOARD & OVERVIEW -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dashboard & Overview</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('dashboard')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="dashboard-arrow"></i>
                </button>
            </div>
            <div id="dashboard-submenu" class="space-y-1">
                <a href="dashboard.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'dashboard.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Analytics</span>
                </a>
                <a href="reports.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'reports.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Reports</span>
                </a>
                <a href="quick_stats.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'quick_stats.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Quick Stats</span>
                </a>
                <a href="recent_activities.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'recent_activities.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-history w-5"></i>
                    <span>Recent Activities</span>
                </a>
                <a href="system_status.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'system_status.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-server w-5"></i>
                    <span>System Status</span>
                </a>
                <a href="weather_updates.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'weather_updates.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-cloud-sun w-5"></i>
                    <span>Weather Updates</span>
                </a>
                <a href="peak_hours.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'peak_hours.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-clock w-5"></i>
                    <span>Peak Hours</span>
                </a>
                <a href="popular_destinations.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'popular_destinations.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-map-marker-alt w-5"></i>
                    <span>Popular Destinations</span>
                </a>
                <a href="activity_logs.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'activity_logs.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span>Activity Logs</span>
                </a>
            </div>
        </div>

        <!-- GROUP 2: BOOKINGS -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Bookings</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('bookings')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="bookings-arrow"></i>
                </button>
            </div>
            <div id="bookings-submenu" class="space-y-1 hidden">
                <a href="bookings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'bookings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span>All Bookings</span>
                </a>
                <a href="pending_bookings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'pending_bookings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-clock w-5"></i>
                    <span>Pending Bookings</span>
                </a>
                <a href="confirmed_bookings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'confirmed_bookings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-check-circle w-5"></i>
                    <span>Confirmed Bookings</span>
                </a>
                <a href="completed_bookings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'completed_bookings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-flag-checkered w-5"></i>
                    <span>Completed Bookings</span>
                </a>
                <a href="cancelled_bookings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'cancelled_bookings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-times-circle w-5"></i>
                    <span>Cancelled Bookings</span>
                </a>
                <a href="booking_calendar.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'booking_calendar.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Booking Calendar</span>
                </a>
                <a href="booking_history.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'booking_history.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-history w-5"></i>
                    <span>Booking History</span>
                </a>
                <a href="special_requests.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'special_requests.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-star w-5"></i>
                    <span>Special Requests</span>
                </a>
            </div>
        </div>

        <!-- GROUP 3: BOATS -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Boats</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('boats')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="boats-arrow"></i>
                </button>
            </div>
            <div id="boats-submenu" class="space-y-1 hidden">
                <a href="boats.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'boats.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-ship w-5"></i>
                    <span>All Boats</span>
                </a>
                <a href="add_boat.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'add_boat.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-plus-circle w-5"></i>
                    <span>Add New Boat</span>
                </a>
                <a href="boat_categories.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'boat_categories.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-tags w-5"></i>
                    <span>Boat Categories</span>
                </a>
                <a href="maintenance_schedule.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'maintenance_schedule.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-tools w-5"></i>
                    <span>Maintenance Schedule</span>
                </a>
                <a href="availability_calendar.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'availability_calendar.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-calendar w-5"></i>
                    <span>Availability Calendar</span>
                </a>
                <a href="boat_status.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'boat_status.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-info-circle w-5"></i>
                    <span>Boat Status</span>
                </a>
                <a href="boat_pricing.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'boat_pricing.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-tag w-5"></i>
                    <span>Boat Pricing</span>
                </a>
                <a href="boat_images.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'boat_images.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-images w-5"></i>
                    <span>Boat Images</span>
                </a>
                <a href="boat_features.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'boat_features.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-list w-5"></i>
                    <span>Boat Features</span>
                </a>
            </div>
        </div>

        <!-- GROUP 4: CUSTOMERS -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Customers</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('customers')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="customers-arrow"></i>
                </button>
            </div>
            <div id="customers-submenu" class="space-y-1 hidden">
                <a href="customers.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'customers.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-users w-5"></i>
                    <span>All Customers</span>
                </a>
                <a href="customer_reviews.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'customer_reviews.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-star w-5"></i>
                    <span>Customer Reviews</span>
                </a>
                <a href="customer_feedback.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'customer_feedback.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-comments w-5"></i>
                    <span>Customer Feedback</span>
                </a>
                <a href="customer_support.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'customer_support.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-headset w-5"></i>
                    <span>Customer Support</span>
                </a>
                <a href="customer_history.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'customer_history.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-history w-5"></i>
                    <span>Customer History</span>
                </a>
                <a href="customer_preferences.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'customer_preferences.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-cog w-5"></i>
                    <span>Customer Preferences</span>
                </a>
                <a href="vip_customers.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'vip_customers.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-crown w-5"></i>
                    <span>VIP Customers</span>
                </a>
                <a href="customer_messages.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'customer_messages.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-envelope w-5"></i>
                    <span>Customer Messages</span>
                </a>
            </div>
        </div>

        <!-- GROUP 5: FINANCE -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('finance')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="finance-arrow"></i>
                </button>
            </div>
            <div id="finance-submenu" class="space-y-1 hidden">
                <a href="revenue_reports.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'revenue_reports.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-dollar-sign w-5"></i>
                    <span>Revenue Reports</span>
                </a>
                <a href="payment_history.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'payment_history.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-receipt w-5"></i>
                    <span>Payment History</span>
                </a>
                <a href="refunds.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'refunds.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-undo w-5"></i>
                    <span>Refunds</span>
                </a>
                <a href="financial_analytics.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'financial_analytics.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span>Financial Analytics</span>
                </a>
                <a href="daily_sales.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'daily_sales.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-calendar-day w-5"></i>
                    <span>Daily Sales</span>
                </a>
                <a href="monthly_reports.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'monthly_reports.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Monthly Reports</span>
                </a>
                <a href="tax_reports.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'tax_reports.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-file-invoice-dollar w-5"></i>
                    <span>Tax Reports</span>
                </a>
                <a href="commission_reports.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'commission_reports.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-hand-holding-usd w-5"></i>
                    <span>Commission Reports</span>
                </a>
            </div>
        </div>

        <!-- GROUP 6: SETTINGS -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('settings')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="settings-arrow"></i>
                </button>
            </div>
            <div id="settings-submenu" class="space-y-1 hidden">
                <a href="profile_settings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'profile_settings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-user-cog w-5"></i>
                    <span>Profile Settings</span>
                </a>
                <a href="email_settings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'email_settings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-envelope w-5"></i>
                    <span>Email Settings</span>
                </a>
                <a href="system_backup.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'system_backup.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-database w-5"></i>
                    <span>System Backup</span>
                </a>
                <a href="user_permissions.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'user_permissions.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-user-shield w-5"></i>
                    <span>User Permissions</span>
                </a>
                <a href="system_logs.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'system_logs.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span>System Logs</span>
                </a>
            </div>
        </div>

        <!-- GROUP 7: ADMIN -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('admin')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="admin-arrow"></i>
                </button>
            </div>
            <div id="admin-submenu" class="space-y-1 hidden">
                <a href="admin_users.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'admin_users.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-users-cog w-5"></i>
                    <span>Admin Users</span>
                </a>
                <a href="admin_settings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'admin_settings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-cog w-5"></i>
                    <span>Admin Settings</span>
                </a>
                <a href="access_control.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'access_control.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-lock w-5"></i>
                    <span>Access Control</span>
                </a>
                <a href="security_settings.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'security_settings.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-shield-alt w-5"></i>
                    <span>Security Settings</span>
                </a>
            </div>
        </div>

        <!-- GROUP 8: HELP & SUPPORT -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Help & Support</h3>
                <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleSubmenu('help')">
                    <i class="fas fa-chevron-down transition-transform duration-200" id="help-arrow"></i>
                </button>
            </div>
            <div id="help-submenu" class="space-y-1 hidden">
                <a href="help_center.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'help_center.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-question-circle w-5"></i>
                    <span>Help Center</span>
                </a>
                <a href="faq_management.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'faq_management.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-question w-5"></i>
                    <span>FAQ Management</span>
                </a>
                <a href="support_tickets.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'support_tickets.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-ticket-alt w-5"></i>
                    <span>Support Tickets</span>
                </a>
                <a href="contact_information.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'contact_information.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-address-card w-5"></i>
                    <span>Contact Information</span>
                </a>
                <a href="documentation.php" class="flex items-center px-4 py-2 text-sm <?php echo $current_page == 'documentation.php' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800'; ?> rounded-lg">
                    <i class="fas fa-book w-5"></i>
                    <span>Documentation</span>
                </a>
            </div>
        </div>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.flex-1');
    let isSidebarOpen = true;

    toggleBtn.addEventListener('click', () => {
        isSidebarOpen = !isSidebarOpen;
        sidebar.classList.toggle('-translate-x-full');
        mainContent.classList.toggle('ml-64');
        
        // Update toggle button icon
        toggleBtn.innerHTML = isSidebarOpen ? 
            '<i class="fas fa-bars"></i>' : 
            '<i class="fas fa-times"></i>';
    });

    // Toggle profile dropdown
    const profileBtn = document.getElementById('profileDropdown');
    const profileMenu = document.getElementById('profileMenu');
    let isProfileOpen = false;

    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        isProfileOpen = !isProfileOpen;
        profileMenu.classList.toggle('hidden');
        
        // Update dropdown button icon
        profileBtn.innerHTML = isProfileOpen ? 
            '<i class="fas fa-times"></i>' : 
            '<i class="fas fa-ellipsis-v"></i>';
    });

    // Close profile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.add('hidden');
            isProfileOpen = false;
            profileBtn.innerHTML = '<i class="fas fa-ellipsis-v"></i>';
        }
    });

    // Toggle submenus
    function toggleSubmenu(submenuId) {
        const submenu = document.getElementById(`${submenuId}-submenu`);
        const arrow = document.getElementById(`${submenuId}-arrow`);
        
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }

    // Make toggleSubmenu function available globally
    window.toggleSubmenu = toggleSubmenu;
});
</script>

<style>
/* Add smooth transitions */
#sidebar {
    transition: transform 0.3s ease-in-out;
}

[id$="-submenu"] {
    transition: max-height 0.3s ease-out;
    overflow: hidden;
}

/* Improve hover effects */
.menu-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(4px);
    transition: all 0.2s ease;
}

/* Add active state animation */
.menu-item.active {
    background-color: #2563eb;
    transform: translateX(4px);
}

/* Improve profile dropdown */
#profileMenu {
    transition: opacity 0.2s ease-in-out;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Add scrollbar styling */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Update search input styles */
#dashboardSearch {
    transition: all 0.2s ease;
    background-color: rgba(255, 255, 255, 0.05);
}

#dashboardSearch:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    background-color: rgba(255, 255, 255, 0.1);
}

/* Improve search icon */
.fa-search {
    transition: color 0.2s ease;
}

#dashboardSearch:focus + .fa-search {
    color: #3b82f6;
}

/* Adjust navigation height for new search bar position */
nav {
    height: calc(100vh - 180px);
}
</style> 