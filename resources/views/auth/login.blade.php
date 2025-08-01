<x-guest-layout>
    <h4 class="mb-1">Selamat Datang! 👋</h4>
    <p class="mb-6">Silakan masuk ke akun Anda untuk memulai</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-6" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                placeholder="Masukkan email Anda"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username" />
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label class="form-label" for="password">Password</label>
            <input
                type="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                required
                autocomplete="current-password" />
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="mb-8">
            <div class="d-flex justify-content-between">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                    <label class="form-check-label" for="remember-me">
                        Ingat Saya
                    </label>
                </div>
            </div>
        </div>

        <!-- Login Button -->
        <div class="mb-6">
            <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
        </div>
    </form>

    <!-- Register Link -->
    @if (Route::has('register'))
        <p class="text-center">
            <span>Baru di platform kami?</span>
            <a href="{{ route('register') }}">
                <span>Buat akun</span>
            </a>
        </p>
    @endif
</x-guest-layout>
