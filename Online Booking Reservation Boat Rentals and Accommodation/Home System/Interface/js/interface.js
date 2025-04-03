/**
 * Main JavaScript for Travel Website
 * Includes animations, interactions, and dynamic elements
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations on page load
    initPageLoadAnimations();
    
    // Initialize interactive features
    initInteractiveFeatures();
    
    // Initialize parallax effects
    initParallaxEffects();
    
    // Initialize destination cards
    initDestinationCards();
    
    // Initialize smooth scrolling
    initSmoothScrolling();
    
    // Initialize header transparency effect
    initHeaderTransparency();
    
    // Mobile menu toggle
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            menuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            
            // Accessibility
            const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
            menuToggle.setAttribute('aria-expanded', !expanded);
            mobileMenu.setAttribute('aria-hidden', expanded);
        });
    }
    
    // Search icon functionality
    const searchToggle = document.querySelector('.search-toggle');
    const searchContainer = document.querySelector('.search-container');
    const socialIcons = document.querySelector('.social-icons');
    
    if (searchToggle && socialIcons) {
        searchToggle.addEventListener('click', function() {
            socialIcons.classList.toggle('expanded');
            searchToggle.classList.toggle('active');
            
            if (searchContainer) {
                searchContainer.classList.toggle('active');
            }
        });
    }
    
    // Scroll animation detection
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.85 &&
            rect.bottom >= 0
        );
    }
    
    // Handle scroll-based animations and transitions
    function handleScrollAnimations() {
        // Reveal elements with scroll-transition class
        document.querySelectorAll('.scroll-transition.scroll-hidden').forEach((element) => {
            if (isElementInViewport(element)) {
                element.classList.remove('scroll-hidden');
                element.classList.add('scroll-visible');
            }
        });
        
        // Handle background sequence transitions
        const scrollPosition = window.scrollY;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        // Apply scrolled class to sections for background transitions
        document.querySelectorAll('.section-bg-sequence-1, .section-bg-sequence-2, .section-bg-sequence-3, .section-bg-sequence-4, .section-bg-sequence-5').forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            
            // Add scrolled class when section is in view (with offset for early transition)
            if (scrollPosition > sectionTop - windowHeight * 0.5 && 
                scrollPosition < sectionTop + sectionHeight) {
                section.classList.add('scrolled');
            } else if (scrollPosition > sectionTop + sectionHeight || 
                      scrollPosition < sectionTop - windowHeight) {
                // Remove scrolled class when section is fully out of view
                section.classList.remove('scrolled');
            }
            
            // Calculate scroll percentage through section for parallax effects
            if (scrollPosition > sectionTop - windowHeight && 
                scrollPosition < sectionTop + sectionHeight) {
                const scrollPercentage = (scrollPosition - (sectionTop - windowHeight)) / 
                                         (sectionHeight + windowHeight);
                
                // Apply additional scroll-based effects
                const translateY = scrollPercentage * 50; // Max 50px movement
                
                // Find background elements
                const bgElements = section.querySelectorAll('.bg-image-container, .parallax-bg');
                bgElements.forEach(bg => {
                    bg.style.transform = `translateY(${translateY}px)`;
                });
            }
        });
        
        // Progress along overall page
        const scrollProgress = scrollPosition / (documentHeight - windowHeight);
        document.documentElement.style.setProperty('--scroll-progress', scrollProgress);
        
        // Apply specific effects based on scroll position
        applyScrollEffects(scrollPosition, windowHeight);
    }
    
    // Apply special effects for specific sections
    function applyScrollEffects(scrollPosition, windowHeight) {
        // Hero section parallax
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            const heroHeight = heroSection.offsetHeight;
            const heroParallax = Math.min(scrollPosition * 0.4, heroHeight * 0.2);
            heroSection.style.backgroundPositionY = `calc(50% + ${heroParallax}px)`;
        }
        
        // Apply scale effect to booking form section as it enters view
        const bookingFormSection = document.querySelector('.booking-form-section');
        if (bookingFormSection) {
            const bookingFormTop = bookingFormSection.offsetTop;
            const distanceFromTop = bookingFormTop - scrollPosition - windowHeight * 0.8;
            
            if (distanceFromTop < 0) {
                const scaleValue = Math.max(0.9, Math.min(1, 1 + distanceFromTop / (windowHeight * 2)));
                const opacityValue = Math.max(0.8, Math.min(1, 1 + distanceFromTop / (windowHeight * 1.5)));
                bookingFormSection.style.opacity = opacityValue;
            }
        }
    }
    
    // Initial check on page load
    handleScrollAnimations();
    
    // Check on scroll with throttling for performance
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                handleScrollAnimations();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Handle resize for responsive adjustments
    window.addEventListener('resize', handleScrollAnimations);
    
    // Initialize additional features
    initBookingForm();
});

/**
 * Initialize animations when page loads
 */
function initPageLoadAnimations() {
    // Animate the hero content elements with a staggered delay
    const heroElements = document.querySelectorAll('.travel-label, .hero-title, .hero-text, .btn-explore');
    
    heroElements.forEach((element, index) => {
        // Add initial hidden class
        element.classList.add('hidden');
        
        // Staggered animation with setTimeout
        setTimeout(() => {
            element.classList.add('animate-in');
            element.classList.remove('hidden');
        }, 200 * index);
    });
    
    // Animate destination cards with a staggered delay
    const cards = document.querySelectorAll('.destination-card');
    
    cards.forEach((card, index) => {
        // Add initial hidden class
        card.classList.add('hidden');
        
        // Staggered animation with setTimeout
        setTimeout(() => {
            card.classList.add('animate-in');
            card.classList.remove('hidden');
        }, 200 * (index + heroElements.length));
    });
}

/**
 * Initialize interactive features
 */
function initInteractiveFeatures() {
    // Add hover effects to navigation links
    const navLinks = document.querySelectorAll('.nav-links li');
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.classList.add('hover');
            }
        });
        
        link.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
        
        // Add click handler to set active class
        link.addEventListener('click', function(e) {
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
        });
    });
    
    // Initialize search button toggle
    const searchBtn = document.querySelector('.search-btn');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('active');
            
            // Here you would typically show a search form
            // For now, we'll just do a pulse animation
            this.classList.add('pulse');
            setTimeout(() => {
                this.classList.remove('pulse');
            }, 500);
        });
    }
    
    // Add hover/click effects to social icons
    const socialIcons = document.querySelectorAll('.social-icons a');
    
    socialIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        icon.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
        
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('pulse');
            setTimeout(() => {
                this.classList.remove('pulse');
            }, 500);
        });
    });
}

/**
 * Initialize parallax effects
 */
function initParallaxEffects() {
    // Add parallax effect to hero section
    const heroSection = document.querySelector('.hero-section');
    
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            
            // Parallax effect for background - slow down movement
            heroSection.style.backgroundPositionY = `${scrollPosition * 0.2}px`;
            
            // Fade out hero content as user scrolls
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.opacity = 1 - (scrollPosition / 700);
            }
        });
    }
}

/**
 * Initialize destination cards interactions
 */
function initDestinationCards() {
    const cards = document.querySelectorAll('.destination-card');
    
    cards.forEach(card => {
        // Tilt effect on mouse move
        card.addEventListener('mousemove', function(e) {
            const cardRect = this.getBoundingClientRect();
            const cardCenterX = cardRect.left + cardRect.width / 2;
            const cardCenterY = cardRect.top + cardRect.height / 2;
            
            // Calculate tilt based on mouse position relative to card center
            const mouseX = e.clientX;
            const mouseY = e.clientY;
            
            const tiltX = (cardCenterY - mouseY) / 10;
            const tiltY = (mouseX - cardCenterX) / 10;
            
            // Apply the tilt transform
            this.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) scale(1.05)`;
            
            // Add shine effect based on mouse position
            const shine = this.querySelector('.card-overlay');
            if (shine) {
                const shinePosX = (mouseX - cardRect.left) / cardRect.width * 100;
                const shinePosY = (mouseY - cardRect.top) / cardRect.height * 100;
                shine.style.background = `radial-gradient(circle at ${shinePosX}% ${shinePosY}%, rgba(255,255,255,0.2) 0%, rgba(0,0,0,0.4) 80%)`;
            }
        });
        
        // Reset transform on mouse leave
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            
            // Reset shine effect
            const shine = this.querySelector('.card-overlay');
            if (shine) {
                shine.style.background = 'linear-gradient(to top, rgba(0,0,0,0.6), rgba(0,0,0,0.1))';
            }
        });
        
        // Click effect
        card.addEventListener('click', function() {
            // Display category name
            const category = this.getAttribute('data-category');
            console.log(`Selected category: ${category}`);
            
            // Add click animation
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 500);
            
            // Show category name with toast notification
            showToast(`You selected: ${category}`);
        });
    });
}

/**
 * Show a toast notification
 */
function showToast(message) {
    // Create toast element if it doesn't exist
    let toast = document.querySelector('.custom-toast');
    
    if (!toast) {
        toast = document.createElement('div');
        toast.className = 'custom-toast';
        document.body.appendChild(toast);
        
        // Add toast styles if not in CSS file
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.backgroundColor = 'rgba(27,153,139,0.9)';
        toast.style.color = '#fff';
        toast.style.padding = '15px 25px';
        toast.style.borderRadius = '30px';
        toast.style.zIndex = '9999';
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        toast.style.fontWeight = '500';
        toast.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
    }
    
    // Set message and show toast
    toast.textContent = message;
    toast.style.opacity = '1';
    
    // Hide toast after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
    }, 3000);
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            // Get the target element
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                // Add animation to scroll
                window.scrollTo({
                    top: targetElement.offsetTop - 100, // 100px offset for header
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Initialize header transparency effect on scroll
 */
function initHeaderTransparency() {
    const header = document.querySelector('.site-header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
}

/**
 * Enhanced Swiper functionality
 */
function enhanceSwiperFunctionality() {
    // This is already initialized in the HTML file
    // We can add more advanced features here if needed
    
    // Add click handlers for navigation arrows
    const prevButton = document.querySelector('.swiper-button-prev');
    const nextButton = document.querySelector('.swiper-button-next');
    
    if (prevButton && nextButton) {
        // Add hover effects
        prevButton.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        prevButton.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
        
        nextButton.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        nextButton.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    }
}

// Booking form functionality
function initBookingForm() {
    const durationSelect = document.getElementById('duration');
    const customDurationGroup = document.querySelector('.custom-duration-group');
    
    if (durationSelect && customDurationGroup) {
        // Initially hide custom duration field
        customDurationGroup.style.display = 'none';
        
        durationSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDurationGroup.style.display = 'flex';
            } else {
                customDurationGroup.style.display = 'none';
            }
        });
    }
    
    // Calculate price functionality
    const calculatePriceBtn = document.querySelector('.booking-form-container .btn-outline');
    const summaryContent = document.getElementById('summaryContent');
    
    if (calculatePriceBtn && summaryContent) {
        calculatePriceBtn.addEventListener('click', updateBookingSummary);
    }
    
    // Form submission
    const bookingForm = document.getElementById('boatBookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Salamat sa iyong booking request! Makikipag-ugnayan kami sa iyo sa lalong madaling panahon.');
        });
    }
}

// Update booking summary
function updateBookingSummary() {
    const boatType = document.getElementById('boatType');
    const passengers = document.getElementById('passengers');
    const duration = document.getElementById('duration');
    const customHours = document.getElementById('customHours');
    const summaryContent = document.getElementById('summaryContent');
    
    if (!boatType || !passengers || !duration || !summaryContent) return;
    
    if (!boatType.value || !passengers.value || !duration.value) {
        summaryContent.innerHTML = '<p class="error-message"></p>';
        return;
    }
    
    // Get boat base prices
    const boatPrices = {
        'small': 2500,
        'medium': 4500,
        'large': 8000,
        'premium': 12000,
        'speed': 15000,
        'yacht': 25000
    };
    
    // Duration multipliers
    const durationMultipliers = {
        'halfday': 1,
        'fullday': 1.8,
        'custom': 0
    };
    
    // Calculate
    let basePrice = boatPrices[boatType.value] || 0;
    let durationMultiplier = durationMultipliers[duration.value] || 0;
    
    // Custom hours calculation
    if (duration.value === 'custom' && customHours.value) {
        durationMultiplier = Math.min(Math.max(parseFloat(customHours.value) / 4, 0.5), 3);
    }
    
    // Calculate total boat price
    let boatTotal = basePrice * durationMultiplier;
    
    // Calculate additional services
    let servicesTotal = 0;
    let servicesList = [];
    
    document.querySelectorAll('input[name="services[]"]').forEach(service => {
        if (service.checked) {
            const servicePrice = getServicePrice(service.value, parseInt(passengers.value));
            servicesTotal += servicePrice;
            servicesList.push({
                name: service.nextElementSibling.textContent.split(' ')[0],
                price: servicePrice
            });
        }
    });
    
    // Calculate grand total
    const grandTotal = boatTotal + servicesTotal;
    const depositAmount = grandTotal * 0.5;
    
    // Update summary HTML
    let summaryHTML = `
        <div class="summary-item">
            <span class="summary-label">Uri ng Bangka:</span>
            <span class="summary-value">${boatType.options[boatType.selectedIndex].text}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Tagal:</span>
            <span class="summary-value">${duration.value === 'custom' ? 
                `${customHours.value} oras` : 
                duration.options[duration.selectedIndex].text}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Pasahero:</span>
            <span class="summary-value">${passengers.value}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Halaga ng Bangka:</span>
            <span class="summary-value">₱${boatTotal.toLocaleString()}</span>
        </div>
    `;
    
    if (servicesList.length > 0) {
        summaryHTML += `
            <div class="summary-item">
                <span class="summary-label">Karagdagang Serbisyo:</span>
                <span class="summary-value"></span>
            </div>
            <ul class="services-list">
                ${servicesList.map(service => 
                    `<li>${service.name}: ₱${service.price.toLocaleString()}</li>`).join('')}
            </ul>
            <div class="summary-item">
                <span class="summary-label">Kabuuang Serbisyo:</span>
                <span class="summary-value">₱${servicesTotal.toLocaleString()}</span>
            </div>
        `;
    }
    
    summaryHTML += `
        <div class="summary-divider"></div>
        <div class="summary-item total">
            <span class="summary-label">Kabuuang Halaga:</span>
            <span class="summary-value">₱${grandTotal.toLocaleString()}</span>
        </div>
        <div class="summary-item deposit">
            <span class="summary-label">Kinakailangang Deposito (50%):</span>
            <span class="summary-value">₱${depositAmount.toLocaleString()}</span>
        </div>
    `;
    
    summaryContent.innerHTML = summaryHTML;
    
    // Animate the summary update
    summaryContent.classList.add('updated');
    setTimeout(() => {
        summaryContent.classList.remove('updated');
    }, 500);
}

// Get service price based on service type and passenger count
function getServicePrice(serviceType, passengerCount) {
    // Service prices
    const servicePrices = {
        'tourGuide': 500,
        'lunchPackage': 300 * passengerCount,
        'snorkelingGear': 150 * passengerCount,
        'photographer': 1500,
        'karaoke': 800,
        'icebox': 200
    };
    
    return servicePrices[serviceType] || 0;
} 