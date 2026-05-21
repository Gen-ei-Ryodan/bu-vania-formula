<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Dashboard' }}</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        <div class="layout">
            <aside class="sidebar" data-sidebar>
                <div class="brand">
                    <div class="brand-badge"></div>
                    <div class="brand-title">
                        <strong>BU VANIA2</strong>
                        <span>Sistem Produksi Pakan</span>
                    </div>
                </div>

                <nav class="nav">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span>Dashboard</span>
                        <small>Ringkas</small>
                    </a>
                    <a href="{{ route('units.index') }}" class="{{ request()->routeIs('units.*') ? 'active' : '' }}">
                        <span>Satuan</span>
                        <small>Master</small>
                    </a>
                    <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'active' : '' }}">
                        <span>Item</span>
                        <small>Master</small>
                    </a>
                    <a href="{{ route('concepts.index') }}" class="{{ request()->routeIs('concepts.*') ? 'active' : '' }}">
                        <span>Konsep</span>
                        <small>Resep</small>
                    </a>
                    <a href="{{ route('productions.index') }}" class="{{ request()->routeIs('productions.*') ? 'active' : '' }}">
                        <span>Produksi</span>
                        <small>Proses</small>
                    </a>
                    <a href="{{ route('treatments.index') }}" class="{{ request()->routeIs('treatments.*') ? 'active' : '' }}">
                        <span>Pengobatan</span>
                        <small>Proses</small>
                    </a>
                </nav>
            </aside>

            <main class="content">
                <div class="topbar">
                    <button class="burger" type="button" data-sidebar-toggle>Menu</button>
                    <h1>{{ $heading ?? ($title ?? 'Dashboard') }}</h1>
                    <div class="right muted">{{ now()->format('d M Y, H:i') }}</div>
                </div>

                <div class="page">
                    @if (session('ok'))
                        <div class="alert alert-ok">{{ session('ok') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="stack">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
