<?php
require_once('../config/connect.php'); // Include database connection
require_once('../includes/header.php'); // Include header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo '/Online Booking Reservation Boat Rentals and Accommodation/css/interface.css'; ?>"<link rel="stylesheet" type="text/css" href="<?php echo 'css/style.css'; ?>">
    <title>Online Booking Reservation Boat Rentals and Accommodation</title>


    <title>Carles Tourism</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style></style>
</head>
<body>
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
            <a href="#" class="nav-link">Boat Rentals</a>
            <a href="#" class="nav-link">Accommodations</a>
        </div>
        
        <!-- Center Logo -->
        <div class="nav-center">
            <img src="../img/timbook-carles-tourism.jpg" alt="Carles Logo" class="nav-logo">
        </div>
        
        <!-- Right Navigation -->
        <div class="nav-right">
            <div class="nav-links">
                <a href="#" class="nav-link">About Us</a>
                <a href="#" class="nav-link">Contact Us</a>
            </div>
            <div class="nav-buttons">
                <button class="btn-book">Book Now</button>
                <button class="btn-login">Login</button>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Your Adventure Awaits with Tourism</h1>
            <p>Discover the beauty of Carles with our boat rentals and comfortable accommodations. Book your unforgettable experience today and create memories that will last a lifetime.</p>
            <div class="search-container">
                <div class="search-bar">
                    <input type="text" placeholder="Search destinations, boats, or accommodations...">
                    <button class="search-btn"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="hero-buttons">
                <button class="btn-primary">Boat Rentals</button>
                <button class="btn-secondary">Accommodations</button>
                <button class="btn-browse">Browse the Highlights <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </section>
    
    <!-- Discover Section -->
    <section class="discover">
        <h2>Discover Carles Tourism</h2>
        <p>At Carles Tourism, we are committed to enhancing your travel experiences with our innovative booking platform. Our mission is to make vacations accessible and enjoyable for everyone.</p>
        
        <div class="features">
            <div class="feature">
                <i class="fas fa-star"></i>
                <h3>Our Mission</h3>
                <p>To enhance travel experiences by offering a user-friendly platform for booking boat rentals and accommodations, making vacations more accessible and enjoyable for all travelers.</p>
            </div>
            <div class="feature">
                <i class="fas fa-thumbs-up"></i>
                <h3>Our Values</h3>
                <p>We prioritize customer satisfaction, continuously innovate our services, and operate with integrity. These values guide us in providing the best possible experience for our clients.</p>
            </div>
            <div class="feature">
                <i class="fas fa-users"></i>
                <h3>Meet Our Team</h3>
                <p>Our dedicated team, led by Lhance Monter, is here to ensure you have a seamless booking experience. From technology to customer service, we are committed to your satisfaction.</p>
            </div>
        </div>
    </section>
    
    <!-- Boat Rental Section -->
    <section class="boat-rental">
        <div class="section-content">
            <h2>Discover Your Perfect <em>Boat Rental</em> for an Unforgettable Adventure</h2>
            <p>At Carles Tourism, we offer a wide selection of boats for rent, from luxurious yachts to small fishing boats. Our easy online booking system makes it simple to find the perfect vessel for your next adventure. Explore our options today!</p>
            <div class="rental-features">
                <div class="feature">
                    <h3>Variety</h3>
                    <p>We offer a diverse range of boats to suit every need.</p>
                </div>
                <div class="feature">
                    <h3>Booking</h3>
                    <p>Our online booking process saves you time and effort.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Accommodation Section -->
    <section class="accommodations">
        <h2>Discover Your Perfect Stay with <em>Carles Tourism</em></h2>
        <p>At Carles Tourism, we understand that finding the right accommodation is crucial for an unforgettable vacation. That's why we offer a diverse range of lodging options to suit every traveler's needs and preferences.</p>
        
        <div class="accommodation-options">
            <div class="option">
                <img src="../img/beachfront.jpg" alt="Beachfront Cottages">
                <h3>Beachfront Cottages</h3>
                <p>Wake up to the sound of waves and enjoy breathtaking ocean views from our beachfront cottages. Perfect for families or couples seeking a romantic getaway.</p>
                <button class="learn-more">Learn More</button>
            </div>
            <div class="option">
                <img src="../img/cityview.jpg" alt="City View Apartments">
                <h3>City View Apartments</h3>
                <p>Experience the vibrant city life from our modern apartments, equipped with all the amenities you need for a comfortable and convenient stay.</p>
                <button class="learn-more">Learn More</button>
            </div>
            <div class="option">
                <img src="../img/cabin.jpg" alt="Secluded Cabins">
                <h3>Secluded Cabins</h3>
                <p>Escape to nature in our secluded cabins, surrounded by lush forests and tranquil landscapes. Perfect for those seeking peace and relaxation.</p>
                <button class="learn-more">Learn More</button>
            </div>
        </div>
    </section>
    
    <!-- Destinations Section -->
    <section class="destinations">
        <h2>Discover the <em>Best Destinations</em> with Carles Tourism Today!</h2>
        <p>Embark on unforgettable journeys with Carles Tourism. Explore breathtaking destinations, from pristine beaches to serene lakes.</p>
        
        <div class="destination-cards">
            <div class="card">
                <img src="../img/isla.jpg" alt="Isla de Gigantes">
                <h3>Isla de Gigantes: A Tropical Paradise</h3>
                <p>Discover the untouched beauty of Isla de Gigantes. Explore hidden lagoons, swim in crystal-clear waters, and bask in the sun on pristine beaches.</p>
                <button class="learn-more">Learn More</button>
            </div>
            <div class="card">
                <img src="../img/balay.jpg" alt="Balay Dako">
                <h3>Balay Dako: A Cultural Gem</h3>
                <p>Immerse yourself in the rich culture and heritage of Balay Dako. Explore traditional architecture and enjoy breathtaking views.</p>
                <button class="learn-more">Learn More</button>
            </div>
            <div class="card">
                <img src="../img/manlot.jpg" alt="Manlot Island">
                <h3>Manlot Island: A Secluded Escape</h3>
                <p>Escape to the tranquil shores of Manlot Island. Relax on pristine beaches and soak up the sun in this secluded paradise.</p>
                <button class="learn-more">Learn More</button>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="contact">
        <h2>Get in Touch</h2>
        <p>We'd love to hear from you! Reach out anytime.</p>
        
        <div class="contact-container">
            <div class="contact-info">
                <div class="info-item">
                    <span class="icon">✉️</span>
                    <p>Email: info@carlestourism.com</p>
                </div>
                <div class="info-item">
                    <span class="icon">📞</span>
                    <p>Phone: +1-555-123-4567</p>
                </div>
                <div class="info-item">
                    <span class="icon">📍</span>
                    <p>Address: 123 Ocean Drive, Miami, FL 33139, United States</p>
                </div>
            </div>
            
            <form class="contact-form">
                <input type="text" placeholder="Enter your name" required>
                <input type="email" placeholder="Enter your email" required>
                <textarea placeholder="Type your message" required></textarea>
                <div class="terms-container">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I accept the Terms and Conditions</label>
                    </div>
                    <small class="terms-note">By checking this box, you agree to our Terms of Service and Privacy Policy.</small>
                </div>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Explore</h3>
                <ul>
                    <li><a href="#">Boat Rentals</a></li>
                    <li><a href="#">Accommodations</a></li>
                    <li><a href="#">Explore Destinations</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Company</h3>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Login</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">Booking Process</a></li>
                    <li><a href="#">Message Us</a></li>
                </ul>
            </div>
            <div class="footer-section newsletter">
                <h3>Subscribe to our Newsletter</h3>
                <p>Stay updated with our latest offers and destinations.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email address" required>
                    <button type="submit" class="submit-btn">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Carles. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Terms And Conditions</a>
                <a href="#">Privacy Policy</a>
            </div>
            <div class="social-links">
                <a href="#" class="social-icon">fb</a>
                <a href="#" class="social-icon">ig</a>
                <a href="#" class="social-icon">tw</a>
            </div>
        </div>
    </footer>
    
    <script src="../javascript/interface.js"></script>




<style>
    /* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Georgia', serif;
    line-height: 1.6;
    color: #333;
}

/* Enhanced Navigation Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 4rem;
    background: transparent;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    height: 80px;
    transition: all 0.4s ease;
}

/* Scrolled state with white background */
.navbar.scrolled {
    background: rgba(255, 255, 255, 0.98);
    height: 70px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
}

/* Left navigation section */
.nav-left {
    display: flex;
    align-items: center;
    gap: 3rem;
    min-width: 400px;
}

/* Center logo section */
.nav-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    padding: 5px;
}

.nav-logo {
    height: 70px;
    width: auto;
    display: block;
    transition: all 0.4s ease;
}

/* Right navigation section */
.nav-right {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 3rem;
    min-width: 400px;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 3rem;
}

.nav-buttons {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-left: 2rem;
}

/* Navigation Links */
.navbar a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    padding: 0.5rem;
    white-space: nowrap;
    position: relative;
    transition: all 0.4s ease;
}

/* Change link color when navbar is scrolled */
.navbar.scrolled a {
    color: #333;
}

.navbar a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    background-color: white;
    transition: all 0.4s ease;
    transform: translateX(-50%);
}

.navbar.scrolled a::after {
    background-color: #4169E1;
}

.navbar a:hover::after {
    width: 100%;
}

/* Common Button Styles */
.btn-book, .btn-login, .btn-primary, .btn-secondary, .learn-more {
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
}

/* Oblong buttons (all except hero section) */
.btn-book, .btn-login, .learn-more {
    padding: 12px 32px;
    border-radius: 50px;
    border: 1px solid #4169E1;
}

/* Box-type buttons (hero section only) */
.btn-primary, .btn-secondary {
    padding: 15px 40px;
    border-radius: 4px;
    font-size: 18px;
    font-weight: 500;
}

/* Primary button styles */
.btn-book, .btn-primary, .learn-more {
    background: #4169E1;
    color: white;
    border: 1px solid #4169E1;
}

.btn-book:hover, .btn-primary:hover, .learn-more:hover {
    background: #3151b0;
    border-color: #3151b0;
}

/* Secondary button styles */
.btn-login {
    background: transparent;
    border: 2px solid white;
    color: white;
}

.navbar.scrolled .btn-login {
    border-color: #333;
    color: #333;
}

.btn-login:hover, .navbar.scrolled .btn-login:hover {
    background: #4169E1;
    color: white;
    border-color: #4169E1;
}

.btn-secondary {
    background: transparent;
    border: 2px solid white;
    color: white;
}

.btn-secondary:hover {
    background: #4169E1;
    color: white;
    border-color: #4169E1;
}

/* Mobile Menu */
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
}

.mobile-menu-btn span {
    display: block;
    width: 25px;
    height: 2px;
    background: white;
    margin: 5px 0;
    transition: all 0.4s ease;
}

.navbar.scrolled .mobile-menu-btn span {
    background: #333;
}

/* Hero Section */
.hero {
    height: 100vh;
    background-image: url('../img/gigantes.png');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    display: flex;
    align-items: center;
    padding: 0 5%;
    color: white;
    position: relative;
    margin-top: 0px;
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
    animation: fadeInUp 1s ease-out;
}

.hero h1 {
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    line-height: 1.8;
}

/* Search bar styles */
.search-container {
    margin-bottom: 2rem;
    width: 100%;
    max-width: 600px;
}

.search-bar {
    display: flex;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50px;
    padding: 0.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.search-bar input {
    flex: 1;
    padding: 0.8rem 1.5rem;
    border: none;
    background: none;
    font-size: 1rem;
    color: #333;
    outline: none;
}

.search-bar input::placeholder {
    color: #666;
}

.search-btn {
    background: #4169E1;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: #3151b0;
}

.hero-buttons {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    align-items: center;
}

/* Browse button style */
.btn-browse {
    background: transparent;
    color: white;
    border: none;
    padding: 12px 32px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-browse i {
    transition: transform 0.3s ease;
}

.btn-browse:hover i {
    transform: translateX(5px);
}

/* Discover Section */
.discover {
    padding: 5rem 5%;
    text-align: center;
    background: white;
}

.discover h2 {
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
}

.features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 3rem;
}

.feature {
    padding: 2.5rem;
    background: #f9f9f9;
    border-radius: 15px;
    transition: all 0.4s ease;
    cursor: pointer;
}

.feature:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* Boat Rental Section */
.boat-rental {
    padding: 5rem 5%;
    background: #000;
    color: white;
    position: relative;
}

.section-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

/* Accommodations Section */
.accommodations {
    padding: 5rem 5%;
    background: white;
}

.accommodation-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 3rem;
}

.option {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
}

.option img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: all 0.4s ease;
}

.option:hover {
    transform: translateY(-10px);
}

.option:hover img {
    transform: scale(1.1);
}

/* Destinations Section */
.destinations {
    padding: 5rem 5%;
    background: #f9f9f9;
}

.destination-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 3rem;
}

.card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

/* Contact Section */
.contact {
    padding: 5rem 5%;
    background: white;
    text-align: center;
}

.contact h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: normal;
}

.contact > p {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
}

.contact-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-top: 3rem;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.contact-form input:focus,
.contact-form textarea:focus {
    border-color: #4169E1;
    box-shadow: 0 0 10px rgba(65, 105, 225, 0.1);
    transform: scale(1.02);
}

/* Newsletter styles */
.newsletter-form {
    display: flex;
    gap: 1rem;
    max-width: 500px;
    margin: 0 0 0 -50px;
}

.footer-section.newsletter p {
    margin-bottom: 12px;
}

.newsletter-form input[type="email"] {
    flex: 1;
    padding: 0.8rem 1.2rem;
    border: 2px solid #e0e0e0;
    border-radius: 50px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    outline: none;
    color: #333;
}

.newsletter-form input[type="email"]:focus {
    border-color: #4169E1;
    box-shadow: 0 0 15px rgba(65, 105, 225, 0.1);
}

.newsletter-form .submit-btn {
    min-width: 160px;
    padding: 0.8rem 2rem;
    font-size: 0.95rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #333;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
}

.newsletter-form .submit-btn:hover {
    background: #4169E1;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(65, 105, 225, 0.2);
}

/* Enhanced Checkbox Styles */
.terms-container {
    margin: 1.5rem 0;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    background: #f8f8f8;
}

.checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    position: relative;
    margin-bottom: 8px;
}

.checkbox-wrapper input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    border: 1.5px solid #4169E1;
    border-radius: 3px;
    outline: none;
    cursor: pointer;
    position: relative;
    background: white;
    margin: 0;
    margin-top: 3px;
    transition: all 0.2s ease;
}

.checkbox-wrapper input[type="checkbox"]:checked {
    background: #4169E1;
}

.checkbox-wrapper input[type="checkbox"]:checked::before {
    content: '✓';
    position: absolute;
    color: white;
    font-size: 11px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.checkbox-wrapper label {
    cursor: pointer;
    font-size: 0.95rem;
    color: #555;
    user-select: none;
    line-height: 1.4;
}

.terms-note {
    display: block;
    font-size: 0.85rem;
    color: #666;
    margin-left: 32px;
    line-height: 1.4;
}

/* Footer */
footer {
    background: #000;
    color: white;
    padding: 5rem 5% 1rem;
}

.footer-section {
    margin-bottom: 2rem;
}

.footer-section h3 {
    font-size: 1.1rem;
    font-weight: normal;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 0.5rem;
}

.footer-section ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 0.9rem;
    opacity: 0.8;
    transition: opacity 0.3s ease;
    background: none;
    box-shadow: none;
    border: none;
}

.footer-section ul li a:hover {
    opacity: 1;
    background: none;
    box-shadow: none;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 3rem;
    margin-bottom: 3rem;
}

/* Animations */
@keyframes float {
    0% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .navbar {
        padding: 0 2rem;
    }
    
    .nav-left, .nav-right {
        min-width: 300px;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .navbar {
        padding: 0 1rem;
    }

    .mobile-menu-btn {
        display: block;
    }

    .nav-left, .nav-right {
        display: none;
        position: absolute;
        top: 80px;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.98);
        padding: 1rem;
    }

    .navbar.mobile-active .nav-left,
    .navbar.mobile-active .nav-right {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .features,
    .accommodation-options,
    .destination-cards,
    .contact-container,
    .footer-content {
        grid-template-columns: 1fr;
    }

    .hero h1 {
        font-size: 2.5rem;
    }

    .terms-container {
        margin: 0.5rem 0;
        padding: 0.5rem;
    }

    .checkbox-wrapper {
        flex-direction: row;
        align-items: flex-start;
    }

    .checkbox-wrapper input[type="checkbox"] {
        margin-top: 3px;
    }
}

</style>









</body>
</html>
<?php
require_once('../includes/footer.php'); // Include footer
?>



