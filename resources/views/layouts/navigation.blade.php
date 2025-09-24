{{-- resources/views/layouts/navigation.blade.php --}}
@php
  $isRtl       = app()->getLocale() === 'ar';
  $dir         = $isRtl ? 'rtl' : 'ltr';
  $isAdminArea = request()->routeIs('admin.*');   // هل نحن داخل لوحات الأدمن؟
@endphp

<nav x-data="{ open:false }" class="topnav rounded-[18px]" dir="{{ $dir }}">
  {{-- زر همبرغر (موبايل) --}}
  <button
    class="hamburger md-hide"
    :class="{ 'open': open }"
    @click="open = !open"
    aria-label="Toggle menu"
    :aria-expanded="open ? 'true' : 'false'">
    <span></span><span></span><span></span>
  </button>

  {{-- روابط الديسكتوب --}}
  <div class="mainnav desktop-nav">
    
    <div class="item row-split">
      
    @if(!$isAdminArea)
      <a class="pill {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('app.home') }}</a>
      <a class="pill {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">{{ __('app.categories') }}</a>
      <a class="pill {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">{{ __('app.services') }}</a>
      <a class="pill {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a>
      <a class="pill {{ request()->routeIs('rfq.*') ? 'active' : '' }}" href="{{ route('rfq.create') }}">{{ __('app.rfq') }}</a>
      <a class="pill {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('app.contact_us') }}</a>

      @auth
        {{-- لو المستخدم Admin --}}
        @role('admin')
          <a class="pill {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">{{ __('app.admin') }}</a>
        @endrole

        {{-- لو المستخدم فني --}}
        @role('technician')
          <a class="pill {{ request()->routeIs('reports.create') ? 'active' : '' }}" href="{{ route('reports.create') }}">إنشاء تقرير</a>
          <a class="pill {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">تقاريري</a>
        @endrole
      @endauth
    @else
      <a class="pill {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">{{ __('app.admin') }}</a>
      <a class="pill {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">{{ __('app.categories') }}</a>
      <a class="pill {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">{{ __('app.projects') }}</a>
      <a class="pill {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">{{ __('app.reports') }}</a>
      <a class="pill {{ request()->routeIs('admin.rfqs.*') ? 'active' : '' }}" href="{{ route('admin.rfqs.index') }}">{{ __('app.rfqs') }}</a>
      <a class="pill {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">{{ __('app.catalog') }}</a>
      <a class="pill {{ request()->routeIs('admin.screens.*') ? 'active' : '' }}" href="{{ route('admin.screens.ClientPortal') }}">{{ __('app.ClientPortal') }}</a>
      <a class="pill {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}" href="{{ route('admin.ads.index') }}">{{ __('app.ads') }}</a>
    @endif
  </div>

  {{-- إجراءات المستخدم --}}
  <div class="user-actions">
    @guest
      <a class="pill btn-start only-desktop" href="{{ route('login') }}">{{ __('app.start_now') }}</a>
    @else
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button id="themeIconBtnMobile" class="toggle icon theme-btn" type="button" aria-label="Toggle theme">
        <img id="themeIconMobile" src="{{ asset('images/moon.png') }}" alt="" width="18" height="18">
      </button>
        <button type="submit" class="logout-icon" title="{{ __('app.logout') }}" aria-label="{{ __('app.logout') }}">
          <img src="{{ asset('images/logout.png') }}" alt="">
        </button>
      </form>
    @endguest
  </div>

  {{-- قائمة الموبايل --}}
  <div class="mobile-menu md-hide"
       x-cloak
       x-show="open"
       x-transition.opacity
       x-transition.duration.150ms
       @click.outside="open=false">

    {{-- بطاقة المستخدم --}}
    <div class="mobile-user">
      @php
        $avatar = asset('images/returning-visitor.png');
        $name   = __('app.guest_hello');
        $email  = __('app.start_now');

        if(auth()->check()){
          $name  = auth()->user()->name;
          $email = auth()->user()->email;
          $avatar = auth()->user()->hasRole('admin')
            ? (auth()->user()->profile_photo_url ?? asset('images/manager.png'))
            : asset('images/user.png');
        }
      @endphp
      <img src="{{ $avatar }}" alt="avatar">
      <div class="info">
        <div class="name">{{ $name }}</div>
        <div class="email ltr">{{ $email }}</div>
      </div>
    </div>

    {{-- روابط القائمة --}}
    @if(!$isAdminArea)
      <a class="item {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('app.home') }}</a>
      <a class="item {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">{{ __('app.services') }}</a>
      <a class="item {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a>
      <a class="item {{ request()->routeIs('rfq.*') ? 'active' : '' }}" href="{{ route('rfq.create') }}">{{ __('app.rfq') }}</a>
      <a class="item {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('app.contact_us') }}</a>

      @auth
        @role('admin')
          <a class="item" href="{{ route('admin.dashboard') }}">{{ __('app.admin') }}</a>
        @endrole

        @role('technician')
          <a class="item {{ request()->routeIs('reports.create') ? 'active' : '' }}" href="{{ route('reports.create') }}">إنشاء تقرير</a>
          <a class="item {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">تقاريري</a>
        @endrole
      @endauth
    @else
      <a class="item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">{{ __('app.admin') }}</a>
      <a class="pill {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">{{ __('app.categories') }}</a>
      <a class="item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">{{ __('app.projects') }}</a>
      <a class="item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">{{ __('app.reports') }}</a>
      <a class="item {{ request()->routeIs('admin.rfqs.*') ? 'active' : '' }}" href="{{ route('admin.rfqs.index') }}">{{ __('app.rfqs') }}</a>
      <a class="item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">{{ __('app.catalog') }}</a>
      <a class="item {{ request()->routeIs('admin.screens.*') ? 'active' : '' }}" href="{{ route('admin.screens.ClientPortal') }}">
        {{ __('app.ClientPortal') }}
      </a>
      <a class="item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}" href="{{ route('admin.ads.index') }}">
        {{ __('app.ads') }}
      </a>
    @endif

    {{-- الثيم + اللغة (موبايل فقط) --}}
    <div class="item row-split">
      <button id="themeIconBtnMobile" class="toggle icon theme-btn" type="button" aria-label="Toggle theme">
        <img id="themeIconMobile" src="{{ asset('images/moon.png') }}" alt="" width="18" height="18">
      </button>
      @if(app()->getLocale()==='ar')
        <a class="lang" href="{{ route('lang.switch','en') }}">English</a>
      @else
        <a class="lang" href="{{ route('lang.switch','ar') }}">العربية</a>
      @endif
    </div>

    {{-- دخول/خروج --}}
    @guest
      <a class="item start" href="{{ route('login') }}">{{ __('app.start_now') }}</a>
    @else
      <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <button type="submit" class="item danger">{{ __('app.logout') }}</button>
      </form>
    @endguest
  </div>
</nav>