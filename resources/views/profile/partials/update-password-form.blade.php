<section class="pf-sec pf-sec--password">
    <header class="pf-secHeader">
        <h2 class="pf-secTitle">{{ __('app.update_password') }}</h2>
        <p class="pf-secHelp">{{ __('app.update_password_help') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="pf-form">
        @csrf
        @method('put')

        <div class="pf-field">
            <x-input-label for="update_password_current_password" :value="__('app.current_password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                          class="pf-input" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="pf-error" />
        </div>

        <div class="pf-field">
            <x-input-label for="update_password_password" :value="__('app.new_password')" />
            <x-text-input id="update_password_password" name="password" type="password"
                          class="pf-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="pf-error" />
        </div>

        <div class="pf-field">
            <x-input-label for="update_password_password_confirmation" :value="__('app.confirm_password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                          class="pf-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="pf-error" />
        </div>

        <div class="pf-actions">
            <x-primary-button class="pf-btn pf-btn--primary">{{ __('app.save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="pf-saved">
                   {{ __('app.saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
