-- Create the database
CREATE DATABASE IF NOT EXISTS boat_rentals_db;
USE boat_rentals_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    address TEXT,
    user_type ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create boats table
CREATE TABLE IF NOT EXISTS boats (
    boat_id INT PRIMARY KEY AUTO_INCREMENT,
    boat_name VARCHAR(100) NOT NULL,
    description TEXT,
    capacity INT NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    status ENUM('available', 'booked', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create accommodations table
CREATE TABLE IF NOT EXISTS accommodations (
    accommodation_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price_per_night DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    image_url VARCHAR(255),
    status ENUM('available', 'booked', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    boat_id INT,
    accommodation_id INT,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (boat_id) REFERENCES boats(boat_id),
    FOREIGN KEY (accommodation_id) REFERENCES accommodations(accommodation_id)
);

-- Insert sample users
INSERT INTO users (username, password, email, full_name, phone_number, address, user_type) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Administrator', '09123456789', 'Carles, Iloilo', 'admin'),
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'john@example.com', 'John Doe', '09187654321', 'Manila', 'customer'),
('jane_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jane@example.com', 'Jane Smith', '09123456780', 'Cebu', 'customer');

-- Insert sample boats
INSERT INTO boats (boat_name, description, capacity, price_per_day, image_url) VALUES
('Island Hopper', 'Perfect for island hopping adventures', 10, 5000.00, 'images/boats/island_hopper.jpg'),
('Sea Explorer', 'Ideal for fishing trips', 8, 4000.00, 'images/boats/sea_explorer.jpg'),
('Sunset Cruiser', 'Perfect for sunset cruises', 15, 6000.00, 'images/boats/sunset_cruiser.jpg');

-- Insert sample accommodations
INSERT INTO accommodations (name, description, price_per_night, capacity, room_type, image_url) VALUES
('Beach Front Villa', 'Luxurious villa with ocean view', 3000.00, 4, 'Villa', 'images/accommodations/villa.jpg'),
('Garden Cottage', 'Cozy cottage surrounded by gardens', 1500.00, 2, 'Cottage', 'images/accommodations/cottage.jpg'),
('Family Suite', 'Spacious suite perfect for families', 2500.00, 6, 'Suite', 'images/accommodations/suite.jpg');

-- Insert sample bookings
INSERT INTO bookings (user_id, boat_id, accommodation_id, check_in_date, check_out_date, total_amount, booking_status, payment_status) VALUES
(2, 1, NULL, '2024-03-20', '2024-03-22', 10000.00, 'confirmed', 'paid'),
(3, NULL, 1, '2024-03-25', '2024-03-27', 6000.00, 'pending', 'pending'),
(2, 2, 2, '2024-04-01', '2024-04-03', 11000.00, 'confirmed', 'paid'); 