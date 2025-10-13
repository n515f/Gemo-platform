{{-- إعادة تعيين كلمة المرور (لجميع المستخدمين) --}}
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
        <h1 class="headline">{{ __('app.reset_headline') }}</h1>
        <p class="subhead">{{ __('app.reset_subtitle') }}</p>
      </aside>

      {{-- بطاقة إعادة التعيين --}}
      <section class="auth-card">
        <h2 class="auth-title">{{ __('app.reset_title') }}</h2>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-3">
          @csrf
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <div class="field">
            <x-input-label for="email" :value="__('app.email')" />
            <x-text-input
              id="email"
              class="block mt-1 w-full"
              type="email"
              name="email"
              :value="old('email', $request->email)"
              required
              autofocus
              autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          <div class="row row-2">
            <div class="field">
              <x-input-label for="password" :value="__('app.password')" />
              <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password" />
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="field">
              <x-input-label for="password_confirmation" :value="__('app.password_confirm')" />
              <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password" />
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
          </div>

          <div class="auth-actions">
            <button class="auth-btn" type="submit">{{ __('app.reset_password_button') }}</button>
            <div class="auth-links">
              <a href="{{ route('login') }}">{{ __('app.back_to_login') }}</a>
            </div>
          </div>
        </form>
      </section>

    </div>
  </div>
</x-guest-layout>
