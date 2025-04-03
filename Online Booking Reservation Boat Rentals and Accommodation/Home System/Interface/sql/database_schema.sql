-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `booking_system`;
USE `booking_system`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(20),
  `address` TEXT,
  `user_type` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Boats table
CREATE TABLE IF NOT EXISTS `boats` (
  `boat_id` INT AUTO_INCREMENT PRIMARY KEY,
  `boat_name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `capacity` INT NOT NULL,
  `price_per_hour` DECIMAL(10,2) NOT NULL,
  `price_per_day` DECIMAL(10,2) NOT NULL,
  `image_url` VARCHAR(255),
  `status` ENUM('available', 'maintenance', 'booked') DEFAULT 'available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings table
CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `boat_id` INT NOT NULL,
  `check_in_date` DATE NOT NULL,
  `check_out_date` DATE NOT NULL,
  `check_in_time` TIME NOT NULL,
  `check_out_time` TIME NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `additional_services` TEXT,
  `booking_status` ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
  `payment_status` ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`boat_id`) REFERENCES `boats`(`boat_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Login attempts table
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ip_address` VARCHAR(45) NOT NULL,
  `attempts` INT NOT NULL DEFAULT 1,
  `last_attempt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user
INSERT INTO `users` (`full_name`, `email`, `password`, `user_type`) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample customer
INSERT INTO `users` (`full_name`, `email`, `password`, `phone_number`, `address`, `user_type`) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123-456-7890', '123 Main St, Anytown, USA', 'customer');

-- Insert sample boats
INSERT INTO `boats` (`boat_name`, `description`, `capacity`, `price_per_hour`, `price_per_day`, `image_url`, `status`) VALUES
('Speedboat 2000', 'Fast and comfortable speedboat for day trips', 6, 50.00, 300.00, 'images/boats/speedboat.jpg', 'available'),
('Luxury Yacht', 'Elegant yacht with all amenities for a perfect day on the water', 12, 150.00, 900.00, 'images/boats/yacht.jpg', 'available'),
('Fishing Boat', 'Equipped with fishing gear and fish finder', 4, 35.00, 200.00, 'images/boats/fishingboat.jpg', 'available'),
('Pontoon Party Boat', 'Perfect for parties and group outings', 15, 75.00, 450.00, 'images/boats/partyboat.jpg', 'available'),
('Kayak', 'Single-person kayak for exploring quiet waters', 1, 10.00, 50.00, 'images/boats/kayak.jpg', 'available');

-- Insert sample bookings
INSERT INTO `bookings` (`user_id`, `boat_id`, `check_in_date`, `check_out_date`, `check_in_time`, `check_out_time`, `total_price`, `booking_status`, `payment_status`) VALUES
(2, 1, '2023-07-15', '2023-07-15', '09:00:00', '17:00:00', 400.00, 'confirmed', 'paid'),
(2, 3, '2023-08-01', '2023-08-03', '10:00:00', '16:00:00', 600.00, 'pending', 'pending');

-- Note: Password for both accounts is 'password' 