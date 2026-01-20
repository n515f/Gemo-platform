<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale()==='ar'?'rtl':'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600;700;800&display=swap" rel="stylesheet">

  {{-- منع وميض الثيم --}}
  <script>
    (function(){try{
      const s=localStorage.getItem('theme');
      const p=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;
      if(s==='dark'||(!s&&p)) document.documentElement.classList.add('dark');
    }catch(_){}})();
  </script>

  <link rel="icon" type="image/x-icon" href="{{ asset('favicon-nuxt.ico') }}">
  <link rel="shortcut icon" href="{{ asset('favicon-nuxt.ico') }}">
  @vite(['resources/css/entries/auth.css','resources/js/app.js'])
  @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
  <div class="min-h-screen">
     @include('layouts.navigation')

     @isset($header)
      <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
     @endisset

     <main class="py-6">
       {{ $slot }}
     </main>
  </div>

  @stack('scripts')
</body>
</html>
