@extends('adminlte::page')

@section('title', 'Matrículas')

@section('content_header')
    <h1>Matrículas</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de matrículas</h3>
            <div class="card-tools">
                <a href="{{ route('admin.matriculas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva matrícula
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>Código</th>
                        <th>Grupo</th>
                        <th>Nivel</th>
                        <th>Sede</th>
                        <th>Cuotas</th>
                        <th>Valor cuota</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matriculas as $matricula)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</td>
                            <td>{{ $matricula->alumno->nombre_completo ?? '—' }}</td>
                            <td>
                                @if ($matricula->alumno && $matricula->alumno->codigo)
                                    <span class="badge badge-primary">{{ $matricula->alumno->codigo }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $matricula->grupo->nombre ?? '—' }}</td>
                            <td>{{ $matricula->grupo->nivel->nombre ?? '—' }}</td>
                            <td>{{ $matricula->grupo->sede->nombre ?? '—' }}</td>
                            <td>{{ $matricula->numero_cuotas }}</td>
                            <td>${{ number_format($matricula->valor_cuota, 0, ',', '.') }}</td>
                            <td>
                                @if ($matricula->estado === 'activa')
                                    <span class="badge badge-success">Activa</span>
                                @elseif ($matricula->estado === 'finalizada')
                                    <span class="badge badge-info">Finalizada</span>
                                @else
                                    <span class="badge badge-danger">Cancelada</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.matriculas.edit', $matricula->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.matriculas.destroy', $matricula->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="matrícula" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay matrículas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

@stop
