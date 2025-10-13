{{-- تأكيد كلمة المرور (لجميع المستخدمين) --}}
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
        <h1 class="headline">{{ __('app.confirm_headline') }}</h1>
        <p class="subhead">{{ __('app.confirm_subtitle') }}</p>
      </aside>

      {{-- البطاقة --}}
      <section class="auth-card">
        <h2 class="auth-title">{{ __('app.confirm_title') }}</h2>
        <p class="auth-subtitle">{{ __('app.confirm_intro') }}</p>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-3">
          @csrf

          <div class="field">
            <x-input-label for="password" :value="__('app.password')" />
            <x-text-input
              id="password"
              class="block mt-1 w-full"
              type="password"
              name="password"
              required
              autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          <div class="auth-actions">
            <button class="auth-btn" type="submit">{{ __('app.confirm_button') }}</button>
            <div class="auth-links">
              <a href="{{ route('login') }}">{{ __('app.back_to_login') }}</a>
            </div>
          </div>
        </form>
      </section>

    </div>
  </div>
</x-guest-layout>
