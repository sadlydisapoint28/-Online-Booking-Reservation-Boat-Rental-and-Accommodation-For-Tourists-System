// Theme toggle functionality
document.addEventListener("DOMContentLoaded", function () {
   // Initialize theme
   const savedTheme = localStorage.getItem("theme") || "light";
   document.documentElement.classList.toggle("dark", savedTheme === "dark");
   updateThemeToggleButton(savedTheme);
 
   // Theme toggle button
   const themeToggleButton = document.getElementById("theme-toggle");
   if (themeToggleButton) {
     themeToggleButton.addEventListener("click", toggleTheme);
   }
 
   // Initialize date and time
   const dateTimeElement = document.getElementById("date-time");
   if (dateTimeElement) {
     updateDateTime(dateTimeElement);
     setInterval(() => updateDateTime(dateTimeElement), 1000);
   }
 
   // View toggle buttons
   const viewToggleButtons = document.querySelectorAll(".view-toggle-button");
   viewToggleButtons.forEach((button) => {
     button.addEventListener("click", function () {
       const view = this.getAttribute("data-view");
       viewToggleButtons.forEach((btn) => btn.classList.remove("active"));
       this.classList.add("active");
 
       document
         .getElementById("grid-view")
         .classList.toggle("view-active", view === "grid");
       document
         .getElementById("map-view")
         .classList.toggle("view-active", view === "map");
     });
   });
});
