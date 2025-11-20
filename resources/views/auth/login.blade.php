<x-guest-layout>
    <h4 class="text-center fw-semibold mb-3">Masuk ke ERP Keuangan</h4>
    <p class="text-muted small text-center mb-3">Gunakan akun demo di bawah atau kredensial Anda sendiri.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <div class="alert alert-info py-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-key text-primary"></i>
            <div>
                <div class="fw-semibold">Akun Demo</div>
                <div class="small">Email: <code>admin@example.com</code> • Password: <code>password</code></div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mt-2">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3">
            @if (Route::has('password.request'))
                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                    <i class="fa-regular fa-envelope me-1"></i>{{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="btn btn-primary">
                <i class="fa-solid fa-right-to-bracket me-1"></i>{{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
