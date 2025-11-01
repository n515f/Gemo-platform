// resources/js/app.js
import './bootstrap';
import './admin';
import './catalog';
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

/* =============================== */
/* ===== كود سلايدر الإعلانات المصحح ===== */
/* =============================== */
function initAdsRotator() {
  const rotators = document.querySelectorAll(".ads-rotator");
  if (!rotators.length) return;

  rotators.forEach((rotator) => {
    if (rotator.dataset.inited === "1") return;
    rotator.dataset.inited = "1";

    const ads = Array.from(rotator.querySelectorAll(".ad"));
    if (ads.length === 0) return;

    // حمّل كل صور السلايدر مسبقًا (منع الشاشة البيضاء)
    const preloadImages = () => {
      const imagePromises = [];
      ads.forEach(ad => {
        const img = ad.querySelector(".ad-visual img");
        if (img && img.src) {
          img.loading = "eager";
          img.decoding = "auto";
          const promise = new Promise((resolve) => {
            const newImg = new Image();
            newImg.onload = resolve;
            newImg.onerror = resolve; // Continue even if error
            newImg.src = img.currentSrc || img.src;
          });
          imagePromises.push(promise);
        }
      });
      return Promise.all(imagePromises).catch(() => console.log('بعض صور الإعلانات فشلت في التحميل'));
    };

    // إعلان واحد: فعّله فقط
    if (ads.length === 1) {
      ads[0].classList.add("active");
      return;
    }

    const intervalSec = Math.max(parseInt(rotator.dataset.intervalSec || "8", 10), 2);

    // عناصر التحكم
    let dotsWrap = rotator.querySelector(".ads-dots");
    if (!dotsWrap) {
      dotsWrap = document.createElement("div");
      dotsWrap.className = "ads-dots";
      rotator.appendChild(dotsWrap);
    }
    
    // إزالة أي أسهم موجودة سابقاً
    const oldPrevButtons = rotator.querySelectorAll(".ads-nav.prev, .ads-prev");
    const oldNextButtons = rotator.querySelectorAll(".ads-nav.next, .ads-next");
    
    oldPrevButtons.forEach(btn => btn.remove());
    oldNextButtons.forEach(btn => btn.remove());
    
    // إنشاء أسهم جديدة
    const prevBtn = document.createElement("button");
    prevBtn.className = "ads-nav prev";
    prevBtn.type = "button";
    prevBtn.innerHTML = "‹";
    prevBtn.setAttribute("aria-label", "الإعلان السابق");
    rotator.appendChild(prevBtn);
    
    const nextBtn = document.createElement("button");
    nextBtn.className = "ads-nav next";
    nextBtn.type = "button";
    nextBtn.innerHTML = "›";
    nextBtn.setAttribute("aria-label", "الإعلان التالي");
    rotator.appendChild(nextBtn);

    // النقاط
    dotsWrap.innerHTML = "";
    const dots = ads.map((_, index) => {
      const dot = document.createElement("button");
      dot.type = "button";
      if (index === 0) dot.classList.add("active");
      dot.setAttribute("aria-label", `انتقل إلى الإعلان ${index + 1}`);
      dotsWrap.appendChild(dot);
      return dot;
    });

    let current = 0;
    let autoplayId = null;

    function setActive(i, prev) {
      if (i < 0 || i >= ads.length) return; // تحقق من صحة الفهرس
      
      if (prev != null && ads[prev]) {
        ads[prev].classList.remove("active");
        ads[prev].classList.add("out-left");
        setTimeout(() => ads[prev]?.classList.remove("out-left"), 600);
      }
      ads[i].classList.add("active");
      dots.forEach((d, k) => d.classList.toggle("active", k === i));
      current = i;
    }

    function gotoSlide(index) {
      if (index < 0) index = ads.length - 1;
      if (index >= ads.length) index = 0;
      const prev = current;
      if (index === prev) return;
      setActive(index, prev);
      restartAutoplay();
    }

    const next = () => gotoSlide(current + 1);
    const prev = () => gotoSlide(current - 1);

    function startAutoplay() {
      stopAutoplay();
      autoplayId = setInterval(next, intervalSec * 1000);
    }
    function stopAutoplay() {
      if (autoplayId) { clearInterval(autoplayId); autoplayId = null; }
    }
    function restartAutoplay() { stopAutoplay(); startAutoplay(); }

    // أحداث التحكم
    nextBtn.addEventListener("click", () => gotoSlide(current + 1));
    prevBtn.addEventListener("click", () => gotoSlide(current - 1));
    dots.forEach((dot, i) => dot.addEventListener("click", () => gotoSlide(i)));

    // إيقاف عند اختفاء التبويب فقط (لا توقف بمجرد hover حتى ما يتجمد)
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) stopAutoplay(); else startAutoplay();
    });
    window.addEventListener("blur", stopAutoplay);
    window.addEventListener("focus", startAutoplay);

    // بدء بعد تحميل الصور
    preloadImages().then(() => {
      setActive(0, null);
      startAutoplay();
    }).catch(() => {
      // في حالة فشل تحميل الصور، ابدأ على أي حال
      setActive(0, null);
      startAutoplay();
    });
  });
}


document.addEventListener('DOMContentLoaded', () => {
  try { 
    initAdsRotator(); 
  } catch(e) { 
    console.error('Ads rotator error:', e); 
  }
  
  try { 
    initRevealOnScroll(); 
  } catch(e) {
    console.error('Reveal on scroll error:', e);
  }

  const topbar = document.querySelector('.topbar');
  if (topbar) {
    const onScroll = () => {
      if (window.scrollY > 6) {
        topbar.classList.add('scrolled');
      } else {
        topbar.classList.remove('scrolled');
      }
    };
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
  
  themeBtns.forEach(btn => {
    if (btn) {
      btn.addEventListener('click', toggleTheme);
    }
  });

  prefersDark.addEventListener('change', e => {
    const saved = localStorage.getItem('theme');
    if (saved !== 'dark' && saved !== 'light') {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  });

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