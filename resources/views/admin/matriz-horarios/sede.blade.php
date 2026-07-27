@extends('adminlte::page')

@section('title', 'Matriz — ' . $sede->nombre)

@section('content_header')
    <h1>Matriz de horarios — {{ $sede->nombre }}</h1>
@stop

@section('content')

    <a href="{{ route('admin.matriz-horarios.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <a href="{{ route('admin.horarios-atencion.edit', $sede->id) }}" class="btn btn-warning mb-3">
        <i class="fas fa-cog"></i> Configurar horario de atención
    </a>

    <div class="mb-3">
        <span class="badge" style="background-color:#28a745; color:#fff; padding:6px 12px;">Libre</span>
        <span class="badge" style="background-color:#ffc107; color:#212529; padding:6px 12px;">Ocupado</span>
        <span class="badge" style="background-color:#adb5bd; color:#fff; padding:6px 12px;">Cerrado</span>
    </div>

    @if ($sinConfigurar)
        <div class="alert alert-warning">
            Esta sede aún no tiene configurado su horario de atención.
            <a href="{{ route('admin.horarios-atencion.edit', $sede->id) }}">Configúralo aquí</a> para poder ver la matriz.
        </div>
    @else
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
                        @foreach ($filas as $fila)
                            <tr>
                                <td class="align-middle font-weight-bold" style="white-space:nowrap;">
                                    {{ $fila['inicio'] }} - {{ $fila['fin'] }}
                                </td>
                                @foreach ($dias as $dia)
                                    @php $celda = $fila['celdas'][$dia]; @endphp
                                    @if ($celda['estado'] === 'cerrado')
                                        <td style="background-color:#adb5bd;"></td>
                                    @elseif ($celda['estado'] === 'libre')
                                        <td style="background-color:#28a745; cursor:pointer;"
                                            onclick="irACrearHorario('{{ $sede->id }}', '{{ $dia }}', '{{ $fila['inicio'] }}', '{{ $fila['fin'] }}')">
                                        </td>
                                    @else
                                        <td style="background-color:#ffc107; cursor:pointer;"
                                            onclick='mostrarOcupantes({{ json_encode($celda['grupos']) }}, "{{ $dia }}", "{{ $fila['inicio'] }}", "{{ $sede->id }}", "{{ $fila['fin'] }}")'>
                                            <i class="fas fa-users"></i>
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <script>
        function irACrearHorario(sedeId, dia, horaInicio, horaFin) {
            const url = '{{ url("admin/horarios/create") }}'
                + '?sede_id=' + sedeId
                + '&dia_semana=' + dia
                + '&hora_inicio=' + horaInicio
                + '&hora_fin=' + horaFin;
            window.location.href = url;
        }

        function mostrarOcupantes(grupos, dia, horaInicio, sedeId, horaFin) {
            let filas = grupos.map(g =>
                '<tr><td>' + g.grupo + '</td><td>' + g.nivel + '</td><td>' + g.docente + '</td></tr>'
            ).join('');

            Swal.fire({
                title: 'Grupos en ' + dia + ' ' + horaInicio,
                html: `
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Grupo</th><th>Nivel</th><th>Docente</th></tr></thead>
                        <tbody>${filas}</tbody>
                    </table>
                `,
                showCancelButton: true,
                confirmButtonText: 'Agregar otro grupo aquí',
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                if (result.isConfirmed) {
                    irACrearHorario(sedeId, dia, horaInicio, horaFin);
                }
            });
        }
    </script>

@stop
