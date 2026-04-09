function toggleMobileMenu() {
  const menu = document.getElementById("mobile-menu");
  menu.classList.toggle("hidden");
}

function updateActiveNav(pageName) {
  document.querySelectorAll(".nav-btn").forEach((btn) => btn.classList.remove("active-link"));
  const activeNav = document.getElementById("nav-" + pageName);
  if (activeNav) activeNav.classList.add("active-link");
}

function closeMobileMenu() {
  document.getElementById("mobile-menu").classList.add("hidden");
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  // Get current page name from URL or data attribute
  const pageName = document.body.dataset.page || "beranda";
  updateActiveNav(pageName);
});
