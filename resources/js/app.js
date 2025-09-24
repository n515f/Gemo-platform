// resources/js/app.js
import './bootstrap';
import './admin';               // سكربتات لوحة التحكم
import initAdsRotator from './ads';
import initRevealOnScroll from './services'; // <<< إضافة الاستيراد

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/* -------------------- الثيم -------------------- */

// مسارات أيقونات الثيم الافتراضية (يمكن استبدالها عبر data-*)
const ICONS = { sun: '/images/sun.png', moon: '/images/moon.png' };

// مراجع مساعدة
const html = document.documentElement;
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

// رجّع الثيم الحالي (المحفوظ أو المفضّل من النظام)
function currentTheme() {
  const saved = localStorage.getItem('theme');
  if (saved === 'dark' || saved === 'light') return saved;
  return prefersDark.matches ? 'dark' : 'light';
}

// حدّث أيقونات أزرار الثيم بحسب الوضع الحالي
function updateThemeIcons() {
  const isDark = html.classList.contains('dark');
  const targets = [
    document.getElementById('themeIcon'),
    document.getElementById('themeIconMobile'),
    document.getElementById('themeIconDesktop'),
    ...Array.from(document.querySelectorAll('.theme-btn img')),
  ].filter(Boolean);

  targets.forEach(img => {
    const sun  = img.dataset.sun  || ICONS.sun;
    const moon = img.dataset.moon || ICONS.moon;
    img.src = isDark ? sun : moon;
  });
}

// طبّق الثيم
function applyTheme(theme, { persist = false } = {}) {
  const isDark = theme === 'dark';
  html.classList.toggle('dark', isDark);
  if (persist) localStorage.setItem('theme', isDark ? 'dark' : 'light');
  updateThemeIcons();
}

// بدّل الثيم يدوياً
function toggleTheme() {
  const next = html.classList.contains('dark') ? 'light' : 'dark';
  applyTheme(next, { persist: true });
}

/* -------------------- تشغيل عند التحميل -------------------- */

document.addEventListener('DOMContentLoaded', () => {
  // 1) سلايدر الإعلانات (إن وُجد)
  initAdsRotator();

  // 2) تأثير التوب بار عند التمرير
  const topbar = document.querySelector('.topbar');
  if (topbar) {
    const onScroll = () => {
      if (window.scrollY > 6) topbar.classList.add('scrolled');
      else topbar.classList.remove('scrolled');
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // 3) تهيئة الثيم وتوصيل أزراره
  applyTheme(currentTheme());

  const btns = new Set([
    document.getElementById('themeIconBtn'),        // فوتر
    document.getElementById('themeIconBtnMobile'),  // موبايل
    document.getElementById('themeIconBtnDesktop'), // ديسكتوب (إن وُجد)
    ...document.querySelectorAll('.theme-btn'),     // أي زر يحمل هذه الكلاس
  ].filter(Boolean));
  btns.forEach(btn => btn.addEventListener('click', toggleTheme));

  // لو المستخدم ما اختار ثيم يدويًا، اتبع النظام تلقائيًا
  prefersDark.addEventListener('change', e => {
    const saved = localStorage.getItem('theme');
    if (saved !== 'dark' && saved !== 'light') {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  });

  // 4) إظهار الأقسام بتأثير عند التمرير
  initRevealOnScroll(); // <<< الاستدعاء بعد التحميل

  // 5) تأكيد للحذف لأي form يحمل data-confirm
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (form && form.matches('form[data-confirm]')) {
      const msg = form.getAttribute('data-confirm') || 'Are you sure?';
      if (!window.confirm(msg)) {
        e.preventDefault();
        e.stopPropagation();
      }
    }
  });
});