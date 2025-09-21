import './bootstrap';
import './admin'; // سكريبتات لوحة التحكم

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  const topbar = document.querySelector('.topbar');
  if (topbar) {
    const onScroll = () => (window.scrollY > 6 ? topbar.classList.add('scrolled') : topbar.classList.remove('scrolled'));
    onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
  }

  // ===== تبديل الثيم (يُستخدم في الفوتر وقائمة الموبايل فقط) =====
  const html = document.documentElement;
  const iconPaths = { sun:'/images/sun.png', moon:'/images/moon.png' };

  const setIcon = (imgEl) => {
    if (!imgEl) return;
    const isDark = html.classList.contains('dark');
    imgEl.src = isDark ? iconPaths.sun : iconPaths.moon;
  };

  const toggleTheme = () => {
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    setIcon(document.getElementById('themeIcon'));
    setIcon(document.getElementById('themeIconMobile'));
  };

  // أزرار الثيم (الفوتر + الموبايل)
  const footBtn   = document.getElementById('themeIconBtn');
  const mobBtn    = document.getElementById('themeIconBtnMobile');
  if (footBtn) footBtn.addEventListener('click', toggleTheme);
  if (mobBtn)  mobBtn.addEventListener('click', toggleTheme);

  // تحديث الأيقونات عند التحميل
  setIcon(document.getElementById('themeIcon'));
  setIcon(document.getElementById('themeIconMobile'));
});