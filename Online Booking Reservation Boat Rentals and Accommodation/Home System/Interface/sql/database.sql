-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables in correct order (child tables first, then parent tables)
DROP TABLE IF EXISTS payment_records;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS boat_reservations;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS boats;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admin_requests;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS booking_system;
USE booking_system;

-- Create users table (no dependencies)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(50) NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    user_type ENUM('customer', 'admin', 'admin_pending') DEFAULT 'customer',
    verification_token VARCHAR(255),
    verification_expires DATETIME,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create customers table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    type ENUM('regular', 'vip', 'group') DEFAULT 'regular',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create boats table
CREATE TABLE boats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    boat_name VARCHAR(255) NOT NULL,
    boat_type VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    price_per_hour DECIMAL(10,2) NOT NULL,
    description TEXT,
    status ENUM('available', 'unavailable', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    boat_id INT NOT NULL,
    booking_date DATETIME NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'partial', 'fully_paid', 'refunded') DEFAULT 'pending',
    destination VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (boat_id) REFERENCES boats(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create boat_reservations table
CREATE TABLE boat_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_reference VARCHAR(20) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    boat_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    number_of_hours INT NOT NULL,
    number_of_persons INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    required_downpayment DECIMAL(10,2) NOT NULL,
    remaining_balance DECIMAL(10,2) NOT NULL,
    booking_status ENUM('pending', 'reserved', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'partially_paid', 'fully_paid', 'refunded') DEFAULT 'unpaid',
    special_requests TEXT,
    cancellation_reason TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (boat_id) REFERENCES boats(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create payment_records table
CREATE TABLE payment_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_reference VARCHAR(20) NOT NULL,
    payment_amount DECIMAL(10,2) NOT NULL,
    payment_type ENUM('downpayment', 'full_payment', 'remaining_balance', 'refund') NOT NULL,
    payment_method ENUM('cash', 'gcash', 'bank_transfer') NOT NULL,
    payment_date DATE NOT NULL,
    payment_time TIME NOT NULL,
    reference_number VARCHAR(100),
    receipt_number VARCHAR(50),
    received_by VARCHAR(255) NOT NULL,
    recorded_by INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_reference) REFERENCES boat_reservations(booking_reference) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create activity_logs table
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create payment summary view
CREATE OR REPLACE VIEW payment_summary AS
SELECT 
    br.booking_reference,
    br.booking_date,
    br.total_amount,
    br.required_downpayment,
    br.remaining_balance,
    br.booking_status,
    br.payment_status,
    COALESCE(SUM(CASE WHEN pr.payment_type != 'refund' THEN pr.payment_amount ELSE 0 END), 0) as total_paid,
    COALESCE(SUM(CASE WHEN pr.payment_type = 'refund' THEN pr.payment_amount ELSE 0 END), 0) as total_refunded
FROM boat_reservations br
LEFT JOIN payment_records pr ON br.booking_reference = pr.booking_reference
GROUP BY br.booking_reference;

-- Insert default admin user
INSERT INTO users (
    full_name,
    email,
    password,
    date_of_birth,
    age,
    gender,
    nationality,
    address,
    phone_number,
    user_type,
    is_verified
) VALUES (
    'System Admin',
    'admin@admin.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    '1990-01-01',
    33,
    'Other',
    'Filipino',
    'System Address',
    '09123456789',
    'admin',
    1
) ON DUPLICATE KEY UPDATE email=email;

-- Insert sample customers
INSERT INTO customers (name, email, phone, type) VALUES
('John Doe', 'john@example.com', '09123456789', 'regular'),
('Jane Smith', 'jane@example.com', '09234567890', 'vip'),
('Mike Johnson', 'mike@example.com', '09345678901', 'group');

-- Insert sample boats
INSERT INTO boats (user_id, boat_name, boat_type, capacity, price_per_hour, description) VALUES
(1, 'Luxury Yacht A', 'Yacht', 10, 5000.00, 'Luxury yacht with modern amenities'),
(1, 'Speed Boat X', 'Speed Boat', 6, 3000.00, 'Fast and comfortable speed boat'),
(1, 'Fishing Boat B', 'Fishing Boat', 8, 2500.00, 'Perfect for fishing trips'),
(1, 'Party Boat C', 'Party Boat', 15, 4000.00, 'Ideal for group celebrations'),
(1, 'Sailing Boat D', 'Sailboat', 4, 2000.00, 'Classic sailing experience');
