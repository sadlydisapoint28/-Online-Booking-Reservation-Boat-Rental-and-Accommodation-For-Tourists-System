// JavaScript for dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.dropdown');
    const boatRentalsLink = document.querySelector('nav ul li a[href="#"]');

    boatRentalsLink.addEventListener('click', function(event) {
        event.preventDefault();
        dropdown.classList.toggle('show');
    });
});
