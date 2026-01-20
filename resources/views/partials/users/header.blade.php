{{-- resources/views/partials/header.blade.php --}}

@php
  $isRtl       = app()->getLocale() === 'ar';
  $dir         = $isRtl ? 'rtl' : 'ltr';
  $isAdminArea = request()->routeIs('admin.*');
  $user        = auth()->user();

  $rawAvatar = null;
  if ($user && !empty($user->profile_image)) {
    $rawAvatar = \Illuminate\Support\Facades\Storage::url($user->profile_image);
  }
  $hasAvatar  = !empty($rawAvatar);
  $avatarUrl  = $hasAvatar ? $rawAvatar : null;
@endphp

<nav class="topbar" x-data="{ open:false, userMenu:false }" dir="{{ $dir }}">
  <div class="shell">

    {{-- همبرغر (موبايل/تابلت فقط) --}}
    <button
      class="hamburger md-only"
      :class="{ 'open': open }"
      @click="open = !open"
      aria-label="Toggle menu"
      :aria-expanded="open">
      <span></span><span></span><span></span>
    </button>

    {{-- الشعار --}}
    <div class="brand-left right-logo">
      <a href="{{ route('home') }}" class="brand-logo-link" aria-label="{{ config('app.name') }}">
        <img class="logo-big" src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
      </a>
      <div class="only-desktop">
        <div class="brand-text">
          <span class="title">{{ config('app.name') }}</span>
        </div>
      </div>
    </div>

    {{-- الوسط/اليمين --}}
    <div class="topnav">

      {{-- روابط الديسكتوب --}}
      <div class="center-nav desktop-nav">
        @unless($isAdminArea)
          <a class="pill {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('app.home') }}</a>
          <a class="pill {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">{{ __('app.categories') }}</a>
          <a class="pill {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">{{ __('app.services') }}</a>
          <a class="pill {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a>
          <a class="pill {{ request()->routeIs('rfq.*') ? 'active' : '' }}" href="{{ route('rfq.create') }}">{{ __('app.rfq') }}</a>
          <a class="pill {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('app.contact_us') }}</a>
        @endunless
      </div>

      {{-- أزرار اليمين --}}
      <div class="user-actions">
        {{-- تبديل الثيم --}}
        <button id="themeIconBtnDesktop" class="theme-btn clean-btn" type="button" aria-label="Theme">
          <svg id="themeIconDesktop" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </button>

        {{-- اللغة --}}
        @if(app()->getLocale() === 'ar')
          <a class="pill clean-btn" href="{{ route('lang.switch','en') }}">{{ __('app.english_short') }}</a>
        @else
          <a class="pill clean-btn" href="{{ route('lang.switch','ar') }}">{{ __('app.arabic_short') }}</a>
        @endif

        @guest         
@guest
  <a href="{{ route('login') }}" class="pill cta md-only">{{ __('app.start_now') }}</a>      {{-- موبايل --}}
@endguest
        @else
          {{-- زر يفتح قائمة المستخدم (لا يُخفي الاسم/الإيميل) --}}
          <div class="user-menu-wrap">
              <button
                  type="button"
                  class="avatar-wrap only-desktop"
                  @click="userMenu = !userMenu"
                  @keydown.escape.window="userMenu=false"
                  :aria-expanded="userMenu"
                  aria-haspopup="menu"
              >
                  @if($hasAvatar)
                    <img class="manager-img user-photo" src="{{ $avatarUrl }}" alt="{{ $user->name }}">
                  @else
                    <span class="user-photo user-photo--icon" aria-hidden="true">
                      <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M4 19.2a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                  @endif
              
                  <div class="brand-text brand-text--user">
                    <span class="title title--clip">{{ \Illuminate\Support\Str::limit($user->name, 24) }}</span>
                    <small class="email email--clip">{{ $user->email }}</small>
                  </div>
              </button>
          
              {{-- قائمة المستخدم (ديسكتوب) — مثبتة أسفل الصورة + النص --}}
              <div
                class="user-menu"
                x-show="userMenu"
                x-transition.opacity.scale.origin.top
                @click.outside="userMenu=false"
                x-cloak
                role="menu"
              >
                <a href="{{ route('profile.edit') }}" class="user-menu__item" role="menuitem">
                  <svg class="ico" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.8" fill="none"/>
                    <path d="M4 19.2a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                  </svg>
                  {{ __('app.profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}" role="none">
                  @csrf
                  <button type="submit" class="user-menu__item user-menu__item--danger" role="menuitem">
                    <svg class="ico" viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M15 17l5-5-5-5M20 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                    {{ __('app.logout') }}
                  </button>
                </form>
              </div>
          </div>

          {{-- موبايل: صورة/أيقونة فقط تنتقل للبروفايل --}}
          <a href="{{ route('profile.edit') }}" class="user-chip md-only" aria-label="Open profile">
            @if($hasAvatar)
              <img class="chip-avatar" src="{{ $avatarUrl }}" alt="{{ $user->name }}">
            @else
              <span class="chip-avatar chip-avatar--icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M4 19.2a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
              </span>
            @endif
          </a>
        @endguest
      </div>
    </div>

    {{-- قائمة الموبايل --}}
    <div id="mobileNav" class="mobile-menu" x-show="open" x-transition.opacity.scale.origin.top @click.outside="open=false" x-cloak>
      @auth
        <div class="mobile-user">
          @if($hasAvatar)
            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}">
          @else
            <span class="chip-avatar chip-avatar--icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.8"/>
                <path d="M4 19.2a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
              </svg>
            </span>
          @endif
          <div>
            <div class="name">{{ \Illuminate\Support\Str::limit($user->name, 26) }}</div>
            <div class="email ltr">{{ $user->email }}</div>
          </div>
        </div>
@else
  <div class="mobile-user">
    <span class="chip-avatar chip-avatar--icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <circle cx="12" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.8"/>
        <path d="M4 19.2a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
      </svg>
    </span>
    <div>
      <div class="name">{{ __('app.welcome_guest') }}</div>
      <div class="email">{{ __('app.start_now') }}</div>
    </div>
  </div>
@endauth


      <a class="item {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('app.home') }}</a>
      <a class="item" href="{{ route('categories.index') }}">{{ __('app.categories') }}</a>
      <a class="item" href="{{ route('services.index') }}">{{ __('app.services') }}</a>
      <a class="item" href="{{ route('catalog.index') }}">{{ __('app.catalog') }}</a>
      <a class="item" href="{{ route('rfq.create') }}">{{ __('app.rfq') }}</a>
      <a class="item" href="{{ route('contact') }}">{{ __('app.contact_us') }}</a>

      <div class="row-split" style="margin-top:.25rem">
        <button id="themeIconBtnMobile" class="theme-btn clean-btn" type="button" aria-label="Theme">
          <svg id="themeIconMobile" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </button>
        @if(app()->getLocale() === 'ar')
          <a class="lang-switch" href="{{ route('lang.switch', $isRtl ? 'en' : 'ar') }}">
            {{ $isRtl ? __('app.language_en') : __('app.language_ar') }}
          </a>
        @else
          <a class="lang" href="{{ route('lang.switch','ar') }}">{{ __('app.arabic') }}</a>
        @endif
      </div>

      @guest
        <a class="item start" href="{{ route('login') }}">{{ __('app.start_now') }}</a>
      @else
        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <button type="submit" class="item danger flex items-center gap-2">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M15 17l5-5-5-5M20 12H9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>{{ __('app.logout') }}</span>
          </button>
        </form>
      @endguest
    </div>
  </div>
</nav>
