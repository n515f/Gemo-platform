{{-- التحقق من البريد الإلكتروني (لجميع المستخدمين) --}}
@push('styles')
  @vite('resources/css/entries/auth.css')
@push('scripts')
  {{-- سكربت التأثيرات لصفحات المصادقة --}}
  @vite('resources/js/app.js')
@endpush
  {{-- إخفاء شعار الهيدر في هذه الصفحة فقط --}}
  <style>
    header .app-logo-img,
    .site-header .app-logo-img { display: none !important; }
  </style>
@endpush

<x-guest-layout :hideHeaderLogo="true" :boxed="false">
  <div class="auth-shell">
    <div class="auth-grid">

      {{-- اللوحة التعريفية (تظهر على الشاشات الواسعة) --}}
      <aside class="auth-illustration">
        <div class="brand">
          <x-application-logo />
          <span>{{ __('app.brand_name') }}</span>
        </div>
        <h1 class="headline">{{ __('app.verify_headline') }}</h1>
        <p class="subhead">{{ __('app.verify_subtitle') }}</p>
      </aside>

      {{-- البطاقة --}}
      <section class="auth-card">
        <h2 class="auth-title">{{ __('app.verify_title') }}</h2>

        <div class="mb-4 text-sm auth-subtitle">
          {{ __('app.verify_intro') }}
        </div>

        @if (session('status') === 'verification-link-sent')
          <div class="auth-alert success">
            {{ __('app.verify_link_sent') }}
          </div>
        @endif

        <div class="auth-actions">
          <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="auth-btn" type="submit">{{ __('app.resend_verification') }}</button>
          </form>

          <div class="auth-links">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="auth-btn secondary">{{ __('app.logout') }}</button>
            </form>
          </div>
        </div>
      </section>

    </div>
  </div>
</x-guest-layout>
