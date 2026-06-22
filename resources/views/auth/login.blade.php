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
                    <input type="password" name="password" required placeholder="Enter your password">
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

                <div style="text-align: center; font-size: 12px; color: var(--text-muted);">
                    Demo: admin@buvania.com / admin123
                </div>
            </form>
        </div>
    </div>

    @if (session('ok'))
        <div style="position: fixed; top: 20px; right: 20px;">
            <div class="alert alert-success">{{ session('ok') }}</div>
        </div>
    @endif
</body>
</html>
