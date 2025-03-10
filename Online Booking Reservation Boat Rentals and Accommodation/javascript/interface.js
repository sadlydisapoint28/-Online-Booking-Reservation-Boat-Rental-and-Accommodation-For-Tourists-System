// Navbar scroll effect
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');

    // Enhanced scroll effect with smooth transition
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        // Add/remove scrolled class based on scroll position with smooth transition
        if (currentScroll > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle with enhanced animation
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navbar.classList.toggle('mobile-active');
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target) && navbar.classList.contains('mobile-active')) {
            navbar.classList.remove('mobile-active');
        }
    });

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                // Close mobile menu if open
                navbar.classList.remove('mobile-active');
            }
        });
    });

    // Enhanced intersection observer for smooth animations
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.feature, .option, .card').forEach(element => {
        observer.observe(element);
    });

    // Enhanced image loading animation
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
        });
    });

    // Enhanced hover effects for buttons
    const buttons = document.querySelectorAll('.btn-book, .btn-login, .learn-more');
    buttons.forEach(button => {
        button.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            this.style.setProperty('--x', `${x}px`);
            this.style.setProperty('--y', `${y}px`);
        });
    });

    // Form submission handling
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            alert('Form submitted successfully!');
            this.reset();
        });
    });
});
