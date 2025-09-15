<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Business Name -->
        <div class="mt-4">
            <x-input-label for="business_name" :value="__('Business Name')" />
            <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <!-- Business Type -->
        <div class="mt-4">
            <x-input-label for="business_type" :value="__('Business Platform Type')" />
            <select id="business_type" name="business_type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="pos" {{ old('business_type') == 'pos' ? 'selected' : '' }}>Business Management System</option>
                <option value="service" {{ old('business_type') == 'service' ? 'selected' : '' }}>Point of Service</option>
            </select>
            <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
        </div>

      <!-- Password -->
<div class="mt-4 position-relative">
    <x-input-label for="password" :value="__('Password')" />
    <div class="input-group">
        <x-text-input id="password"
                      type="password"
                      class="form-control"
                      name="password"
                      required
                      autocomplete="new-password" />
        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">
            <i class="fa fa-eye"></i>
        </button>
    </div>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<!-- Confirm Password -->
<div class="mt-4 position-relative">
    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
    <div class="input-group">
        <x-text-input id="password_confirmation"
                      type="password"
                      class="form-control"
                      name="password_confirmation"
                      required
                      autocomplete="new-password" />
        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password_confirmation">
            <i class="fa fa-eye"></i>
        </button>
    </div>
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>
<div id="password-rules" class="mt-2 text-sm text-muted">
        <p id="length" class="text-red-600">• At least 8 characters</p>
        <p id="upper" class="text-red-600">• At least one uppercase</p>
        <p id="lower" class="text-red-600">• At least one lowercase</p>
        <p id="number" class="text-red-600">• At least one number</p>
        <p id="symbol" class="text-red-600">• At least one special character</p>
    </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
document.getElementById('password').addEventListener('input', function() {
  let value = this.value;
  document.getElementById('length').className = value.length >= 8 ? 'text-green-600' : 'text-red-600';
  document.getElementById('upper').className = /[A-Z]/.test(value) ? 'text-green-600' : 'text-red-600';
  document.getElementById('lower').className = /[a-z]/.test(value) ? 'text-green-600' : 'text-red-600';
  document.getElementById('number').className = /\d/.test(value) ? 'text-green-600' : 'text-red-600';
  document.getElementById('symbol').className = /[^A-Za-z0-9]/.test(value) ? 'text-green-600' : 'text-red-600';
});

 document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const target = document.querySelector(this.dataset.target);
            const icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    </script>
</x-guest-layout>
