<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" dir="{{ app()->getLocale()==='ar'?'rtl':'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', __('app.admin')) — {{ config('app.name','Gemo') }}</title>

  {{-- ثيم قبل التحميل --}}
  <script>
    (function(){try{
      const s=localStorage.getItem('theme');
      const p=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;
      if(s==='dark'||(!s&&p)) document.documentElement.classList.add('dark');
    }catch(_){}})();
  </script>

  @vite(['resources/css/entries/admin.css','resources/js/app.js']) {{-- app.js يستورد admin.js --}}
  @stack('styles')
</head>
<body class="admin-body">
@include('partials.admin.icons-sprite')

  {{-- Sidebar --}}
  @include('partials.admin.sidebar')

  <div class="admin-wrap">
    {{-- Topbar --}}
    @include('partials.admin.topbar')

    {{-- Content --}}
    <main class="admin-content">
      @hasSection('content')
        @yield('content')
      @elseif(isset($slot))
        {{ $slot }}
      @endif
    </main>
  </div>

  {{-- Overlay للموبايل --}}
  <div class="admin-overlay" data-admin-overlay hidden></div>

  @stack('scripts')
</body>
</html>
