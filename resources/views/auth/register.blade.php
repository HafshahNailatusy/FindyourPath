<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FindYourPath</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    @vite(['resources/css/register.css', 'resources/js/register.js'])
</head>

<body>
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>
    <div class="bg-blob blob-4"></div>

    <div class="auth-card" id="authCard">
        <div class="auth-left">
            <div class="logo-header">
                <img src="{{ asset('images/LOGO.png') }}" alt="logo-fyp" class="logo-fyp">
            </div>

            <div class="welcome-section">
                <h2 class="welcome-title">Halo, Selamat Datang!</h2>
                <p class="welcome-subtitle">Bergabung dan temukan peluang<br>untuk masa depanmu.</p>
            </div>

            <div class="illustration-box">
                <img
                    src="{{ asset('images/assest_login_register.png') }}"
                    alt="FindYourPath Illustration"
                    class="hero-illustration"
                    id="heroIllustration">
            </div>
        </div>

        <div class="auth-right">
            @php
                $serverMessage = session('success') ?? session('error') ?? $errors->first();
                $serverType = session('success') ? 'success' : 'error';
            @endphp
            <div id="alertToast" class="alert-toast {{ $serverMessage ? 'show '.$serverType : '' }}">
                <span class="toast-text" id="toastText">{{ $serverMessage }}</span>
            </div>

            <form id="registerForm" data-login-url="{{ route('login') }}" method="POST" action="{{ route('register.store') }}" novalidate>
                @csrf
                <div class="form-group">
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="Nama Lengkap"
                        required
                        autocomplete="name">
                </div>

                <div class="form-group">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="Email"
                        required
                        autocomplete="email">
                </div>

                <div class="form-group">
                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="form-control"
                        value="{{ old('username') }}"
                        placeholder="Username"
                        required
                        autocomplete="username">
                </div>

                <div class="form-group">
                    <div class="password-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Password"
                            required
                            autocomplete="new-password">
                        <button
                            type="button"
                            id="togglePassword"
                            class="password-toggle"
                            title="Tampilkan / Sembunyikan Password"
                            aria-label="Toggle password visibility">
                            <svg class="eye-icon" id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn-submit">
                    <span class="btn-text">Register</span>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>
            </form>

            <div class="auth-footer">
                <small>Sudah punya akun? <a href="{{ route('login') }}">Login</a></small>
            </div>
        </div>
    </div>
</body>

</html>