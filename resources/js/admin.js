document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("form.needs-confirm").forEach((f) => {
    f.addEventListener("submit", (e) => {
      const msg = f.getAttribute("data-confirm") || "Are you sure?";
      if (!window.confirm(msg)) {
        e.preventDefault();
      }
    });
  });
});