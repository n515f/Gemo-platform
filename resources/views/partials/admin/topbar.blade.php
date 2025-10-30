@php
  $u = auth()->user();
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  $avatar = $u?->profile_image
    ? (Str::startsWith($u->profile_image, ['http', '/storage'])
        ? $u->profile_image
        : Storage::url($u->profile_image))
    : asset('images/avatar.png');

  $isRtl = app()->getLocale()==='ar';
  $swapTo = $isRtl ? 'en' : 'ar';
@endphp

<header class="admin-topbar">
  <div class="tb-left">
    {{-- زر القائمة (الوحيد) — ظاهر على كل المقاسات --}}
    <button class="icon-btn menu-btn" data-admin-open aria-label="Open menu">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-menu"/></svg>
    </button>

    {{-- اسم الدور + الإسم + صورة --}}
    <div class="user-mini">
      <div class="meta">
        <strong class="name">{{ $u->name ?? 'admin' }}</strong>
        <small class="role">{{ $u?->role?->name ?? 'Admin' }}</small>
      </div>
      <img class="avatar" src="{{ $avatar }}" alt="avatar">
    </div>
  </div>

  <h1 class="page-title">@yield('title', __('app.admin'))</h1>

  <div class="tb-right">
    {{-- تبديل الثيم --}}
    <button id="themeIconBtn" class="icon-btn theme-btn" type="button" aria-label="Toggle theme">
      <svg id="iconSun" class="ico" width="18" height="18" aria-hidden="true"><use href="#i-sun"/></svg>
      <svg id="iconMoon" class="ico" width="18" height="18" aria-hidden="true" style="display:none"><use href="#i-moon"/></svg>
    </button>

    {{-- تبديل اللغة --}}
    <a class="icon-btn lang-btn" href="{{ route('lang.switch',$swapTo) }}" title="{{ $isRtl ? 'English' : 'العربية' }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-globe"/></svg>
      <span class="lang-text">{{ $isRtl ? 'EN' : 'AR' }}</span>
    </a>
  </div>
</header>
