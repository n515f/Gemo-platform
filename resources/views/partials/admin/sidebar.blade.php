@php
  $u = auth()->user();
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;
  use Illuminate\Support\Facades\Route;

  $avatar = $u?->profile_image
    ? (Str::startsWith($u->profile_image, ['http', '/storage'])
        ? $u->profile_image
        : Storage::url($u->profile_image))
    : null; // بدون صورة: سنعرض أيقونة i-admin

  $settingsUrl = Route::has('admin.settings.index')
      ? route('admin.settings.index')
      : (Route::has('admin.settings') ? route('admin.settings') : url('/admin/settings'));

  $clientPortalUrl = Route::has('admin.screens.client-portal')
      ? route('admin.screens.client-portal')
      : (Route::has('admin.client-portal')
          ? route('admin.client-portal')
          : url('/admin/screens/client-portal'));

  $isSettingsActive = request()->routeIs('admin.settings*') || request()->is('admin/settings*');
  $isClientPortalActive = request()->routeIs('admin.screens.client-portal*') ||
                          request()->routeIs('admin.client-portal*') ||
                          request()->is('admin/screens/client-portal*');
@endphp

<aside class="admin-sidebar" data-admin-sidebar data-collapsed="1">
  <div class="sidebar-header">
    <div class="brand">
      <img class="brand-mark" src="{{ asset('images/logo.png') }}" alt="logo">
      <strong class="title show-when-expanded">{{ config('app.name','Adel Platform') }}</strong>
    </div>
    {{-- تم إخفاء/حذف زري الإغلاق والطي حسب الطلب --}}
  </div>

  <div class="sidebar-user show-when-expanded">
    <div class="u-left">
      @if($avatar)
        <img class="u-avatar" src="{{ $avatar }}" alt="{{ __('app.avatar_alt') }}">
      @else
        <svg class="u-avatar ico" width="32" height="32" aria-label="{{ __('app.admin_icon_alt') }}"><use href="#i-admin"/></svg>
      @endif
    </div>
    <div class="u-right">
      <div class="u-name">{{ $u->name }}</div>
      <div class="u-email">{{ $u->email }}</div>
    </div>
  </div>

  <nav class="sidebar-nav" data-admin-nav>
    <a class="item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-dashboard"/></svg>
      <span>{{ __('app.admin') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-categories"/></svg>
      <span>{{ __('app.categories') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-catalog"/></svg>
      <span>{{ __('app.catalog') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-projects"/></svg>
      <span>{{ __('app.projects') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-reports"/></svg>
      <span>{{ __('app.reports') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.rfqs.*') ? 'active' : '' }}" href="{{ route('admin.rfqs.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-rfq"/></svg>
      <span>{{ __('app.rfqs') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}" href="{{ route('admin.ads.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-ads"/></svg>
      <span>{{ __('app.ads') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-users"/></svg>
      <span>{{ __('app.user') }}</span>
    </a>

    <a class="item {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-edit"/></svg>
      <span>{{ __('app.Profile') }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.screens.*') ? 'active' : ''}}" href="{{ route('admin.screens.ClientPortal') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-portal"/></svg>
      <span>{{ __('app.client_portal') ?: 'Client Portal' }}</span>
    </a>

    <a class="item {{ request()->routeIs('admin.settings.*') ? 'active' : ''}}" href="{{ route('admin.settings.index') }}">
      <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-settings"/></svg>
      <span>{{ __('app.settings') ?: 'Settings' }}</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="item item-btn needs-confirm" data-confirm="{{ __('app.logout') }}?">
      @csrf
      <button class="btn-like" type="submit">
        <svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-logout"/></svg>
        <span>{{ __('app.logout') }}</span>
      </button>
    </form>
  </nav>
</aside>
