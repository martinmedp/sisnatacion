<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio no disponible — SisNatación</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #005F8F 0%, #00B4D8 100%);
            font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            padding: 20px;
        }

        .contenedor {
            background: #ffffff;
            border-radius: 16px;
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .ilustracion {
            width: 140px;
            margin: 0 auto 1.5rem;
        }

        h1 {
            font-size: 22px;
            color: #1a1a1a;
            margin-bottom: 0.75rem;
        }

        p {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 1.75rem;
        }

        .btn-reintentar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #005F8F;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }

        .btn-reintentar:hover {
            background: #00456b;
            color: #fff;
        }

        .nota {
            margin-top: 1.5rem;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="contenedor">
        <svg class="ilustracion" viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="100" cy="140" rx="70" ry="10" fill="#e8f4f8"/>
            <rect x="55" y="30" width="90" height="70" rx="8" fill="#cfe9f2" stroke="#005F8F" stroke-width="3"/>
            <rect x="65" y="42" width="70" height="8" rx="4" fill="#005F8F" opacity="0.3"/>
            <rect x="65" y="58" width="70" height="8" rx="4" fill="#005F8F" opacity="0.3"/>
            <rect x="65" y="74" width="45" height="8" rx="4" fill="#005F8F" opacity="0.3"/>
            <circle cx="140" cy="105" r="22" fill="#ffffff" stroke="#e74c3c" stroke-width="4"/>
            <line x1="131" y1="96" x2="149" y2="114" stroke="#e74c3c" stroke-width="4" stroke-linecap="round"/>
            <line x1="149" y1="96" x2="131" y2="114" stroke="#e74c3c" stroke-width="4" stroke-linecap="round"/>
            <path d="M20 130 Q 30 122, 40 130 T 60 130 T 80 130 T 100 130 T 120 130 T 140 130 T 160 130 T 180 130"
                  stroke="#00B4D8" stroke-width="3" fill="none" stroke-linecap="round"/>
        </svg>

        <h1>Servicio temporalmente no disponible</h1>
        <p>
            En este momento no podemos conectarnos con la base de datos.
            Puede ser algo temporal — inténtalo de nuevo en unos minutos.
        </p>

        <a href="{{ url()->current() }}" class="btn-reintentar">
            ↻ Reintentar
        </a>

        <p class="nota">Si el problema persiste, contacta al administrador del sistema.</p>
    </div>

</body>
</html>