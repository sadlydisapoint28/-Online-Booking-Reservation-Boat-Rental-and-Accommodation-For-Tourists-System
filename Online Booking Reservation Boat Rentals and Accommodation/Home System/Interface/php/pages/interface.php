<?php
// Check if user is coming from loading screen
if (!isset($_GET['loaded']) && !isset($_SERVER['HTTP_REFERER'])) {
    header("Location: ../../Loading-Screen/loading.html");
    exit;
}

require_once('../config/connect.php');
// Hindi na muna gagamitin ang existing header
// include '../../../php/includes/header.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore the world with our travel services, featuring boat rentals and accommodation packages.">
    <title>Timbook Carles Tourism - Isla de Gigantes</title>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/interface.css">
    <!-- Inline styles for section backgrounds -->
    <style>
        :root {
            --color-primary: #3282b8;
            --color-primary-dark: #2673a8;
            --color-primary-rgb: 50, 130, 184;
            --color-secondary: #102030;
            --color-success: #28a745;
            --color-danger: #dc3545;
            --color-warning: #ffc107;
            --color-text: #ffffff;
            --color-text-muted: rgba(255, 255, 255, 0.7);
            --color-background: #000000;
        }
        
        /* Fix parallax scroll and background overlap issues */
        .background-image-container {
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background-image: url('../../img/background system.jpg');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            z-index: -10;
            transition: opacity 0.5s ease;
            visibility: visible;
        }
        
        /* Improved section handling */
        .section-bg-sequence-1 {
            position: relative;
            z-index: 1;
            background-color: transparent !important;
        }
        
        .section-bg-sequence-2 {
            position: relative;
            z-index: 2;
            background-color: #102030 !important;
        }
        
        .booking-section-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../../img/bcground2.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            z-index: -9;
            opacity: 0;
            transition: opacity 0.5s ease;
            visibility: hidden;
        }
        
        /* Make booking section have proper stacking context */
        .booking-form-section {
            position: relative;
            z-index: 3;
            background: transparent !important;
            padding: 80px 0;
        }
        
        /* Ensure booking section has a proper contrast with background */
        .booking-form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8); /* Darker overlay for better contrast */
            z-index: -1;
        }
        
        /* Improved form styles for better visibility */
        .booking-form-section .section-header {
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            margin-bottom: 40px;
        }
        
        .booking-form-section .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        /* Updated styles to center the booking form */
        .booking-form-section .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        
        .booking-form-container {
            position: relative;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }
        
        /* Override any conflicting styles */
        .booking-form {
            width: 100% !important;
            max-width: 100% !important;
            display: block !important;
        }
        
        /* Ensure form steps display properly */
        .form-step {
            width: 100%;
            max-width: 100%;
        }
        
        .booking-form {
            display: flex;
            flex-wrap: nowrap;
            width: max-content;
            padding: 0 10px;
        }
        
        .booking-form .form-row {
            display: flex;
            margin-bottom: 20px;
            gap: 20px;
        }
        
        .booking-form .form-group {
            flex: 1;
        }
        
        .booking-form label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff;
            font-weight: 500;
            font-size: 1rem;
        }
        
        .booking-form input,
        .booking-form select,
        .booking-form textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.95);
            color: #000;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .booking-form input:focus,
        .booking-form select:focus,
        .booking-form textarea:focus {
            outline: none;
            border-color: #3282b8;
            box-shadow: 0 0 0 3px rgba(50, 130, 184, 0.4);
            background-color: #fff;
        }
        
        .booking-form .form-section h3 {
            color: #ffffff;
            margin-top: 30px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
        
        .booking-form .form-text {
            display: block;
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }
        
        /* Override Bootstrap's default form-select styling */
        .booking-form select.form-select {
            background-image: none;
        }
        
        /* Add more specificity to ensure our styles take precedence */
        .booking-form-section .booking-form-container .booking-form select {
            cursor: pointer !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 16px !important;
            padding-right: 40px !important;
        }
        
        /* Remove default arrow in IE10+ */
        .booking-form select::-ms-expand {
            display: none;
        }
        
        .booking-form-section .booking-form-container .booking-form select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%233282b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
        }
        
        .booking-form .services-options {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .booking-form .service-option {
            flex-basis: calc(50% - 15px);
            background-color: rgba(255, 255, 255, 0.15);
            padding: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .booking-form .service-option:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .booking-form .service-option label {
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        
        .booking-form .service-price {
            color: #3acfff;
            font-weight: 700;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .booking-form .full-width {
            width: 100%;
            margin-top: 20px;
        }
        
        .booking-form .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 15px;
        }
        
        .booking-form .form-actions button {
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .booking-form .btn-outline {
            background-color: transparent;
            border: 2px solid #3282b8;
            color: #ffffff;
        }
        
        .booking-form .btn-outline:hover {
            background-color: rgba(50, 130, 184, 0.1);
        }
        
        .booking-form .btn-primary {
            background-color: #3282b8;
            border: none;
            color: #ffffff;
        }
        
        .booking-form .btn-primary:hover {
            background-color: #2673a8;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        
        .booking-form .terms-link {
            color: #3282b8;
            text-decoration: underline;
        }
        
        .booking-summary {
            background-color: rgba(50, 130, 184, 0.1);
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #3282b8;
            margin-top: 30px;
        }
        
        .booking-summary h3 {
            color: #ffffff;
            margin-bottom: 15px;
        }
        
        .booking-summary .summary-content {
            color: #ffffff;
        }
        
        @media (max-width: 768px) {
            .booking-form .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .booking-form .service-option {
                flex-basis: 100%;
            }
        }
        
        /* Improved checkbox styling */
        .booking-form input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #3282b8;
        }
        
        .booking-form .checkbox-container {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
        }
        
        .booking-form .checkbox-container label {
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }
        
        /* Custom select styling to fix arrow issues */
        .booking-form-section .custom-select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: calc(100% - 12px) center !important;
            background-size: 12px !important;
            padding-right: 35px !important;
            cursor: pointer !important;
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        /* Hide browser's default arrow */
        .booking-form-section select::-ms-expand {
            display: none;
        }
        
        /* Hide default arrow in Firefox */
        .booking-form-section .custom-select {
            text-indent: 0.01px;
            text-overflow: '';
        }
        
        /* Fix select option text color */
        .booking-form-section .custom-select option {
            background-color: #333;
            color: white;
        }
        
        /* Clear transitions for body state */
        body.booking-section-active .background-image-container {
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        body.booking-section-active .booking-section-background {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Animation for scroll indicator */
        @keyframes pulse {
            0% { opacity: 0.6; transform: scale(0.95); }
            50% { opacity: 1; transform: scale(1); }
            100% { opacity: 0.6; transform: scale(0.95); }
        }
        
        /* Style the scrollbar for the booking form container */
        .booking-form-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .booking-form-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .booking-form-container::-webkit-scrollbar-thumb {
            background: rgba(50, 130, 184, 0.5);
            border-radius: 4px;
        }
        
        .booking-form-container::-webkit-scrollbar-thumb:hover {
            background: rgba(50, 130, 184, 0.7);
        }
        
        /* Add navigation buttons to move between form steps */
        .form-nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            width: 100%;
            padding: 0 10px;
        }
        
        .form-nav-button {
            background-color: rgba(50, 130, 184, 0.2);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .form-nav-button:hover {
            background-color: rgba(50, 130, 184, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        .form-nav-button:disabled {
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.3);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        /* Step indicator dots */
        .step-indicators {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }
        
        .step-dot {
            width: 10px;
            height: 10px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .step-dot.active {
            background-color: #3282b8;
            transform: scale(1.2);
        }
        
        /* Booking form navigation styling */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            width: 100%;
        }
        
        .prev-step, .next-step {
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .prev-step:before {
            content: "â†";
        }
        
        .next-step:after {
            content: "â†’";
        }
        
        .prev-step:hover, .next-step:hover {
            background-color: var(--color-primary-dark);
            transform: translateY(-2px);
        }
        
        .prev-step:disabled, .next-step:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .step-indicators {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        
        .step-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #e0e0e0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .step-dot.active {
            background-color: var(--color-primary);
            transform: scale(1.2);
        }
        
        .scroll-indicator {
            color: var(--color-text);
            text-align: center;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            animation: pulse 1.5s infinite;
            transition: opacity 0.5s ease;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
        
        /* Mobile swipe hint */
        .swipe-hint {
            display: none;
            padding: 0.5rem;
            background-color: rgba(var(--color-primary-rgb), 0.1);
            border-radius: 0.5rem;
            text-align: center;
            margin-bottom: 1rem;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .swipe-hint i {
            animation: swipeAnim 1.5s infinite;
        }
        
        @keyframes swipeAnim {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(10px); }
        }
        
        @media (max-width: 768px) {
            .swipe-hint {
                display: flex;
            }
        }

        /* Updated booking form styles for proper step navigation */
        .booking-form-container {
            position: relative;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 30px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 100%;
        }

        /* Step numbers styling */
        .step-numbers {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
            z-index: 5;
        }

        .step-numbers:after {
            content: '';
            position: absolute;
            top: 20px;
            left: 40px;
            right: 40px;
            height: 2px;
            background-color: rgba(255,255,255,0.2);
            z-index: -1;
        }

        .step-number {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .step-number > div {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .step-number > span {
            color: white;
            font-size: 0.8rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .step-number.active > div {
            background-color: var(--color-primary);
            transform: scale(1.1);
        }

        .step-number.active > span {
            color: var(--color-primary);
            font-weight: bold;
        }

        /* Tab content styles */
        .tab-content {
            min-height: 400px;
            position: relative;
            margin-bottom: 20px;
        }

        .tab-pane {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tab-pane.active,
        .tab-pane[style*="display: block"] {
            display: block;
            opacity: 1;
        }

        .tab-pane h3 {
            color: #ffffff;
            margin-bottom: 20px;
            font-size: 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Form element styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: 500;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px rgba(50, 130, 184, 0.3);
            background-color: rgba(255, 255, 255, 0.25);
        }

        /* For date and select inputs to be more visible */
        .form-group input[type="date"] {
            color-scheme: dark;
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 12px) center;
            padding-right: 35px !important;
        }

        .form-text {
            display: block;
            margin-top: 5px;
            color: var(--color-text-muted);
            font-size: 0.85rem;
        }

        .service-option {
            margin-bottom: 15px;
            background-color: rgba(255, 255, 255, 0.15);
            padding: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .service-option:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .checkbox-container {
            margin-bottom: 25px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 15px;
        }

        .booking-summary {
            background-color: rgba(50, 130, 184, 0.1);
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #3282b8;
            margin-top: 25px;
        }

        /* Button styles */
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--color-primary);
            color: var(--color-primary);
        }

        .btn-outline:hover {
            background-color: rgba(50, 130, 184, 0.1);
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--color-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-dark, #2673a8);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        /* Navigation buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            width: 100%;
        }

        .prev-step, .next-step {
            padding: 12px 25px;
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .prev-step:hover, .next-step:hover {
            background-color: var(--color-primary-dark, #2673a8);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        .prev-step i, .next-step i {
            transition: transform 0.3s ease;
        }

        .prev-step:hover i {
            transform: translateX(-3px);
        }

        .next-step:hover i {
            transform: translateX(3px);
        }

        .prev-step:disabled {
            background-color: rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.5);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Progress bar */
        .form-progress {
            margin-top: 20px;
            background-color: rgba(255,255,255,0.2);
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            width: 20%;
            height: 100%;
            background-color: var(--color-primary);
            transition: width 0.5s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .step-numbers {
                gap: 10px;
                justify-content: flex-start;
                padding-bottom: 10px;
                overflow-x: auto;
            }
            
            .step-number {
                min-width: 60px;
            }
            
            .step-numbers:after {
                left: 20px;
                right: 20px;
            }
            
            .tab-pane {
                padding: 15px;
            }
            
            .prev-step, .next-step {
                padding: 10px 15px;
            }
        }

        /* Fixed booking form layout */
        .booking-form-container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            margin: 0 auto;
        }

        /* Step indicators row */
        .step-indicators-row {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        /* Line connecting steps */
        .step-indicators-row:after {
            content: '';
            position: absolute;
            top: 25px;
            left: 30px;
            right: 30px;
            height: 2px;
            background-color: rgba(255, 255, 255, 0.2);
            z-index: 1;
        }

        /* Individual step indicators */
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .step.active .step-circle {
            background-color: var(--color-primary);
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(50, 130, 184, 0.5);
        }

        .step-label {
            color: white;
            font-size: 0.85rem;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .step.active .step-label {
            color: var(--color-primary);
            font-weight: bold;
        }

        .step.completed .step-circle {
            background-color: var(--color-success);
        }

        .step.completed .step-circle:before {
            content: 'âœ“';
            font-weight: bold;
        }

        /* Form steps */
        .form-step {
            display: none;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            transition: opacity 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .form-step h3 {
            color: #ffffff;
            margin-bottom: 20px;
            font-size: 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
        }

        /* Form navigation */
        .form-navigation {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-prev, .btn-next {
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-prev:hover, .btn-next:hover {
            background-color: var(--color-primary-dark);
            transform: translateY(-2px);
        }

        .btn-prev:disabled {
            background-color: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.5);
            cursor: not-allowed;
            transform: none;
        }

        .progress-container {
            flex: 1;
            height: 6px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            margin: 0 15px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--color-primary);
            width: 20%;
            transition: width 0.5s ease;
        }

        /* Form fields and elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(50, 130, 184, 0.3);
        }

        /* For date inputs */
        .form-group input[type="date"] {
            color-scheme: dark;
        }

        /* For select dropdowns */
        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 35px;
        }

        .form-text {
            display: block;
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        /* Service options */
        .service-option {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .service-option:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .service-option label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
            cursor: pointer;
            font-weight: normal;
        }

        .service-price {
            color: #3acfff;
            font-weight: 600;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        /* Checkbox container */
        .checkbox-container {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Improved checkbox styling */
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: var(--color-primary);
        }

        /* Booking summary */
        .booking-summary {
            background-color: rgba(50, 130, 184, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid var(--color-primary);
        }

        .booking-summary h4 {
            color: white;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .summary-content {
            color: rgba(255, 255, 255, 0.9);
        }

        .summary-placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }

        /* Form actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--color-primary);
            color: white;
        }

        .btn-outline:hover {
            background-color: rgba(50, 130, 184, 0.2);
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--color-primary);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .booking-form-container {
                padding: 20px 15px;
            }
            
            .step-indicators-row {
                overflow-x: auto;
                justify-content: flex-start;
                padding-bottom: 10px;
            }
            
            .step {
                min-width: 70px;
                margin-right: 10px;
            }
            
            .step-indicators-row:after {
                left: 20px;
                right: 20px;
            }
            
            .form-step {
                padding: 15px;
            }
            
            .form-navigation {
                flex-direction: column;
                gap: 15px;
            }
            
            .progress-container {
                width: 100%;
                margin: 10px 0;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        @media (max-width: 992px) {
            .booking-form-container {
                padding: 20px 15px;
                max-width: 95%;
            }
        }

        @media (max-width: 576px) {
            .booking-form-container {
                max-width: 100%;
            }
        }

        /* Animation on scroll effects */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-fade-in {
            opacity: 0;
            transition: opacity 0.8s ease;
        }

        .animate-fade-in.animated {
            opacity: 1;
        }

        .animate-scale-in {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .animate-scale-in.animated {
            opacity: 1;
            transform: scale(1);
        }

        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }
        .delay-400 { transition-delay: 0.4s; }
        .delay-500 { transition-delay: 0.5s; }
        .delay-600 { transition-delay: 0.6s; }

        /* Remove our custom animation on scroll effects */
        .animate-on-scroll,
        .animate-fade-in,
        .animate-scale-in {
            /* Reset these classes so they don't affect anything */
            opacity: initial;
            transform: initial;
            transition: none;
        }

        .animate-on-scroll.animated,
        .animate-fade-in.animated,
        .animate-scale-in.animated {
            opacity: initial;
            transform: initial;
        }

        .delay-100, .delay-200, .delay-300, .delay-400, .delay-500, .delay-600 {
            transition-delay: 0s;
        }

        /* Restore original animations and ensure they work */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .fade-in-up.scroll-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-200 {
            transition-delay: 0.2s;
        }

        .delay-400 {
            transition-delay: 0.4s;
        }

        .delay-600 {
            transition-delay: 0.6s;
        }

        .scroll-transition {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .scroll-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Background image div for landing page -->
    <div class="background-image-container"></div>
    
    <!-- Booking section background -->
    <div class="booking-section-background"></div>
    
    <!-- Skip link for accessibility -->
    <a href="#main-content" class="skip-nav">Laktawan papunta sa pangunahing nilalaman</a>
    
    <!-- Header/Navigation -->
    <header class="site-header" role="banner">
        <div class="container">
            <nav class="main-nav" aria-label="Pangunahing navigation">
                <div class="logo">
                    <a href="#" aria-label="Carles Tourism Home" class="d-flex align-items-center">
                        <img src="../../img/carleslogomunicipality.png" alt="Municipality of Carles Logo" width="60" height="60" class="me-3">
                        <img src="../../img/timbook-carles-tourism.png" alt="Timbook Carles Tourism Logo" width="80" height="80">
                    </a>
                </div>
                <div class="nav-links">
                    <ul role="menubar">
                        <li role="menuitem" class="active"><a href="#" aria-current="page">HOME</a></li>
                        <li role="menuitem"><a href="#">BOAT RENTALS</a></li>
                        <li role="menuitem"><a href="#">ISLANDS</a></li>
                        <li role="menuitem"><a href="#">BEACHES</a></li>
                        <li role="menuitem"><a href="#">ABOUT</a></li>
                        <li role="menuitem"><a href="#">GALLERY</a></li>
                    </ul>
                </div>
                <div class="nav-right">
                    <div class="secondary-nav">
                        <a href="#" class="contact-link">CONTACT US</a>
                        <a href="#" class="book-link">BOOK NOW</a>
                        <a href="http://localhost/Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/Home%20System/Interface/Admin%20and%20User%20Loginup/loginup_admin.php" class="login-link">LOGIN</a>
                    </div>
                    <div class="social-icons" aria-label="Social media links">
                        <a href="#" aria-label="Information"><i class="fas fa-info-circle" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Search" class="search-toggle"><i class="fas fa-search" aria-hidden="true"></i></a>
                    </div>
                </div>
                <!-- Mobile menu button -->
                <button class="mobile-menu-toggle d-lg-none" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle navigation">
                    <span class="hamburger-icon"></span>
                </button>
            </nav>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="mobile-menu d-lg-none" aria-hidden="true">
                <ul>
                    <li class="active"><a href="#">HOME</a></li>
                    <li><a href="#">BOAT RENTALS</a></li>
                    <li><a href="#">ISLANDS</a></li>
                    <li><a href="#">BEACHES</a></li>
                    <li><a href="#">ABOUT</a></li>
                    <li><a href="#">GALLERY</a></li>
                </ul>
                <div class="mobile-secondary-nav">
                    <a href="#" class="contact-link">CONTACT US</a>
                    <a href="#" class="book-link">BOOK NOW</a>
                    <a href="http://localhost/Online%20Booking%20Reservation%20Boat%20Rentals%20and%20Accommodation/Home%20System/Interface/Admin%20and%20User%20Loginup/loginup_admin.php" class="login-link">LOGIN</a>
                </div>
                <div class="mobile-social-icons">
                    <a href="#" aria-label="Information"><i class="fas fa-info-circle" aria-hidden="true"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                    <a href="#" aria-label="Search" class="search-toggle-mobile"><i class="fas fa-search" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </header>

<!-- Hero Section -->
    <main id="main-content">
        <section class="hero-section section-bg-sequence-1" aria-label="Main banner">
                <div class="hero-content">
                    <div class="container">
                        <div class="row align-items-center">
                        <div class="col-lg-8 col-md-12 mb-md-5">
                            <p class="travel-label fade-in-up">TIMBOOK CARLES</p>
                            <h1 class="hero-title fade-in-up delay-200">DISCOVER THE<br>BEAUTY OF<br>CARLES</h1>
                            <p class="hero-text fade-in-up delay-400">Journey to the stunning islands of Gigantes, home to pristine white sand beaches, majestic limestone cliffs, and crystal-clear blue waters. Hop on a boat and experience the true beauty of the Philippines.</p>
                            <a href="#explore" class="btn btn-explore fade-in-up delay-600" aria-label="Learn more about our travel offerings">LEARN MORE</a>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <!-- Island Vertical List -->
                            <div class="islands-vertical-container">
                                <div class="island-vertical-list">
                                    <div class="island-card-small" data-category="ISLAND">
                                        <div class="island-card-image-small">
                                            <img src="../../img/Gigantes Islands Eco-Tour.jpg" alt="Isla Gigantes Norte" loading="lazy">
                                            <div class="card-overlay"></div>
                                        </div>
                                        <div class="island-card-content-small">
                                            <h3>GIGANTES NORTE</h3>
                                        </div>
                                        <div class="hover-info">
                                            <p>Beautiful white sand beaches and limestone formations</p>
                                            <a href="#" class="view-btn">VIEW</a>
                                        </div>
                                    </div>
                                    
                                    <div class="island-card-small" data-category="ISLAND">
                                        <div class="island-card-image-small">
                                            <img src="../../img/gigantes sur.jpg" alt="Isla Gigantes Sur" loading="lazy">
                                            <div class="card-overlay"></div>
                                        </div>
                                        <div class="island-card-content-small">
                                            <h3>GIGANTES SUR</h3>
                                        </div>
                                        <div class="hover-info">
                                            <p>Discover Tangway beach and crystal clear waters</p>
                                            <a href="#" class="view-btn">VIEW</a>
                                        </div>
                                    </div>
                                    
                                    <div class="island-card-small" data-category="ISLAND">
                                        <div class="island-card-image-small">
                                            <img src="../../img/sicogon.jpg" alt="Sicogon Island" loading="lazy">
                                            <div class="card-overlay"></div>
                                        </div>
                                        <div class="island-card-content-small">
                                            <h3>SICOGON</h3>
                                        </div>
                                        <div class="hover-info">
                                            <p>Expansive beach paradise and luxury resorts</p>
                                            <a href="#" class="view-btn">VIEW</a>
                            </div>
                                    </div>
                                    
                                    <div class="island-card-small" data-category="ISLAND">
                                        <div class="island-card-image-small">
                                            <img src="../../img/calagnaan.JPG" alt="Calagnaan Island" loading="lazy">
                                            <div class="card-overlay"></div>
                                        </div>
                                        <div class="island-card-content-small">
                                            <h3>CALAGNAAN</h3>
                                        </div>
                                        <div class="hover-info">
                                            <p>Hidden beaches and pristine natural landscapes</p>
                                            <a href="#" class="view-btn">VIEW</a>
                                        </div>
                                    </div>
                                    
                                    <div class="island-card-small" data-category="ISLAND">
                                        <div class="island-card-image-small">
                                            <img src="../../img/bantique.jpg" alt="Bantigue Island" loading="lazy">
                                            <div class="card-overlay"></div>
                                        </div>
                                        <div class="island-card-content-small">
                                            <h3>BANTIGUE</h3>
                                        </div>
                                        <div class="hover-info">
                                            <p>Amazing sandbar and crystal clear waters</p>
                                            <a href="#" class="view-btn">VIEW</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- How We Work Section -->
    <section id="how-we-work" class="how-we-work-section section-bg-sequence-2" style="background-color: #102030;">
        <div class="container">
            <div class="section-header text-center fade-in-up">
                <h2 class="section-title">How We Work</h2>
                <p class="section-subtitle">Easy booking process for a hassle-free vacation</p>
            </div>
            
            <div class="process-container mt-5">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step scroll-transition scroll-hidden">
                            <div class="step-number">01</div>
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>Choose Your Package</h3>
                            <p>Browse through our boat rentals and accommodations to find the perfect option for your trip.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step scroll-transition scroll-hidden">
                            <div class="step-number">02</div>
                            <div class="step-icon">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <h3>Select Your Dates</h3>
                            <p>Choose your preferred dates from our availability calendar to plan your visit.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step scroll-transition scroll-hidden">
                            <div class="step-number">03</div>
                            <div class="step-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3>Secure Your Booking</h3>
                            <p>Make a 50% advance payment to confirm your reservation with us.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step scroll-transition scroll-hidden">
                            <div class="step-number">04</div>
                            <div class="step-icon">
                                <i class="fas fa-umbrella-beach"></i>
                            </div>
                            <h3>Enjoy Your Trip</h3>
                            <p>Arrive at the designated location and enjoy your island adventure!</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="policies-container mt-5">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="policy-card scroll-transition scroll-hidden">
                            <h3><i class="fas fa-clipboard-list"></i> Booking Policies</h3>
                            <ul>
                                <li>Reservations must be made at least 3 days in advance</li>
                                <li>50% advance payment required to confirm booking</li>
                                <li>Full payment must be completed before the trip</li>
                                <li>Valid ID required during check-in</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="policy-card scroll-transition scroll-hidden">
                            <h3><i class="fas fa-exchange-alt"></i> Cancellation Policy</h3>
                            <ul>
                                <li>Free cancellation up to 7 days before scheduled trip</li>
                                <li>50% refund for cancellations 3-7 days before the trip</li>
                                <li>No refund for cancellations less than 3 days before the trip</li>
                                <li>Full refund if cancelled due to severe weather conditions</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="policy-card scroll-transition scroll-hidden">
                            <h3><i class="fas fa-life-ring"></i> Safety Guidelines</h3>
                            <ul>
                                <li>Life jackets provided and must be worn during boat trips</li>
                                <li>Follow guide instructions at all times</li>
                                <li>Children under 12 must be accompanied by adults</li>
                                <li>Trips may be rescheduled due to inclement weather</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="policy-card scroll-transition scroll-hidden">
                            <h3><i class="far fa-credit-card"></i> Payment Methods</h3>
                            <ul>
                                <li>Online payment via credit/debit cards</li>
                                <li>Bank transfers to our official account</li>
                                <li>GCash, PayMaya, and other e-wallets accepted</li>
                                <li>Cash payment available for walk-in bookings</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="cta-container text-center mt-4 fade-in-up delay-500">
                <p class="mb-3">Have questions about our policies? Feel free to contact us!</p>
                <a href="#contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section id="booking-form" class="booking-form-section">
    <div class="container">
            <div class="section-header fade-in-up">
                <h2>Book Your Boat</h2>
                <p>Complete the form below to start your reservation process</p>
            </div>
            
            <!-- Simplified booking form with fixed layout -->
            <div class="booking-form-container fade-in-up delay-200">
                <!-- Step indicators -->
                <div class="step-indicators-row fade-in-up delay-300">
                    <div class="step active" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Date & Time</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Boat Details</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Services</div>
                </div>
                    <div class="step" data-step="4">
                        <div class="step-circle">4</div>
                        <div class="step-label">Contact</div>
                    </div>
                    <div class="step" data-step="5">
                        <div class="step-circle">5</div>
                        <div class="step-label">Complete</div>
                    </div>
                </div>
                
                <!-- Form content -->
                <form id="boat-booking-form">
                    <!-- Step 1: Date & Time -->
                    <div class="form-step active" id="step1">
                        <h3>1. Select Date & Time</h3>
                        <div class="form-group">
                            <label for="tripDate"><i class="fas fa-calendar"></i> Trip Date</label>
                            <input type="date" id="tripDate" name="tripDate" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                            <small class="form-text">Please select a date within the next year</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tripTime"><i class="fas fa-clock"></i> Trip Time</label>
                            <select id="tripTime" name="tripTime" required>
                                <option value="">Select time</option>
                                <option value="morning">Morning (8:00 AM)</option>
                                <option value="midday">Midday (11:00 AM)</option>
                                <option value="afternoon">Afternoon (2:00 PM)</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Step 2: Boat Details -->
                    <div class="form-step" id="step2">
                        <h3>2. Boat Details</h3>
                        <div class="form-group">
                            <label for="boatType"><i class="fas fa-ship"></i> Boat Type</label>
                            <select id="boatType" name="boatType" required>
                                <option value="">Select boat</option>
                                <option value="small">Small Boat (5-8 persons)</option>
                                <option value="medium">Medium Boat (10-15 persons)</option>
                                <option value="large">Large Boat (18-25 persons)</option>
                                <option value="premium">Premium Boat (10-12 persons)</option>
                                <option value="speed">Speed Boat (6-8 persons)</option>
                                <option value="yacht">Day Yacht (up to 20 persons)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="passengers"><i class="fas fa-users"></i> Number of Passengers</label>
                            <input type="number" id="passengers" name="passengers" min="1" max="30" required>
                            <small class="form-text">Enter the number of people in your group (1-30)</small>
                    </div>
                    
                        <div class="form-group">
                            <label for="duration"><i class="fas fa-hourglass-half"></i> Duration</label>
                            <select id="duration" name="duration" required>
                                <option value="">Select duration</option>
                                <option value="halfday">Half Day (4 hours)</option>
                                <option value="fullday">Full Day (8 hours)</option>
                                <option value="custom">Custom Duration</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Step 3: Additional Services -->
                    <div class="form-step" id="step3">
                        <h3>3. Additional Services</h3>
                            <div class="service-option">
                                <input type="checkbox" id="tourGuide" name="services[]" value="tourGuide">
                            <label for="tourGuide">Tour Guide <span class="service-price">+ â‚±500</span></label>
                            </div>
                            
                            <div class="service-option">
                                <input type="checkbox" id="lunchPackage" name="services[]" value="lunchPackage">
                            <label for="lunchPackage">Lunch Package <span class="service-price">+ â‚±300 per person</span></label>
                            </div>
                            
                            <div class="service-option">
                                <input type="checkbox" id="snorkelingGear" name="services[]" value="snorkelingGear">
                            <label for="snorkelingGear">Snorkeling Gear <span class="service-price">+ â‚±150 per set</span></label>
                            </div>
                            
                            <div class="service-option">
                                <input type="checkbox" id="photographer" name="services[]" value="photographer">
                            <label for="photographer">Photographer <span class="service-price">+ â‚±1,500</span></label>
                            </div>
                            
                            <div class="service-option">
                                <input type="checkbox" id="karaoke" name="services[]" value="karaoke">
                            <label for="karaoke">Karaoke Set <span class="service-price">+ â‚±800</span></label>
                            </div>
                            
                            <div class="service-option">
                                <input type="checkbox" id="icebox" name="services[]" value="icebox">
                            <label for="icebox">Ice Box with Ice <span class="service-price">+ â‚±200</span></label>
                        </div>
                    </div>
                    
                    <!-- Step 4: Contact Information -->
                    <div class="form-step" id="step4">
                        <h3>4. Contact Information</h3>
                            <div class="form-group">
                                <label for="fullName"><i class="fas fa-user"></i> Full Name</label>
                                <input type="text" id="fullName" name="fullName" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                                <input type="email" id="email" name="email" required>
                        </div>
                        
                            <div class="form-group">
                                <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="nationality"><i class="fas fa-globe"></i> Nationality</label>
                                <input type="text" id="nationality" name="nationality">
                        </div>
                    </div>
                    
                    <!-- Step 5: Complete Booking -->
                    <div class="form-step" id="step5">
                        <h3>5. Complete Booking</h3>
                        <div class="form-group">
                            <label for="specialRequests"><i class="fas fa-comment"></i> Special Requests</label>
                        <textarea id="specialRequests" name="specialRequests" rows="3"></textarea>
                    </div>
                    
                        <div class="checkbox-container">
                            <input type="checkbox" id="termsAgreed" name="termsAgreed" required>
                            <label for="termsAgreed">I agree to the <a href="#" class="terms-link">Terms and Conditions</a> and <a href="#" class="terms-link">Privacy Policy</a></label>
                        </div>
                        
                        <div class="booking-summary">
                            <h4>Booking Summary</h4>
                            <div id="summaryContent" class="summary-content">
                                <p class="summary-placeholder">Complete the form to see your booking summary</p>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                            <button type="button" class="btn btn-outline">Calculate Price</button>
                            <button type="submit" class="btn btn-primary">Book Now</button>
                        </div>
                    </div>
                </form>
                
                <!-- Navigation buttons -->
                <div class="form-navigation fade-in-up delay-400">
                    <button type="button" class="btn-prev" disabled>
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <div class="progress-container">
                        <div class="progress-bar"></div>
                </div>
                    <button type="button" class="btn-next">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
            </div>
        </div>
    </div>
</section>

    <footer class="visually-hidden">
        <p>&copy; <?php echo date('Y'); ?> Timbook Carles Tourism. All rights reserved.</p>
    </footer>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/interface.js"></script>

    <!-- Simplified Initialization Script -->
<script>
(function() {
    // Force scroll to top on page load
    window.onload = function() {
        window.scrollTo(0, 0);
    };
    
            // Mobile menu toggle
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', function() {
                    menuToggle.classList.toggle('active');
                    mobileMenu.classList.toggle('active');
                });
            }
            
            // Search icon functionality
            const searchToggle = document.querySelector('.search-toggle');
            const socialIcons = document.querySelector('.social-icons');
            
            if (searchToggle && socialIcons) {
                searchToggle.addEventListener('click', function() {
                    searchToggle.classList.toggle('active');
                    socialIcons.classList.toggle('expanded');
                });
            }
            
            // Reveal elements with scroll-transition class
    function revealScrollElements() {
            document.querySelectorAll('.scroll-transition.scroll-hidden').forEach((element) => {
                if (element.getBoundingClientRect().top <= window.innerHeight * 0.8) {
                    element.classList.remove('scroll-hidden');
                    element.classList.add('scroll-visible');
                }
            });
    }
    
    // Initialize scroll animations
    revealScrollElements();
    
    // Handle background switching
    const landingBackground = document.querySelector('.background-image-container');
    const bookingBackground = document.querySelector('.booking-section-background');
    const bookingSection = document.querySelector('.booking-form-section');
    
    function handleBackgrounds() {
        if (!bookingSection || !landingBackground || !bookingBackground) return;
        
        const bookingSectionRect = bookingSection.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        
        // Clear transition point - when 40% of the booking section enters viewport
        if (bookingSectionRect.top < viewportHeight * 0.6) {
            // Apply transition
            document.body.classList.add('booking-section-active');
            
            // Force immediate transition for cleaner effect
            landingBackground.style.opacity = '0';
            landingBackground.style.visibility = 'hidden';
            bookingBackground.style.opacity = '1';  
            bookingBackground.style.visibility = 'visible';
        } else {
            // Return to landing background
            document.body.classList.remove('booking-section-active');
            
            // Force immediate transition
            landingBackground.style.opacity = '1';
            landingBackground.style.visibility = 'visible';
            bookingBackground.style.opacity = '0';
            bookingBackground.style.visibility = 'hidden';
        }
    }
    
    // Handle keyboard navigation for horizontal form
    const bookingFormContainer = document.querySelector('.booking-form-container');
    if (bookingFormContainer) {
        // Scroll amount for each arrow key press
        const scrollAmount = 300;
        
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!isElementInViewport(bookingFormContainer)) return;
            
            if (e.key === 'ArrowRight') {
                bookingFormContainer.scrollLeft += scrollAmount;
                e.preventDefault();
            } else if (e.key === 'ArrowLeft') {
                bookingFormContainer.scrollLeft -= scrollAmount;
                e.preventDefault();
            }
        });
        
        // Hide scroll indicator after scrolling
        let scrollTimeout;
        bookingFormContainer.addEventListener('scroll', function() {
            const scrollIndicator = document.querySelector('.scroll-indicator');
            if (scrollIndicator) {
                scrollIndicator.style.opacity = '1';
                clearTimeout(scrollTimeout);
                
                scrollTimeout = setTimeout(function() {
                    scrollIndicator.style.opacity = '0';
                }, 1500);
            }
        });
    }
    
    // Helper function to check if element is in viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    // Initial call - remove automatic background handling on page load
    // handleBackgrounds();

    // Only trigger background handling on scroll, not on initial page load
            window.addEventListener('scroll', function() {
        revealScrollElements();
        handleBackgrounds();
    });

    // Update on window resize
    window.addEventListener('resize', function() {
        // Don't call handleBackgrounds() on resize to prevent jumping to booking section
        // Only update elements that need resizing but won't cause scroll jumps
        revealScrollElements();
    });
})();

// Handle booking form navigation
function setupBookingForm() {
    // Get DOM elements
    const formSteps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step');
    const prevButton = document.querySelector('.btn-prev');
    const nextButton = document.querySelector('.btn-next');
    const progressBar = document.querySelector('.progress-bar');
    const form = document.getElementById('boat-booking-form');
    
    if (!formSteps.length || !stepIndicators.length) return;
    
    // Current step index
    let currentStep = 0;
    const totalSteps = formSteps.length;
    
    // Function to display a specific step
    function showStep(stepIndex) {
        // Validate step index
        if (stepIndex < 0 || stepIndex >= totalSteps) return;
        
        // Update current step
        currentStep = stepIndex;
        
        // Update step display
        formSteps.forEach((step, index) => {
            step.classList.toggle('active', index === currentStep);
        });
        
        // Update step indicators
        stepIndicators.forEach((indicator, index) => {
            const isActive = index === currentStep;
            const isCompleted = index < currentStep;
            
            // Reset classes
            indicator.classList.remove('active', 'completed');
            
            // Set appropriate class
            if (isActive) {
                indicator.classList.add('active');
            } else if (isCompleted) {
                indicator.classList.add('completed');
            }
        });
        
        // Update buttons
        prevButton.disabled = currentStep === 0;
        nextButton.textContent = currentStep === totalSteps - 1 ? 'Complete' : 'Next';
        nextButton.innerHTML = currentStep === totalSteps - 1 
            ? 'Complete <i class="fas fa-check"></i>' 
            : 'Next <i class="fas fa-arrow-right"></i>';
        
        // Update progress bar
        const progress = ((currentStep + 1) / totalSteps) * 100;
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }
        
        // Scroll to top of form
        const formContainer = document.querySelector('.booking-form-container');
        if (formContainer) {
            formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    // Handle next button click
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            if (currentStep < totalSteps - 1) {
                // Go to next step if validation passes
                if (validateStep(currentStep)) {
                    showStep(currentStep + 1);
                }
            } else {
                // Submit form if on last step and validation passes
                if (validateStep(currentStep)) {
                    form.dispatchEvent(new Event('submit'));
                }
            }
        });
    }
    
    // Handle previous button click
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            showStep(currentStep - 1);
        });
    }
    
    // Handle step indicator clicks
    stepIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            // Only allow backward navigation or moving one step forward
            if (index <= currentStep + 1) {
                if (index > currentStep) {
                    // Validate current step before proceeding
                    if (validateStep(currentStep)) {
                        showStep(index);
                    }
                } else {
                    // Always allow going back
                    showStep(index);
                }
            }
        });
    });
    
    // Basic validation function
    function validateStep(stepIndex) {
        const currentFormStep = formSteps[stepIndex];
        if (!currentFormStep) return true;
        
        // For quick testing, disabling validation temporarily
        return true;
        
        // Uncomment for real validation:
        /*
        const requiredFields = currentFormStep.querySelectorAll('[required]');
        let isValid = true;
        
        // Clear previous error messages
        const existingErrors = currentFormStep.querySelectorAll('.form-error');
        existingErrors.forEach(err => err.remove());
        
        // Check each required field
        requiredFields.forEach(field => {
            // Reset styling
            field.style.borderColor = '';
            
            if (!field.value.trim()) {
                isValid = false;
                
                // Add error styling
                field.style.borderColor = 'var(--color-danger, #dc3545)';
                
                // Add error message below the field
                const errorDiv = document.createElement('div');
                errorDiv.className = 'form-error';
                errorDiv.textContent = 'This field is required';
                errorDiv.style.color = 'var(--color-danger, #dc3545)';
                errorDiv.style.fontSize = '0.8rem';
                errorDiv.style.marginTop = '5px';
                field.parentNode.appendChild(errorDiv);
                
                // Clear error on input
                field.addEventListener('input', function() {
                    this.style.borderColor = '';
                    const error = this.parentNode.querySelector('.form-error');
                    if (error) error.remove();
                }, { once: true });
            }
        });
        
        return isValid;
        */
    }
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get all form data
            const formData = new FormData(form);
            
            // Here you would typically send the data to the server
            console.log('Form submission:', Object.fromEntries(formData));
            
            // For now, just show a success message
            alert('Booking successfully submitted!');
        });
    }
    
    // Initialize first step
    showStep(0);
    
    // Return the controller for external access
    return {
        goToStep: showStep,
        getCurrentStep: () => currentStep
    };
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = setupBookingForm();
    
    // Ensure page starts at the top
    window.scrollTo({
        top: 0,
        behavior: 'auto'
    });
    
    // Remove any URL hash to prevent automatic scrolling
    if (window.location.hash) {
        history.replaceState(null, document.title, window.location.pathname + window.location.search);
    }
    
    // Initialize animations with the original system
    setTimeout(function() {
        revealScrollElements();
    }, 100);
});

</script>
</body>
</html>







