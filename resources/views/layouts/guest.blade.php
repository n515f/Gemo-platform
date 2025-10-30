@props([
  'hideHeaderLogo' => false,  // لإخفاء شعار الهيدر عند الحاجة
  'boxed' => false            // لاحتواء المحتوى في كرت افتراضي (سلوك Breeze القديم)
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale()==='ar'?'rtl':'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600;700;800&display=swap" rel="stylesheet">

  <script>
    (function(){try{
      const s=localStorage.getItem('theme');
      const p=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;
      if(s==='dark'||(!s&&p)) document.documentElement.classList.add('dark');
    }catch(_){}})();
  </script>

  {{-- الأوث تدفع auth.css عبر @push من صفحاتها --}}
  @vite(['resources/js/app.js'])
  @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900">

  {{-- هيدر الشعار (اختياري) --}}
  @unless($hideHeaderLogo)
    <header class="py-6 flex justify-center">
      <a href="/" aria-label="Home">
        <x-application-logo class="app-logo-img w-20 h-20 fill-current text-gray-500" />
      </a>
    </header>
  @endunless

  <main>
    @if($boxed)
      {{-- سلوك Breeze القديم بكرت وسطي --}}
      <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
          {{ $slot }}
        </div>
      </div>
    @else
      {{-- صفحات فل-بليد مثل صفحات الأوث الجديدة --}}
      {{ $slot }}
    @endif
  </main>

  @stack('scripts')
</body>
</html>
