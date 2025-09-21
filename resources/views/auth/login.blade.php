{{-- صفحة الدخول --}}
@push('styles')
  @vite('resources/css/auth.css')
@endpush

<x-guest-layout>
  <div class="auth-wrap">
    <div class="auth-card">
      <h1 class="rfq-title">{{ __('Log in') }} <span>🔐</span></h1>

      @if (session('status'))
        <div class="auth-alert success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <label class="auth-label" for="email">{{ __('Email') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
               class="auth-input @error('email') is-invalid @enderror">
        @error('email') <div class="auth-error">{{ $message }}</div> @enderror

        <label class="auth-label" for="password">{{ __('Password') }}</label>
        <input id="password" type="password" name="password" required
               class="auth-input @error('password') is-invalid @enderror">
        @error('password') <div class="auth-error">{{ $message }}</div> @enderror

        <label class="auth-check mt-2">
          <input type="checkbox" name="remember"> <span>{{ __('Remember me') }}</span>
        </label>

        <button class="auth-submit mt-3" type="submit">{{ __('Log in') }}</button>

        <p class="auth-note">
          <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
          &nbsp;•&nbsp;
          <a href="{{ route('register') }}">{{ __('Register') }}</a>
        </p>
      </form>
    </div>
  </div>
</x-guest-layout>