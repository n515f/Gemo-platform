{{-- resources/views/layouts/site.blade.php --}}
@php
  $isRtl = app()->getLocale() === 'ar';
  $dir   = $isRtl ? 'rtl' : 'ltr';
  $lang  = app()->getLocale();

  $brand = $settings['company.name_'.($lang === 'ar' ? 'ar' : 'en')] ?? __('app.brand');
  $tag   = $settings['company.tagline_'.($lang === 'ar' ? 'ar' : 'en')] ?? __('app.tagline');

  $logoPath    = $settings['company.logo']    ?? 'images/logo.png';
  $managerPath = $settings['company.manager'] ?? 'images/manager.png';

  $darkDefault = (($settings['ui.dark_mode_default'] ?? 'false') === 'true');

  // الصورة حسب المستخدم
  $avatar = asset('images/returning-visitor.png');
  if (auth()->check()) {
      $avatar = auth()->user()->profile_photo_url ?? asset('images/user.png');
  }
@endphp

<!doctype html>
<html lang="{{ $lang }}" dir="{{ $dir }}" data-dark-default="{{ $darkDefault ? '1' : '0' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $brand }}</title>

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600;700;800&display=swap" rel="stylesheet">

  {{-- منع وميض الثيم قبل تحميل JS --}}
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

  {{-- عبر Vite --}}
  @vite(['resources/css/entries/site.css','resources/js/app.js'])
  @stack('styles')

  {{-- ستايل خفيف للـ Bottom Sheet الخاص بصورة البروفايل --}}
  <style>
    .avatar-wrap{ position:relative; display:flex; align-items:center; gap:10px; }
    .avatar-btn{ position:relative; border:none; background:transparent; padding:0; cursor:pointer; }
    .avatar-btn img.manager-img{ width:42px; height:42px; border-radius:12px; object-fit:cover; display:block; }
    .avatar-btn .edit-badge{
      position:absolute; inset:auto -6px -6px auto; width:22px; height:22px; border-radius:50%;
      background:var(--brand,#38bdf8); display:grid; place-items:center; box-shadow:0 4px 10px rgba(0,0,0,.2);
    }
    .avatar-btn .edit-badge svg{ width:13px; height:13px; color:#0b1220; }

    .sheet-backdrop{
      position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:60; opacity:0; pointer-events:none;
      transition:opacity .2s ease;
    }
    .sheet{ position:fixed; left:0; right:0; bottom:-340px; z-index:61;
      background:var(--card,#fff); border-radius:16px 16px 0 0; border:1px solid var(--ring,#e2e8f0);
      padding:12px; box-shadow:0 -20px 40px rgba(0,0,0,.25);
      transition:bottom .25s cubic-bezier(.22,.61,.36,1);
    }
    .sheet.open{ bottom:0; }
    .sheet-backdrop.open{ opacity:1; pointer-events:auto; }
    .sheet .bar{ width:42px; height:4px; border-radius:999px; background:var(--ring,#e2e8f0); margin:4px auto 10px; }
    .sheet .item{
      display:flex; align-items:center; gap:10px; width:100%; padding:12px 10px;
      border-radius:12px; border:1px solid var(--ring,#e2e8f0); background:var(--soft,#f8fafc);
      font-weight:800; cursor:pointer; text-align:start;
    }
    .sheet .item + .item{ margin-top:8px; }
    .sheet .item.danger{ background:#fee2e2; color:#991b1b; border-color:#fecaca; }
    .sheet .item svg{ width:18px; height:18px; }
    .only-desktop{ display:none; }
    @media (min-width: 900px){ .only-desktop{ display:block; } }
  </style>
</head>

<body class="app">
  {{-- ======= الهيدر ======= --}}
  <header class="topbar">
    <div class="shell no-wrap">
      <div class="right-logo">
        <a href="{{ route('home') }}" class="brand-logo-link" aria-label="Logo">
          <img src="{{ asset($logoPath) }}" alt="Logo" class="logo-big" loading="lazy">
        </a>
      </div>

      {{-- شريط التنقل --}}
      @include('layouts.navigation')

      {{-- بطاقة المستخدم (ديسكتوب) + زر الصورة --}}
      <div class="brand-left only-desktop">
        <div class="avatar-wrap">
          <button class="avatar-btn js-avatar-btn" type="button" aria-label="Change avatar" @guest disabled @endguest>
            <img src="{{ $avatar }}" class="manager-img" alt="Avatar" loading="lazy">
            @auth
              <span class="edit-badge" title="{{ __('تعديل الصورة') }}">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41L18.37 3.29a.9959.9959 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
              </span>
            @endauth
          </button>
        </div>

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

  {{-- ======= Bottom Sheet (للمستخدم المُسجّل) ======= --}}
  @auth
    <div class="sheet-backdrop js-avatar-sheet-backdrop" hidden></div>
    <div class="sheet js-avatar-sheet" hidden>
      <div class="bar"></div>

      <button class="item js-avatar-choose">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 20h14v-2H5v2zm7-18l-5.5 5.5h3.5V15h4V7.5H17L12 2z"/></svg>
        {{ __('اختيار صورة من الجهاز') }}
      </button>

      <button class="item js-avatar-camera">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 5h-3.17L15 3H9L7.17 5H4c-1.1 0-2 .9-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2zm-8 13a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/></svg>
        {{ __('التقاط صورة بالكاميرا') }}
      </button>

      {{-- حذف الصورة --}}
<form class="js-avatar-delete" method="POST" action="{{ route('profile.avatar.destroy') }}">
  @csrf
  @method('DELETE')
  <button type="submit" class="item danger" style="width:100%">
    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
      <path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1z"/>
    </svg>
    <span>{{ __('حذف الصورة') }}</span>
  </button>
</form>
      <button class="item js-avatar-cancel">{{ __('إلغاء') }}</button>

      {{-- حقول الرفع المخفية --}}
      <form id="avatarUploadForm" method="POST" action="{{ route('profile.avatar.store') }}" enctype="multipart/form-data" hidden>
        @csrf
        <input type="file" name="avatar" id="avatarFile" accept="image/*">
      </form>
      <input type="file" id="avatarCamera" accept="image/*" capture="environment" hidden>
    </div>
  @endauth

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