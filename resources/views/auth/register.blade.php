<x-guest-layout>
    <h4 class="mb-1">Petualangan dimulai di sini 🚀</h4>
    <p class="mb-6">Buat akun baru untuk memulai!</p>

    <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="form-label">Nama</label>
            <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                placeholder="Masukkan nama lengkap Anda"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name" />
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

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
                autocomplete="new-password" />
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
            <input
                type="password"
                id="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                name="password_confirmation"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                required
                autocomplete="new-password" />
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Register Button -->
        <div class="mb-6">
            <button class="btn btn-primary d-grid w-100" type="submit">Daftar</button>
        </div>
    </form>

    <!-- Login Link -->
    <p class="text-center">
        <span>Sudah punya akun?</span>
        <a href="{{ route('login') }}">
            <span>Masuk di sini</span>
        </a>
    </p>
</x-guest-layout>
