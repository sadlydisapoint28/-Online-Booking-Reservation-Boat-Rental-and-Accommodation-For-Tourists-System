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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $boat_name = $_POST['boat_name'];
        $description = $_POST['description'];
        $capacity = $_POST['capacity'];
        $price_per_hour = $_POST['price_per_hour'];
        $status = $_POST['status'];
        
        // Handle image upload
        $image_path = '';
        if (isset($_FILES['boat_image']) && $_FILES['boat_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../../../uploads/boats/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['boat_image']['name'], PATHINFO_EXTENSION));
            $file_name = uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['boat_image']['tmp_name'], $target_path)) {
                $image_path = 'uploads/boats/' . $file_name;
            }
        }
        
        $query = "INSERT INTO boats (boat_name, description, capacity, price_per_hour, status, image_path) 
                  VALUES (:boat_name, :description, :capacity, :price_per_hour, :status, :image_path)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'boat_name' => $boat_name,
            'description' => $description,
            'capacity' => $capacity,
            'price_per_hour' => $price_per_hour,
            'status' => $status,
            'image_path' => $image_path
        ]);
        
        $success_message = "Boat added successfully!";
    } catch (PDOException $e) {
        $error_message = "Error adding boat: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Boat - Boat Rental System</title>
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
                    
                    <!-- Boats Section -->
                    <div class="mt-4">
                        <p class="px-6 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Boats</p>
                        <a href="boats.php" class="flex items-center px-6 py-3 text-indigo-200 hover:bg-indigo-800 rounded-lg transition-colors duration-200">
                            <i class="fas fa-ship mr-3"></i>
                            All Boats
                        </a>
                        <a href="add_boat.php" class="flex items-center px-6 py-3 text-white bg-indigo-800 rounded-lg">
                            <i class="fas fa-plus-circle mr-3"></i>
                            Add New Boat
                        </a>
                        <a href="boat_categories.php" class="flex items-center px-6 py-3 text-indigo-200 hover:bg-indigo-800 rounded-lg transition-colors duration-200">
                            <i class="fas fa-tags mr-3"></i>
                            Categories
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
                    <h2 class="text-xl font-semibold text-gray-800">Add New Boat</h2>
                </div>
            </header>

            <main class="p-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <?php if (isset($success_message)): ?>
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Boat Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Boat Name</label>
                                <input type="text" name="boat_name" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Capacity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Capacity</label>
                                <input type="number" name="capacity" required min="1"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Price per Hour -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price per Hour</label>
                                <input type="number" name="price_per_hour" required min="0" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="available">Available</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="rented">Rented</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Boat Image</label>
                            <input type="file" name="boat_image" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100">
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Add Boat
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html> 