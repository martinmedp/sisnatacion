@extends('adminlte::page')

@section('title', 'Nueva matrícula')

@section('content_header')
    <h1>Nueva matrícula</h1>
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.matriculas.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Alumno</label>
                        <div class="input-group">
                            <select name="alumno_id" id="alumno_id" class="form-control @error('alumno_id') is-invalid @enderror">
                                <option value="">-- Seleccionar alumno --</option>
                                @foreach ($alumnos as $alumno)
                                    <option value="{{ $alumno->id }}" {{ old('alumno_id') == $alumno->id ? 'selected' : '' }}>
                                        {{ $alumno->nombre_completo }}
                                        @if ($alumno->codigo) ({{ $alumno->codigo }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalAlumno">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button>
                            </div>
                        </div>
                        @error('alumno_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha de matrícula</label>
                        <input type="date" name="fecha_matricula"
                            class="form-control @error('fecha_matricula') is-invalid @enderror"
                            value="{{ old('fecha_matricula', date('Y-m-d')) }}">
                        @error('fecha_matricula')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Grupo</label>
                    <select name="grupo_id" id="grupo_id" class="form-control @error('grupo_id') is-invalid @enderror"
                        onchange="mostrarInfoGrupo()">
                        <option value="">-- Seleccionar grupo --</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}"
                                data-valor="{{ $grupo->nivel->valor_clase ?? 0 }}"
                                data-meses="{{ $grupo->nivel->duracion_meses ?? 1 }}"
                                data-nivel="{{ $grupo->nivel->nombre ?? '—' }}"
                                {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }} — {{ $grupo->nivel->nombre ?? '' }} ({{ $grupo->sede->nombre ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('grupo_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Número de cuotas</label>
                        <input type="number" name="numero_cuotas" id="numero_cuotas" min="1" max="60"
                            class="form-control @error('numero_cuotas') is-invalid @enderror"
                            value="{{ old('numero_cuotas', 1) }}" onchange="mostrarInfoGrupo()">
                        <small class="form-text text-muted">Se sugiere al elegir el grupo, pero puedes ajustarla</small>
                        @error('numero_cuotas')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Periodicidad</label>
                        <select name="periodicidad" id="periodicidad"
                            class="form-control @error('periodicidad') is-invalid @enderror"
                            onchange="mostrarInfoGrupo()">
                            <option value="mensual" {{ old('periodicidad', 'mensual') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                            <option value="quincenal" {{ old('periodicidad') == 'quincenal' ? 'selected' : '' }}>Quincenal</option>
                        </select>
                        @error('periodicidad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Descuento (opcional)</label>
                    <select name="descuento_id" id="descuento_id"
                        class="form-control @error('descuento_id') is-invalid @enderror"
                        onchange="mostrarInfoGrupo()">
                        <option value="">-- Sin descuento --</option>
                        @foreach ($descuentos as $descuento)
                            <option value="{{ $descuento->id }}"
                                data-tipo="{{ $descuento->tipo }}"
                                data-valor="{{ $descuento->valor }}"
                                {{ old('descuento_id') == $descuento->id ? 'selected' : '' }}>
                                {{ $descuento->nombre }}
                                ({{ $descuento->tipo === 'porcentaje' ? $descuento->valor . '%' : '$' . number_format($descuento->valor, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('descuento_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div id="resumen" class="card card-outline card-info" style="display:none;">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-calculator"></i> Resumen de la matrícula</h5>
                        <table class="table table-sm">
                            <tr>
                                <td>Nivel</td>
                                <td id="r-nivel" class="text-right font-weight-bold"></td>
                            </tr>
                            <tr>
                                <td>Valor total del nivel</td>
                                <td id="r-valor" class="text-right"></td>
                            </tr>
                            <tr>
                                <td>Descuento aplicado</td>
                                <td id="r-descuento" class="text-right text-danger"></td>
                            </tr>
                            <tr>
                                <td>Cuotas / Periodicidad</td>
                                <td id="r-meses" class="text-right"></td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Valor por cuota mensual</strong></td>
                                <td id="r-cuota" class="text-right font-weight-bold text-success"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-save"></i> Generar matrícula y cuotas
                </button>
                <a href="{{ route('admin.matriculas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: Nuevo acudiente rápido --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalAcudiente" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white"><i class="fas fa-user-friends"></i> Nuevo acudiente</h5>
                    <button type="button" class="close text-white" onclick="cerrarModalAcudiente()">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="alerta-acudiente"></div>

                    <div class="form-group">
                        <label>Nombre completo *</label>
                        <input type="text" id="ac-nombre" class="form-control">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Parentesco</label>
                            <select id="ac-parentesco" class="form-control">
                                <option value="">-- Seleccionar --</option>
                                <option>Padre</option>
                                <option>Madre</option>
                                <option>Abuelo/a</option>
                                <option>Tío/a</option>
                                <option>Hermano/a</option>
                                <option>Tutor legal</option>
                                <option>Otro</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Teléfono</label>
                            <input type="text" id="ac-telefono" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Correo</label>
                        <input type="email" id="ac-correo" class="form-control">
                    </div>
                    <small class="text-muted">
                        Los demás datos (documento, dirección, observaciones) se pueden completar luego desde el módulo de Acudientes.
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModalAcudiente()">Cancelar</button>
                    <button type="button" class="btn btn-info" onclick="crearAcudienteAjax()">
                        <i class="fas fa-save"></i> Guardar acudiente
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: Nuevo alumno rápido --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalAlumno" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white"><i class="fas fa-swimmer"></i> Nuevo alumno</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="alerta-alumno"></div>

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Nombre completo *</label>
                            <input type="text" id="al-nombre" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Fecha de nacimiento</label>
                            <input type="date" id="al-fecha-nacimiento" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Tipo documento</label>
                            <select id="al-tipo-documento" class="form-control">
                                <option value="">-- Seleccionar --</option>
                                <option value="RC">Registro civil</option>
                                <option value="TI">Tarjeta de identidad</option>
                                <option value="CC">Cédula de ciudadanía</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Número documento</label>
                            <input type="text" id="al-numero-documento" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Sexo</label>
                            <select id="al-sexo" class="form-control">
                                <option value="">-- Seleccionar --</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Teléfono</label>
                            <input type="text" id="al-telefono" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Acudiente</label>
                            <div class="input-group">
                                <select id="al-acudiente-id" class="form-control">
                                    <option value="">-- Seleccionar acudiente --</option>
                                    @foreach ($acudientes as $acu)
                                        <option value="{{ $acu->id }}">{{ $acu->nombre_completo }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-info" onclick="abrirModalAcudienteDesdeAlumno()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small class="text-muted">
                        Los demás datos (foto, dirección, contacto de emergencia, observaciones) se pueden completar luego desde el módulo de Alumnos.
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="crearAlumnoAjax()">
                        <i class="fas fa-save"></i> Guardar alumno
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FIX: en vez de anidar modales (bug conocido de Bootstrap 4    --}}
    {{-- que bloquea la página), ocultamos el modal de Alumno mientras --}}
    {{-- se abre el de Acudiente. Al cerrar el de Acudiente (ya sea    --}}
    {{-- guardando o cancelando), reabrimos el de Alumno directamente  --}}
    {{-- en ese mismo momento — sin depender de listeners globales     --}}
    {{-- registrados al cargar la página, que pueden llegar tarde.     --}}
    {{-- ============================================================ --}}
    <script>
        function abrirModalAcudienteDesdeAlumno() {
            $('#modalAlumno').one('hidden.bs.modal', function () {
                $('#modalAcudiente').modal('show');
            });
            $('#modalAlumno').modal('hide');
        }

        function cerrarModalAcudiente() {
            $('#modalAcudiente').one('hidden.bs.modal', function () {
                $('#modalAlumno').modal('show');
            });
            $('#modalAcudiente').modal('hide');
        }
    </script>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';

        let grupoAnterior = null;

        function mostrarInfoGrupo() {
            const selectGrupo = document.getElementById('grupo_id');
            const selectDescuento = document.getElementById('descuento_id');
            const inputCuotas = document.getElementById('numero_cuotas');
            const selectPeriodicidad = document.getElementById('periodicidad');
            const opcionGrupo = selectGrupo.options[selectGrupo.selectedIndex];
            const opcionDescuento = selectDescuento.options[selectDescuento.selectedIndex];

            if (!opcionGrupo.value) {
                document.getElementById('resumen').style.display = 'none';
                grupoAnterior = null;
                return;
            }

            // Al cambiar de grupo (no al ajustar cuotas/periodicidad), sugerir el número de cuotas
            // según la duración del nivel y la periodicidad elegida
            if (opcionGrupo.value !== grupoAnterior) {
                const mesesNivel = parseInt(opcionGrupo.dataset.meses || 1);
                const sugerido = selectPeriodicidad.value === 'quincenal' ? mesesNivel * 2 : mesesNivel;
                inputCuotas.value = sugerido;
                grupoAnterior = opcionGrupo.value;
            }

            const valorNivel = parseFloat(opcionGrupo.dataset.valor || 0);
            const nombreNivel = opcionGrupo.dataset.nivel || '';
            const numeroCuotas = parseInt(inputCuotas.value || 1);
            const periodicidad = selectPeriodicidad.value;

            let descuentoAplicado = 0;
            if (opcionDescuento.value) {
                const tipo = opcionDescuento.dataset.tipo;
                const valorDescuento = parseFloat(opcionDescuento.dataset.valor || 0);
                descuentoAplicado = tipo === 'porcentaje'
                    ? valorNivel * (valorDescuento / 100)
                    : valorDescuento;
            }

            const valorConDescuento = valorNivel - descuentoAplicado;
            const valorCuota = numeroCuotas > 0 ? valorConDescuento / numeroCuotas : 0;

            const formatoMoneda = (valor) => '$' + valor.toLocaleString('es-CO', { maximumFractionDigits: 0 });
            const etiquetaPeriodo = periodicidad === 'quincenal' ? 'quincena' : 'mes';

            document.getElementById('r-nivel').textContent = nombreNivel;
            document.getElementById('r-valor').textContent = formatoMoneda(valorNivel);
            document.getElementById('r-descuento').textContent = descuentoAplicado > 0 ? '-' + formatoMoneda(descuentoAplicado) : '$0';
            document.getElementById('r-meses').textContent = numeroCuotas + ' cuota(s) — ' + (periodicidad === 'quincenal' ? 'quincenal' : 'mensual');
            document.getElementById('r-cuota').textContent = formatoMoneda(valorCuota) + ' cada ' + etiquetaPeriodo;

            document.getElementById('resumen').style.display = 'block';
        }

        function crearAcudienteAjax() {
            const nombre = document.getElementById('ac-nombre').value.trim();

            if (!nombre) {
                document.getElementById('alerta-acudiente').innerHTML =
                    '<div class="alert alert-danger">El nombre completo es obligatorio.</div>';
                return;
            }

            const formData = new FormData();
            formData.append('nombre_completo', nombre);
            formData.append('parentesco', document.getElementById('ac-parentesco').value);
            formData.append('telefono', document.getElementById('ac-telefono').value);
            formData.append('correo', document.getElementById('ac-correo').value);
            formData.append('estado', 'activo');

            fetch('{{ route('admin.acudientes.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => {
                if (!response.ok) throw new Error('Error al guardar');
                return response.json();
            })
            .then(data => {
                // Agregar la nueva opción al select de acudiente dentro del modal de alumno
                const select = document.getElementById('al-acudiente-id');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.nombre + (data.parentesco ? ' (' + data.parentesco + ')' : '');
                select.appendChild(option);
                select.value = data.id;

                // Limpiar formulario y cerrar modal
                document.getElementById('ac-nombre').value = '';
                document.getElementById('ac-parentesco').value = '';
                document.getElementById('ac-telefono').value = '';
                document.getElementById('ac-correo').value = '';
                document.getElementById('alerta-acudiente').innerHTML = '';
                cerrarModalAcudiente();

                Swal.fire({
                    icon: 'success',
                    title: 'Acudiente creado',
                    text: data.nombre + ' fue agregado correctamente',
                    timer: 1800,
                    showConfirmButton: false,
                });
            })
            .catch(() => {
                document.getElementById('alerta-acudiente').innerHTML =
                    '<div class="alert alert-danger">Ocurrió un error al guardar el acudiente.</div>';
            });
        }

        function crearAlumnoAjax() {
            const nombre = document.getElementById('al-nombre').value.trim();

            if (!nombre) {
                document.getElementById('alerta-alumno').innerHTML =
                    '<div class="alert alert-danger">El nombre completo es obligatorio.</div>';
                return;
            }

            const formData = new FormData();
            formData.append('nombre_completo', nombre);
            formData.append('fecha_nacimiento', document.getElementById('al-fecha-nacimiento').value);
            formData.append('tipo_documento', document.getElementById('al-tipo-documento').value);
            formData.append('numero_documento', document.getElementById('al-numero-documento').value);
            formData.append('sexo', document.getElementById('al-sexo').value);
            formData.append('telefono', document.getElementById('al-telefono').value);
            formData.append('acudiente_id', document.getElementById('al-acudiente-id').value);
            formData.append('estado', 'activo');

            fetch('{{ route('admin.alumnos.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => {
                if (!response.ok) throw new Error('Error al guardar');
                return response.json();
            })
            .then(data => {
                // Agregar la nueva opción al select principal de alumno
                const select = document.getElementById('alumno_id');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.nombre + (data.codigo ? ' (' + data.codigo + ')' : '');
                select.appendChild(option);
                select.value = data.id;

                // Limpiar formulario y cerrar modal
                document.getElementById('al-nombre').value = '';
                document.getElementById('al-fecha-nacimiento').value = '';
                document.getElementById('al-tipo-documento').value = '';
                document.getElementById('al-numero-documento').value = '';
                document.getElementById('al-sexo').value = '';
                document.getElementById('al-telefono').value = '';
                document.getElementById('al-acudiente-id').value = '';
                document.getElementById('alerta-alumno').innerHTML = '';
                $('#modalAlumno').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Alumno creado',
                    text: data.nombre + ' fue agregado y seleccionado automáticamente',
                    timer: 1800,
                    showConfirmButton: false,
                });
            })
            .catch(() => {
                document.getElementById('alerta-alumno').innerHTML =
                    '<div class="alert alert-danger">Ocurrió un error al guardar el alumno.</div>';
            });
        }
    </script>

@stop
