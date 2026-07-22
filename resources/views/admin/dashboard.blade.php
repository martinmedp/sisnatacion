@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    {{-- ── Fila 1 — Números clave ──────────────────────────── --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $alumnosActivos }}</h3>
                    <p>Alumnos activos</p>
                </div>
                <div class="icon"><i class="fas fa-swimmer"></i></div>
                <a href="{{ route('admin.alumnos.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $docentesActivos }}</h3>
                    <p>Docentes activos</p>
                </div>
                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <a href="{{ route('admin.docentes.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $matriculasActivas }}</h3>
                    <p>Matrículas activas</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <a href="{{ route('admin.matriculas.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $gruposActivos }}</h3>
                    <p>Grupos activos</p>
                </div>
                <div class="icon"><i class="fas fa-users-cog"></i></div>
                <a href="{{ route('admin.grupos.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ── Fila 2 — Financiero ─────────────────────────────── --}}
    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Cartera morosa</span>
                    <span class="info-box-number">${{ number_format($carteraMorosaTotal, 0, ',', '.') }}</span>
                    <span class="progress-description">{{ $carteraMorosaCantidad }} cuota(s) vencida(s)</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Recaudado este mes</span>
                    <span class="info-box-number">${{ number_format($recaudadoEsteMes, 0, ',', '.') }}</span>
                    <span class="progress-description">{{ now()->translatedFormat('F Y') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Por vencer esta semana</span>
                    <span class="info-box-number">${{ number_format($porVencerTotal, 0, ',', '.') }}</span>
                    <span class="progress-description">{{ $porVencerCantidad }} cuota(s)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Fila 3 — Cartera morosa detallada ───────────────── --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-exclamation-triangle text-danger"></i> Cartera morosa — top 10 alumnos
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.cobros.index', ['estado' => 'vencido']) }}" class="btn btn-sm btn-danger">
                    Ver todos los vencidos
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Código</th>
                        <th>Nivel / Grupo</th>
                        <th class="text-center">Cuotas vencidas</th>
                        <th class="text-center">Días de atraso</th>
                        <th class="text-right">Saldo vencido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($carteraMorosaPorAlumno as $fila)
                        <tr>
                            <td>{{ $fila['alumno']->nombre_completo ?? '—' }}</td>
                            <td>
                                @if ($fila['alumno']->codigo ?? null)
                                    <span class="badge badge-primary">{{ $fila['alumno']->codigo }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $fila['grupo']->nivel->nombre ?? '—' }}
                                <br><small class="text-muted">{{ $fila['grupo']->nombre ?? '' }}</small>
                            </td>
                            <td class="text-center">{{ $fila['cuotasVencidas'] }}</td>
                            <td class="text-center text-danger font-weight-bold">{{ $fila['diasAtraso'] }} día(s)</td>
                            <td class="text-right text-danger font-weight-bold">
                                ${{ number_format($fila['saldoTotal'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-success">
                                <i class="fas fa-check-circle"></i> No hay cartera morosa actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Fila 4 — Extras ─────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Alumnos por nivel</h3>
                </div>
                <div class="card-body">
                    @forelse ($alumnosPorNivel as $nivel => $cantidad)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>{{ $nivel }}</span>
                                <strong>{{ $cantidad }}</strong>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info"
                                    style="width: {{ round(($cantidad / $maxAlumnosPorNivel) * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No hay matrículas activas.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimas matrículas</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($ultimasMatriculas as $matricula)
                            <li class="list-group-item">
                                <strong>{{ $matricula->alumno->nombre_completo ?? '—' }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $matricula->grupo->nivel->nombre ?? '—' }} —
                                    {{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Sin matrículas recientes.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimas noticias publicadas</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($ultimasNoticias as $noticia)
                            <li class="list-group-item">
                                <strong>{{ $noticia->titulo }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($noticia->fecha_publicacion)->format('d/m/Y') }}
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Sin noticias recientes.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

@stop
