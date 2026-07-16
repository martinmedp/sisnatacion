@extends('adminlte::page')

@section('title', 'Carnets')

@section('content_header')
    <h1>Carnets estudiantiles</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Impresión masiva de carnets</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.carnets.imprimir') }}" method="GET" class="form-inline" target="_blank">
                <select name="nivel_id" class="form-control mr-2" style="width: 220px;">
                    <option value="">-- Todos los niveles --</option>
                    @foreach ($niveles as $nivel)
                        <option value="{{ $nivel->id }}">{{ $nivel->nombre }}</option>
                    @endforeach
                </select>

                <select name="grupo_id" class="form-control mr-2" style="width: 220px;">
                    <option value="">-- Todos los grupos --</option>
                    @foreach ($grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }} ({{ $grupo->nivel->nombre ?? '' }})</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-print"></i> Imprimir carnets
                </button>
            </form>
            <small class="form-text text-muted mt-1">
                Si eliges un grupo, se ignora el nivel. Si no eliges nada, se imprimen todos los alumnos con matrícula activa.
            </small>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Generar carnet individual con código QR</h3>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Solo se listan alumnos con una matrícula activa, ya que el carnet incluye el nivel y grupo actual.
                El código QR guarda el código del alumno — más adelante se usará para registrar asistencia escaneándolo.
            </p>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Nivel</th>
                        <th>Grupo</th>
                        <th>Sede</th>
                        <th width="140">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alumnos as $alumno)
                        @php $matricula = $alumno->matriculas->first(); @endphp
                        <tr>
                            <td width="55">
                                @if ($alumno->foto)
                                    <img src="{{ asset($alumno->foto) }}"
                                        style="width:36px;height:36px;object-fit:cover;border-radius:50%;">
                                @else
                                    <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                @endif
                            </td>
                            <td>{{ $alumno->nombre_completo }}</td>
                            <td>
                                @if ($alumno->codigo)
                                    <span class="badge badge-primary">{{ $alumno->codigo }}</span>
                                @else
                                    <span class="text-muted">Sin código</span>
                                @endif
                            </td>
                            <td>{{ $matricula->grupo->nivel->nombre ?? '—' }}</td>
                            <td>{{ $matricula->grupo->nombre ?? '—' }}</td>
                            <td>{{ $matricula->grupo->sede->nombre ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.carnets.generar', $alumno->id) }}" target="_blank"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-id-card"></i> Generar carnet
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No hay alumnos con matrícula activa para generar carnet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (session('error'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin resultados',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

@stop
