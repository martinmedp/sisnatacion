<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carnets — {{ $titulo }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            width: 155.91pt;
            height: 240.95pt;
        }

        /* Marco alrededor de todo el carnet */
        .carnet {
            width: 94%;
            height: 91%;
            border: 2.5px solid #005F8F;
            border-radius: 6px;
            position: relative;
            overflow: hidden;
            padding: 4px;
        }

        .carnet-inner {
            width: 90%;
            height: 100%;
            border: 0.5px solid #cfd8dc;
            border-radius: 3px;
            padding: 6px 8px;
            text-align: center;
        }

        /* Encabezado */
        .header {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 4px;
            margin-bottom: 6px;
            text-align: center;
        }

        .header .institucion {
            font-size: 10px;
            font-weight: bold;
            color: #005F8F;
        }

        .header .descripcion {
            font-size: 6.5px;
            color: #777;
            margin-top: 2px;
        }

        .header .anio-lectivo {
            font-size: 7px;
            color: #005F8F;
            font-weight: bold;
            margin-top: 3px;
        }

        /* Cuerpo — todo centrado y apilado verticalmente */
        .foto {
            width: 62px;
            height: 62px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin: 2px auto 6px;
        }

        .foto-vacia {
            width: 62px;
            height: 62px;
            background: #eee;
            border-radius: 4px;
            margin: 2px auto 6px;
        }

        .nombre {
            font-size: 10px;
            font-weight: bold;
            color: #222;
            margin-bottom: 5px;
            line-height: 1.2;
            text-align: center;
        }

        .fila {
            margin-bottom: 5px;
            text-align: justify;
        }

        .etiqueta {
            color: #888;
            font-size: 6px;
            text-transform: uppercase;
            /* display: block; */
        }

        .valor {
            font-size: 8px;
            color: #333;
        }

        .codigo-destacado {
            font-size: 10px;
            font-weight: bold;
            color: #005F8F;
        }

        .qr-wrapper {
            margin-top: 2px;
        }

        .qr {
            width: 58px;
            height: 58px;
        }

        /* Pie de página */
        .footer {
            /* position: absolute; */
            bottom: 2px;
            left: 8px;
            right: 8px;
            border-top: 1px dashed #ccc;
            padding-top: 1px;
            font-size: 5.5px;
            color: #888;
            text-align: center;
        }
    

        /* Cada carnet ocupa una página independiente */
        .pagina-carnet {
            page-break-after: always;
        }
        .pagina-carnet:last-child {
            page-break-after: auto;
        }
    </style>
</head>

<body>

    @foreach ($registros as $registro)
        <div class="pagina-carnet">
            @php
                $alumno = $registro['alumno'];
                $matricula = $registro['matricula'];
                $qrBase64 = $registro['qrBase64'];
            @endphp

            @include('admin.carnets._carnet')
        </div>
    @endforeach

</body>

</html>
