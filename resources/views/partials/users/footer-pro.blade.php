{{-- resources/views/partials/footer-pro.blade.php --}}
@php
  $lang  = app()->getLocale();
  $isRtl = $lang === 'ar';
  $dir   = $isRtl ? 'rtl' : 'ltr';
  $y     = date('Y');

  // القراءة الآمنة من $site مع بدائل منطقية
  $brandPreferred = $isRtl ? ($site?->company_name_ar) : ($site?->company_name_en);
  $brandFallback  = $isRtl ? ($site?->company_name_en) : ($site?->company_name_ar);
  $brand          = $brandPreferred ?: ($brandFallback ?: config('app.name', 'Adel Saeed'));

  $taglinePreferred = $isRtl ? ($site?->company_tagline_ar) : ($site?->company_tagline_en);
  $taglineFallback  = $isRtl ? ($site?->company_tagline_en) : ($site?->company_tagline_ar);
  $tagline          = $taglinePreferred ?: ($taglineFallback ?: '');

  $logoPath = $site?->theme_logo_path;
  $logo     = $logoPath ? asset($logoPath) : asset('images/logo.png');

  $phoneRaw  = $site?->company_phone ?? '';
  $phone     = trim($phoneRaw);
  $phoneHref = preg_replace('/[^\d\+]/', '', $phone);

  $email     = $site?->company_email ?? '';
  $emailAlt  = $site?->company_email_alt ?? '';
  $address   = $isRtl ? ($site?->company_address_ar ?? '') : ($site?->company_address_en ?? '');
  $hours     = ''; // لاحقاً إن رغبت

  $waLink    = $site?->social_whatsapp_url ?? '';
  $wechat    = ''; // غير مستخدم حالياً
  $facebook  = $site?->social_facebook_url ?? '';
  $instagram = $site?->social_instagram_url ?? '';
  $xLink     = $site?->social_twitter_url ?? '';

  // التحويل بين اللغتين
  $langSwitchHref = route('lang.switch', $isRtl ? 'en' : 'ar');
  $langSwitchText = $isRtl ? __('app.english') : __('app.arabic');
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
        @if($tagline !== '')
          <p class="pro-tagline">{{ $tagline }}</p>
        @endif

        {{-- شارات --}}
        <ul class="pro-badges">
          <li>
            <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M12 3l8 4v6c0 5-3.8 8.4-8 9c-4.2-.6-8-4-8-9V7l8-4Zm-1 11.7L7.3 12.2l1.4-1.4L11 13l4.3-4.3l1.4 1.4L11 16.9Z"/></svg>
            <span>{{ __('app.badge_trusted') }}</span>
          </li>
          <li>
            <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M12 2a10 10 0 1 1 0 20a10 10 0 0 1 0-20m1 5h-2v6l5 3l1-1.7l-4-2.3z"/></svg>
            <span>{{ __('app.badge_fast') }}</span>
          </li>
          <li>
            <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M20 15.5c-1.2 0-2.4-.2-3.5-.7a1 1 0 0 0-1 .2l-1.6 1.6A14.7 14.7 0 0 1 7.4 9.6L9 8a1 1 0 0 0 .2-1c-.5-1.1-.7-2.3-.7-3.5A1.5 1.5 0 0 0 7 2H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3a1.5 1.5 0 0 0-1.5-1.5"/></svg>
            <span>{{ __('app.badge_support_247') }}</span>
          </li>
        </ul>

        {{-- شبكات التواصل --}}
        <div class="brand-socials" aria-label="{{ __('app.channels') }}">
          @if($waLink)
            <a class="bso" href="{{ $waLink }}" target="_blank" rel="noopener" title="{{ __('app.whatsapp') }}" aria-label="{{ __('app.whatsapp') }}">
              <img src="{{ asset('images/whatsapp.png') }}" alt="{{ __('app.whatsapp') }}">
            </a>
          @endif
          @if($email)
            <a class="bso" href="mailto:{{ $email }}" title="{{ __('app.gmail') }}" aria-label="{{ __('app.gmail') }}">
              <img src="{{ asset('images/icons/gmail.png') }}" alt="{{ __('app.gmail') }}">
            </a>
          @endif
          @if($emailAlt)
            <a class="bso" href="mailto:{{ $emailAlt }}" title="{{ __('app.yahoo') }}" aria-label="{{ __('app.yahoo') }}">
              <img src="{{ asset('images/icons/yahoo.png') }}" alt="{{ __('app.yahoo') }}">
            </a>
          @endif
          @if($wechat && $wechat !== '#')
            <a class="bso" href="{{ $wechat }}" target="_blank" rel="noopener" title="{{ __('app.wechat') }}" aria-label="{{ __('app.wechat') }}">
              <img src="{{ asset('images/icons/wechat.png') }}" alt="{{ __('app.wechat') }}">
            </a>
          @endif
          @if($facebook && $facebook !== '#')
            <a class="bso" href="{{ $facebook }}" target="_blank" rel="noopener" title="{{ __('app.facebook') }}" aria-label="{{ __('app.facebook') }}">
              <img src="{{ asset('images/icons/facebook.png') }}" alt="{{ __('app.facebook') }}">
            </a>
          @endif
          @if($instagram && $instagram !== '#')
            <a class="bso" href="{{ $instagram }}" target="_blank" rel="noopener" title="{{ __('app.instagram') }}" aria-label="{{ __('app.instagram') }}">
              <img src="{{ asset('images/instagram.png') }}" alt="{{ __('app.instagram') }}">
            </a>
          @endif
          @if($xLink && $xLink !== '#')
            <a class="bso" href="{{ $xLink }}" target="_blank" rel="noopener" title="{{ __('app.x') }}" aria-label="{{ __('app.x') }}">
              <img src="{{ asset('images/X.png') }}" alt="{{ __('app.x') }}">
            </a>
          @endif
        </div>
      </section>

      {{-- تواصل معنا --}}
      <section class="pro-foot-col">
        <h4 class="pro-col-title">{{ __('app.contact_us') }}</h4>
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
        <h4 class="pro-col-title">{{ __('app.information') }}</h4>
        <ul class="pro-links">
          <li><a href="{{ route('home') }}#about">{{ __('app.about_us') }}</a></li>
          <li><a href="{{ route('contact') }}">{{ __('app.contact_us') }}</a></li>
          <li><a href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a></li>
          <li><a href="{{ route('rfq.create') }}">{{ __('app.rfq') }}</a></li>
          <li><a href="{{ url('#faq') }}">{{ __('app.faq') }}</a></li>
        </ul>
      </section>
    </div>
  </div>

  {{-- الشريط السفلي --}}
  <div class="pro-footer-bottom">
    <div class="pro-footer-bottom-shell">
      <div class="pro-left-controls">
        <button id="themeIconBtnFooter" class="theme-btn" type="button" aria-label="{{ __('app.toggle_theme') }}">
          <img id="themeIconFooter" data-sun="{{ asset('images/sun.png') }}" data-moon="{{ asset('images/moon.png') }}" alt="" width="18" height="18">
        </button>
        <a class="lang-switch" href="{{ $langSwitchHref }}">{{ $langSwitchText }}</a>
      </div>

      <p class="pro-copy">© {{ $y }} {{ $brand }} — {{ __('app.rights') }}</p>

      <div class="pro-right">
        <div class="pro-quick">
          <a class="pro-btn call" href="tel:{{ $phoneHref }}">
            <svg viewBox="0 0 24 24" class="ico"><path fill="currentColor" d="M20 15.5c-1.2 0-2.4-.2-3.5-.7a1 1 0 0 0-1 .2l-1.6 1.6A14.7 14.7 0 0 1 7.4 9.6L9 8a1 1 0 0 0 .2-1c-.5-1.1-.7-2.3-.7-3.5A1.5 1.5 0 0 0 7 2H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3a1.5 1.5 0 0 0-1.5-1.5"/></svg>
            <span>{{ __('app.call') }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- أزرار عائمة --}}
  @if($waLink)
    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="fab fab-whats" aria-label="{{ __('app.whatsapp') }}">
      <img src="{{ asset('images/whatsapp.png') }}" alt="{{ __('app.whatsapp') }}">
    </a>
  @endif
  <a href="tel:{{ $phoneHref }}" class="fab fab-call" aria-label="{{ __('app.call') }}">
    <svg viewBox="0 0 24 24"><path fill="#fff" d="M20 15.5c-1.2 0-2.4-.2-3.5-.7a1 1 0 0 0-1 .2l-1.6 1.6A14.7 14.7 0 0 1 7.4 9.6L9 8a1 1 0 0 0 .2-1c-.5-1.1-.7-2.3-.7-3.5A1.5 1.5 0 0 0 7 2H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3a1.5 1.5 0 0 0-1.5-1.5"/></svg>
  </a>
</footer>
