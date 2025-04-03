<?php
require_once('../../../../php/config/connect.php');

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../../../php/pages/login admin/login.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['update_email_settings'])) {
            // Update email settings
            $stmt = $pdo->prepare("INSERT INTO email_settings (setting_key, setting_value) 
                                 VALUES (?, ?) 
                                 ON DUPLICATE KEY UPDATE setting_value = ?");
            
            $settings = [
                'smtp_host' => $_POST['smtp_host'],
                'smtp_port' => $_POST['smtp_port'],
                'smtp_username' => $_POST['smtp_username'],
                'smtp_password' => $_POST['smtp_password'],
                'smtp_encryption' => $_POST['smtp_encryption'],
                'from_email' => $_POST['from_email'],
                'from_name' => $_POST['from_name']
            ];
            
            foreach ($settings as $key => $value) {
                $stmt->execute([$key, $value, $value]);
            }
            
            $success_message = "Email settings updated successfully!";
        }
        
        if (isset($_POST['update_payment_gateway'])) {
            // Update payment gateway settings
            $stmt = $pdo->prepare("INSERT INTO payment_gateways (gateway_name, gateway_key, gateway_secret) 
                                 VALUES (?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE 
                                 gateway_key = ?, gateway_secret = ?");
            
            $stmt->execute([
                $_POST['gateway_name'],
                $_POST['gateway_key'],
                $_POST['gateway_secret'],
                $_POST['gateway_key'],
                $_POST['gateway_secret']
            ]);
            
            $success_message = "Payment gateway settings updated successfully!";
        }
        
        if (isset($_POST['create_backup'])) {
            // Create system backup
            $backup_dir = '../../../../backups/';
            if (!file_exists($backup_dir)) {
                mkdir($backup_dir, 0777, true);
            }
            
            $timestamp = date('Y-m-d_H-i-s');
            $backup_file = $backup_dir . 'backup_' . $timestamp . '.sql';
            
            // Get database credentials
            $db_host = 'localhost';
            $db_name = 'booking_system';
            $db_user = 'root';
            $db_pass = '';
            
            // Create backup command
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($db_host),
                escapeshellarg($db_user),
                escapeshellarg($db_pass),
                escapeshellarg($db_name),
                escapeshellarg($backup_file)
            );
            
            // Execute backup
            exec($command, $output, $return_var);
            
            if ($return_var === 0) {
                // Record backup in database
                $stmt = $pdo->prepare("INSERT INTO system_backups (backup_file, backup_size, backup_type, created_by) 
                                     VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $backup_file,
                    filesize($backup_file),
                    'full',
                    $_SESSION['admin_id']
                ]);
                
                $success_message = "System backup created successfully!";
            } else {
                $error_message = "Failed to create system backup.";
            }
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Get current settings
try {
    // Get email settings
    $email_settings = $pdo->query("SELECT * FROM email_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Get payment gateway settings
    $payment_gateways = $pdo->query("SELECT * FROM payment_gateways")->fetchAll();
    
    // Get recent backups
    $recent_backups = $pdo->query("
        SELECT sb.*, u.full_name as created_by_name 
        FROM system_backups sb 
        JOIN users u ON sb.created_by = u.user_id 
        ORDER BY sb.created_at DESC 
        LIMIT 5
    ")->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error fetching settings: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Boat Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">System Settings</h2>
                </div>
            </header>

            <main class="p-6">
                <?php if (isset($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Email Settings -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Email Settings</h3>
                    <form method="POST" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                                <input type="text" name="smtp_host" value="<?php echo $email_settings['smtp_host'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                                <input type="number" name="smtp_port" value="<?php echo $email_settings['smtp_port'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                                <input type="text" name="smtp_username" value="<?php echo $email_settings['smtp_username'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SMTP Password</label>
                                <input type="password" name="smtp_password" value="<?php echo $email_settings['smtp_password'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SMTP Encryption</label>
                                <select name="smtp_encryption" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="tls" <?php echo ($email_settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : ''; ?>>TLS</option>
                                    <option value="ssl" <?php echo ($email_settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">From Email</label>
                                <input type="email" name="from_email" value="<?php echo $email_settings['from_email'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">From Name</label>
                                <input type="text" name="from_name" value="<?php echo $email_settings['from_name'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <button type="submit" name="update_email_settings" 
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Update Email Settings
                        </button>
                    </form>
                </div>

                <!-- Payment Gateway Settings -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Payment Gateway Settings</h3>
                    <form method="POST" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gateway Name</label>
                                <input type="text" name="gateway_name" value="<?php echo $payment_gateways[0]['gateway_name'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gateway Key</label>
                                <input type="text" name="gateway_key" value="<?php echo $payment_gateways[0]['gateway_key'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gateway Secret</label>
                                <input type="password" name="gateway_secret" value="<?php echo $payment_gateways[0]['gateway_secret'] ?? ''; ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <button type="submit" name="update_payment_gateway" 
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Update Payment Gateway
                        </button>
                    </form>
                </div>

                <!-- System Backup -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">System Backup</h3>
                    <form method="POST" class="mb-6">
                        <button type="submit" name="create_backup" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            Create New Backup
                        </button>
                    </form>

                    <h4 class="text-md font-semibold mb-2">Recent Backups</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($recent_backups as $backup): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo date('M d, Y H:i', strtotime($backup['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo ucfirst($backup['backup_type']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo number_format($backup['backup_size'] / 1024, 2) . ' KB'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($backup['created_by_name']); ?>
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
</body>
</html> 