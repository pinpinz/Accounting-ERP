<x-guest-layout>
    <div class="mb-3">
        <h4 class="fw-semibold mb-1">Masuk ke ERP Keuangan</h4>
        <p class="text-muted small mb-0">Kelola transaksi, pilih perusahaan, dan lihat ringkasan keuangan real-time.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <div class="alert alert-primary d-flex align-items-center gap-3 mb-4">
        <div class="avatar bg-white text-primary fw-bold rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border: 1px solid #dbeafe;">
            <i class="fa-solid fa-key"></i>
        </div>
        <div>
            <div class="fw-semibold">Akun Demo</div>
            <div class="small text-muted">Email: <code>admin@example.com</code> • Password: <code>password</code></div>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
        @csrf

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-2">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
            </div>
            @if (Route::has('password.request'))
                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                    <i class="fa-regular fa-envelope me-1"></i>{{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-1">
            <div class="small text-muted d-flex align-items-center gap-2">
                <i class="fa-solid fa-building-circle-check text-primary"></i>
                <span>Pilih perusahaan sesudah login</span>
            </div>
            <x-primary-button class="btn btn-primary">
                <i class="fa-solid fa-right-to-bracket me-1"></i>{{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
