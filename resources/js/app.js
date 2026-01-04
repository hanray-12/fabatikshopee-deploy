import "./bootstrap";

/**
 * Close all <details> when click outside or ESC
 */
function setupDetailsAutoClose() {
  const detailsEls = Array.from(document.querySelectorAll("details[data-autoclose='true']"));

  document.addEventListener("click", (e) => {
    detailsEls.forEach((d) => {
      if (!d.open) return;
      const summary = d.querySelector("summary");
      const dropdown = d.querySelector("[data-dropdown='true']");
      const clickedInside = d.contains(e.target);
      const clickedSummary = summary && summary.contains(e.target);
      const clickedDropdown = dropdown && dropdown.contains(e.target);

      // kalau klik di luar details -> tutup
      if (!clickedInside) d.removeAttribute("open");

      // kalau klik link di dropdown -> tutup (biar feel premium)
      if (clickedDropdown && e.target.closest("a,button")) {
        // biar tetap jalan actionnya, tutup setelah sedikit delay
        setTimeout(() => d.removeAttribute("open"), 50);
      }

      // klik summary biarin toggle normal
      if (clickedSummary) return;
    });
  });

  document.addEventListener("keydown", (e) => {
    if (e.key !== "Escape") return;
    detailsEls.forEach((d) => d.removeAttribute("open"));
  });
}

/**
 * Button add-to-cart loading + anti double click
 */
function setupCartLoading() {
  const forms = document.querySelectorAll("form[data-cart-form='true']");
  forms.forEach((form) => {
    const btn = form.querySelector("button[type='submit']");
    if (!btn) return;

    form.addEventListener("submit", () => {
      // prevent double submit
      if (btn.dataset.loading === "1") return;
      btn.dataset.loading = "1";

      btn.classList.add("btn-loading");
      btn.disabled = true;

      const original = btn.innerHTML;
      btn.dataset.original = original;

      // tampilkan spinner + teks
      btn.innerHTML = `
        <span class="spinner" aria-hidden="true"></span>
        <span>Menambahkan...</span>
      `;
    });
  });
}

/**
 * Back to top button
 */
function setupBackToTop() {
  const btn = document.getElementById("backToTop");
  if (!btn) return;

  const toggle = () => {
    if (window.scrollY > 450) btn.classList.add("show");
    else btn.classList.remove("show");
  };

  toggle();
  window.addEventListener("scroll", toggle);

  btn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  setupDetailsAutoClose();
  setupCartLoading();
  setupBackToTop();
});
