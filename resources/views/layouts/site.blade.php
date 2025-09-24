@php
  $isRtl = app()->getLocale() === 'ar';
  $dir   = $isRtl ? 'rtl' : 'ltr';
  $lang  = app()->getLocale();

  $brand = $settings['company.name_'.($lang === 'ar' ? 'ar' : 'en')] ?? __('app.brand');
  $tag   = $settings['company.tagline_'.($lang === 'ar' ? 'ar' : 'en')] ?? __('app.tagline');

  $logoPath    = $settings['company.logo']    ?? 'images/logo.png';
  $managerPath = $settings['company.manager'] ?? 'images/manager.png';

  $darkDefault = (($settings['ui.dark_mode_default'] ?? 'false') === 'true');

  // الصورة حسب نوع المستخدم
  $avatar = asset('images/returning-visitor.png');
  if (auth()->check()) {
      $avatar = auth()->user()->hasRole('admin')
                ? (auth()->user()->profile_photo_url ?? asset($managerPath))
                : asset('images/user.png');
  }
@endphp

<!doctype html>
<html lang="{{ $lang }}" dir="{{ $dir }}" data-dark-default="{{ $darkDefault ? '1' : '0' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $brand }}</title>
  {{-- الخط --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600;700;800&display=swap" rel="stylesheet">

  {{-- منع وميض الثيم --}}
  <script>
    (function () {
      try {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDefaultDark = document.documentElement.dataset.darkDefault === '1';
        if (saved === 'dark' || (!saved && (isDefaultDark || prefersDark))) {
          document.documentElement.classList.add('dark');
        }
      } catch (_) {}
    })();
  </script>
  {{-- حزمة الموقع --}}
  @vite(['resources/css/entries/site.css','resources/js/app.js'])
  @stack('styles')
</head>

<body class="app">
  {{-- ======= الهيدر ======= --}}
  <header class="topbar">
    <div class="shell no-wrap">
      {{-- الشعار (جهة البداية حسب الاتجاه) --}}
      <div class="right-logo">
        <a href="{{ route('home') }}" class="brand-logo-link" aria-label="Logo">
          <img src="{{ asset($logoPath) }}" alt="Logo" class="logo-big" loading="lazy">
        </a>
      </div>

      {{-- شريط التنقل (يحوي الهمبرغر وأزرار المستخدم/الثيم) --}}
      @include('layouts.navigation')

      {{-- بطاقة المستخدم (ديسكتوب فقط؛ تُخفى في الموبايل عبر CSS) --}}
      <div class="brand-left only-desktop">
        <img src="{{ $avatar }}" class="manager-img" alt="Avatar" loading="lazy">
        <div class="brand-text">
          @auth
            <a href="{{ route('home') }}" class="title">{{ Auth::user()->name }}</a>
            <small>{{ Auth::user()->email }}</small>
          @else
            <span class="title">{{ __('مرحبًا، زائر') }}</span>
            <small><a class="get-started-link" href="{{ route('login') }}">{{ __('ابدأ الآن') }}</a></small>
          @endauth
        </div>
      </div>
    </div>
  </header>

  {{-- ======= المحتوى ======= --}}
  <main class="container">
    @yield('hero')
    @yield('content')
  </main>

  {{-- ======= الفوتر ======= --}}
  <footer class="footer footer-triple">
    <div class="left-controls">
      <button id="themeIconBtnMobile" class="toggle icon theme-btn" type="button" aria-label="Toggle theme">
        <img id="themeIcon" src="{{ asset('images/moon.png') }}" alt="" width="18" height="18">
      </button>

      @if($lang === 'ar')
        <a class="lang" href="{{ route('lang.switch','en') }}">English</a>
      @else
        <a class="lang" href="{{ route('lang.switch','ar') }}">العربية</a>
      @endif
    </div>

    <div class="copy center">© {{ date('Y') }} {{ $brand }} — {{ __('app.rights') }}</div>

    <div class="socials right">
      <a class="social" href="{{ $settings['social.whatsapp']  ?? '#' }}" target="_blank" rel="noopener">
        <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp">
      </a>
      <a class="social" href="{{ $settings['social.instagram'] ?? '#' }}" target="_blank" rel="noopener">
        <img src="{{ asset('images/instagram.png') }}" alt="Instagram">
      </a>
    </div>
  </footer>

  @stack('scripts')
</body>
</html>