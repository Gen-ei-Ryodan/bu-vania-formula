<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Program Formula</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: var(--bg); font-family: var(--font); color: var(--text); -webkit-font-smoothing: antialiased;">

    <div class="form-card" style="width: 420px; max-width: 92%;">
        <div class="form-card-header" style="text-align: center; justify-content: center; flex-direction: column;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--primary); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 20px; margin: 0 auto 12px;">PF</div>
            <h3 style="margin: 0;">Program Formula</h3>
            <p style="margin: 4px 0 0; font-size: 13px; color: var(--text-secondary);">Sistem Produksi Pakan</p>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('login') }}" style="display: grid; gap: 16px;">
                @csrf

                <div class="field">
                    <div class="label">Email</div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@buvania.com">
                </div>

                <div class="field">
                    <div class="label">Password</div>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password-field" required placeholder="Enter your password" style="padding-right: 40px;">
                        <button type="button" id="toggle-password" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: var(--text-muted);" aria-label="Toggle password visibility">
                            <svg id="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-off-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="field-inline">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat Saya</label>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" style="margin: 0;">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <button class="btn btn-primary btn-lg" type="submit" style="width: 100%; justify-content: center;">Masuk</button>
            </form>
        </div>
    </div>

    @if (session('ok'))
        <div style="position: fixed; top: 20px; right: 20px;">
            <div class="alert alert-success">{{ session('ok') }}</div>
        </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggle-password');
        const passwordField = document.getElementById('password-field');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        if (toggleBtn && passwordField) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';
                eyeIcon.style.display = isPassword ? 'none' : '';
                eyeOffIcon.style.display = isPassword ? '' : 'none';
            });
        }
    });
    </script>
</body>
</html>
