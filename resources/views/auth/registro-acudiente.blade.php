<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Acudiente — {{ config('adminlte.title', 'SisNatación') }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background-color: var(--bg-principal);
            font-family: 'Nunito', sans-serif;
            padding: 40px 20px;
        }

        .registro-card {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .registro-header {
            background-color: var(--color-primario);
            color: #fff;
            padding: 2rem 2.5rem;
            text-align: center;
        }

        .registro-header img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.12);
            padding: 8px;
            margin-bottom: 0.75rem;
        }

        .registro-header h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .registro-header p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
        }

        .registro-body {
            padding: 2.5rem;
        }

        .seccion-titulo {
            font-size: 15px;
            font-weight: 700;
            color: var(--color-primario);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--bg-secundario);
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

        .form-control {
            height: 44px;
            font-size: 14px;
            border-color: var(--border-color);
            border-radius: var(--radius-sm);
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(0, 95, 143, 0.12);
        }

        .btn-registrar {
            width: 100%;
            height: 46px;
            border-radius: var(--radius-sm);
            background: var(--color-primario);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-registrar:hover {
            background: var(--color-secundario);
        }

        .volver-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            font-size: 13px;
            color: var(--texto-secundario);
            text-decoration: none;
        }

        .volver-link:hover {
            color: var(--color-primario);
        }
    </style>
</head>

<body>

    <div class="registro-card">
        <div class="registro-header">
            @php
                $config = \App\Models\Configuracion::first();
                $logoPath = $config && $config->logo ? asset($config->logo) : asset('uploads/logos/logo.png');
                $nombre = $config ? $config->nombre : config('adminlte.title', 'SisNatación');
            @endphp
            <img src="{{ $logoPath }}" alt="Logo {{ $nombre }}">
            <h1>{{ $nombre }}</h1>
            <p>Registro de acudiente y alumno</p>
        </div>

        <div class="registro-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-info" style="font-size:13px;">
                <i class="fas fa-info-circle"></i>
                Tu registro quedará <strong>pendiente de revisión</strong> por el administrador antes de poder
                iniciar sesión. Te avisaremos cuando esté activo.
            </div>

            <form method="POST" action="{{ route('registro.acudiente.store') }}">
                @csrf

                <h5 class="seccion-titulo"><i class="fas fa-user-friends"></i> Tus datos (acudiente)</h5>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" name="acudiente_nombre" class="form-control @error('acudiente_nombre') is-invalid @enderror"
                            value="{{ old('acudiente_nombre') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label">Parentesco</label>
                        <select name="acudiente_parentesco" class="form-control">
                            <option value="">-- Seleccionar --</option>
                            @foreach (['Padre', 'Madre', 'Abuelo/a', 'Tío/a', 'Hermano/a', 'Tutor legal', 'Otro'] as $p)
                                <option value="{{ $p }}" {{ old('acudiente_parentesco') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="form-label">Documento</label>
                        <input type="text" name="acudiente_documento" class="form-control" value="{{ old('acudiente_documento') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label">Teléfono *</label>
                        <input type="text" name="acudiente_telefono" class="form-control @error('acudiente_telefono') is-invalid @enderror"
                            value="{{ old('acudiente_telefono') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label">Correo *</label>
                        <input type="email" name="acudiente_correo" class="form-control @error('acudiente_correo') is-invalid @enderror"
                            value="{{ old('acudiente_correo') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="acudiente_password" class="form-control @error('acudiente_password') is-invalid @enderror">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Confirmar contraseña *</label>
                        <input type="password" name="acudiente_password_confirmation" class="form-control">
                    </div>
                </div>

                <h5 class="seccion-titulo mt-4"><i class="fas fa-swimmer"></i> Datos del alumno</h5>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" name="alumno_nombre" class="form-control @error('alumno_nombre') is-invalid @enderror"
                            value="{{ old('alumno_nombre') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="alumno_nacimiento" class="form-control" value="{{ old('alumno_nacimiento') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Documento</label>
                        <input type="text" name="alumno_documento" class="form-control" value="{{ old('alumno_documento') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Sexo</label>
                        <select name="alumno_sexo" class="form-control">
                            <option value="">-- Seleccionar --</option>
                            <option value="masculino" {{ old('alumno_sexo') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="femenino" {{ old('alumno_sexo') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Correo del alumno (opcional)</label>
                    <input type="email" name="alumno_correo" class="form-control @error('alumno_correo') is-invalid @enderror"
                        value="{{ old('alumno_correo') }}">
                    <small class="text-muted" style="font-size:12px;">
                        Si el alumno es mayor y quieres que también pueda iniciar sesión por su cuenta, déjalo aquí.
                        Si no, puedes dejarlo vacío.
                    </small>
                </div>

                <button type="submit" class="btn-registrar mt-3">
                    <i class="fas fa-user-plus"></i> Registrarme
                </button>
            </form>

            <a href="{{ route('login') }}" class="volver-link">← Volver a iniciar sesión</a>
        </div>
    </div>

</body>

</html>
