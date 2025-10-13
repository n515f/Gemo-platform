{{-- صفحة التسجيل (لجميع المستخدمين) --}}
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
        <h1 class="headline">{{ __('app.register_headline') }}</h1>
        <p class="subhead">{{ __('app.register_subtitle') }}</p>

        <div class="features">
          <div class="feat">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13 3L4 14h6l-1 7l9-11h-6l1-7Z"/></svg>
            <span>{{ __('app.feature_fast') }}</span>
          </div>
          <div class="feat">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M17 8h-1V6a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-6 7.73V17h2v-1.27a2 2 0 1 0-2 0ZM9 6a3 3 0 1 1 6 0v2H9Z"/></svg>
            <span>{{ __('app.feature_secure') }}</span>
          </div>
          <div class="feat">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20 6h-8l-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2Zm-5 8h-2v3h-2v-3H9l3-3l3 3Z"/></svg>
            <span>{{ __('app.feature_attachments') }}</span>
          </div>
        </div>
      </aside>

      {{-- بطاقة نموذج التسجيل --}}
      <section class="auth-card">
        <h2 class="auth-title">{{ __('app.register_title') }}</h2>
        <p class="auth-subtitle">{{ __('app.register_subtitle_small') }}</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-3">
          @csrf

          <div class="row row-2">
            <div class="field">
              <x-input-label for="name" :value="__('app.name')" />
              <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="field">
              <x-input-label for="email" :value="__('app.email')" />
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
          </div>

          <div class="row row-2">
            <div class="field">
              <x-input-label for="password" :value="__('app.password')" />
              <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="field">
              <x-input-label for="password_confirmation" :value="__('app.password_confirm')" />
              <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
          </div>

          <div class="auth-actions">
            <button class="auth-btn" type="submit">{{ __('app.register_button') }}</button>
            <div class="auth-links">
              <a href="{{ route('login') }}">{{ __('app.already_registered') }}</a>
            </div>
          </div>
        </form>
      </section>

    </div>
  </div>
</x-guest-layout>
