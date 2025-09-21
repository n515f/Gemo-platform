@push('styles')
  @vite('resources/css/auth.css')
@endpush

<x-guest-layout>
  <div class="auth-wrap">
    <div class="auth-card">
      <h1 class="rfq-title">{{ __('Register') }} <span>🧾</span></h1>

      <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <label class="auth-label" for="name">{{ __('Name') }}</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required
               class="auth-input @error('name') is-invalid @enderror">
        @error('name') <div class="auth-error">{{ $message }}</div> @enderror

        <label class="auth-label" for="email">{{ __('Email') }}</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
               class="auth-input @error('email') is-invalid @enderror">
        @error('email') <div class="auth-error">{{ $message }}</div> @enderror

        <label class="auth-label" for="password">{{ __('Password') }}</label>
        <input id="password" name="password" type="password" required
               class="auth-input @error('password') is-invalid @enderror">
        @error('password') <div class="auth-error">{{ $message }}</div> @enderror

        <label class="auth-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required class="auth-input">

        <button class="auth-submit mt-3" type="submit">{{ __('Create account') }}</button>

        <p class="auth-note">
          <a href="{{ route('login') }}">{{ __('Already registered? Log in') }}</a>
        </p>
      </form>
    </div>
  </div>
</x-guest-layout>