// resources/js/ads.js
export default function initAdsRotator() {
  const rotators = document.querySelectorAll(".ads-rotator");
  if (!rotators.length) return;

  rotators.forEach(rotator => {
    const ads = Array.from(rotator.querySelectorAll(".ad"));
    if (!ads.length) return;

    const intervalSec = parseInt(rotator.dataset.intervalSec || "20", 10);
    const fitMode = String(rotator.dataset.fit || 'cover').toLowerCase();

    // ضبط ملاءمة الصور
    ads.forEach(ad => {
      const img = ad.querySelector('.ad-visual img');
      if (img) {
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = (fitMode === 'center' || fitMode === 'contain') ? 'contain' : 'cover';
        img.style.objectPosition = 'center';
        img.setAttribute('draggable', 'false');
      }
    });

    // أسهم + نقاط (إن لم تكن موجودة)
    let prevBtn = rotator.querySelector(".ads-nav.prev");
    let nextBtn = rotator.querySelector(".ads-nav.next");
    if (!prevBtn || !nextBtn) {
      prevBtn = document.createElement("button");
      nextBtn = document.createElement("button");
      prevBtn.className = "ads-nav prev"; prevBtn.textContent = "‹";
      nextBtn.className = "ads-nav next"; nextBtn.textContent = "›";
      rotator.appendChild(prevBtn); rotator.appendChild(nextBtn);
    }
    const dotsWrap = document.createElement("div");
    dotsWrap.className = "ads-dots";
    rotator.appendChild(dotsWrap);

    let current = ads.findIndex(a => a.classList.contains('active'));
    if (current < 0) current = 0;

    // بناء النقاط
    ads.forEach((_, idx) => {
      const btn = document.createElement("button");
      if (idx === current) btn.classList.add("active");
      btn.addEventListener("click", () => { showAd(idx); resetTimer(); });
      dotsWrap.appendChild(btn);
    });

    function setActiveDot(i) {
      dotsWrap.querySelectorAll("button").forEach((d, k) => d.classList.toggle("active", k === i));
    }

    function showAd(index) {
      ads.forEach((ad, i) => {
        ad.classList.remove("active", "out-left");
        if (i === index) ad.classList.add("active");
        else if (i === current) ad.classList.add("out-left");
      });
      setActiveDot(index);
      current = index;
    }

    function nextAd() { showAd((current + 1) % ads.length); }
    function prevAd() { showAd((current - 1 + ads.length) % ads.length); }

    prevBtn.addEventListener("click", () => { prevAd(); resetTimer(); });
    nextBtn.addEventListener("click", () => { nextAd(); resetTimer(); });

    let timer;
    function resetTimer() { clearInterval(timer); timer = setInterval(nextAd, intervalSec * 1000); }

    // إيقاف/تشغيل عند المرور
    const pause  = () => clearInterval(timer);
    const resume = () => resetTimer();
    [rotator, dotsWrap, prevBtn, nextBtn].forEach(el => {
      el.addEventListener("mouseenter", pause);
      el.addEventListener("mouseleave", resume);
    });

    // تفعيل البداية دائمًا
    if (!ads.some(a => a.classList.contains('active'))) {
      ads[0].classList.add('active');
      current = 0;
    }
    showAd(current);
    resetTimer();
  });
}
