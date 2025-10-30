// resources/js/admin.js
document.addEventListener('DOMContentLoaded', () => {
  // عناصر أساسية
  const sidebar = document.querySelector('[data-admin-sidebar]');
  const wrapper = document.querySelector('.admin-wrap');
  const menuBtn = document.querySelector('[data-admin-open]');
  const nav     = document.querySelector('[data-admin-nav]');

  if (!sidebar || !wrapper || !menuBtn) return;

  // ميديا: تمييز ديسكتوب/موبايل
  const isDesktop = () => window.matchMedia('(min-width: 992px)').matches;

  // -------- Overlay (للموبايل فقط) --------
  let overlay = null;
  const makeOverlay = () => {
    const el = document.createElement('div');
    el.className = 'admin-overlay';
    el.addEventListener('click', closeMobile); // نقر خارج السايدبار يغلقه
    return el;
  };

  // -------- حالات الموبايل --------
  function openMobile() {
    if (isDesktop()) return;                 // لا نفتح كدرج على الديسكتوب
    sidebar.setAttribute('data-open', '1');  // يفتح الدرج
    sidebar.removeAttribute('data-collapsed');
    wrapper.removeAttribute('data-collapsed');

    if (!overlay) {
      overlay = makeOverlay();
      document.body.appendChild(overlay);
      document.body.classList.add('no-scroll');
    }
  }
  function closeMobile() {
    sidebar.removeAttribute('data-open');
    // نرجّع الوضع المصغّر (لو حاب تستمر مصغّر على الموبايل)
    sidebar.setAttribute('data-collapsed', '1');
    wrapper.setAttribute('data-collapsed', '1');

    if (overlay) {
      overlay.remove();
      overlay = null;
      document.body.classList.remove('no-scroll');
    }
  }

  // -------- حالات الديسكتوب --------
  function toggleDesktop() {
    // تبديل طيّ/توسيع بدون أي overlay
    const collapsed = sidebar.getAttribute('data-collapsed') === '1' ? '0' : '1';
    sidebar.setAttribute('data-collapsed', collapsed);
    wrapper.setAttribute('data-collapsed', collapsed);
  }

  // -------- الزر الرئيسي --------
  menuBtn.addEventListener('click', () => {
    if (isDesktop()) {
      toggleDesktop();
      return;
    }
    // موبايل: نفس الزر يفتح/يغلق
    const isOpen = sidebar.getAttribute('data-open') === '1';
    isOpen ? closeMobile() : openMobile();
  });

  // إغلاق عند اختيار عنصر داخل السايدبار (موبايل)
  nav?.addEventListener('click', (e) => {
    const t = e.target.closest('a,button,form');
    if (!t || isDesktop()) return;
    // نترك النقر يمرّ ثم نغلق بسرعة
    setTimeout(closeMobile, 80);
  });

  // ESC → إغلاق على الموبايل
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !isDesktop()) closeMobile();
  });

  // عند تغيير المقاس: تنظيف حالة الموبايل
  window.addEventListener('resize', () => {
    if (isDesktop()) {
      // تخلص من أي overlay واترك التحكم بالطيّ/التوسيع
      if (overlay) { overlay.remove(); overlay = null; }
      document.body.classList.remove('no-scroll');
      sidebar.removeAttribute('data-open');
    }
  }, { passive: true });

  // حالة افتراضية مريحة عند التحميل:
  // ديسكتوب: مصغّر (collapsed) — موبايل: مغلق
  if (isDesktop()) {
    sidebar.setAttribute('data-collapsed', '1');
    wrapper.setAttribute('data-collapsed', '1');
  } else {
    closeMobile();
  }

  // تأكيدات عامة للنماذج
  document.querySelectorAll('form.needs-confirm').forEach(f => {
    f.addEventListener('submit', (e) => {
      const msg = f.getAttribute('data-confirm') || 'Are you sure?';
      if (!window.confirm(msg)) e.preventDefault();
    });
  });
});
