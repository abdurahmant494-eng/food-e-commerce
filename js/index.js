document.getElementById("hamburger-btn").addEventListener("click", function () {
  document.getElementById("nav-menu").classList.toggle("open");
});

// Initialize Swiper
const swiper = new Swiper(".swiper", {
  loop: true,
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  effect: "fade",
  fadeEffect: {
    crossFade: true,
  },
  breakpoints: {
    768: {
      slidesPerView: 1,
    },
  },
});
