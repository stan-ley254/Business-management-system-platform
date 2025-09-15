<section>
    <header>
        <h2 class="h5">
            {{ __('Update Password') }}
        </h2>

        <p class="text-muted small">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="mb-3">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="input-group">
                <x-text-input id="update_password_current_password"
                              name="current_password"
                              type="password"
                              class="form-control"
                              autocomplete="current-password" />
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#update_password_current_password">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="text-danger small mt-1" />
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="input-group">
                <x-text-input id="update_password_password"
                              name="password"
                              type="password"
                              class="form-control"
                              autocomplete="new-password" />
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#update_password_password">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            
            <x-input-error :messages="$errors->updatePassword->get('password')" class="text-danger small mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="input-group">
                <x-text-input id="update_password_password_confirmation"
                              name="password_confirmation"
                              type="password"
                              class="form-control"
                              autocomplete="new-password" />
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#update_password_password_confirmation">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="text-danger small mt-1" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-muted small"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

</section>
    <!-- Password Rules -->
    <ul id="password-rules" class="list-unstyled small mt-2 text-muted">
        <li id="rule-length"><i class="fa fa-circle text-secondary"></i> At least 8 characters</li>
        <li id="rule-uppercase"><i class="fa fa-circle text-secondary"></i> At least 1 uppercase letter</li>
        <li id="rule-lowercase"><i class="fa fa-circle text-secondary"></i> At least 1 lowercase letter</li>
        <li id="rule-number"><i class="fa fa-circle text-secondary"></i> At least 1 number</li>
        <li id="rule-symbol"><i class="fa fa-circle text-secondary"></i> At least 1 special character</li>
    </ul>
<!-- Toggle Script -->
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const target = document.querySelector(this.dataset.target);
            const icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

   const passwordInput = document.getElementById('update_password_password');
    const rules = {
        length: document.getElementById('rule-length'),
        uppercase: document.getElementById('rule-uppercase'),
        lowercase: document.getElementById('rule-lowercase'),
        number: document.getElementById('rule-number'),
        symbol: document.getElementById('rule-symbol'),
    };

    passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;

        // Rule checks
        updateRule(rules.length, value.length >= 8);
        updateRule(rules.uppercase, /[A-Z]/.test(value));
        updateRule(rules.lowercase, /[a-z]/.test(value));
        updateRule(rules.number, /\d/.test(value));
        updateRule(rules.symbol, /[^A-Za-z0-9]/.test(value));
    });

    function updateRule(element, isValid) {
        const icon = element.querySelector('i');
        if (isValid) {
            icon.classList.replace('fa-circle', 'fa-check-circle');
            icon.classList.replace('text-secondary', 'text-success');
        } else {
            icon.classList.replace('fa-check-circle', 'fa-circle');
            icon.classList.replace('text-success', 'text-secondary');
        }
    }
</script>
