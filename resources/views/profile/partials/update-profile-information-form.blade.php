<section class="pf-sec pf-sec--info">
    <header class="pf-secHeader">
        <h2 class="pf-secTitle">{{ __('app.profile_information') }}</h2>
        <p class="pf-secHelp">{{ __('app.profile_information_help') }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="pf-form">
        @csrf
        @method('patch')

        <div class="pf-field">
            <x-input-label for="name" :value="__('app.name')" />
            <x-text-input id="name" name="name" type="text" class="pf-input"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="pf-error" :messages="$errors->get('name')" />
        </div>

        <div class="pf-field">
            <x-input-label for="email" :value="__('app.email')" />
            <x-text-input id="email" name="email" type="email" class="pf-input"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="pf-error" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="pf-note">
                    <p class="pf-noteText">
                        {{ __('app.unverified_email') }}
                        <button form="send-verification" class="pf-link">
                            {{ __('app.resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="pf-successSmall">
                            {{ __('app.verification_link_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="pf-actions">
            <x-primary-button class="pf-btn pf-btn--primary">{{ __('app.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
