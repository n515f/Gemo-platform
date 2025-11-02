// resources/js/pages/catalog.js
(() => {
  "use strict";

  const onReady = (fn) => {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  };

  onReady(() => {
    // ===== Slider داخل كل بطاقة =====
    document.querySelectorAll('[data-slider]').forEach(slider => {
      const track  = slider.querySelector('.track');
      const slides = Array.from(slider.querySelectorAll('.slide'));
      if (!track || slides.length === 0) return;

      // إنشاء نقاط التنقل للسلايدر
      const dotsContainer = slider.querySelector('.slider-dots');
      if (dotsContainer && slides.length > 1) {
        slides.forEach((_, idx) => {
          const dot = document.createElement('button');
          dot.className = 'dot' + (idx === 0 ? ' active' : '');
          dot.setAttribute('type', 'button');
          dot.setAttribute('aria-label', `Slide ${idx + 1}`);
          dot.addEventListener('click', () => go(idx));
          dotsContainer.appendChild(dot);
        });
      }

      let i = 0, tmr = null;
      const go = (idx) => { 
        i = (idx + slides.length) % slides.length; 
        track.style.transform = `translateX(-${i*100}%)`; 
        
        // تحديث النقاط النشطة
        if (dotsContainer) {
          dotsContainer.querySelectorAll('.dot').forEach((dot, dotIdx) => {
            dot.classList.toggle('active', dotIdx === i);
          });
        }
      };
      
      const next = () => go(i - 1);
      const prev = () => go(i + 1);

      slider.querySelector('[data-next]')?.addEventListener('click', (e)=>{ e.preventDefault(); next(); });
      slider.querySelector('[data-prev]')?.addEventListener('click', (e)=>{ e.preventDefault(); prev(); });

      // استخدام الفاصل الزمني من البيانات أو الافتراضي (5 دقائق = 300 ثانية)
      const intervalSec = parseInt(slider.dataset.intervalSec || '300', 10);
      const intervalMs = intervalSec * 1000;
      
      const start = () => { stop(); tmr = setInterval(next, intervalMs); };
      const stop  = () => { if (tmr) { clearInterval(tmr); tmr = null; } };

      slider.addEventListener('mouseenter', stop);
      slider.addEventListener('mouseleave', start);
      start();
    });

    // ===== RFQ Modal =====
    const $back    = document.getElementById('rfqBackdrop');
    const $modal   = document.getElementById('rfqModal');
    const $form    = document.getElementById('rfqForm');
    const $close   = document.getElementById('rfqClose');
    const $cancel  = document.getElementById('rfqCancel');
    const $openBtns= document.querySelectorAll('.js-open-rfq');
    const $prodId  = document.getElementById('rfqProductId');
    const $prodName= document.getElementById('rfqProdName');
    const $prodMeta= document.getElementById('rfqProdMeta');
    const $wa      = document.getElementById('rfqWhats');

    if (!$modal || !$back) return;

    const open = () => {
      $back.hidden = false; $modal.hidden = false;
      requestAnimationFrame(()=>{
        $back.classList.add('open'); $modal.classList.add('open');
        document.body.style.overflow = 'hidden';
      });
    };
    const close = () => {
      $back.classList.remove('open'); $modal.classList.remove('open');
      setTimeout(()=>{ $back.hidden = true; $modal.hidden = true; document.body.style.overflow = ''; }, 220);
    };

    const labels = {
      ask:     $modal.dataset.lblAsk     || 'Ask for a quote',
      product: $modal.dataset.lblProduct || 'Product',
      qty:     $modal.dataset.lblQty     || 'Quantity',
      name:    $modal.dataset.lblName    || 'Name',
      phone:   $modal.dataset.lblPhone   || 'Phone',
      email:   $modal.dataset.lblEmail   || 'Email',
    };

    const buildWhatsLink = () => {
      const phone = ($modal.dataset.whats || '').trim(); // لو فارغ، يفتح واتساب بدون رقم
      const data  = new FormData($form);
      const msg =
`${labels.ask}:
- ${labels.product}: ${($prodName.textContent || '').trim()}
- ${labels.qty}: ${data.get('quantity') || '1'}
- ${labels.name}: ${data.get('name') || ''}
- ${labels.phone}: ${data.get('phone') || ''}
- ${labels.email}: ${data.get('email') || ''}

${data.get('message') || ''}`;
      const url = new URL('https://wa.me/' + encodeURIComponent(phone));
      url.searchParams.set('text', msg);
      $wa.href = url.toString();
    };

    document.addEventListener('input', (e)=>{
      if ($modal.hidden) return;
      if (e.target.closest('#rfqForm')) buildWhatsLink();
    });

    $openBtns.forEach(btn=>{
      btn.addEventListener('click', (e)=>{
        e.preventDefault();
        const name = btn.dataset.name || '';
        const code = btn.dataset.code || '';
        const sku  = btn.dataset.sku  || '';
        $prodId.value = btn.dataset.id || '';
        $prodName.textContent = name;
        $prodMeta.textContent = [code ? `Code: ${code}` : '', sku ? `SKU: ${sku}` : ''].filter(Boolean).join(' — ');
        buildWhatsLink();
        open();
      });
    });

    $close?.addEventListener('click', close);
    $cancel?.addEventListener('click', close);
    $back?.addEventListener('click', close);
    document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') close(); });
  });
})();
