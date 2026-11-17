'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // Get all dark-light switchers
    var switchers = document.querySelectorAll('.dark-light-switcher');

    // Function to update icons and theme
    function updateTheme(isDarkMode) {
        switchers.forEach(function (switcher) {
            var darkIcon = switcher.querySelector('.svg-white');
            var lightIcon = switcher.querySelector('.svg-dark');

            if (isDarkMode) {
                if (lightIcon) lightIcon.style.display = 'none';
                if (darkIcon) darkIcon.style.display = 'inline-flex';
            } else {
                if (lightIcon) lightIcon.style.display = 'inline-flex';
                if (darkIcon) darkIcon.style.display = 'none';
            }
        });

        document.documentElement.setAttribute('data-bs-theme', isDarkMode ? 'dark' : 'light');
    }

    // Retrieve stored theme and update the theme on page load
    var storedTheme = localStorage.getItem('theme') || 'light';
    var isDarkMode = storedTheme === 'dark';
    updateTheme(isDarkMode);

    // Add event listeners to all switchers
    switchers.forEach(function (switcher) {
        switcher.addEventListener('click', function () {
            var currentTheme = localStorage.getItem('theme');
            var isDarkMode = currentTheme === 'dark';

            // Toggle theme
            var newTheme = isDarkMode ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            updateTheme(newTheme === 'dark');
        });
    });
});
