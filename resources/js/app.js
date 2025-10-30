// resources/js/app.js
import './bootstrap';
import './admin';
import './catalog';
import initAdsRotator from './ads';
import initRevealOnScroll from './services';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

const ICONS = { sun: '/images/sun.png', moon: '/images/moon.png' };
const html = document.documentElement;
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

function currentTheme() {
  const saved = localStorage.getItem('theme');
  if (saved === 'dark' || saved === 'light') return saved;
  return prefersDark.matches ? 'dark' : 'light';
}
function updateThemeIcons() {
  const isDark = html.classList.contains('dark');
  const targets = [
    document.getElementById('themeIcon'),
    document.getElementById('themeIconMobile'),
    document.getElementById('themeIconDesktop'),
    ...Array.from(document.querySelectorAll('.theme-btn img')),
  ].filter(Boolean);
  targets.forEach(img => {
    const sun  = img?.dataset?.sun  || ICONS.sun;
    const moon = img?.dataset?.moon || ICONS.moon;
    img.src = isDark ? sun : moon;
  });
}
function applyTheme(theme, { persist = false } = {}) {
  const isDark = theme === 'dark';
  html.classList.toggle('dark', isDark);
  if (persist) localStorage.setItem('theme', isDark ? 'dark' : 'light');
  updateThemeIcons();
}
function toggleTheme() {
  applyTheme(html.classList.contains('dark') ? 'light' : 'dark', { persist: true });
}

document.addEventListener('DOMContentLoaded', () => {
  // تشغيل مكوّنات الصفحة
  try { initAdsRotator(); } catch (e) { console.error('Ads rotator error:', e); }
  try { initRevealOnScroll(); } catch {}

  // Topbar
  const topbar = document.querySelector('.topbar');
  if (topbar) {
    const onScroll = () => { (window.scrollY > 6) ? topbar.classList.add('scrolled') : topbar.classList.remove('scrolled'); };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // Theme
  applyTheme(currentTheme());
  const themeBtns = new Set([
    document.getElementById('themeIconBtn'),
    document.getElementById('themeIconBtnMobile'),
    document.getElementById('themeIconBtnDesktop'),
    ...document.querySelectorAll('.theme-btn'),
  ].filter(Boolean));
  themeBtns.forEach(btn => btn.addEventListener('click', toggleTheme));
  prefersDark.addEventListener('change', e => {
    const saved = localStorage.getItem('theme');
    if (saved !== 'dark' && saved !== 'light') {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  });

  // Bottom-sheet للأفاتار + تأكيدات عامة … (كما هي عندك)
});

/* ========= تشغيل عند التحميل ========= */
document.addEventListener('DOMContentLoaded', () => {
  initAdsRotator();

  const topbar = document.querySelector('.topbar');
  if (topbar) {
    const onScroll = () => { (window.scrollY > 6) ? topbar.classList.add('scrolled') : topbar.classList.remove('scrolled'); };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  applyTheme(currentTheme());

  const themeBtns = new Set([
    document.getElementById('themeIconBtn'),
    document.getElementById('themeIconBtnMobile'),
    document.getElementById('themeIconBtnDesktop'),
    ...document.querySelectorAll('.theme-btn'),
  ].filter(Boolean));
  themeBtns.forEach(btn => btn.addEventListener('click', toggleTheme));

  prefersDark.addEventListener('change', e => {
    const saved = localStorage.getItem('theme');
    if (saved !== 'dark' && saved !== 'light') {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  });

  initRevealOnScroll();

  // ===== Avatar bottom sheet (only when logged in) =====
  const btn     = document.querySelector('.js-avatar-btn');
  const sheet   = document.querySelector('.js-avatar-sheet');
  const back    = document.querySelector('.js-avatar-sheet-backdrop');
  const choose  = document.querySelector('.js-avatar-choose');
  const camera  = document.querySelector('.js-avatar-camera');
  const delForm = document.querySelector('.js-avatar-delete');
  const cancel  = document.querySelector('.js-avatar-cancel');
  const file    = document.getElementById('avatarFile');
  const cam     = document.getElementById('avatarCamera');
  const uploadF = document.getElementById('avatarUploadForm');

  if (btn && sheet && back) {
    const openSheet = () => {
      sheet.hidden = false; back.hidden = false;
      requestAnimationFrame(() => { sheet.classList.add('open'); back.classList.add('open'); });
    };
    const closeSheet = () => {
      sheet.classList.remove('open'); back.classList.remove('open');
      setTimeout(() => { sheet.hidden = true; back.hidden = true; }, 220);
    };

    btn.addEventListener('click', openSheet);
    back.addEventListener('click', closeSheet);
    cancel?.addEventListener('click', closeSheet);

    choose?.addEventListener('click', () => file?.click());
    camera?.addEventListener('click', () => cam?.click());

    file?.addEventListener('change', () => {
      if (file.files?.length) uploadF.submit();
    });

    cam?.addEventListener('change', () => {
      if (!cam.files?.length) return;
      const dt = new DataTransfer();
      dt.items.add(cam.files[0]);
      file.files = dt.files;
      uploadF.submit();
    });

    delForm?.addEventListener('submit', (e) => {
      if (!confirm('هل تريد حذف الصورة؟')) e.preventDefault();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeSheet();
    });
  }

  // تأكيد حذف عام
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (form && form.matches('form[data-confirm]')) {
      const msg = form.getAttribute('data-confirm') || 'Are you sure?';
      if (!window.confirm(msg)) { e.preventDefault(); e.stopPropagation(); }
    }
  });
});
// تأثير دخول العناصر + نبض زر الإرسال عند جاهزية الصفحة
document.addEventListener('DOMContentLoaded', () => {
  // Reveal on scroll
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('in');
        io.unobserve(e.target);
      }
    });
  }, {threshold: 0.08});

  document.querySelectorAll('.auth-card, .auth-illustration .feat').forEach(el => io.observe(el));

  // Pulse on primary button for انتباه خفيف
  const primaryBtn = document.querySelector('.auth-btn');
  if (primaryBtn) {
    primaryBtn.classList.add('pulse');
    setTimeout(()=>primaryBtn.classList.remove('pulse'), 4000);
  }
});
document.addEventListener('DOMContentLoaded', () => {
  try { initAdsRotator(); } catch (e) { console.error(e); }
});