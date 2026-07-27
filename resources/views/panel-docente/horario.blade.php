@extends('adminlte::page')

@section('title', 'Mi Horario')

@section('content_header')
    <h1>Mi horario</h1>
@stop

@section('content')

    <div class="mb-3">
        <span class="badge" style="background-color:#28a745; color:#fff; padding:6px 12px;">Libre</span>
        <span class="badge" style="background-color:#ffc107; color:#212529; padding:6px 12px;">Clase asignada</span>
    </div>

    <p class="text-muted">
        Este horario combina todas las sedes donde dictas clase. Es de solo consulta —
        cualquier cambio debe solicitarse al administrador.
    </p>

    <div class="card">
        <div class="card-body p-0" style="overflow-x:auto;">
            <table class="table table-bordered mb-0 text-center" style="min-width: 900px;">
                <thead>
                    <tr>
                        <th>Hora</th>
                        @foreach ($dias as $dia)
                            <th>{{ ucfirst($dia) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($filas as $fila)
                        <tr>
                            <td class="align-middle font-weight-bold" style="white-space:nowrap;">
                                {{ $fila['inicio'] }} - {{ $fila['fin'] }}
                            </td>
                            @foreach ($dias as $dia)
                                @php $celda = $fila['celdas'][$dia]; @endphp
                                @if ($celda['estado'] === 'libre')
                                    <td style="background-color:#28a745;"></td>
                                @else
                                    <td style="background-color:#ffc107; cursor:pointer;"
                                        onclick='mostrarOcupacion({{ json_encode($celda['grupos']) }}, "{{ $dia }}", "{{ $fila['inicio'] }}")'>
                                        <i class="fas fa-map-marker-alt"></i>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Aún no tienes horarios asignados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function mostrarOcupacion(grupos, dia, horaInicio) {
            let filas = grupos.map(g =>
                '<tr><td>' + g.sede + '</td><td>' + g.grupo + '</td><td>' + g.nivel + '</td></tr>'
            ).join('');

            Swal.fire({
                title: 'Clase el ' + dia + ' ' + horaInicio,
                html: `
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Sede</th><th>Grupo</th><th>Nivel</th></tr></thead>
                        <tbody>${filas}</tbody>
                    </table>
                `,
                icon: 'info',
            });
        }
    </script>

@stop
