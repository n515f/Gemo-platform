{{-- resources/views/partials/footer-pro.blade.php --}}
@php
  use App\Models\Setting;

  // قارئ إعدادات مرن
  $get = function (string $key, $default = null) use ($settings) {
      if (isset($settings) && is_array($settings) && array_key_exists($key, $settings)) return $settings[$key];
      if (isset($settings) && $settings instanceof \Illuminate\Support\Collection) { $v = $settings->get($key); if(!is_null($v)) return $v; }
      return \App\Models\Setting::where('key',$key)->value('value') ?? $default;
  };

  $lang  = app()->getLocale();
  $isRtl = $lang === 'ar';
  $dir   = $isRtl ? 'rtl' : 'ltr';
  $y     = date('Y');

  // الهوية
  $brand   = $get('company.name_'.($isRtl ? 'ar' : 'en'), 'جيمو');
  $tagline = $get('company.tagline_'.($isRtl ? 'ar' : 'en'), __('app.tagline'));
  $logo    = $get('company.logo', asset('images/logo.png'));

  // تواصل
  $phoneRaw  = $get('company.phone', '+967 738 742 001');
  $phone     = trim($phoneRaw);
  $phoneHref = preg_replace('/[^\d\+]/', '', $phone);
  $email     = $get('company.email', 'info@example.com');
  $emailAlt  = $get('company.email_alt');
  $address   = $get('company.address_'.($isRtl ? 'ar' : 'en'), $isRtl ? 'صنعاء - اليمن' : 'Sanaa, Yemen');
  $hours     = $get('company.hours_'.($isRtl ? 'ar' : 'en'), $isRtl ? 'الأحد - الخميس: 9:00 ص - 6:00 م' : 'Sun - Thu: 9:00 AM - 6:00 PM');

  // سوشال (مع واتساب)
  $waLink    = $get('social.whatsapp',  'https://wa.me/967738742001');
  $wechat    = $get('social.wechat',    '#');
  $facebook  = $get('social.facebook',  '#');
  $instagram = $get('social.instagram', '#');
  $xLink     = $get('social.x',         $get('social.twitter', '#'));

  // تبديل اللغة
  $langSwitchHref = route('lang.switch', $isRtl ? 'en' : 'ar');
  $langSwitchText = $isRtl ? 'English' : 'العربية';
@endphp

<footer class="pro-footer" dir="{{ $dir }}">
  <div class="pro-footer-shell">
    <div class="pro-footer-grid">

      {{-- الشعار / الاسم / الأيقونات --}}
      <section class="pro-foot-col">
        <div class="brand-card">
          <img class="pro-logo" src="{{ $logo }}" alt="{{ $brand }}">
          <div class="brand-title">{{ $brand }}</div>
        </div>
        {{-- وصف مختصر --}}
        <p class="pro-tagline">{{ $tagline }}</p>
        {{-- شارات صغيرة في الأسفل --}}
      <ul class="pro-badges">
        <li>
          <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M12 3l8 4v6c0 5-3.8 8.4-8 9c-4.2-.6-8-4-8-9V7l8-4Zm-1 11.7L7.3 12.2l1.4-1.4L11 13l4.3-4.3l1.4 1.4L11 16.9Z"/></svg>
          <span>{{ $isRtl ? 'موثوق ومرخّص' : 'Trusted & Licensed' }}</span>
        </li>
        <li>
          <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M12 2a10 10 0 1 1 0 20a10 10 0 0 1 0-20m1 5h-2v6l5 3l1-1.7l-4-2.3z"/></svg>
          <span>{{ $isRtl ? 'سرعة في الإنجاز' : 'Fast Processing' }}</span>
        </li>
        <li>
          <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M20 15.5c-1.2 0-2.4-.2-3.5-.7a1 1 0 0 0-1 .2l-1.6 1.6A14.7 14.7 0 0 1 7.4 9.6L9 8a1 1 0 0 0 .2-1c-.5-1.1-.7-2.3-.7-3.5A1.5 1.5 0 0 0 7 2H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3a1.5 1.5 0 0 0-1.5-1.5"/></svg>
          <span>{{ $isRtl ? 'دعم 24/7' : '24/7 Support' }}</span>
        </li>
      </ul>
        {{-- أيقونات أسفل الشعار (خلفية موحّدة) --}}
        <div class="brand-socials" aria-label="{{ $isRtl ? 'قنوات التواصل' : 'Channels' }}">
          @if($waLink)
            <a class="bso" href="{{ $waLink }}" target="_blank" rel="noopener" title="WhatsApp" aria-label="WhatsApp">
              <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp">
            </a>
          @endif
          @if($email)
            <a class="bso" href="mailto:{{ $email }}" title="Gmail" aria-label="Gmail">
              <img src="{{ asset('images/icons/gmail.png') }}" alt="Gmail">
            </a>
          @endif
          @if($emailAlt)
            <a class="bso" href="mailto:{{ $emailAlt }}" title="Yahoo" aria-label="Yahoo">
              <img src="{{ asset('images/icons/yahoo.png') }}" alt="Yahoo">
            </a>
          @endif
          @if($wechat && $wechat !== '#')
            <a class="bso" href="{{ $wechat }}" target="_blank" rel="noopener" title="WeChat" aria-label="WeChat">
              <img src="{{ asset('images/icons/wechat.png') }}" alt="WeChat">
            </a>
          @endif
          @if($facebook && $facebook !== '#')
            <a class="bso" href="{{ $facebook }}" target="_blank" rel="noopener" title="Facebook" aria-label="Facebook">
              <img src="{{ asset('images/icons/facebook.png') }}" alt="Facebook">
            </a>
          @endif
          @if($instagram && $instagram !== '#')
            <a class="bso" href="{{ $instagram }}" target="_blank" rel="noopener" title="Instagram" aria-label="Instagram">
              <img src="{{ asset('images/instagram.png') }}" alt="Instagram">
            </a>
          @endif
          @if($xLink && $xLink !== '#')
            <a class="bso" href="{{ $xLink }}" target="_blank" rel="noopener" title="X" aria-label="X">
              <img src="{{ asset('images/X.png') }}" alt="X">
            </a>
          @endif
        </div>
        

        
      </section>

      {{-- تواصل معنا --}}
      <section class="pro-foot-col">
        <h4 class="pro-col-title">{{ $isRtl ? 'تواصل معنا' : 'Contact Us' }}</h4>
        <ul class="pro-contact">
          <li>
            <div class="ico-wrap">
              <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M20 15.5c-1.2 0-2.4-.2-3.5-.7a1 1 0 0 0-1 .2l-1.6 1.6A14.7 14.7 0 0 1 7.4 9.6L9 8a1 1 0 0 0 .2-1c-.5-1.1-.7-2.3-.7-3.5A1.5 1.5 0 0 0 7 2H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3a1.5 1.5 0 0 0-1.5-1.5"/></svg>
            </div>
            <a href="tel:{{ $phoneHref }}">{{ $phone }}</a>
          </li>
          <li>
            <div class="ico-wrap">
              <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4l-8 5L4 8V6l8 5l8-5z"/></svg>
            </div>
            <a href="mailto:{{ $email }}">{{ $email }}</a>
          </li>
          <li>
            <div class="ico-wrap">
              <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M12 2a7 7 0 0 1 7 7c0 5.3-7 13-7 13S5 14.3 5 9a7 7 0 0 1 7-7Zm0 9.5a2.5 2.5 0 1 0 0-5a2.5 2.5 0 0 0 0 5Z"/></svg>
            </div>
            <span>{{ $address }}</span>
          </li>
          <li>
            <div class="ico-wrap">
              <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M12 2a10 10 0 1 1 0 20a10 10 0 0 1 0-20m1 5h-2v6l5 3l1-1.7l-4-2.3z"/></svg>
            </div>
            <span>{{ $hours }}</span>
          </li>
        </ul>
      </section>

      {{-- خدماتنا --}}
      <section class="pro-foot-col">
        <h4 class="pro-col-title">{{ __('app.services') }}</h4>
        <ul class="pro-links">
          <li><a href="{{ route('services.index') }}#import">{{ __('app.service_import') }}</a></li>
          <li><a href="{{ route('services.index') }}#customs">{{ __('app.service_customs') }}</a></li>
          <li><a href="{{ route('services.index') }}#install">{{ __('app.service_installation') }}</a></li>
          <li><a href="{{ route('services.index') }}#training">{{ __('app.service_training') }}</a></li>
          <li><a href="{{ route('services.index') }}#full-line">{{ __('app.service_full_line') }}</a></li>
        </ul>
      </section>

      {{-- معلومات --}}
      <section class="pro-foot-col">
        <h4 class="pro-col-title">{{ $isRtl ? 'معلومات' : 'Information' }}</h4>
        <ul class="pro-links">
          <li><a href="{{ route('home') }}#about">{{ $isRtl ? 'من نحن' : 'About Us' }}</a></li>
          <li><a href="{{ route('contact') }}">{{ __('app.contact_us') }}</a></li>
          <li><a href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a></li>
          <li><a href="{{ route('rfq.create') }}">{{ __('app.rfq') }}</a></li>
          <li><a href="{{ url('#faq') }}">{{ $isRtl ? 'الأسئلة الشائعة' : 'FAQ' }}</a></li>
        </ul>
      </section>
    </div>
  </div>

  {{-- الشريط السفلي --}}
  <div class="pro-footer-bottom">
    <div class="pro-footer-bottom-shell">
      <div class="pro-left-controls">
        <button id="themeIconBtnFooter" class="theme-btn" type="button" aria-label="Toggle theme">
          <img id="themeIconFooter" data-sun="{{ asset('images/sun.png') }}" data-moon="{{ asset('images/moon.png') }}" alt="" width="18" height="18">
        </button>
        <a class="lang-switch" href="{{ $langSwitchHref }}">{{ $langSwitchText }}</a>
      </div>

      

      <p class="pro-copy">© {{ $y }} {{ $brand }} — {{ __('app.rights') }}</p>

      <div class="pro-right">
        <div class="pro-quick">
          <a class="pro-btn call" href="tel:{{ $phoneHref }}">
            <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M20 15.5c-1.2 0-2.4-.2-3.5-.7a1 1 0 0 0-1 .2l-1.6 1.6A14.7 14.7 0 0 1 7.4 9.6L9 8a1 1 0 0 0 .2-1c-.5-1.1-.7-2.3-.7-3.5A1.5 1.5 0 0 0 7 2H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3a1.5 1.5 0 0 0-1.5-1.5"/></svg>
            <span>{{ $isRtl ? 'اتصال' : 'Call' }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- أزرار عائمة --}}
  @if($waLink)
    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="fab fab-whats" aria-label="WhatsApp">
      <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp">
    </a>
  @endif
  <a href="tel:{{ $phoneHref }}" class="fab fab-call" aria-label="Call">
    <svg viewBox="0 0 24 24"><path fill="#fff" d="M20 15.5c-1.2 0-2.4-.2-3.5-.7a1 1 0 0 0-1 .2l-1.6 1.6A14.7 14.7 0 0 1 7.4 9.6L9 8a1 1 0 0 0 .2-1c-.5-1.1-.7-2.3-.7-3.5A1.5 1.5 0 0 0 7 2H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3a1.5 1.5 0 0 0-1.5-1.5"/></svg>
  </a>
</footer>
