// resources/js/ads.js
// سلايدر إعلانات: النص ثابت، والصورة تتبدّل كل 5 ثواني داخل الإعلان نفسه.
// ينتقل إلى الإعلان التالي كل (data-interval) ملّي ثانية.

export default function initAdsRotator() {
  const wrap = document.querySelector('.ads-rotator');
  if (!wrap) return;

  const adInterval = parseInt(wrap.dataset.interval || '7000', 10); // مدة كل إعلان
  const fade       = parseInt(wrap.dataset.fade || '500', 10);      // مدة الذوبان بين الصور
  const ads        = Array.from(wrap.querySelectorAll('.ad'));
  if (!ads.length) return;

  // base للصور النسبية (إن احتجتها)
  const assetBaseMeta = document.querySelector('meta[name="asset-base"]');
  const base = (assetBaseMeta?.content || '/').replace(/\/+$/, '/') ;

  let currentAd  = 0;
  let imageTimer = null;

  function stopImageCycle() {
    if (imageTimer) { clearInterval(imageTimer); imageTimer = null; }
  }

  function startImageCycle(adEl) {
    stopImageCycle();
    let imgs = [];
    try {
      imgs = JSON.parse(adEl.getAttribute('data-images') || '[]') || [];
    } catch (_) {
      imgs = [];
    }
    if (!imgs.length) return;

    const imgTag = adEl.querySelector('.ad-visual img');
    let i = 0;

    imageTimer = setInterval(() => {
      i = (i + 1) % imgs.length;
      imgTag.style.opacity = '0';
      setTimeout(() => {
        const next = imgs[i];
        const nextSrc = /^https?:\/\//i.test(next) ? next : (base + next.replace(/^\/+/, ''));
        imgTag.src = nextSrc;
        imgTag.style.opacity = '1';
      }, fade);
    }, 5000);
  }

  function showAd(idx) {
    ads.forEach((ad, k) => ad.classList.toggle('active', k === idx));
    startImageCycle(ads[idx]);
  }

  // بدء التشغيل
  showAd(currentAd);
  setInterval(() => {
    currentAd = (currentAd + 1) % ads.length;
    showAd(currentAd);
  }, adInterval);
}