<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Online Booking Reservation Boat Rentals and Accommodation/php/config/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Online Booking Reservation Boat Rentals and Accommodation/php/includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carles Tourism</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
       
        section {
            margin-bottom: 0px !important;
            position: relative;
        }
        
        /* Remove extra whitespace */
        .process {
            padding-top: 30px !important;
            padding-bottom: 30px !important;
        }
        
        /* Fix booking section spacing */
        .booking {
            margin-top: 30px !important;
        }
        
        /* Remove excessive whitespace in section headers */
        .section-header {
            margin-bottom: 30px !important;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* How We Work Section */
        .how-we-work {
            padding: 100px 5%;
            background: #FFF5EE;
            text-align: center;
        }

        .section-header {
            margin-bottom: 60px;
        }

        .section-header .subtitle {
            color: #4BB1DA;
            font-weight: 500;
            letter-spacing: 2px;
            margin-bottom: 15px;
            display: block;
        }

        .section-header h2 {
            font-size: clamp(2rem, 4vw, 2.5rem);
            color: #333;
            margin-bottom: 20px;
        }

        .section-header p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .process-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .step {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(75, 177, 218, 0.1);
        }

        .step:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(75, 177, 218, 0.15);
        }

        .step-number {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 3rem;
            font-weight: 700;
            color: rgba(75, 177, 218, 0.1);
            line-height: 1;
        }

        .step-icon {
            width: 80px;
            height: 80px;
            background: rgba(75, 177, 218, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.3s ease;
        }

        .step:hover .step-icon {
            background: #4BB1DA;
        }

        .step-icon i {
            font-size: 32px;
            color: #4BB1DA;
            transition: all 0.3s ease;
        }

        .step:hover .step-icon i {
            color: white;
        }

        .step h3 {
            color: #333;
            font-size: 1.25rem;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .step p {
            color: #666;
            line-height: 1.6;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .how-we-work {
                padding: 60px 5%;
            }

            .process-steps {
                grid-template-columns: 1fr;
                max-width: 400px;
            }

            .step {
                padding: 30px 20px;
            }

            .step-number {
                font-size: 2.5rem;
            }

            .step-icon {
                width: 60px;
                height: 60px;
            }

            .step-icon i {
                font-size: 24px;
            }
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6));
            z-index: -1;
        }
        
        .hero-content {
            text-align: center;
            color: white;
            max-width: 800px;
            padding: 0 20px;
            z-index: 1;
            position: relative;
        }
        
        .municipality-logo {
            width: 120px;
            height: auto;
            margin-bottom: 30px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
            transition: transform 0.3s ease;
        }
        
        .municipality-logo:hover {
            transform: scale(1.05);
        }
        
        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        
        .hero p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            text-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        
        .hero-btn {
            padding: 15px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-shadow: none;
        }
        
        .hero-btn.primary {
            background: #4BB1DA;
            color: white;
            border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .hero-btn.primary:hover {
            background: #3A8FB7;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        .hero-btn.secondary {
            background: transparent;
            color: white;
            border: 2px solid #E6BE8A;
        }

        .hero-btn.secondary:hover {
            background: #E6BE8A;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: #FFF5EE;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            font-size: 15px;
            position: relative;
            padding: 0.5rem 0;
        }

        .navbar.scrolled .nav-link {
            color: #3A8FB7;
        }

        .nav-link:hover {
            color: #98FF98;
        }

        .navbar.scrolled .nav-link:hover {
            color: #4BB1DA;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #98FF98;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-book, .btn-login {
            padding: 0.8rem 2rem;
            border-radius: 30px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            font-size: 15px;
            cursor: pointer;
            border: none;
            font-family: 'Poppins', sans-serif;
        }

        .btn-book {
            background: #4BB1DA;
            color: white;
            box-shadow: 0 4px 15px rgba(75, 177, 218, 0.15);
        }

        .btn-book:hover {
            background: #3A8FB7;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(75, 177, 218, 0.25);
        }

        .btn-login {
            background: transparent;
            color: white;
            border: 2px solid #E6BE8A;
        }

        .navbar.scrolled .btn-login {
            color: #3A8FB7;
            border-color: #E6BE8A;
        }

        .btn-login:hover {
            background: #E6BE8A;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(230, 190, 138, 0.25);
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            width: 44px;
            height: 44px;
            position: relative;
            z-index: 20;
        }

        .mobile-menu-btn span {
            display: block;
            width: 24px;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem 5%;
                background: transparent;
            }

            .navbar.scrolled {
                background: #FFF5EE;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            }

            .mobile-menu-btn {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 4px;
                width: 30px;
                height: 30px;
                padding: 0;
                background: transparent;
                border: none;
                cursor: pointer;
                z-index: 101;
            }

            .navbar.scrolled .mobile-menu-btn span {
                background: #4BB1DA;
            }

            .nav-left, .nav-right {
                display: none;
            }

            .nav-left.active, .nav-right.active {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: #FFF5EE;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 100;
                padding: 5rem 2rem 2rem;
            }

            .nav-center {
                position: static;
                transform: none;
            }

            .nav-logo {
                height: 40px;
            }

            .nav-right.active {
                flex-direction: column;
                gap: 2rem;
            }

            .nav-right.active .nav-links {
                flex-direction: column;
                gap: 1.5rem;
                width: 100%;
                margin-right: 0;
            }

            .nav-buttons {
                flex-direction: column;
                width: 100%;
                gap: 1rem;
            }

            .btn-book, .btn-login {
                width: 100%;
                text-align: center;
                padding: 1rem;
            }

            .mobile-menu-btn.active span {
                background: #4BB1DA;
            }

            .mobile-menu-btn.active span:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }

            .mobile-menu-btn.active span:nth-child(2) {
                opacity: 0;
            }

            .mobile-menu-btn.active span:nth-child(3) {
                transform: rotate(-45deg) translate(5px, -5px);
            }

            .hero-buttons {
                justify-content: center;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                text-align: center;
            }
        }

        /* Hero Mobile Styles */
        .hero {
            background-attachment: scroll;
            padding: 0;
            min-height: 100vh;
            align-items: center;
        }
        
        .hero-content {
            padding: 1rem;
            margin-top: 80px;
            width: 100%;
        }

        .hero-logo {
            width: 120px;
            margin-bottom: 2rem;
        }

        .hero-content h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            padding: 0 0.5rem;
            margin-bottom: 1.25rem;
        }

        .hero-content p {
            font-size: 1rem;
            padding: 0 0.5rem;
            margin-bottom: 2rem;
            max-width: 100%;
        }

        .hero-buttons {
            flex-direction: column;
            gap: 1rem;
            padding: 0;
            width: 100%;
            max-width: 100%;
            margin-top: 0.5rem;
        }

        .hero-btn {
            width: 100%;
            max-width: none;
            min-width: 0;
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
        }

        /* Hero Content */
        .hero-content {
            color: white;
            max-width: 800px;
        }

        .hero-content h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }

        .hero-content p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            margin-bottom: 2.5rem;
            opacity: .95;
            letter-spacing: 0.5px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: #4BB1DA;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: .3s;
        }

        .btn-primary:hover {
            background: #3A8FB7;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.5px;
            border: 2px solid #E6BE8A;
        }

        .btn-secondary:hover {
            background: #E6BE8A;
            border-color: #E6BE8A;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(230, 190, 138, 0.2);
        }

        @media (max-width: 768px) {
            .navbar {
                justify-content: space-between;
            }

            .nav-logo {
                position: static;
                transform: none;
            }

            .nav-links, .nav-buttons {
                display: none;
            }

            .nav-links.active {
                display: flex;
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
                width: 80%;
                background: #FFF5EE;
                flex-direction: column;
                padding: 80px 40px;
                transition: .3s ease-in-out;
                z-index: 15;
            }

            .right-links {
                margin-top: 1rem;
            }

            .nav-links.active .nav-link {
                color: #3A8FB7;
                font-size: 1.1rem;
                padding: 15px 0;
                width: 100%;
                text-align: center;
                border-bottom: 1px solid rgba(75, 177, 218, 0.1);
            }

            .nav-links.active .nav-link:hover {
                color: #4BB1DA;
                background: rgba(75, 177, 218, 0.05);
            }

            .nav-links.active .nav-btn {
                display: block;
                width: 100%;
                text-align: center;
                margin-top: 1rem;
            }

            .mobile-menu-btn {
                display: block;
                width: 35px;
                height: 30px;
                position: relative;
                cursor: pointer;
                background: transparent;
                border: none;
                z-index: 20;
            }

            .mobile-menu-btn span,
            .mobile-menu-btn span::before,
            .mobile-menu-btn span::after {
                content: '';
                position: absolute;
                width: 100%;
                height: 2px;
                background: #4BB1DA;
                transition: .3s ease-in-out;
            }

            .mobile-menu-btn span {
                top: 50%;
                transform: translateY(-50%);
            }

            .mobile-menu-btn span::before {
                top: -10px;
            }

            .mobile-menu-btn span::after {
                bottom: -10px;
            }

            .mobile-menu-btn.active span {
                background: transparent;
            }

            .mobile-menu-btn.active span::before {
                transform: rotate(45deg);
                top: 0;
            }

            .mobile-menu-btn.active span::after {
                transform: rotate(-45deg);
                bottom: 0;
            }

            .hero-buttons {
                justify-content: center;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                text-align: center;
            }
        }

        /* Navbar Layout */
        .nav-left, .nav-right {
            display: flex;
            align-items: center;
            gap: 2.5rem;
        }

        .nav-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav-logo {
            height: 50px;
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        }
        
        .nav-logo:hover {
            transform: scale(1.05);
        }

        .nav-right .nav-links {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            margin-right: 2rem;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Mobile Menu */
        @media (max-width: 768px) {
            .navbar {
                background: #FFF5EE;
            }

            body.menu-active {
                overflow: hidden;
            }

            .nav-left, .nav-right {
                display: none;
            }

            .nav-left.active, .nav-right.active {
                display: flex;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: #FFF5EE;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 99;
                padding: 5rem 2rem 2rem;
            }

            .nav-center {
                position: static;
                transform: none;
            }

            .nav-logo {
                height: 40px;
            }

            .nav-right.active {
                flex-direction: column;
                gap: 2rem;
            }

            .nav-right.active .nav-links {
                flex-direction: column;
                gap: 1.5rem;
                width: 100%;
                margin-right: 0;
            }

            .nav-buttons {
                flex-direction: column;
                width: 100%;
                gap: 1rem;
            }

            .btn-book, .btn-login {
                width: 100%;
                text-align: center;
                padding: 1rem;
            }
        }

        /* Card Styles */
        .card-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            padding: 0.8rem 0;
            border-top: 1px solid rgba(75, 177, 218, 0.1);
            border-bottom: 1px solid rgba(75, 177, 218, 0.1);
            font-size: 0.95rem;
            color: #666;
        }

        .card-details span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-details i {
            color: #4BB1DA;
            font-size: 1.1rem;
        }

        .accommodation-card:hover .card-details {
            border-color: rgba(75, 177, 218, 0.2);
        }

        .accommodation-card:hover .card-details i {
            color: #3A8FB7;
        }

        .accommodation-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(75, 177, 218, 0.1);
            transition: all 0.3s ease;
        }

        .accommodation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(75, 177, 218, 0.15);
        }

        .card-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .accommodation-card:hover .card-image img {
            transform: scale(1.05);
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-content h3 {
            color: #333;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .card-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .card-content .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: #4BB1DA;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-content .btn:hover {
            background: #3A8FB7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 177, 218, 0.2);
        }

        @media (max-width: 768px) {
            .card-image {
                height: 200px;
            }

            .card-content {
                padding: 1.25rem;
            }

            .card-details {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .card-details span {
                width: 100%;
            }
        }

        /* Destinations Section */
        .destinations {
            padding: 80px 5%;
            background: linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)),
                        url('/Online Booking Reservation Boat Rentals and Accommodation/img/wave-pattern.png');
            background-size: cover;
            background-position: center;
        }

        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .destination-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(75, 177, 218, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid transparent;
        }

        .destination-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(75, 177, 218, 0.2);
            border-color: #4BB1DA;
        }

        .destination-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .destination-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .destination-card:hover .destination-image img {
            transform: scale(1.1);
        }

        .destination-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.7));
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .destination-card:hover .destination-overlay {
            opacity: 1;
        }

        .destination-overlay i {
            color: white;
            font-size: 3rem;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .destination-card:hover .destination-overlay i {
            transform: translateY(0);
        }

        .destination-content {
            padding: 25px;
        }

        .destination-content h3 {
            color: #3A8FB7;
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .destination-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            min-height: 80px;
        }

        .destination-content .btn {
            background: #4BB1DA;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            font-weight: 500;
        }

        .destination-content .btn:hover {
            background: #3A8FB7;
            transform: translateX(5px);
        }

        /* Booking Section */
        .booking {
            padding: 100px 5%;
            margin-top: 80px;
            position: relative;
            background: linear-gradient(to right, rgba(75, 177, 218, 0.9), rgba(58, 143, 183, 0.85)),
                        url('https://images.unsplash.com/photo-1517760444937-f6397edcbbcd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-attachment: fixed;
        }

        .booking-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(75, 177, 218, 0.15);
            border: 2px solid #4BB1DA;
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            color: #3A8FB7;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #4BB1DA;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 15px;
            border: 2px solid #E6BE8A;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4BB1DA;
            box-shadow: 0 0 0 3px rgba(75, 177, 218, 0.1);
        }

        .btn-primary {
            background: #4BB1DA;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background: #3A8FB7;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .booking-container {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-primary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero" id="home">
        <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" class="hero-background" alt="Beach Background">
        <div class="hero-overlay"></div>
        <!-- Enhanced Navigation -->
        <nav class="navbar">
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Left Navigation -->
            <div class="nav-left">
                <a href="#" class="nav-link">Home</a>
                <a href="#boat-rental" class="nav-link">Boat Rentals</a>
                <a href="#accommodations" class="nav-link">Accommodations</a>
            </div>
            <!-- Center Logo -->
            <div class="nav-center">
                <img src="/Online Booking Reservation Boat Rentals and Accommodation/img/Gigantes Islands Eco-Tour.jpg" alt="Carles Logo" class="nav-logo">
            </div>

            <!-- Right Navigation -->
            <div class="nav-right">
                <div class="nav-links">
                    <a href="#about" class="nav-link">About Us</a>
                    <a href="#contact" class="nav-link">Contact Us</a>
                </div>
                <div class="nav-buttons">
                    <button class="btn-book">Book Now</button>
                    <button class="btn-login" onclick="window.location.href='../../Loginup Admin/loginup_admin.php'">Login</button>
                </div>
            </div>
        </nav>

        <div class="hero-content">
            <div class="hero-logo">
                <img src="/Online Booking Reservation Boat Rentals and Accommodation/img/Gigantes Islands Eco-Tour.jpg" alt="Gigantes Islands Eco-Tour" class="municipality-logo">
            </div>
            <h1>Freedom is just an<br>adventure away</h1>
            <p>Experience the beauty of Carles waters with our premium boat rentals and island tours.</p>
            <div class="hero-buttons">
                <a href="#booking" class="hero-btn primary">GET STARTED</a>
                <a href="#about" class="hero-btn secondary">ABOUT US</a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const menuToggle = document.querySelector('.mobile-menu-btn');
            const navLeft = document.querySelector('.nav-left');
            const navRight = document.querySelector('.nav-right');
            const body = document.body;

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Mobile menu toggle
            menuToggle.addEventListener('click', function() {
                menuToggle.classList.toggle('active');
                navLeft.classList.toggle('active');
                navRight.classList.toggle('active');
                body.classList.toggle('menu-active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.navbar') && navLeft.classList.contains('active')) {
                    menuToggle.classList.remove('active');
                    navLeft.classList.remove('active');
                    navRight.classList.remove('active');
                    body.classList.remove('menu-active');
                }
            });
        });
    </script>

    <!-- Our Working Process Section -->
    <section class="process" id="process">
        <div class="section-header">
            <h2>How It Works</h2>
            <p>Experience seamless service with our simple booking process</p>
        </div>
        
        <div class="process-container">
            <div class="process-step" data-aos="fade-up" data-aos-delay="100">
                <div class="step-icon">
                    <i class="fas fa-ship"></i>
                </div>
                <div class="transport-label">Boat</div>
                <h3>Chart Your Course</h3>
                <p>Browse our selection of boats and accommodations for your perfect island getaway</p>
            </div>
            
            <div class="process-step" data-aos="fade-up" data-aos-delay="200">
                <div class="step-icon">
                    <i class="fas fa-water"></i>
                </div>
                <div class="transport-label">Yacht</div>
                <h3>Set Your Sail Date</h3>
                <p>Select your preferred dates and check real-time availability</p>
            </div>
            
            <div class="process-step" data-aos="fade-up" data-aos-delay="300">
                <div class="step-icon">
                    <i class="fas fa-anchor"></i>
                </div>
                <div class="transport-label">Ferry</div>
                <h3>Secure Your Voyage</h3>
                <p>Reserve your booking with our easy-to-use online system</p>
            </div>
            
            <div class="process-step" data-aos="fade-up" data-aos-delay="400">
                <div class="step-icon">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <div class="transport-label">Jet Ski</div>
                <h3>Enjoy Paradise</h3>
                <p>Start your island adventure with our professional service</p>
            </div>
        </div>
    </section>

    <style>
        /* Process Section */
        .process {
            padding: 80px 5%;
            background: linear-gradient(rgba(75, 177, 218, 0.85), rgba(58, 143, 183, 0.85)),
                        url('https://images.unsplash.com/photo-1484291470158-2f65184471c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-attachment: fixed;
            text-align: center;
            color: white;
        }

        .section-header h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
            text-align: center;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .section-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin-bottom: 50px;
            text-align: center;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .process-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 0 auto;
            max-width: 1200px;
        }

        .process-step {
            flex: 1;
            min-width: 250px;
            max-width: 280px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 5px solid transparent;
            overflow: hidden;
            height: 300px;
            max-height: 300px;
        }
        
        .process-step::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, #4BB1DA, #3A8FB7);
        }

        .process-step:nth-child(1)::before {
            background: linear-gradient(to right, #4BB1DA, #3A8FB7);
        }

        .process-step:nth-child(2)::before {
            background: linear-gradient(to right, #3A8FB7, #00CED1);
        }

        .process-step:nth-child(3)::before {
            background: linear-gradient(to right, #00CED1, #20B2AA);
        }

        .process-step:nth-child(4)::before {
            background: linear-gradient(to right, #20B2AA, #4BB1DA);
        }

        .process-step:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .step-icon {
            width: 80px;
            height: 80px;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .process-step:nth-child(1) .step-icon {
            background: linear-gradient(135deg, #4BB1DA, #3A8FB7);
        }

        .process-step:nth-child(2) .step-icon {
            background: linear-gradient(135deg, #3A8FB7, #00CED1);
        }

        .process-step:nth-child(3) .step-icon {
            background: linear-gradient(135deg, #00CED1, #20B2AA);
        }

        .process-step:nth-child(4) .step-icon {
            background: linear-gradient(135deg, #20B2AA, #4BB1DA);
        }
        
        .step-icon i {
            font-size: 2.2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .process-step:hover .step-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .transport-label {
            position: absolute;
            top: 0;
            right: 0;
            background: linear-gradient(135deg, #4BB1DA, #3A8FB7);
            color: white;
            font-weight: bold;
            padding: 6px 15px;
            border-radius: 0 15px 0 15px;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .process-step:nth-child(1) .transport-label {
            background: linear-gradient(135deg, #4BB1DA, #3A8FB7);
        }
        
        .process-step:nth-child(2) .transport-label {
            background: linear-gradient(135deg, #3A8FB7, #00CED1);
        }
        
        .process-step:nth-child(3) .transport-label {
            background: linear-gradient(135deg, #00CED1, #20B2AA);
        }
        
        .process-step:nth-child(4) .transport-label {
            background: linear-gradient(135deg, #20B2AA, #4BB1DA);
        }
        
        .process-step h3 {
            color: #3A8FB7;
            margin-bottom: 15px;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .process-step p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
        }
    </style>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="section-header">
            <span class="subtitle">What We Offer</span>
            <h2>Our Services</h2>
            <p>Discover our premium offerings for your perfect island experience</p>
        </div>

        <div class="services-grid">
            <div class="service-card" data-aos="fade-up">
                <div class="service-icon">
                    <i class="fas fa-route"></i>
                </div>
                <h3>Island Hopping</h3>
                <p>Guided tours of the stunning Gigantes Islands with experienced local guides, offering both day trips and multi-day adventures.</p>
                <a href="#" class="service-btn">Learn More</a>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Accommodations</h3>
                <p>Comfortable and cozy accommodations ranging from budget-friendly options to premium beachfront cottages for all travelers.</p>
                <a href="#" class="service-btn">Learn More</a>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="fas fa-ship"></i>
                </div>
                <h3>Boat Rentals</h3>
                <p>Safe and reliable boat rental services with experienced crew members for island hopping, fishing trips, and beach excursions.</p>
                <a href="#" class="service-btn">Learn More</a>
            </div>
        </div>
    </section>

    <style>
        /* Services Section Styles */
        .services {
            padding: 80px 5%;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                        url('https://images.unsplash.com/photo-1596459185144-baa6de4d5dfb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-attachment: fixed;
            text-align: center;
        }

        .services .section-header {
            max-width: 800px;
            margin: 0 auto 60px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 2px solid transparent;
        }

        .service-card:hover {
            transform: translateY(-15px);
            border-color: #4BB1DA;
            box-shadow: 0 15px 35px rgba(75, 177, 218, 0.2);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: rgba(75, 177, 218, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            background: #4BB1DA;
        }

        .service-icon i {
            font-size: 32px;
            color: #4BB1DA;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon i {
            color: white;
        }

        .service-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }

        .service-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .service-btn {
            background: #4BB1DA;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            margin-top: auto;
        }

        .service-btn:hover {
            background: #3A8FB7;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 992px) {
            .services-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .services {
                padding: 60px 5%;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
        }
    </style>

    <!-- Add JavaScript for Boat Catalog Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Boat Catalog Slider Functionality
            const track = document.querySelector('.boat-grid');
            const slides = Array.from(document.querySelectorAll('.boat-item'));
            const nextButton = document.querySelector('.slider-next');
            const prevButton = document.querySelector('.slider-prev');
            const dotsNav = document.querySelector('.slider-dots');
            const dots = Array.from(document.querySelectorAll('.slider-dot'));
            
            let slideWidth = slides[0].getBoundingClientRect().width;
            let slideIndex = 0;
            const totalSlides = slides.length;
            
            // Arrange slides next to each other
            const setSlidePosition = () => {
                slideWidth = slides[0].getBoundingClientRect().width;
                slides.forEach((slide, index) => {
                    slide.style.left = slideWidth * index + 'px';
                });
            };
            
            // Initialize positions
            slides.forEach((slide, index) => {
                slide.style.position = 'absolute';
                slide.style.left = slideWidth * index + 'px';
            });
            
            // Move to target slide
            const moveToSlide = (currentIndex) => {
                track.style.transform = 'translateX(-' + (slideWidth * currentIndex) + 'px)';
                
                // Update active dot
                document.querySelector('.slider-dot.active').classList.remove('active');
                dots[currentIndex].classList.add('active');
                
                slideIndex = currentIndex;
            };
            
            // Next button click
            nextButton.addEventListener('click', () => {
                if (slideIndex === totalSlides - 1) {
                    moveToSlide(0); // Loop back to first slide
                } else {
                    moveToSlide(slideIndex + 1);
                }
            });
            
            // Previous button click
            prevButton.addEventListener('click', () => {
                if (slideIndex === 0) {
                    moveToSlide(totalSlides - 1); // Loop to last slide
                } else {
                    moveToSlide(slideIndex - 1);
                }
            });
            
            // Dot click navigation
            dotsNav.addEventListener('click', e => {
                const targetDot = e.target.closest('.slider-dot');
                
                if (!targetDot) return;
                
                const targetIndex = dots.findIndex(dot => dot === targetDot);
                moveToSlide(targetIndex);
            });
            
            // Auto slide every 5 seconds
            let slideInterval = setInterval(() => {
                if (slideIndex === totalSlides - 1) {
                    moveToSlide(0);
                } else {
                    moveToSlide(slideIndex + 1);
                }
            }, 5000);
            
            // Pause auto slide on hover
            track.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            // Resume auto slide when mouse leaves
            track.addEventListener('mouseleave', () => {
                slideInterval = setInterval(() => {
                    if (slideIndex === totalSlides - 1) {
                        moveToSlide(0);
                    } else {
                        moveToSlide(slideIndex + 1);
                    }
                }, 5000);
            });
            
            // Update slide positions on resize
            window.addEventListener('resize', () => {
                setSlidePosition();
                moveToSlide(slideIndex);
            });
        });
    </script>

    

    <!-- Book Your Adventure Section -->
    <section class="booking" id="booking">
        <div class="booking-wrapper">
            <div class="booking-content" data-aos="fade-right">
                <span class="subtitle">Plan Your Trip</span>
                <h2>Book Your Adventure</h2>
                <p>Ready to experience the breathtaking beauty of Carles and the Gigantes Islands? Reserve your tour, accommodation, or boat rental today and prepare for an unforgettable journey.</p>
                <ul class="booking-features">
                    <li><i class="fas fa-check-circle"></i> Instant confirmation</li>
                    <li><i class="fas fa-check-circle"></i> Flexible scheduling</li>
                    <li><i class="fas fa-check-circle"></i> Local expert guides</li>
                    <li><i class="fas fa-check-circle"></i> Inclusive packages</li>
                </ul>
                <div class="booking-cta">
                    <a href="#contact" class="btn-primary">Book Now</a>
                    <a href="#" class="btn-secondary">View Packages</a>
                </div>
            </div>
            
            <div class="booking-form-container" data-aos="fade-left">
                <div class="booking-form">
                    <h3>Quick Inquiry</h3>
                    <form action="#" method="post">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="date">Preferred Date</label>
                                <input type="date" id="date" name="date" required>
                            </div>
                            
                            <div class="form-group half">
                                <label for="guests">Number of Guests</label>
                                <select id="guests" name="guests" required>
                                    <option value="" disabled selected>Select</option>
                                    <option value="1-2">1-2 guests</option>
                                    <option value="3-5">3-5 guests</option>
                                    <option value="6-10">6-10 guests</option>
                                    <option value="11+">11+ guests</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="service">Service Interested In</label>
                            <select id="service" name="service" required>
                                <option value="" disabled selected>Select a service</option>
                                <option value="island-hopping">Island Hopping Tour</option>
                                <option value="boat-rental">Boat Rental</option>
                                <option value="accommodation">Accommodation</option>
                                <option value="package">Complete Package</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Special Requests</label>
                            <textarea id="message" name="message" placeholder="Any special requests or questions?" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">Send Inquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Booking Section Styles */
        .booking {
            padding: 100px 5%;
            margin-top: 80px;
            position: relative;
            background: linear-gradient(to right, rgba(75, 177, 218, 0.9), rgba(58, 143, 183, 0.85)),
                        url('https://images.unsplash.com/photo-1517760444937-f6397edcbbcd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-attachment: fixed;
            color: white;
        }

        .booking-wrapper {
            display: flex;
            gap: 60px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .booking-content {
            flex: 1;
        }

        .booking-content .subtitle {
            display: inline-block;
            color: #FFF5EE;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .booking-content h2 {
            font-size: 2.5rem;
            margin-bottom: 25px;
            position: relative;
        }

        .booking-content h2:after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 70px;
            height: 4px;
            background: #E6BE8A;
            border-radius: 2px;
        }

        .booking-content p {
            line-height: 1.8;
            margin-bottom: 30px;
            font-size: 1.1rem;
            max-width: 90%;
        }

        .booking-features {
            list-style: none;
            padding: 0;
            margin: 0 0 35px 0;
        }

        .booking-features li {
            margin-bottom: 15px;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
        }

        .booking-features li i {
            color: #E6BE8A;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .booking-cta {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .btn-primary, .btn-secondary {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #E6BE8A;
            color: #333;
            box-shadow: 0 5px 15px rgba(230, 190, 138, 0.3);
        }

        .btn-primary:hover {
            background: #D4AC6E;
            transform: translateY(-5px);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .booking-form-container {
            flex: 1;
        }

        .booking-form {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .booking-form h3 {
            color: #3A8FB7;
            font-size: 1.8rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group.half {
            flex: 1;
        }

        .booking-form label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .booking-form input,
        .booking-form select,
        .booking-form textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            color: #333;
            transition: all 0.3s ease;
        }

        .booking-form input:focus,
        .booking-form select:focus,
        .booking-form textarea:focus {
            outline: none;
            border-color: #4BB1DA;
            box-shadow: 0 0 0 3px rgba(75, 177, 218, 0.2);
            background: white;
        }

        .booking-form textarea {
            resize: vertical;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 14px;
            background: #4BB1DA;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover {
            background: #3A8FB7;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 992px) {
            .booking {
                padding: 80px 5%;
            }
            
            .booking-wrapper {
                flex-direction: column;
                gap: 50px;
            }
            
            .booking-content p {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .booking {
                padding: 60px 5%;
            }
            
            .booking-content h2 {
                font-size: 2rem;
            }
            
            .booking-cta {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn-primary, .btn-secondary {
                text-align: center;
            }
            
            .form-row {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>

    <!-- About Us Section -->
    <section class="about-us" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text" data-aos="fade-right">
                    <span class="subtitle">Our Story</span>
                    <h2>About Us</h2>
                    <p>Welcome to Carles Tourism and Boat Rental Services, your premier destination for island adventures in the stunning Gigantes Islands of Iloilo, Philippines.</p>
                    <p>Established in 2015, we are a locally-owned tourism company dedicated to providing exceptional experiences while promoting sustainable tourism practices that protect our natural treasures for future generations.</p>
                    <p>Our team consists of experienced local guides and captains with deep knowledge of the islands and waters of Northern Iloilo. We pride ourselves on delivering safe, memorable, and authentic Filipino experiences to visitors from around the world.</p>
                    
                    <div class="about-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="highlight-info">
                                <h4>Local Expertise</h4>
                                <p>All our guides are local residents with intimate knowledge of the area</p>
                            </div>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="highlight-info">
                                <h4>Safety First</h4>
                                <p>Your safety is our priority with well-maintained equipment and experienced crew</p>
                            </div>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="highlight-info">
                                <h4>Community Support</h4>
                                <p>We actively support local communities through sustainable tourism</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="about-image" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1599710666624-38e29709e116?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Carles Tourism Team">
                    <div class="about-experience">
                        <span class="years">8+</span>
                        <span class="text">Years of<br>Experience</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* About Us Section Styles */
        .about-us {
            padding: 100px 5%;
            background: linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)),
                        url('https://images.unsplash.com/photo-1559128010-7d11871f8fc7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-attachment: fixed;
            overflow: hidden;
        }

        .about-content {
            display: flex;
            align-items: center;
            gap: 60px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-text {
            flex: 1;
        }

        .about-text .subtitle {
            display: inline-block;
            color: #4BB1DA;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .about-text h2 {
            font-size: 2.5rem;
            margin-bottom: 25px;
            color: #333;
            position: relative;
        }

        .about-text h2:after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 70px;
            height: 4px;
            background: #4BB1DA;
            border-radius: 2px;
        }

        .about-text p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .about-highlights {
            margin-top: 40px;
        }

        .highlight-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .highlight-icon {
            width: 60px;
            height: 60px;
            background: rgba(75, 177, 218, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .highlight-icon i {
            font-size: 24px;
            color: #4BB1DA;
        }

        .highlight-info h4 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: #333;
        }

        .highlight-info p {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .about-image {
            flex: 1;
            position: relative;
        }

        .about-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .about-experience {
            position: absolute;
            bottom: 30px;
            left: -30px;
            background: #4BB1DA;
            color: white;
            border-radius: 20px;
            padding: 25px 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(75, 177, 218, 0.3);
        }

        .about-experience .years {
            display: block;
            font-size: 3rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 10px;
        }

        .about-experience .text {
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.3;
        }

        @media (max-width: 992px) {
            .about-us {
                padding: 80px 5%;
            }
            
            .about-content {
                flex-direction: column;
                gap: 60px;
            }
            
            .about-text, .about-image {
                width: 100%;
            }
            
            .about-experience {
                bottom: 20px;
                left: 20px;
                padding: 20px 25px;
            }
        }

        @media (max-width: 768px) {
            .about-us {
                padding: 60px 5%;
            }
            
            .about-text h2 {
                font-size: 2rem;
            }
            
            .about-experience {
                padding: 15px 20px;
            }
            
            .about-experience .years {
                font-size: 2.5rem;
            }
            
            .about-experience .text {
                font-size: 0.9rem;
            }
        }
    </style>

   

    <!-- Login Modal -->
    <div class="modal-container fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="modal-content w-full max-w-4xl h-[90vh] bg-white rounded-lg shadow-2xl overflow-hidden">
            <iframe id="modalFrame" class="w-full h-full border-0"></iframe>
        </div>
    </div>

    <!-- Minimalistic Get in Touch Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-6 contact-info" data-aos="fade-up">
                    <span class="subtitle">Connect With Us</span>
                    <h2>Get in Touch</h2>
                    <p>Have questions about our island tours or boat rentals? We're here to help you plan your perfect adventure.</p>
                    
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h5>Our Location</h5>
                            <p>Carles Port Terminal, Carles, Iloilo, Philippines 5019</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h5>Phone Number</h5>
                            <p>+63 912 345 6789</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h5>Email Address</h5>
                            <p>info@carlesislandtours.com</p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-tripadvisor"></i></a>
                    </div>
                </div>
                
                <div class="col-md-6 contact-form-wrapper" data-aos="fade-up" data-aos-delay="100">
                    <form class="contact-form">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="contactName" placeholder="Your Name" required>
                            <label for="contactName">Your Name</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="contactEmail" placeholder="Your Email" required>
                            <label for="contactEmail">Your Email</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="contactSubject" placeholder="Subject">
                            <label for="contactSubject">Subject</label>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <textarea class="form-control" id="contactMessage" placeholder="Your Message" style="height: 150px" required></textarea>
                            <label for="contactMessage">Your Message</label>
                        </div>
                        
                        <button type="submit" class="btn btn-send">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Minimalistic Contact Section Styles */
        .contact-section {
            padding: 100px 0;
            background: linear-gradient(to right, rgba(75, 177, 218, 0.9), rgba(58, 143, 183, 0.9)), 
                        url('https://images.unsplash.com/photo-1505118380757-4b0a9fe9c7a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: #FFF5EE;
            position: relative;
            overflow: hidden;
        }
        
        .contact-section::before {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        
        .contact-section .subtitle {
            color: #E6BE8A;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 10px;
        }
        
        .contact-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: white;
        }
        
        .contact-section .contact-info p {
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        
        .info-item i {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            transition: all 0.3s ease;
        }
        
        .info-item:hover i {
            background: #E6BE8A;
            transform: translateY(-3px);
        }
        
        .info-item h5 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: white;
        }
        
        .info-item p {
            font-size: 0.9rem;
            margin: 0;
            opacity: 0.8;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .social-links a:hover {
            background: #E6BE8A;
            transform: translateY(-3px);
        }
        
        .contact-form-wrapper {
            padding-left: 30px;
        }
        
        .contact-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        
        .contact-form .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 30px;
            padding: 1.2rem 1rem;
        }
        
        .contact-form .form-control:focus {
            box-shadow: 0 0 0 3px rgba(230, 190, 138, 0.3);
            border-color: #E6BE8A;
            background: rgba(255, 255, 255, 0.15);
        }
        
        .contact-form .form-floating label {
            color: rgba(255, 255, 255, 0.8);
            padding-left: 1rem;
        }
        
        .contact-form .form-floating>.form-control:focus~label, 
        .contact-form .form-floating>.form-control:not(:placeholder-shown)~label {
            color: white;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
        
        .btn-send {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #E6BE8A;
            color: #333;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-send:hover {
            background: #D4AC6E;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-send i {
            transition: transform 0.3s ease;
        }
        
        .btn-send:hover i {
            transform: translateX(3px);
        }
        
        @media (max-width: 992px) {
            .contact-form-wrapper {
                padding-left: 15px;
                margin-top: 50px;
            }
        }
        
        @media (max-width: 768px) {
            .contact-section {
                padding: 70px 0;
            }
            
            .contact-section h2 {
                font-size: 2rem;
            }
            
            .contact-form {
                padding: 30px 20px;
            }
        }
    </style>

    <!-- Beaches of Carles Iloilo Section -->
    <section class="beaches" id="beaches">
        <div class="section-header">
            <span class="subtitle">Paradises</span>
            <h2>Beaches of Carles Iloilo</h2>
            <p>Explore the pristine and breathtaking coastal treasures of Carles</p>
        </div>

        <div class="beach-slider-container">
            <div class="beach-slider-controls">
                <button class="beach-prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="beach-next-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
            
            <div class="beach-slider">
                <div class="beach-item" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Isla de Gigantes Beach">
                    <div class="beach-content">
                        <h3>Isla de Gigantes Beach</h3>
                        <div class="beach-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>Pristine white sand beach with crystal clear waters perfect for swimming and snorkeling.</p>
                        <a href="#" class="beach-btn">View Details</a>
                    </div>
                </div>
                
                <div class="beach-item" data-aos="fade-up" data-aos-delay="100">
                    <img src="https://images.unsplash.com/photo-1559494007-9d11871f8fc7?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Antonia Beach">
                    <div class="beach-content">
                        <h3>Antonia Beach</h3>
                        <div class="beach-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>A secluded beach paradise with powdery white sand and stunning limestone formations.</p>
                        <a href="#" class="beach-btn">View Details</a>
                    </div>
                </div>
                
                <div class="beach-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="beach-image">
                        <img src="https://images.unsplash.com/photo-1506953823976-c4273599097b?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Bantigue Sandbar">
                        <div class="beach-badge">Popular</div>
                    </div>
                    <div class="beach-content">
                        <h3>Bantigue Sandbar</h3>
                        <div class="beach-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>Stunning sandbar that emerges during low tide, creating a pathway between islands.</p>
                        <a href="#" class="beach-btn">View Details</a>
                    </div>
                </div>
                
                <div class="beach-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="beach-image">
                        <img src="https://images.unsplash.com/photo-1577537500263-4b0a9fe9c7a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Tangke Saltwater Lagoon">
                        <div class="beach-badge">Family</div>
                    </div>
                    <div class="beach-content">
                        <h3>Tangke Saltwater Lagoon</h3>
                        <div class="beach-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>Hidden lagoon surrounded by towering limestone cliffs with emerald green waters.</p>
                        <a href="#" class="beach-btn">View Details</a>
                    </div>
                </div>
                
                <div class="beach-item" data-aos="fade-up" data-aos-delay="400">
                    <div class="beach-image">
                        <img src="https://images.unsplash.com/photo-1535262412227-c97e3b42e45c?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Pulupandan Beach">
                        <div class="beach-badge">Luxury</div>
                    </div>
                    <div class="beach-content">
                        <h3>Pulupandan Beach</h3>
                        <div class="beach-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <p>Quiet and unspoiled beach with rich marine biodiversity perfect for diving.</p>
                        <a href="#" class="beach-btn">View Details</a>
                    </div>
                </div>
            </div>
            
            <div class="beach-slider-pagination">
                <span class="beach-pagination-dot active"></span>
                <span class="beach-pagination-dot"></span>
                <span class="beach-pagination-dot"></span>
                <span class="beach-pagination-dot"></span>
                <span class="beach-pagination-dot"></span>
            </div>
        </div>
    </section>

    <style>
        /* Beaches Section Styles */
        .beaches {
            padding: 80px 5%;
            margin-top: 30px;
            margin-bottom: 50px;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                        url('https://images.unsplash.com/photo-1520808889-c5e13163b6a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-attachment: fixed;
        }

        .beaches .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 60px;
        }

        .beach-slider-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 40px;
        }

        .beach-slider {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            gap: 30px;
            padding: 20px 10px;
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        .beach-slider::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .beach-item {
            flex: 0 0 400px;
            scroll-snap-align: start;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .beach-item:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .beach-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .beach-item:hover img {
            transform: scale(1.1);
        }

        .beach-content {
            padding: 25px;
        }

        .beach-content h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .beach-rating {
            color: #E6BE8A;
            margin-bottom: 15px;
        }

        .beach-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .beach-btn {
            display: inline-block;
            background: #4BB1DA;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            margin-top: auto;
        }

        .beach-btn:hover {
            background: #3A8FB7;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .beach-slider-controls {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            z-index: 10;
            pointer-events: none;
        }

        .beach-prev-btn, .beach-next-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            pointer-events: auto;
        }

        .beach-prev-btn:hover, .beach-next-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        .beach-prev-btn i, .beach-next-btn i {
            color: #4BB1DA;
            font-size: 1.2rem;
        }

        .beach-slider-pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .beach-pagination-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(75, 177, 218, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .beach-pagination-dot.active, .beach-pagination-dot:hover {
            background: #4BB1DA;
            transform: scale(1.2);
        }

        @media (max-width: 992px) {
            .beach-item {
                flex: 0 0 350px;
            }
        }

        @media (max-width: 768px) {
            .beaches {
                padding: 60px 5%;
            }
            
            .beach-item {
                flex: 0 0 300px;
            }
            
            .beach-slider-container {
                padding: 20px 20px;
            }
        }

        @media (max-width: 576px) {
            .beach-item {
                flex: 0 0 260px;
            }
            
            .beach-slider-controls {
                display: none;
            }
        }
    </style>

    <script>
        // Beach Slider Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const beachSlider = document.querySelector('.beach-slider');
            const beachItems = document.querySelectorAll('.beach-item');
            const beachPrevBtn = document.querySelector('.beach-prev-btn');
            const beachNextBtn = document.querySelector('.beach-next-btn');
            const beachPaginationDots = document.querySelectorAll('.beach-pagination-dot');
            
            let beachCurrentIndex = 0;
            const beachItemWidth = beachItems[0].offsetWidth + 30; // 30px is the gap
            
            function goToBeachSlide(index) {
                if (index < 0) index = 0;
                if (index > beachItems.length - 1) index = beachItems.length - 1;
                
                beachCurrentIndex = index;
                beachSlider.scrollLeft = beachItemWidth * index;
                
                // Update pagination dots
                beachPaginationDots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
            }
            
            beachPrevBtn.addEventListener('click', () => {
                goToBeachSlide(beachCurrentIndex - 1);
            });
            
            beachNextBtn.addEventListener('click', () => {
                goToBeachSlide(beachCurrentIndex + 1);
            });
            
            // Add click event to pagination dots
            beachPaginationDots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    goToBeachSlide(index);
                });
            });
            
            // Handle scroll events
            beachSlider.addEventListener('scroll', () => {
                const scrollLeft = beachSlider.scrollLeft;
                const index = Math.round(scrollLeft / beachItemWidth);
                
                beachPaginationDots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
                
                beachCurrentIndex = index;
            });
        });
    </script>

    <!-- Gallery - Islands of Carles Iloilo Section -->
    <section class="gallery" id="gallery">
        <div class="section-header">
            <span class="subtitle">Explore Paradise</span>
            <h2>Islands of Carles Iloilo</h2>
            <p>Discover the breathtaking beauty of our pristine island destinations</p>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item large" data-aos="fade-up">
                <img src="https://images.unsplash.com/photo-1597210159514-c97e3b42e45c?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Gigantes Norte Island">
                <div class="gallery-overlay">
                    <h3>Gigantes Norte Island</h3>
                    <p>Home to the iconic lighthouse and beautiful white sand beaches</p>
                    <a href="#" class="gallery-link">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1596351663389-6c83e5de2436?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Gigantes Sur Island">
                <div class="gallery-overlay">
                    <h3>Gigantes Sur Island</h3>
                    <p>Famous for Tangke Saltwater Lagoon and Bantigue Sand Bar</p>
                    <a href="#" class="gallery-link">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                <div class="gallery-image">
                    <img src="https://images.unsplash.com/photo-1591375470980-07ad4ebbd490?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Cabugao Gamay Island">
                    <div class="gallery-badge">Popular</div>
                </div>
                <div class="gallery-overlay">
                    <h3>Cabugao Gamay Island</h3>
                    <p>The most photographed island with its unique hourglass shape</p>
                    <a href="#" class="gallery-link">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                <div class="gallery-image">
                    <img src="https://images.unsplash.com/photo-1577537500263-4b0a9fe9c7a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Antonia Beach">
                    <div class="gallery-badge">Family</div>
                </div>
                <div class="gallery-overlay">
                    <h3>Antonia Beach</h3>
                    <p>Crystal clear waters and powdery white sand perfect for swimming</p>
                    <a href="#" class="gallery-link">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="gallery-item large" data-aos="fade-up" data-aos-delay="400">
                <div class="gallery-image">
                    <img src="https://images.unsplash.com/photo-1595113313255-c97e3b42e45c?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Bantigue Island">
                    <div class="gallery-badge">Luxury</div>
                </div>
                <div class="gallery-overlay">
                    <h3>Bantigue Island</h3>
                    <p>Famous for its stunning sandbar that appears during low tide</p>
                    <a href="#" class="gallery-link">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                <div class="gallery-image">
                    <img src="https://images.unsplash.com/photo-1594100889863-fd3d6e083b7a?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80" alt="Pulupandan Island">
                    <div class="gallery-badge">Unique</div>
                </div>
                <div class="gallery-overlay">
                    <h3>Pulupandan Island</h3>
                    <p>Secluded island with rich marine biodiversity and coral gardens</p>
                    <a href="#" class="gallery-link">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Gallery Section Styles */
        .gallery {
            padding: 80px 5%;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                        url('https://images.unsplash.com/photo-1518509562904-07ad4ebbd490?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-attachment: fixed;
        }

        .gallery .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 60px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-auto-rows: 300px;
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .gallery-item {
            grid-column: span 4;
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .gallery-item.large {
            grid-column: span 8;
        }

        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .gallery-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover .gallery-image img {
            transform: scale(1.1);
        }

        .gallery-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #4BB1DA;
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 500;
            z-index: 2;
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 30px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
            color: white;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
            transform: translateY(0);
        }

        .gallery-overlay h3 {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .gallery-overlay p {
            font-size: 0.9rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .gallery-link {
            color: #E6BE8A;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .gallery-link i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .gallery-link:hover {
            color: #FFF5EE;
        }

        .gallery-link:hover i {
            transform: translateX(5px);
        }

        @media (max-width: 1200px) {
            .gallery-grid {
                grid-template-columns: repeat(8, 1fr);
            }
            
            .gallery-item {
                grid-column: span 4;
            }
            
            .gallery-item.large {
                grid-column: span 8;
            }
        }

        @media (max-width: 768px) {
            .gallery {
                padding: 60px 5%;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(4, 1fr);
                grid-auto-rows: 250px;
                gap: 15px;
            }
            
            .gallery-item,
            .gallery-item.large {
                grid-column: span 4;
            }
        }
    </style>

    <!-- Map Section -->
    <section class="map-section" id="map">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 map-info" data-aos="fade-right">
                    <span class="subtitle">Where to Find Us</span>
                    <h2>Location & Directions</h2>
                    <p>Carles is located at the northernmost tip of Panay Island, approximately 144 kilometers from Iloilo City. Our tour office is conveniently situated near the Carles Port Terminal.</p>
                    
                    <div class="direction-item">
                        <div class="direction-icon">
                            <i class="fas fa-route"></i>
                        </div>
                        <div>
                            <h5>From Iloilo City</h5>
                            <p>Take a bus or van from Tagbak Terminal heading to Carles (4-5 hours travel time). Upon reaching Carles, head to the port area where our office is located.</p>
                        </div>
                    </div>
                    
                    <div class="direction-item">
                        <div class="direction-icon">
                            <i class="fas fa-ship"></i>
                        </div>
                        <div>
                            <h5>Getting to Gigantes Islands</h5>
                            <p>From our port terminal, we provide daily boat transfers to Gigantes Islands. The boat ride takes approximately 1-2 hours depending on sea conditions.</p>
                        </div>
                    </div>
                    
                    <div class="direction-item">
                        <div class="direction-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h5>Travel Tips</h5>
                            <p>Book your island tour at least 2 days in advance. We recommend staying overnight to fully experience the beauty of the islands.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7 map-container" data-aos="fade-left">
                    <div class="map-wrapper">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62504.51017061349!2d123.0880359582031!3d11.572252699999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a2fc1807c9bb03%3A0x5d2c08be0bbabbef!2sCarles%2C%20Iloilo!5e0!3m2!1sen!2sph!4v1692724789152!5m2!1sen!2sph" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div class="map-overlay">
                            <div class="map-card">
                                <h4>Carles Tourism Office</h4>
                                <p><i class="fas fa-clock me-2"></i> Open 7:00 AM - 6:00 PM</p>
                                <a href="https://goo.gl/maps/v2DbqFKzDg2XGwh37" target="_blank" class="btn-directions">Get Directions <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Map Section Styles */
        .map-section {
            padding: 100px 0;
            background: linear-gradient(to right, rgba(255, 245, 238, 0.97), rgba(255, 245, 238, 0.97)),
                        url('https://images.unsplash.com/photo-1520808889-c5e13163b6a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }
        
        .map-section .subtitle {
            color: #4BB1DA;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 10px;
        }
        
        .map-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }
        
        .map-info p {
            font-size: 1rem;
            line-height: 1.7;
            color: #666;
            margin-bottom: 30px;
        }
        
        .direction-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .direction-icon {
            width: 50px;
            height: 50px;
            background: rgba(75, 177, 218, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4BB1DA;
            font-size: 1.3rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .direction-item:hover .direction-icon {
            background: #4BB1DA;
            color: white;
            transform: translateY(-5px);
        }
        
        .direction-item h5 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: #333;
        }
        
        .direction-item p {
            font-size: 0.9rem;
            margin: 0;
            color: #666;
        }
        
        .map-container {
            padding-left: 30px;
        }
        
        .map-wrapper {
            position: relative;
            height: 450px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .map-overlay {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }
        
        .map-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            min-width: 250px;
        }
        
        .map-card h4 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: #333;
        }
        
        .map-card p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }
        
        .btn-directions {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #4BB1DA;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-directions:hover {
            background: #3A8FB7;
            transform: translateY(-3px);
            color: white;
        }
        
        .btn-directions i {
            transition: transform 0.3s ease;
        }
        
        .btn-directions:hover i {
            transform: translateX(3px);
        }
        
        @media (max-width: 992px) {
            .map-info {
                margin-bottom: 50px;
            }
            
            .map-container {
                padding-left: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .map-section {
                padding: 70px 0;
            }
            
            .map-section h2 {
                font-size: 2rem;
            }
            
            .map-wrapper {
                height: 350px;
            }
            
            .map-overlay {
                top: 10px;
                left: 10px;
            }
            
            .map-card {
                padding: 15px;
                min-width: 200px;
            }
        }
    </style>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        duration: 1000,
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
        
        // Smooth scrolling for anchor links
        $('a[href*="#"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 100
            }, 500, 'linear');
        });
        
        // Add active class to navbar items on scroll
        $(window).scroll(function() {
            var scrollDistance = $(window).scrollTop();
            
            // Add navbar background on scroll
            if (scrollDistance > 50) {
                $('.navbar').addClass('scrolled');
            } else {
                $('.navbar').removeClass('scrolled');
            }
            
            // Add active class to menu items based on section
            $('section').each(function(i) {
                if ($(this).position().top <= scrollDistance + 200) {
                    $('.navbar-nav a.active').removeClass('active');
                    $('.navbar-nav a').eq(i).addClass('active');
                }
            });
        }).scroll();
    });
</script>
</html>