<div class="carnet">
        <div class="carnet-inner">

            {{-- Encabezado --}}
            <div class="header">
                <div class="institucion">{{ $configuracion->nombre ?? 'SisNatación' }}</div>
                @if ($configuracion && $configuracion->descripcion)
                    <div class="descripcion">{{ $configuracion->descripcion }}</div>
                @endif
                <div class="anio-lectivo">Año lectivo {{ $anioLectivo }}</div>
            </div>

            {{-- Foto --}}
            @if ($alumno->foto)
                <img class="foto" src="{{ public_path($alumno->foto) }}" alt="Foto">
            @else
                <div class="foto-vacia"></div>
            @endif

            {{-- Nombre --}}
            <div class="nombre">{{ $alumno->nombre_completo }}</div>

            {{-- Código de matrícula --}}
            <div class="fila">
                <span class="etiqueta">Código: </span>
                <span class="codigo-destacado">{{ $alumno->codigo ?? '—' }}</span>
            </div>

            {{-- Acudiente --}}
            <div class="fila">
                <span class="etiqueta">Acudiente</span>
                <span class="valor">{{ $alumno->acudiente->nombre_completo ?? '—' }}</span>
            </div>

            {{-- Nivel / Grupo --}}
            <div class="fila">
                <span class="etiqueta">Grupo</span>
                <span class="valor">
                    {{ $matricula->grupo->nivel->nombre ?? '—' }} — {{ $matricula->grupo->nombre ?? '—' }}
                </span>
            </div>

            {{-- QR --}}
            <div class="qr-wrapper">
                <img class="qr" src="data:image/png;base64,{{ $qrBase64 }}" alt="QR">
            </div>

            {{-- Pie de página --}}
            <div class="footer">
                @if ($configuracion && $configuracion->direccion)
                    {{ $configuracion->direccion }}
                @endif
                @if ($configuracion && $configuracion->telefono)
                    — Tel: {{ $configuracion->telefono }}
                @endif
                @if ($configuracion && ($configuracion->web || $configuracion->correo_electronico))
                    <br>
                    @if ($configuracion->web)
                        Web: {{ $configuracion->web }}
                    @endif
                    @if ($configuracion->correo_electronico)
                        &nbsp;|&nbsp; Correo: {{ $configuracion->correo_electronico }}
                    @endif
                @endif
            </div>

        </div>
    </div>
