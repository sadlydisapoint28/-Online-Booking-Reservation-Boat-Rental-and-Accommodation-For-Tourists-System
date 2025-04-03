<?php
session_start();

// Check if loading screen has been shown
if (!isset($_SESSION['loading_screen_shown'])) {
    header('Location: Loading-Screen/loading.php');
    exit();
}

// Database configuration
$host = 'localhost';
$dbname = 'carles_tourism';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch all data in one query
    $query = "SELECT 'destination' as type, id, name, description, image, price FROM destinations
              UNION ALL
              SELECT 'accommodation' as type, id, name, description, image, price FROM accommodations
              UNION ALL
              SELECT 'boat' as type, id, name, description, image, price FROM boats";
    
    $stmt = $pdo->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Organize results by type
    $destinations = [];
    $accommodations = [];
    $boats = [];
    
    foreach ($results as $row) {
        switch ($row['type']) {
            case 'destination':
                $destinations[] = $row;
                break;
            case 'accommodation':
                $accommodations[] = $row;
                break;
            case 'boat':
                $boats[] = $row;
                break;
        }
    }
    
} catch(PDOException $e) {
    // Log error and initialize empty arrays
    error_log("Database Error: " . $e->getMessage());
    $destinations = [];
    $accommodations = [];
    $boats = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carles Tourism - Boat Rental & Accommodation</title>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/interface.css">
    <!-- Add Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>
<body>
    <!-- Header/Navigation -->
    <header class="site-header">
        <div class="container">
            <nav class="main-nav">
                <div class="logo">
                    <a href="#" aria-label="Carles Tourism Home" class="d-flex align-items-center">
                        <img src="img/carleslogomunicipality.png" alt="Municipality of Carles Logo" width="60" height="60" class="me-3">
                        <img src="img/timbook-carles-tourism.png" alt="Timbook Carles Tourism Logo" width="80" height="80">
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
                        <a href="#" class="book-link">BOOK NOW</a>
                        <a href="Admin and User Loginup/loginup_admin.php" class="login-link">LOGIN</a>
                    </div>
                    <div class="social-icons" aria-label="Social media links">
                        <a href="#" aria-label="Information"><i class="fas fa-info-circle" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-slider swiper-container">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide" style="background-image: url('img/beach-sunset.jpg');">
                    <div class="hero-content">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <p class="travel-label">TIMBOOK CARLES</p>
                                    <h1 class="hero-title">DISCOVER THE<br>BEAUTY OF<br>CARLES</h1>
                                    <p class="hero-text">Journey to the stunning islands of Gigantes, home to pristine white sand beaches, majestic limestone cliffs, and crystal-clear blue waters.</p>
                                    <a href="#explore" class="btn btn-explore">LEARN MORE</a>
                                </div>
                                <div class="col-lg-6">
                                    <div class="islands-vertical-container">
                                        <div class="island-vertical-list">
                                            <?php foreach ($destinations as $destination): ?>
                                            <div class="island-card-small" data-category="ISLAND">
                                                <div class="island-card-image-small">
                                                    <img src="<?php echo htmlspecialchars($destination['image']); ?>" alt="<?php echo htmlspecialchars($destination['name']); ?>" loading="lazy">
                                                    <div class="card-overlay"></div>
                                                </div>
                                                <div class="island-card-content-small">
                                                    <h3><?php echo htmlspecialchars($destination['name']); ?></h3>
                                                </div>
                                                <div class="hover-info">
                                                    <p><?php echo htmlspecialchars($destination['description']); ?></p>
                                                    <a href="#" class="view-btn">VIEW</a>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <div class="swiper-navigation">
                <div class="swiper-button-prev"><i class="fas fa-arrow-left"></i></div>
                <div class="swiper-button-next"><i class="fas fa-arrow-right"></i></div>
            </div>
            <!-- Pagination -->
            <div class="slide-number">01</div>
        </div>
    </section>

    <!-- How We Work Section -->
    <section id="how-we-work" class="how-we-work-section">
        <div class="container">
            <div class="section-header text-center">
                <h2>How We Work</h2>
                <p>Easy booking process for a hassle-free vacation</p>
            </div>
            
            <div class="process-container mt-5">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step">
                            <div class="step-number">01</div>
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>Choose Your Package</h3>
                            <p>Browse through our boat rentals and accommodations to find the perfect option for your trip.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step">
                            <div class="step-number">02</div>
                            <div class="step-icon">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <h3>Select Your Dates</h3>
                            <p>Choose your preferred dates from our availability calendar to plan your visit.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step">
                            <div class="step-number">03</div>
                            <div class="step-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3>Secure Your Booking</h3>
                            <p>Make a 50% advance payment to confirm your reservation with us.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="process-step">
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
        </div>
    </section>

    <!-- Policies Section -->
    <section id="policies" class="policies-section">
        <div class="container">
            <div class="section-title">
                <h2>Booking & Safety Information</h2>
                <p>Important information for your trip</p>
            </div>
            <div class="policies-grid">
                <div class="policy-card">
                    <h3>Booking Policies</h3>
                    <ul>
                        <li>Reservations must be made at least 3 days in advance</li>
                        <li>50% advance payment required to confirm booking</li>
                        <li>Full payment must be completed before the trip</li>
                        <li>Valid ID required during check-in</li>
                    </ul>
                </div>
                <div class="policy-card">
                    <h3>Cancellation Policy</h3>
                    <ul>
                        <li>Free cancellation up to 7 days before scheduled trip</li>
                        <li>50% refund for cancellations 3-7 days before the trip</li>
                        <li>No refund for cancellations less than 3 days before the trip</li>
                        <li>Full refund if cancelled due to severe weather conditions</li>
                    </ul>
                </div>
                <div class="policy-card">
                    <h3>Safety Guidelines</h3>
                    <ul>
                        <li>Life jackets provided and must be worn during boat trips</li>
                        <li>Follow guide instructions at all times</li>
                        <li>Children under 12 must be accompanied by adults</li>
                        <li>Trips may be rescheduled due to inclement weather</li>
                    </ul>
                </div>
                <div class="policy-card">
                    <h3>Payment Methods</h3>
                    <ul>
                        <li>Online payment via credit/debit cards</li>
                        <li>Bank transfers to our official account</li>
                        <li>GCash, PayMaya, and other e-wallets accepted</li>
                        <li>Cash payment available for walk-in bookings</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/interface.js"></script>
    <script>
        // Initialize Swiper
        var swiper = new Swiper('.hero-slider', {
            effect: 'fade',
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            on: {
                slideChange: function () {
                    // Update slide number display
                    let currentSlide = this.realIndex + 1;
                    document.querySelector('.slide-number').textContent = currentSlide.toString().padStart(2, '0');
                }
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            speed: 1000
        });
        
        // Call the swiper enhancements function from our custom JS
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof enhanceSwiperFunctionality === 'function') {
                enhanceSwiperFunctionality();
            }
        });
    </script>
</body>
</html> 