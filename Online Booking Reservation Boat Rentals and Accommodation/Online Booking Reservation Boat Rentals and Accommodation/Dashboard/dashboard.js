document.addEventListener("DOMContentLoaded", function () {
  // Toggle user dropdown
  const userDropdownToggle = document.getElementById("userDropdownToggle");
  const userDropdown = document.getElementById("userDropdown");

  if (userDropdownToggle && userDropdown) {
    userDropdownToggle.addEventListener("click", function () {
      userDropdown.classList.toggle("show");
    });
  }

  // Close dropdown when clicking outside
  document.addEventListener("click", function (event) {
    if (
      userDropdown &&
      userDropdownToggle &&
      !userDropdownToggle.contains(event.target) &&
      !userDropdown.contains(event.target)
    ) {
      userDropdown.classList.remove("show");
    }
  });

  // Toggle sidebar collapse
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("collapsed");
    });
  }

  // Tab navigation
  const tabCards = document.querySelectorAll(".tab-card");
  const tabPanes = document.querySelectorAll(".tab-pane");
  const sidebarLinks = document.querySelectorAll(".sidebar-link");

  function setActiveTab(tabId) {
    // Remove active class from all tabs and panes
    tabCards.forEach((card) => card.classList.remove("active"));
    tabPanes.forEach((pane) => pane.classList.remove("active"));
    sidebarLinks.forEach((link) => link.classList.remove("active"));

    // Add active class to selected tab and pane
    const selectedCard = document.querySelector(
      `.tab-card[data-tab="${tabId}"]`,
    );
    const selectedPane = document.getElementById(`${tabId}-tab`);
    const selectedLink = document.querySelector(
      `.sidebar-link[data-tab="${tabId}"]`,
    );

    if (selectedCard) selectedCard.classList.add("active");
    if (selectedPane) selectedPane.classList.add("active");
    if (selectedLink) selectedLink.classList.add("active");
  }

  // Add click event to tab cards
  tabCards.forEach((card) => {
    card.addEventListener("click", function () {
      const tabId = this.getAttribute("data-tab");
      setActiveTab(tabId);
    });
  });

  // Add click event to sidebar links
  sidebarLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const tabId = this.getAttribute("data-tab");
      setActiveTab(tabId);
    });
  });

  // Booking steps navigation
  const nextToStep2 = document.getElementById("next-to-step-2");
  const nextToStep3 = document.getElementById("next-to-step-3");
  const nextToStep4 = document.getElementById("next-to-step-4");
  const backToStep1 = document.getElementById("back-to-step-1");
  const backToStep2 = document.getElementById("back-to-step-2");
  const backToStep3 = document.getElementById("back-to-step-3");
  const completeBooking = document.getElementById("complete-booking");

  const bookingSteps = document.querySelectorAll(".booking-step");
  const progressSteps = document.querySelectorAll(".progress-step");
  const progressBarFill = document.querySelector(".progress-bar-fill");

  function setActiveStep(stepNumber) {
    // Hide all steps
    bookingSteps.forEach((step) => step.classList.remove("active"));

    // Show active step
    const activeStep = document.getElementById(`booking-step-${stepNumber}`);
    if (activeStep) activeStep.classList.add("active");

    // Update progress steps
    progressSteps.forEach((step, index) => {
      if (index < stepNumber) {
        step.classList.add("active");
      } else {
        step.classList.remove("active");
      }
    });

    // Update progress bar
    if (progressBarFill) {
      progressBarFill.style.width = `${((stepNumber - 1) / 3) * 100}%`;
    }
  }

  // Add click events to navigation buttons
  if (nextToStep2)
    nextToStep2.addEventListener("click", () => setActiveStep(2));
  if (nextToStep3)
    nextToStep3.addEventListener("click", () => setActiveStep(3));
  if (nextToStep4)
    nextToStep4.addEventListener("click", () => setActiveStep(4));
  if (backToStep1)
    backToStep1.addEventListener("click", () => setActiveStep(1));
  if (backToStep2)
    backToStep2.addEventListener("click", () => setActiveStep(2));
  if (backToStep3)
    backToStep3.addEventListener("click", () => setActiveStep(3));

  if (completeBooking) {
    completeBooking.addEventListener("click", function () {
      alert("Booking completed successfully!");
      setActiveTab("calendar");
    });
  }

  // Tutorial overlay
  const showTutorial = document.getElementById("showTutorial");
  const tutorialOverlay = document.getElementById("tutorialOverlay");
  const closeTutorial = document.getElementById("closeTutorial");
  const skipTutorial = document.getElementById("skipTutorial");
  const nextTutorial = document.getElementById("nextTutorial");
  const tutorialDots = document.querySelectorAll(".tutorial-dot");

  let currentTutorialStep = 0;
  const totalTutorialSteps = tutorialDots.length;

  function showTutorialStep(step) {
    // Update dots
    tutorialDots.forEach((dot, index) => {
      dot.classList.toggle("active", index === step);
    });

    currentTutorialStep = step;

    // If last step, change button text
    if (nextTutorial) {
      if (step === totalTutorialSteps - 1) {
        nextTutorial.innerHTML = 'Get Started <i class="fas fa-check"></i>';
      } else {
        nextTutorial.innerHTML = 'Next <i class="fas fa-chevron-right"></i>';
      }
    }
  }

  if (showTutorial && tutorialOverlay) {
    showTutorial.addEventListener("click", function () {
      tutorialOverlay.classList.add("show");
      tutorialOverlay.style.display = "flex";
      showTutorialStep(0);
    });
  }

  if (closeTutorial && tutorialOverlay) {
    closeTutorial.addEventListener("click", function () {
      tutorialOverlay.classList.remove("show");
      tutorialOverlay.style.display = "none";
    });
  }

  if (skipTutorial && tutorialOverlay) {
    skipTutorial.addEventListener("click", function () {
      tutorialOverlay.classList.remove("show");
      tutorialOverlay.style.display = "none";
    });
  }

  if (nextTutorial) {
    nextTutorial.addEventListener("click", function () {
      if (currentTutorialStep < totalTutorialSteps - 1) {
        showTutorialStep(currentTutorialStep + 1);
      } else {
        // Last step, close tutorial
        if (tutorialOverlay) {
          tutorialOverlay.classList.remove("show");
          tutorialOverlay.style.display = "none";
        }

        // Show welcome message
        const welcomeMessage = document.getElementById("welcomeMessage");
        if (welcomeMessage) {
          welcomeMessage.classList.add("show");
          welcomeMessage.style.display = "block";
        }
      }
    });
  }

  // Welcome message
  const welcomeMessage = document.getElementById("welcomeMessage");
  const closeWelcome = document.getElementById("closeWelcome");

  if (closeWelcome && welcomeMessage) {
    closeWelcome.addEventListener("click", function () {
      welcomeMessage.classList.remove("show");
      welcomeMessage.style.display = "none";
    });
  }

  // Toggle help tooltips
  const toggleHelp = document.getElementById("toggleHelp");

  if (toggleHelp) {
    toggleHelp.addEventListener("click", function () {
      const isShowingHelp = this.classList.toggle("active");

      if (isShowingHelp) {
        this.querySelector("span").textContent = "Hide Help";
      } else {
        this.querySelector("span").textContent = "Show Help";
      }
    });
  }
});
