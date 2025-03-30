# Boat Rental Booking System

An online booking and reservation system for boat rentals and accommodations.

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP, WAMP, or similar local development environment

## Setup Instructions

### 1. Database Setup

1. Import the `database_schema.sql` file into your MySQL server to create the database and tables
2. The script will create a database named `booking_system` with all necessary tables and sample data

### 2. Configuration

1. Place the entire project folder in your web server's document root (e.g., `htdocs` for XAMPP)
2. If needed, update the database connection settings in these files:
   - `Home System/Interface/php/config/connect.php`
   - `Home System/Interface/php/classes/Auth.php`
   - `Home System/Interface/php/classes/Security.php`
   - `Home System/Interface/Dashboards/User Dashboard/userdashboard.php`
   - `Home System/Interface/php/pages/admin/admin.php`

### 3. Running the Application

1. Start your web server and MySQL service
2. Access the application through your browser at: 
   ```
   http://localhost/Online Booking Reservation Boat Rentals and Accommodation/Home System/Interface/Admin and User Loginup/loginup_admin.php
   ```

## Default Login Credentials

### Admin User
- Email: admin@example.com
- Password: password

### Customer User
- Email: john@example.com
- Password: password

## System Features

### User Features
- User registration and login
- Browse available boats
- Book boats for specific dates and times
- View and manage personal bookings
- Calendar view of bookings

### Admin Features
- Admin login and dashboard
- Manage all bookings
- View statistics (total bookings, users, boats, revenue)
- Add, edit, and remove boats
- Manage user accounts

## Booking Process

1. User logs in to their account
2. Navigates to the booking form
3. Selects dates, times, and boat
4. Adds any additional services
5. Provides contact information
6. Reviews and confirms booking
7. Payment processing (simulation)
8. Receives booking confirmation

## File Structure Overview

- `Home System/Interface/Admin and User Loginup/` - Login pages
- `Home System/Interface/Dashboards/User Dashboard/` - User dashboard
- `Home System/Interface/php/pages/admin/` - Admin dashboard
- `Home System/Interface/php/classes/` - Core PHP classes
- `Home System/Interface/php/config/` - Configuration files
- `Home System/Interface/php/pages/interface.php` - Booking interface

## Security Features

- Password hashing
- CSRF protection
- Input validation
- Session management
- Login attempt throttling

## Troubleshooting

If you encounter any issues:

1. Ensure your web server and MySQL are running
2. Check database connection settings
3. Verify file paths in the code match your local setup
4. Check PHP error logs for detailed error messages

## Credits

This system was developed as a booking reservation platform for boat rentals and accommodations, implementing modern web technologies and secure authentication practices. 