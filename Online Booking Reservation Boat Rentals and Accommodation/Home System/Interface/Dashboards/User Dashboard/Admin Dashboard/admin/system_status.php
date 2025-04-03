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

// Get system information
$system_info = [
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'],
    'mysql_version' => $conn->query('select version()')->fetchColumn(),
    'max_upload_size' => ini_get('upload_max_filesize'),
    'max_execution_time' => ini_get('max_execution_time') . ' seconds',
    'memory_limit' => ini_get('memory_limit'),
    'timezone' => date_default_timezone_get()
];

// Get disk space information
$disk_total = disk_total_space('/');
$disk_free = disk_free_space('/');
$disk_used = $disk_total - $disk_free;
$disk_usage_percent = round(($disk_used / $disk_total) * 100, 2);

// Get database size
try {
    $db_size_query = $conn->query("
        SELECT 
            table_schema AS 'Database',
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
        GROUP BY table_schema
    ");
    $db_size = $db_size_query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_size = ['Size (MB)' => 'N/A'];
}

// Get active users count
try {
    $active_users = $conn->query("
        SELECT COUNT(*) as count 
        FROM users 
        WHERE last_activity > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
    ")->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    $active_users = 0;
}

// Get system load
if (function_exists('sys_getloadavg')) {
    $load = sys_getloadavg();
    $load_average = $load[0];
} else {
    $load_average = 'N/A';
}

// Get PHP error log size
$error_log_path = ini_get('error_log');
$error_log_size = file_exists($error_log_path) ? filesize($error_log_path) : 0;
$error_log_size_mb = round($error_log_size / 1024 / 1024, 2);

// Get database connection status
$db_status = $conn ? 'Connected' : 'Disconnected';
$db_status_color = $conn ? 'text-green-500' : 'text-red-500';

// Get last backup information
$backup_dir = '../../../../backups/';
$last_backup = 'N/A';
$last_backup_size = 'N/A';
if (is_dir($backup_dir)) {
    $files = glob($backup_dir . '*.sql');
    if (!empty($files)) {
        $last_backup = date('Y-m-d H:i:s', filemtime($files[0]));
        $last_backup_size = round(filesize($files[0]) / 1024 / 1024, 2) . ' MB';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include('includes/sidebar.php'); ?>

        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-gray-800">System Status</h1>
                <p class="text-gray-600">Overview of system health and performance metrics</p>
            </div>

            <!-- System Health Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Server Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Server Status</h3>
                        <span class="text-green-500 bg-green-100 rounded-full p-2">
                            <i class="fas fa-server"></i>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">PHP Version</span>
                            <span class="text-sm font-medium"><?php echo $system_info['php_version']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Server Software</span>
                            <span class="text-sm font-medium"><?php echo $system_info['server_software']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Timezone</span>
                            <span class="text-sm font-medium"><?php echo $system_info['timezone']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Database Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Database Status</h3>
                        <span class="<?php echo $db_status_color; ?> bg-gray-100 rounded-full p-2">
                            <i class="fas fa-database"></i>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="text-sm font-medium"><?php echo $db_status; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">MySQL Version</span>
                            <span class="text-sm font-medium"><?php echo $system_info['mysql_version']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Database Size</span>
                            <span class="text-sm font-medium"><?php echo $db_size['Size (MB)']; ?> MB</span>
                        </div>
                    </div>
                </div>

                <!-- System Resources -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">System Resources</h3>
                        <span class="text-blue-500 bg-blue-100 rounded-full p-2">
                            <i class="fas fa-microchip"></i>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">CPU Load</span>
                            <span class="text-sm font-medium"><?php echo $load_average; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Memory Limit</span>
                            <span class="text-sm font-medium"><?php echo $system_info['memory_limit']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Max Upload Size</span>
                            <span class="text-sm font-medium"><?php echo $system_info['max_upload_size']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Backup Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 text-sm font-medium">Backup Status</h3>
                        <span class="text-purple-500 bg-purple-100 rounded-full p-2">
                            <i class="fas fa-download"></i>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Last Backup</span>
                            <span class="text-sm font-medium"><?php echo $last_backup; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Backup Size</span>
                            <span class="text-sm font-medium"><?php echo $last_backup_size; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Error Log Size</span>
                            <span class="text-sm font-medium"><?php echo $error_log_size_mb; ?> MB</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disk Usage -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Disk Usage</h3>
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                Used Space
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold inline-block text-blue-600">
                                <?php echo $disk_usage_percent; ?>%
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                        <div style="width:<?php echo $disk_usage_percent; ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <span class="text-sm text-gray-600">Total Space</span>
                        <p class="text-lg font-semibold"><?php echo round($disk_total / 1024 / 1024 / 1024, 2); ?> GB</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Used Space</span>
                        <p class="text-lg font-semibold"><?php echo round($disk_used / 1024 / 1024 / 1024, 2); ?> GB</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Free Space</span>
                        <p class="text-lg font-semibold"><?php echo round($disk_free / 1024 / 1024 / 1024, 2); ?> GB</p>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Active Users</h3>
                    <span class="text-green-500 bg-green-100 rounded-full px-3 py-1 text-sm">
                        <?php echo $active_users; ?> online
                    </span>
                </div>
                <p class="text-gray-600">Users active in the last 15 minutes</p>
            </div>
        </div>
    </div>
</body>
</html> 