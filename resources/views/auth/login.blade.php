<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <!-- Password -->
<div class="mt-4">
    <x-input-label for="password" :value="__('Password')" />

    <div class="input-group">
        <x-text-input id="password" class="form-control"
                      type="password"
                      name="password"
                      required autocomplete="current-password" />

        <button type="button" class="btn btn-outline-secondary" 
                onclick="togglePassword('password', this)">
            <i class="fa fa-eye"></i>
        </button>
    </div>

    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>


        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <script> function togglePassword(inputId, el) { const input = document.getElementById(inputId); const icon = el.querySelector('i'); if (input.type === "password") { input.type = "text"; icon.classList.remove("fa-eye"); icon.classList.add("fa-eye-slash"); } else { input.type = "password"; icon.classList.remove("fa-eye-slash"); icon.classList.add("fa-eye"); } } </script>
</x-guest-layout>
