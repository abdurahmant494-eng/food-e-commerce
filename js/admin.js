document.addEventListener("DOMContentLoaded", function () {
  // Tab functionality
  const tabButtons = document.querySelectorAll(".tab-btn");
  const tabContents = document.querySelectorAll(".tab-content");

  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      this.classList.add("active");
      const tabId = this.getAttribute("data-tab");
      document.getElementById(tabId + "-tab").classList.add("active");
    });
  });

  // Check if we're in edit mode by looking at the URL
  const editTabBtn = document.querySelector('[data-tab="edit-product"]');
  if (editTabBtn && window.location.href.includes("edit_product")) {
    editTabBtn.click();
  }

  // Form validation
  const forms = document.querySelectorAll(".admin-form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      const requiredFields = form.querySelectorAll("[required]");
      let valid = true;

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          valid = false;
          field.classList.add("error");
        } else {
          field.classList.remove("error");
        }
      });

      if (!valid) {
        event.preventDefault();
        alert("Please fill in all required fields.");
      }
    });
  });
});
