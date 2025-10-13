{{-- صفحة الدخول (لجميع المستخدمين) --}}
@push('styles')
  @vite('resources/css/entries/auth.css')
@endpush
@push('scripts')
  {{-- سكربت التأثيرات لصفحات المصادقة --}}
  @vite('resources/js/app.js')
@endpush
<x-guest-layout :hideHeaderLogo="true" :boxed="false">
  <div class="auth-shell">
    <div class="auth-grid">

      {{-- شريط شعار للموبايل فقط (يختفي على الشاشات الواسعة) --}}
      <div class="auth-topbar">
        <a href="{{ route('home') }}" class="brand">
          <x-application-logo />
          <span>{{ __('app.brand_name') }}</span>
        </a>
      </div>

      {{-- اللوحة التعريفية (تظهر على الشاشات الواسعة) --}}
      <aside class="auth-illustration">
        <div class="brand">
          <x-application-logo />
          <span>{{ __('app.brand_name') }}</span>
        </div>

        <h1 class="headline">
          <span>{{ __('app.welcome_back_title') }}</span>
        </h1>

        <p class="subhead">
          {{ __('app.welcome_back_subtitle') }}
        </p>

        <div class="features">
          <div class="feat">
            {{-- أيقونة سرعة --}}
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13 3L4 14h6l-1 7l9-11h-6l1-7Z"/></svg>
            <span>{{ __('app.feature_fast') }}</span>
          </div>
          <div class="feat">
            {{-- أيقونة قفل --}}
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M17 8h-1V6a4 4 0 1 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-6 7.73V17h2v-1.27a2 2 0 1 0-2 0ZM9 6a3 3 0 1 1 6 0v2H9Z"/></svg>
            <span>{{ __('app.feature_secure') }}</span>
          </div>
          <div class="feat">
            {{-- أيقونة مجلد/رفع --}}
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20 6h-8l-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2Zm-5 8h-2v3h-2v-3H9l3-3l3 3Z"/></svg>
            <span>{{ __('app.feature_attachments') }}</span>
          </div>
        </div>
      </aside>

      {{-- بطاقة نموذج الدخول --}}
      <section class="auth-card">
        <h2 class="auth-title">{{ __('app.login_title') }}</h2>
        <p class="auth-subtitle">{{ __('app.login_subtitle') }}</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 auth-alert success" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-3">
          @csrf

          <div class="field">
            <x-input-label for="email" :value="__('app.email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

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

          <div class="field">
            <label for="remember_me" class="inline-flex items-center gap-2">
              <input id="remember_me" type="checkbox" class="rounded" name="remember">
              <span class="text-sm">{{ __('app.remember_me') }}</span>
            </label>
          </div>

          <div class="auth-actions">
            <button type="submit" class="auth-btn">{{ __('app.login_button') }}</button>

            <div class="auth-links">
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('app.forgot_password') }}</a>
              @endif
              <a href="{{ route('register') }}">{{ __('app.create_account') }}</a>
            </div>
          </div>
        </form>
      </section>

    </div>
  </div>
</x-guest-layout>
