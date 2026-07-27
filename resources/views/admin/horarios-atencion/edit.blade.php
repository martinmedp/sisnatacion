@extends('adminlte::page')

@section('title', 'Horario de atención — ' . $sede->nombre)

@section('content_header')
    <h1>Horario de atención — {{ $sede->nombre }}</h1>
@stop

@section('content')

    <a href="{{ route('admin.sedes.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a sedes
    </a>

    <a href="{{ route('admin.matriz-horarios.sede', $sede->id) }}" class="btn btn-info mb-3">
        <i class="fas fa-th"></i> Ver matriz de esta sede
    </a>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.horarios-atencion.update', $sede->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Duración de cada bloque de clase (minutos)</label>
                    <input type="number" name="duracion_clase_minutos" min="10" max="240"
                        class="form-control @error('duracion_clase_minutos') is-invalid @enderror"
                        style="max-width: 200px;"
                        value="{{ old('duracion_clase_minutos', $sede->duracion_clase_minutos) }}">
                    <small class="form-text text-muted">Se aplica igual a todos los días de esta sede.</small>
                    @error('duracion_clase_minutos')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                <p class="text-muted">
                    Deja vacías las horas de un día para marcarlo como <strong>cerrado</strong> ese día.
                </p>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Hora inicio</th>
                            <th>Hora fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dias as $dia)
                            @php $atencion = $atencionPorDia->get($dia); @endphp
                            <tr>
                                <td class="align-middle">{{ ucfirst($dia) }}</td>
                                <td>
                                    <input type="time" name="hora_inicio[{{ $dia }}]" class="form-control"
                                        value="{{ old('hora_inicio.' . $dia, $atencion->hora_inicio ?? '') }}">
                                </td>
                                <td>
                                    <input type="time" name="hora_fin[{{ $dia }}]" class="form-control"
                                        value="{{ old('hora_fin.' . $dia, $atencion->hora_fin ?? '') }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar horario de atención
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'success', title: 'Correcto', text: '{{ session('success') }}',
                    timer: 1500, timerProgressBar: true, showConfirmButton: false
                });
            });
        </script>
    @endif

@stop
