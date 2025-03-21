document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLeft = document.querySelector('.nav-left');
    const navRight = document.querySelector('.nav-right');

    // Change navbar style on scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle
    mobileMenuBtn.addEventListener('click', function() {
        navLeft.classList.toggle('show');
        navRight.classList.toggle('show');
        this.classList.toggle('active');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target) && navLeft.classList.contains('show')) {
            navLeft.classList.remove('show');
            navRight.classList.remove('show');
            mobileMenuBtn.classList.remove('active');
        }
    });
}); 