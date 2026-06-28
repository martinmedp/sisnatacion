<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — {{ config('adminlte.title', 'SisNatación') }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-principal);
            font-family: 'Nunito', sans-serif;
        }

        .login-card {
            display: flex;
            width: 900px;
            max-width: 96vw;
            min-height: 520px;
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        /* Panel izquierdo */
        .login-left {
            flex: 1;
            background-color: var(--color-primario);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            top: -80px;
            right: -80px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            bottom: -60px;
            left: -60px;
        }

        .login-logo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.12);
            padding: 12px;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 1;
        }

        .login-brand {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .login-brand span {
            color: var(--color-acento);
        }

        .login-tagline {
            color: rgba(255, 255, 255, 0.65);
            font-size: 13px;
            text-align: center;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .login-lanes {
            margin-top: 2.5rem;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .lane {
            height: 5px;
            border-radius: 3px;
            background: rgba(255, 255, 255, 0.12);
        }

        .lane:nth-child(1) {
            width: 80%;
            background: rgba(255, 255, 255, 0.25);
        }

        .lane:nth-child(2) {
            width: 55%;
            background: rgba(255, 255, 255, 0.15);
        }

        .lane:nth-child(3) {
            width: 90%;
            background: rgba(255, 255, 255, 0.10);
        }

        .lane:nth-child(4) {
            width: 65%;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Panel derecho */
        .login-right {
            flex: 1.1;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
        }

        .login-right h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--texto-principal);
            margin-bottom: 6px;
        }

        .login-right p.subtitle {
            font-size: 13px;
            color: var(--texto-secundario);
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--texto-secundario);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 6px;
        }

        .input-group {
            margin-bottom: 1.25rem;
        }

        .input-group .form-control {
            border-color: var(--border-color);
            border-radius: var(--radius-sm) 0 0 var(--radius-sm) !important;
            height: 44px;
            font-size: 14px;
            color: var(--texto-principal);
            background: var(--bg-input);
            transition: border-color var(--transition);
        }

        .input-group .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(0, 95, 143, 0.12);
        }

        .input-group-text {
            border-color: var(--border-color);
            background: var(--bg-secundario);
            color: var(--color-primario);
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0 !important;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember-label {
            font-size: 13px;
            color: var(--texto-secundario);
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--color-primario);
            text-decoration: none;
        }

        .forgot-link:hover {
            color: var(--color-secundario);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            height: 46px;
            border-radius: var(--radius-sm);
            background: var(--color-primario);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background var(--transition), transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: var(--color-secundario);
        }

        .btn-login:active {
            transform: scale(0.99);
        }

        .alert-danger {
            border-radius: var(--radius-sm);
            font-size: 13px;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 640px) {
            .login-left {
                display: none;
            }

            .login-right {
                padding: 2.5rem 2rem;
            }

            .login-card {
                width: 100%;
                min-height: 100vh;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        {{-- Panel izquierdo --}}
        <div class="login-left">
            @php
                $config = \App\Models\Configuracion::first();
                $logoPath = $config && $config->logo ? asset($config->logo) : asset('uploads/logos/logo.png');
                $nombre = $config ? $config->nombre : config('adminlte.title', 'SisNatación');
            @endphp

            <img src="{{ $logoPath }}" alt="Logo {{ $nombre }}" class="login-logo">
            <p class="login-brand">{{ $nombre }}</p>
            <p class="login-tagline">Panel de administración<br>del sistema de natación</p>

            <div class="login-lanes">
                <div class="lane"></div>
                <div class="lane"></div>
                <div class="lane"></div>
                <div class="lane"></div>
            </div>
        </div>

        {{-- Panel derecho --}}
        <div class="login-right">
            <h2>Bienvenido</h2>
            <p class="subtitle">Ingresa tus credenciales para acceder al panel de administración</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Credenciales incorrectas. Verifica tu correo y contraseña.
                </div>
            @endif

            <form method="POST" action="{{ url('login') }}">
                @csrf

                {{-- Email --}}
                <label class="form-label">Correo electrónico</label>
                <div class="input-group mb-3">
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        placeholder="correo@ejemplo.com" autofocus autocomplete="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>

                {{-- Contraseña --}}
                <label class="form-label">Contraseña</label>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                        autocomplete="current-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>

                {{-- Recordar + olvidé --}}
                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Recordarme
                    </label>
                    <a href="{{ url('password/reset') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar sesión
                </button>
            </form>
        </div>

    </div>

</body>

</html>
