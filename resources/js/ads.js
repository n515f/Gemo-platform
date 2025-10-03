// resources/js/ads.js
export default function initAdsRotator() {
  const wrap = document.querySelector('.ads-rotator');
  if (!wrap) return;

  // --- قراءة الإعدادات العامة ---
  const toMs = (min, sec, ms) => {
    if (!isNaN(min)) return Number(min) * 60_000;
    if (!isNaN(sec)) return Number(sec) * 1_000;
    return Number(ms) || 7_000; // افتراضي 7 ثواني
  };

  const adInterval = toMs(
    parseFloat(wrap.dataset.intervalMin),
    parseFloat(wrap.dataset.intervalSec),
    parseFloat(wrap.dataset.interval)
  );

  const fade = parseInt(wrap.dataset.fade || '500', 10);

  const ads = Array.from(wrap.querySelectorAll('.ad'));
  if (!ads.length) return;

  const base = document.querySelector('meta[name="asset-base"]')?.content || '/';

  let current = 0;
  let timer = null;
  let imageTimer = null;

  function perAdDuration(idx) {
    const el = ads[idx];
    return toMs(
      parseFloat(el?.dataset.durationMin),
      parseFloat(el?.dataset.durationSec),
      parseFloat(el?.dataset.duration)
    ) || adInterval;
  }

  function stopImageCycle() {
    if (imageTimer) { clearInterval(imageTimer); imageTimer = null; }
  }

  function startImageCycle(adEl) {
    stopImageCycle();
    let imgs;
    try { imgs = JSON.parse(adEl.getAttribute('data-images') || '[]') || []; }
    catch { imgs = []; }
    if (!imgs.length) return;

    const imgTag = adEl.querySelector('.ad-visual img');
    if (!imgTag) return;

    let i = 0;
    imageTimer = setInterval(() => {
      i = (i + 1) % imgs.length;
      imgTag.style.opacity = '0';
      setTimeout(() => {
        const nextSrc = (imgs[i] || '').startsWith('http') ? imgs[i] : (base + imgs[i]);
        imgTag.src = nextSrc;
        imgTag.style.opacity = '1';
      }, fade);
    }, 5000);
  }

  function showAd(next) {
    const prev = current;
    if (next === prev) return;

    // خرج اليسار للإعلان السابق
    ads[prev].classList.remove('active');
    ads[prev].classList.add('out-left');

    // دخول للإعلان الجديد
    ads[next].classList.remove('out-left');
    ads[next].classList.add('active');

    current = next;
    startImageCycle(ads[current]);

    // نظّف كلاس الخروج بعد نهاية الحركة حتى لا يتراكم
    setTimeout(() => ads[prev]?.classList.remove('out-left'), 600);
  }

  function startLoop() {
    stopLoop();
    const run = () => {
      const next = (current + 1) % ads.length;
      showAd(next);
      timer = setTimeout(run, perAdDuration(next)); // المدة القادمة حسب الإعلان التالي
    };
    // أظهر الأول وثبّت التوقيت
    ads[current].classList.add('active');
    startImageCycle(ads[current]);
    timer = setTimeout(run, perAdDuration(current));
  }

  function stopLoop() {
    if (timer) { clearTimeout(timer); timer = null; }
  }

  // ابدأ
  startLoop();

  // إيقاف عند إخفاء التبويب لتوفير الموارد
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) { stopLoop(); stopImageCycle(); }
    else { startLoop(); }
  });
  document.addEventListener("DOMContentLoaded", () => {
  const rotators = document.querySelectorAll(".ads-rotator");

  rotators.forEach(rotator => {
    const ads = rotator.querySelectorAll(".ad");
    if (!ads.length) return;

    // إنشاء الحاوية الخاصة بالنقاط
    const dotsWrap = document.createElement("div");
    dotsWrap.className = "ads-dots";
    rotator.appendChild(dotsWrap);

    // إنشاء النقاط حسب عدد الإعلانات
    ads.forEach((_, idx) => {
      const btn = document.createElement("button");
      if (idx === 0) btn.classList.add("active"); // أول نقطة مفعلة
      btn.addEventListener("click", () => {
        showAd(idx);
        resetTimer(); // إعادة المؤقت عند الضغط
      });
      dotsWrap.appendChild(btn);
    });

    let current = 0;
    const intervalSec = parseInt(rotator.dataset.intervalSec || "20", 10);
    const fadeMs = parseInt(rotator.dataset.fade || "500", 10);
    let timer;

    function showAd(index) {
      ads.forEach((ad, i) => {
        ad.classList.remove("active", "out-left");
        if (i === index) {
          ad.classList.add("active");
        } else if (i === current) {
          ad.classList.add("out-left");
        }
      });
      const dots = dotsWrap.querySelectorAll("button");
      dots.forEach((d, i) => d.classList.toggle("active", i === index));
      current = index;
    }

    function nextAd() {
      const next = (current + 1) % ads.length;
      showAd(next);
    }

    function resetTimer() {
      clearInterval(timer);
      timer = setInterval(nextAd, intervalSec * 1000);
    }

    // أول تشغيل
    showAd(0);
    resetTimer();
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const rotators = document.querySelectorAll(".ads-rotator");

  rotators.forEach(rotator => {
    const ads = rotator.querySelectorAll(".ad");
    if (!ads.length) return;

    // ---- وضع ملاءمة الصورة: cover | center/contain (افتراضي cover) ----
    const fitMode = String(rotator.dataset.fit || 'cover').toLowerCase();

    // تأكد أن كل صورة تأخذ مساحة الحاوية وتطبّق object-fit المناسب
    ads.forEach(ad => {
      const img = ad.querySelector('.ad-visual img');
      if (!img) return;
      img.style.width = '100%';
      img.style.height = '100%';
      img.style.objectFit = (fitMode === 'center' || fitMode === 'contain') ? 'contain' : 'cover';
      img.style.objectPosition = 'center center';
      // لمنع سحب الصورة الافتراضي على بعض المتصفحات
      img.setAttribute('draggable', 'false');
    });

    // ======= إنشاء النقاط (Indicators) =======
    const dotsWrap = document.createElement("div");
    dotsWrap.className = "ads-dots";
    rotator.appendChild(dotsWrap);

    ads.forEach((_, idx) => {
      const btn = document.createElement("button");
      if (idx === 0) btn.classList.add("active");
      btn.addEventListener("click", () => {
        showAd(idx);
        resetTimer();
      });
      dotsWrap.appendChild(btn);
    });

    let current = 0;
    const intervalSec = parseInt(rotator.dataset.intervalSec || "20", 10);
    const fadeMs = parseInt(rotator.dataset.fade || "500", 10); // احتياطي إن احتجته للتحريك
    let timer;

    function showAd(index) {
      ads.forEach((ad, i) => {
        ad.classList.remove("active", "out-left");
        if (i === index) {
          ad.classList.add("active");
        } else if (i === current) {
          ad.classList.add("out-left");
        }
      });
      const dots = dotsWrap.querySelectorAll("button");
      dots.forEach((d, i) => d.classList.toggle("active", i === index));
      current = index;
    }

    function nextAd() {
      const next = (current + 1) % ads.length;
      showAd(next);
    }

    function resetTimer() {
      clearInterval(timer);
      timer = setInterval(nextAd, intervalSec * 1000);
    }

    // تشغيل أولي
    showAd(0);
    resetTimer();
  });
});
}