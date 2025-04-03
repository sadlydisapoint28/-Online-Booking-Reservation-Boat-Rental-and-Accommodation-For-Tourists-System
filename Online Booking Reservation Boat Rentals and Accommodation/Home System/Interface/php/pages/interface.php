<?php
// Check if user is coming from loading screen
if (!isset($_GET['loaded']) && !isset($_SERVER['HTTP_REFERER'])) {
    header("Location: ../../Loading-Screen/loading.html");
    exit;
}

require_once('../config/connect.php');

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
    <!-- Add Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
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
                <div class="nav-container">
                <div class="nav-links">
                    <ul role="menubar">
                        <li role="menuitem" class="active"><a href="#" aria-current="page">HOME</a></li>
                        <li role="menuitem"><a href="#">ABOUT</a></li>
                        <li role="menuitem"><a href="#">GALLERY</a></li>
                            
                            <li class="nav-separator"></li>
                            
                            <li role="menuitem" class="nav-dropdown">
                                <a href="#" class="nav-dropdown-toggle">BOAT RENTALS</a>
                                <div class="nav-dropdown-menu">
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-ship"></i> Boat Types</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-anchor"></i> Small Boats (1-5 pax)</a>
                                            <a href="#"><i class="fas fa-ship"></i> Medium Boats (6-10 pax)</a>
                                            <a href="#"><i class="fas fa-ship"></i> Large Boats (11-20 pax)</a>
                                            <a href="#"><i class="fas fa-ship"></i> Luxury Boats (VIP)</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-map-marked-alt"></i> Tour Packages</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-umbrella-beach"></i> Island Hopping</a>
                                            <a href="#"><i class="fas fa-sun"></i> Sunset Cruise</a>
                                            <a href="#"><i class="fas fa-fish"></i> Fishing Tour</a>
                                            <a href="#"><i class="fas fa-user-friends"></i> Private Tour</a>
                                            <a href="#"><i class="fas fa-users"></i> Group Tour</a>
                                            <a href="#"><i class="fas fa-moon"></i> Overnight Tour</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-tags"></i> Special Offers</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-earlybirds"></i> Early Bird Discount</a>
                                            <a href="#"><i class="fas fa-users"></i> Group Discounts</a>
                                            <a href="#"><i class="fas fa-calendar-alt"></i> Seasonal Promos</a>
                                            <a href="#"><i class="fas fa-box"></i> Package Deals</a>
                                            <a href="#"><i class="fas fa-clock"></i> Last Minute Deals</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-calendar-check"></i> Booking Info</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-book"></i> How to Book</a>
                                            <a href="#"><i class="fas fa-credit-card"></i> Payment Methods</a>
                                            <a href="#"><i class="fas fa-times-circle"></i> Cancellation Policy</a>
                                            <a href="#"><i class="fas fa-file-contract"></i> Terms & Conditions</a>
                                            <a href="#"><i class="fas fa-question-circle"></i> FAQ</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-shield-alt"></i> Safety & Guidelines</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-exclamation-triangle"></i> Safety Rules</a>
                                            <a href="#"><i class="fas fa-toolbox"></i> Equipment Provided</a>
                                            <a href="#"><i class="fas fa-cloud-sun"></i> Weather Policy</a>
                                            <a href="#"><i class="fas fa-phone-alt"></i> Emergency Contacts</a>
                                            <a href="#"><i class="fas fa-file-medical"></i> Insurance Info</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li role="menuitem" class="nav-dropdown">
                                <a href="#" class="nav-dropdown-toggle">ISLANDS</a>
                                <div class="nav-dropdown-menu">
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-map-marker-alt"></i>Gigantes Islands</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>Cabugao Gamay</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Antonia Island</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Bantigue Island</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Tangke Lagoon</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Pawikan Cave</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Gigantes Norte</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Gigantes Sur</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-map-marker-alt"></i>Sicogon Island</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>Sicogon Beach</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Sicogon Peak</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Mangrove Forest</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Local Villages</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Hidden Coves</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Viewing Decks</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-route"></i>Tour Routes</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>Gigantes Island Hopping</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Sicogon Island Tour</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Combined Island Tour</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Custom Island Route</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-info-circle"></i>Travel Info</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>From Iloilo City</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>From Roxas City</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>From Estancia</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Travel Requirements</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Boat Schedules</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li role="menuitem" class="nav-dropdown">
                                <a href="#" class="nav-dropdown-toggle">BEACHES</a>
                                <div class="nav-dropdown-menu">
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-umbrella-beach"></i>Popular Beaches</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>Sicogon Beach</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Tangke Beach</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Cabugao Gamay Beach</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Bantigue Sandbar</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Antonia Beach</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Calagnaan Beach</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-swimming-pool"></i>Beach Activities</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>Swimming</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Snorkeling</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Beach Camping</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Sunset Watching</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Beach Photography</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Beach Volleyball</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-building"></i>Beach Facilities</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>Beach Resorts</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Beach Cottages</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Restaurants</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Restrooms</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Parking Areas</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>First Aid Stations</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-shield-alt"></i>Beach Safety</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>Lifeguard Services</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Weather Updates</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Emergency Contacts</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Safety Guidelines</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Beach Rules</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Tide Information</a>
                                        </div>
                                    </div>
                                    <div class="nav-dropdown-item">
                                        <a href="#"><i class="fas fa-directions"></i>Beach Access</a>
                                        <div class="nav-submenu">
                                            <a href="#"><i class="fas fa-chevron-right"></i>How to Get There</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Entrance Fees</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Operating Hours</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Best Time to Visit</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Transportation Options</a>
                                            <a href="#"><i class="fas fa-chevron-right"></i>Parking Information</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                    </ul>
                    </div>
                </div>
                <div class="nav-right">
                    <div class="secondary-nav">
                        <a href="#" class="contact-link"><i class="fas fa-envelope"></i>CONTACT US</a>
                        <a href="#" class="book-link"><i class="fas fa-calendar-check"></i>BOOK NOW</a>
                        <a href="#" class="login-link"><i class="fas fa-user"></i>LOGIN</a>
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
                    <a href="../../Admin and User Loginup/loginup_admin.php" class="login-link">LOGIN</a>
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
        <section class="hero-section" aria-label="Main banner">
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
    <section id="how-we-work" class="how-we-work-section" style="background-color: #102030;">
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
        </div>
    </section>

    <!-- Next section -->
    <section id="next-section">
        <!-- Add your next section content here -->
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
            const mobileMenu = document.querySelector('#mobile-menu');
            const body = document.body;
            
            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', function() {
                    const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
                    
                    // Toggle menu state
                    menuToggle.setAttribute('aria-expanded', !isExpanded);
                    mobileMenu.classList.toggle('active');
                    menuToggle.classList.toggle('active');
                    
                    // Prevent body scrolling when menu is open
                    body.style.overflow = isExpanded ? 'auto' : 'hidden';
                    
                    // Set aria-hidden attribute
                    mobileMenu.setAttribute('aria-hidden', isExpanded);
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && 
                        !menuToggle.contains(event.target) && 
                        mobileMenu.classList.contains('active')) {
                        menuToggle.click();
                    }
                });

                // Close menu on escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && mobileMenu.classList.contains('active')) {
                        menuToggle.click();
                    }
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
    
    // Basic validation function - Uncomment and update validation
    function validateStep(stepIndex) {
        const currentFormStep = formSteps[stepIndex];
        if (!currentFormStep) return true;
        
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
    }
    
    // Handle form submission with proper error handling
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
            // Get all form data
            const formData = new FormData(form);
            
                // Send data to server
                const response = await fetch('process_booking.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Successful!',
                        text: 'Your booking has been submitted successfully.',
                        confirmButtonColor: '#3282b8'
                    }).then(() => {
                        // Redirect to confirmation page or reset form
                        window.location.href = 'booking_confirmation.php?id=' + result.booking_id;
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Failed',
                        text: result.message || 'There was an error processing your booking. Please try again.',
                        confirmButtonColor: '#3282b8'
                    });
                }
            } catch (error) {
                console.error('Booking error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'There was a system error. Please try again later.',
                    confirmButtonColor: '#3282b8'
                });
            }
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

// Initialize Bootstrap dropdowns
var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
    return new bootstrap.Dropdown(dropdownToggleEl)
});

// Custom Dropdown Menu Script
document.addEventListener('DOMContentLoaded', function() {
    // Get the dropdown elements
    const boatRentalsDropdown = document.querySelector('.nav-item.dropdown');
    const dropdownToggle = boatRentalsDropdown.querySelector('.dropdown-toggle');
    const dropdownMenu = boatRentalsDropdown.querySelector('.dropdown-menu');
    
    // Initialize state
    let isOpen = false;
    
    // Toggle dropdown on click
    dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (isOpen) {
            dropdownMenu.classList.remove('show');
            dropdownToggle.classList.remove('active');
            isOpen = false;
        } else {
            dropdownMenu.classList.add('show');
            dropdownToggle.classList.add('active');
            isOpen = true;
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!boatRentalsDropdown.contains(e.target)) {
            dropdownMenu.classList.remove('show');
            dropdownToggle.classList.remove('active');
            isOpen = false;
        }
    });
    
    // Handle submenu hover
    const submenus = document.querySelectorAll('.dropdown-submenu');
    submenus.forEach(function(submenu) {
        const submenuToggle = submenu.querySelector('.dropdown-item');
        const submenuDropdown = submenu.querySelector('.dropdown-menu');
        
        submenu.addEventListener('mouseenter', function() {
            if (submenuDropdown) {
                submenuDropdown.classList.add('show');
            }
        });
        
        submenu.addEventListener('mouseleave', function() {
            if (submenuDropdown) {
                submenuDropdown.classList.remove('show');
            }
        });
    });
});

// Mobile menu toggle
var mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
var mobileMenu = document.querySelector('.mobile-menu');

if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });
}
</script>

    <!-- Navigation Dropdown Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all dropdown elements
        const navDropdowns = document.querySelectorAll('.nav-dropdown');
        
        navDropdowns.forEach(navDropdown => {
            const dropdownToggle = navDropdown.querySelector('.nav-dropdown-toggle');
            
            // Toggle dropdown on click
            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close other dropdowns
                navDropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== navDropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
                
                // Toggle current dropdown
                navDropdown.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!navDropdown.contains(e.target)) {
                    navDropdown.classList.remove('active');
                }
            });
            
            // Handle submenu hover
            const dropdownItems = navDropdown.querySelectorAll('.nav-dropdown-item');
            dropdownItems.forEach(item => {
                const submenu = item.querySelector('.nav-submenu');
                if (submenu) {
                    item.addEventListener('mouseenter', function() {
                        dropdownItems.forEach(otherItem => {
                            if (otherItem !== item) {
                                otherItem.querySelector('.nav-submenu')?.classList.remove('active');
                            }
                        });
                        submenu.classList.add('active');
                    });
                    
                    item.addEventListener('mouseleave', function() {
                        submenu.classList.remove('active');
                    });
                }
            });
        });
    });
    </script>
</body>
</html>