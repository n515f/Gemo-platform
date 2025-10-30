<section class="pf-sec pf-sec--danger">
    <header class="pf-secHeader">
        <h2 class="pf-secTitle">
            {{ __('app.delete_account') }}
        </h2>

        <p class="pf-secHelp">
            {{ __('app.delete_account_help') }}
        </p>
    </header>

    <x-danger-button
        class="pf-btn pf-btn--danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('app.delete_account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="pf-form pf-form--modal">
            @csrf
            @method('delete')

            <h2 class="pf-modalTitle">
                {{ __('app.confirm_delete_title') }}
            </h2>

            <p class="pf-secHelp">
                {{ __('app.confirm_delete_help') }}
            </p>

            <div class="pf-field">
                <x-input-label for="password" :value="__('app.password')" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="pf-input pf-input--modal"
                    placeholder="{{ __('app.password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="pf-error" />
            </div>

            <div class="pf-modalActions">
                <x-secondary-button x-on:click="$dispatch('close')" class="pf-btn">
                    {{ __('app.cancel') }}
                </x-secondary-button>

                <x-danger-button class="pf-btn pf-btn--danger ms-3">
                    {{ __('app.delete_account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
