<?php
// Loading Screen Component
?>
<style>
    /* Loading Screen Styles */
    .loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease-out;
    }

    .loader {
        width: 150px;
        height: 150px;
        position: relative;
    }

    .loader-circle {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 4px solid transparent;
        border-top-color: #4169E1;
        border-radius: 50%;
        animation: rotate 1.5s linear infinite;
    }

    .loader-circle:nth-child(2) {
        width: 80%;
        height: 80%;
        top: 10%;
        left: 10%;
        border-top-color: #fff;
        animation-delay: 0.5s;
    }

    .loader-circle:nth-child(3) {
        width: 60%;
        height: 60%;
        top: 20%;
        left: 20%;
        border-top-color: #4169E1;
        animation-delay: 1s;
    }

    .loader-text {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-size: 1.2rem;
        font-weight: 500;
        text-align: center;
        width: 100%;
    }

    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .loader-wrapper.fade-out {
        opacity: 0;
        pointer-events: none;
    }

    /* Hide main content while loading */
    body {
        opacity: 0;
        transition: opacity 0.5s ease-in;
    }

    body.loaded {
        opacity: 1;
    }
</style>

<!-- Loading Screen HTML -->
<div class="loader-wrapper">
    <div class="loader">
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>
        <div class="loader-text">Loading Carles Tourism...</div>
    </div>
</div>

<script>
    // Loading Screen Script
    document.addEventListener('DOMContentLoaded', function() {
        // Hide loading screen after content is loaded
        setTimeout(function() {
            const loaderWrapper = document.querySelector('.loader-wrapper');
            const body = document.body;
            
            loaderWrapper.classList.add('fade-out');
            body.classList.add('loaded');
            
            // Remove loading screen from DOM after fade out
            setTimeout(function() {
                loaderWrapper.style.display = 'none';
            }, 500);
        }, 2000); // Show loading screen for 2 seconds
    });
</script> 