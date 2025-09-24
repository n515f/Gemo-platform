// resources/js/services.js
export default function initRevealOnScroll() {
  const els = document.querySelectorAll('.reveal');
  if (!els.length) return;

  // fallback للمتصفحات القديمة
  if (!('IntersectionObserver' in window)) {
    els.forEach(el => el.classList.add('in'));
    return;
  }

  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08 });

  els.forEach(el => io.observe(el));
}