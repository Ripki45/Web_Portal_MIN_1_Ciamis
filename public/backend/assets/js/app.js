(function ($) {
  "use strict";

  // sidebar submenu collapsible js
  $(".sidebar-menu .dropdown").on("click", function () {
    var item = $(this);
    item.siblings(".dropdown").children(".sidebar-submenu").slideUp();

    item.siblings(".dropdown").removeClass("dropdown-open");

    item.siblings(".dropdown").removeClass("open");

    item.children(".sidebar-submenu").slideToggle();

    item.toggleClass("dropdown-open");
  });

  $(".sidebar-toggle").on("click", function () {
    $(this).toggleClass("active");
    $(".sidebar").toggleClass("active");
    $(".dashboard-main").toggleClass("active");
  });

  $(".sidebar-mobile-toggle").on("click", function () {
    $(".sidebar").addClass("sidebar-open");
    $("body").addClass("overlay-active");
  });

  $(".sidebar-close-btn").on("click", function () {
    $(".sidebar").removeClass("sidebar-open");
    $("body").removeClass("overlay-active");
  });

  //to keep the current page active
  $(function () {
    for (
      var nk = window.location,
        o = $("ul#sidebar-menu a")
          .filter(function () {
            return this.href == nk;
          })
          .addClass("active-page") // anchor
          .parent()
          .addClass("active-page");
      ;
    ) {
      // li
      if (!o.is("li")) break;
      o = o.parent().addClass("show").parent().addClass("open");
    }
  });

  /**
   * Utility function to calculate the current theme setting based on localStorage.
   */
  function calculateSettingAsThemeString({ localStorageTheme }) {
    if (localStorageTheme !== null) {
      return localStorageTheme;
    }
    return "light"; // default to light theme if nothing is stored
  }

  /**
   * Utility function to update the button text and aria-label.
   */
  function updateButton({ buttonEl, isDark }) {
    const newCta = isDark ? "dark" : "light";
    buttonEl.setAttribute("aria-label", newCta);
    buttonEl.innerText = newCta;
  }

  /**
   * Utility function to update the theme setting on the html tag.
   */
  function updateThemeOnHtmlEl({ theme }) {
    document.querySelector("html").setAttribute("data-theme", theme);
  }

  /**
   * 1. Grab what we need from the DOM and system settings on page load.
   */
  const button = document.querySelector("[data-theme-toggle]");
  const localStorageTheme = localStorage.getItem("theme");

  /**
   * 2. Work out the current site settings.
   */
  let currentThemeSetting = calculateSettingAsThemeString({
    localStorageTheme,
  });

  /**
   * 3. If the button exists, update the theme setting and button text according to current settings.
   */
  if (button) {
    updateButton({ buttonEl: button, isDark: currentThemeSetting === "dark" });
    updateThemeOnHtmlEl({ theme: currentThemeSetting });

    /**
     * 4. Add an event listener to toggle the theme.
     */
    button.addEventListener("click", (event) => {
      const newTheme = currentThemeSetting === "dark" ? "light" : "dark";

      localStorage.setItem("theme", newTheme);
      updateButton({ buttonEl: button, isDark: newTheme === "dark" });
      updateThemeOnHtmlEl({ theme: newTheme });

      currentThemeSetting = newTheme;
    });
  } else {
    // If no button is found, just apply the current theme to the page
    updateThemeOnHtmlEl({ theme: currentThemeSetting });
  }

  // =========================== Dark and light mode from sidebar js Start ================================
  const buttons = document.querySelectorAll(".dark-light-mode .theme-btn");

  function applyTheme(theme) {
    if (theme === "system") {
      const prefersDark = window.matchMedia(
        "(prefers-color-scheme: dark)",
      ).matches;
      document.documentElement.setAttribute(
        "data-theme",
        prefersDark ? "dark" : "light",
      );
    } else {
      document.documentElement.setAttribute("data-theme", theme);
    }
  }

  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      const theme = button.getAttribute("data-theme");

      // Apply theme
      applyTheme(theme);

      // Save in localStorage
      localStorage.setItem("theme", theme);

      // Remove active from all buttons
      buttons.forEach((btn) => btn.classList.remove("active"));

      // Add active to clicked button
      button.classList.add("active");
    });
  });

  // Load theme on page refresh
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    applyTheme(savedTheme);
    buttons.forEach((btn) => {
      if (btn.getAttribute("data-theme") === savedTheme) {
        btn.classList.add("active");
      }
    });
  }
  // =========================== Dark and light mode from sidebar js Start ================================

  // =========================== Theme Customization Show Hide js Start ================================
  $(".theme-customization__button").on("click", function () {
    $(".theme-customization-sidebar").toggleClass("active");
    $(".body-overlay").toggleClass("show");
  });

  $(".theme-customization-sidebar__close, .body-overlay").on(
    "click",
    function () {
      $(".theme-customization-sidebar").removeClass("active");
      $(".body-overlay").removeClass("show");
    },
  );
  // =========================== Theme Customization Show Hide js End ================================

  // =========================== RTL Mode js Start ================================
  $(".ltr-mode-btn").on("click", function () {
    $("html").attr("dir", "ltr");
    localStorage.setItem("direction", "ltr");

    // Toggle active state
    $(".theme-setting-item__btn").removeClass("active");
    $(this).addClass("active");
  });

  // RTL button
  $(".rtl-mode-btn").on("click", function () {
    $("html").attr("dir", "rtl");
    localStorage.setItem("direction", "rtl");

    // Toggle active state
    $(".theme-setting-item__btn").removeClass("active");
    $(this).addClass("active");
  });

  // Load saved direction from localStorage on page load
  $(document).ready(function () {
    const savedDir = localStorage.getItem("direction");
    if (savedDir) {
      $("html").attr("dir", savedDir);

      // Keep correct button active
      if (savedDir === "rtl") {
        $(".rtl-mode-btn").addClass("active");
      } else {
        $(".ltr-mode-btn").addClass("active");
      }
    }
  });
  // =========================== RTL Mode js End ================================

  //   // =========================== Color Schema js Start ================================
  // const colorPickerButtons = document.querySelectorAll(".color-picker-btn");
  // const colors = {
  //   blue: "#2563eb",
  //   red: "#dc2626",
  //   green: "#16a34a",
  //   yellow: "#ff9f29",
  //   cyan: "#00b8f2",
  //   violet: "#7c3aed",
  // };

  // function applyColor(color) {
  //   const newColor = colors[color];
  //   document.documentElement.style.setProperty("--primary-600", newColor);
  //   localStorage.setItem("templateColor", color);

  //   // Update charts instantly with the new color
  //   updateChartColors(newColor);
  // }

  // colorPickerButtons.forEach((btn) => {
  //   btn.addEventListener("click", () => {
  //     const color = btn.getAttribute("data-color");
  //     if (!color) return;
  //     applyColor(color);
  //     colorPickerButtons.forEach((b) => b.classList.remove("active"));
  //     btn.classList.add("active");
  //   });
  // });

  // // Load saved color on refresh
  // const savedColor = localStorage.getItem("templateColor");
  // if (savedColor && colors[savedColor]) {
  //   applyColor(savedColor);
  //   document
  //     .querySelector(`.color-picker-btn[data-color="${savedColor}"]`)
  //     .classList.add("active");
  // } else {
  //   document
  //     .querySelector(`.color-picker-btn[data-color="blue"]`)
  //     .classList.add("active");
  // }

  const colorPickerButtons = document.querySelectorAll(".color-picker-btn");
  const colors = {
    blue: "#2563eb",
    red: "#dc2626",
    green: "#16a34a",
    yellow: "#ff9f29",
    cyan: "#00b8f2",
    violet: "#7c3aed",
  };

  function applyColor(color) {
    const newColor = colors[color];
    document.documentElement.style.setProperty("--primary-600", newColor);
    localStorage.setItem("templateColor", color);

    // Update charts instantly with the new color.
    // Guarded: the page-specific chart script (which defines updateChartColors)
    // may not have loaded yet, or the page may have no charts at all.
    if (typeof updateChartColors === "function") {
      updateChartColors(newColor);
    }
  }

  colorPickerButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const color = btn.getAttribute("data-color");
      if (!color) return;
      applyColor(color);
      colorPickerButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });

  // Load saved color on refresh
  const savedColor = localStorage.getItem("templateColor");
  if (savedColor && colors[savedColor]) {
    applyColor(savedColor);
    document
      .querySelector(`.color-picker-btn[data-color="${savedColor}"]`)
      .classList.add("active");
  } else {
    document
      .querySelector(`.color-picker-btn[data-color="blue"]`)
      .classList.add("active");
  }
  // =========================== Color Schema js End ================================



  // =========================== Table Header Checkbox checked all js Start ================================
  $("#selectAll").on("change", function () {
    $(".form-check .form-check-input").prop("checked", $(this).prop("checked"));
  });

  // Remove Table Tr when click on remove btn start
  $(".remove-btn").on("click", function () {
    $(this).closest("tr").remove();

    // Check if the table has no rows left
    if ($(".table tbody tr").length === 0) {
      $(".table").addClass("bg-danger");

      // Show notification
      $(".no-items-found").show();
    }
  });
  // Remove Table Tr when click on remove btn end


  // =========================== Period toggle tabs (1W / 1M / 6M / 1Y / ALL) Start ===========================
  // Makes the pill toggle groups behave like tabs: clicking a pill activates it
  // and deactivates the others. Delegated per group so it scales to any number
  // of `.period-tabs` groups on the page.
  document.querySelectorAll(".period-tabs").forEach(function (group) {
    group.addEventListener("click", function (e) {
      var btn = e.target.closest("button");
      if (!btn || !group.contains(btn)) return;

      group.querySelectorAll("button").forEach(function (b) {
        b.classList.remove("bg-primary-600", "text-white");
        b.classList.add("text-secondary-light");
      });

      btn.classList.remove("text-secondary-light");
      btn.classList.add("bg-primary-600", "text-white");
    });
  });
  // =========================== Period toggle tabs End ===========================
})(jQuery);
