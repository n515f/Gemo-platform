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
  // ===== بطائق الإعدادات: فتح/إغلاق + منع الإغلاق عند وجود تغييرات =====
  const formSnapshots = new WeakMap();

  function snapshotForm(form) {
    const snap = {};
    form.querySelectorAll('input,select,textarea').forEach(el => {
      const name = el.name || el.id;
      if (!name) return;
      if (el.type === 'checkbox' || el.type === 'radio') {
        snap[name] = el.checked;
      } else if (el.type === 'file') {
        snap[name] = null; // لا يمكن حفظ الملف نفسه
      } else {
        snap[name] = el.value;
      }
    });
    formSnapshots.set(form, snap);
    form.dataset.dirty = '0';
  }

  function isFormDirty(form) {
    const snap = formSnapshots.get(form);
    if (!snap) return false;
    let dirty = false;
    form.querySelectorAll('input,select,textarea').forEach(el => {
      const name = el.name || el.id;
      if (!name) return;
      let cur;
      if (el.type === 'checkbox' || el.type === 'radio') {
        cur = el.checked;
      } else if (el.type === 'file') {
        cur = null; // نتجاهل الملفات للمقارنة
      } else {
        cur = el.value;
      }
      if (snap[name] !== cur) dirty = true;
    });
    form.dataset.dirty = dirty ? '1' : '0';
    return dirty;
  }

  function revertForm(form) {
    const snap = formSnapshots.get(form);
    if (!snap) return;
    form.querySelectorAll('input,select,textarea').forEach(el => {
      const name = el.name || el.id;
      if (!name) return;
      if (el.type === 'checkbox' || el.type === 'radio') {
        el.checked = !!snap[name];
      } else if (el.type === 'file') {
        el.value = ''; // نفرّغ اختيار الملف
      } else {
        el.value = snap[name] ?? '';
      }
    });
    form.dataset.dirty = '0';
  }

  // ربط مستمعات تغيير لضبط حالة dirty
  document.querySelectorAll('[data-card-form]').forEach(form => {
    form.addEventListener('input', () => isFormDirty(form));
    form.addEventListener('change', () => isFormDirty(form));

    // تأكيد قبل الحفظ
    form.addEventListener('submit', (e) => {
      const sure = window.confirm('هل أنت متأكد من تغيير إعدادات الموقع؟');
      if (!sure) {
        e.preventDefault();
        revertForm(form); // أعد القيم كما كانت
        alert('تم إرجاع القيم قبل التعديل.');
        return;
      }
      // إن وافق: الاستمرار بالحفظ — سيعيد التوجيه ويُحدّث الصفحة
    });
  });

  // تبديل فتح/إغلاق البطاقات + تبديل الأيقونة
  document.querySelectorAll('[data-card-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
      const card = btn.closest('[data-settings-card]');
      if (!card) return;
      const form = card.querySelector('[data-card-form]');
      const iconUse = btn.querySelector('[data-toggle-icon] use');

      const isOpen = card.getAttribute('data-open') === '1';

      if (isOpen) {
        // منع الإغلاق إذا هناك تغييرات غير محفوظة
        if (form && isFormDirty(form)) {
          alert('يرجى حفظ التغييرات داخل البطاقة قبل الإغلاق.');
          return;
        }
        card.setAttribute('data-open', '0');
        btn.setAttribute('aria-expanded', 'false');
        if (iconUse) iconUse.setAttribute('href', '#i-chevron-down');
      } else {
        card.setAttribute('data-open', '1');
        btn.setAttribute('aria-expanded', 'true');
        if (iconUse) iconUse.setAttribute('href', '#i-x');
        if (form) snapshotForm(form);
      }
    });
  });
});
