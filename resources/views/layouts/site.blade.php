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

 
</head>

<body class="app">
  {{-- ======= الهيدر ======= --}}
    @include('partials.users.header')
  

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
  @include('partials.users.footer-pro')

  {{-- عبر Vite --}}
  @vite(['resources/js/app.js'])
  {{-- سكريبت تغيير الصورة --}}
  @stack('scripts')
</body>
</html>
