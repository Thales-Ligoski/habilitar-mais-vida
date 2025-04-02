document.addEventListener("DOMContentLoaded", () => {
  // Theme Toggle
  const themeToggle = document.getElementById("theme-toggle")
  const html = document.documentElement

  // Check for saved theme
  const currentTheme = localStorage.getItem("theme") || "light"
  html.setAttribute("data-theme", currentTheme)

  // Update theme toggle icon
  updateThemeIcon(currentTheme)

  // Theme toggle functionality
  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      const currentTheme = html.getAttribute("data-theme")
      const newTheme = currentTheme === "light" ? "dark" : "light"

      html.setAttribute("data-theme", newTheme)
      localStorage.setItem("theme", newTheme)

      updateThemeIcon(newTheme)
    })
  }

  function updateThemeIcon(theme) {
    const themeToggles = document.querySelectorAll(".theme-toggle")
    themeToggles.forEach((toggle) => {
      toggle.innerHTML = theme === "dark" ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>'
    })
  }

  // Mobile Menu Toggle
  const menuToggle = document.querySelector(".menu-toggle")
  const mainMenu = document.querySelector(".main-menu")

  if (menuToggle && mainMenu) {
    menuToggle.addEventListener("click", () => {
      mainMenu.classList.toggle("active")
      menuToggle.innerHTML = mainMenu.classList.contains("active")
        ? '<i class="fas fa-times"></i>'
        : '<i class="fas fa-bars"></i>'
    })
  }

  // FAQ Toggle
  const faqItems = document.querySelectorAll(".faq-item")

  faqItems.forEach((item) => {
    const question = item.querySelector(".faq-question")

    question.addEventListener("click", () => {
      item.classList.toggle("active")

      // Update icon
      const toggleIcon = item.querySelector(".faq-toggle i")
      if (toggleIcon) {
        toggleIcon.className = item.classList.contains("active") ? "fas fa-minus" : "fas fa-plus"
      }
    })
  })

  // Initialize Swiper if it exists
  if (typeof Swiper !== "undefined" && document.querySelector(".swiper")) {
    // Swiper is available, initialize it
    const swiper = new Swiper(".mySwiper", {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    })
  }
})

