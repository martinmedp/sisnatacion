@extends('layouts.website')

@section('content')
    {{-- ── Hero de sección ──────────────────────────────────────────── --}}
    <section class="hero-section">
        <span class="hero-badge">{{ $configuracion->nombre ?? 'Sistema Web' }}</span>
        <h1>Contác<span class="accent">tanos</span></h1>
        <p class="hero-desc">
            Estamos disponibles para atender tus consultas e inquietudes.
        </p>

        @php
            $telefonoWhatsapp = preg_replace('/\D/', '', $configuracion->telefono1 ?? '');
            if (strlen($telefonoWhatsapp) === 10) {
                $telefonoWhatsapp = '57' . $telefonoWhatsapp; // Colombia
            }
            $mensajeWhatsapp = urlencode(
                'Hola, me gustaría más información sobre ' . ($configuracion->nombre ?? 'la institución') . '.',
            );
        @endphp
        @if ($telefonoWhatsapp)
            <a href="https://wa.me/{{ $telefonoWhatsapp }}?text={{ $mensajeWhatsapp }}" class="btn btn-success mt-3"
                target="_blank" rel="noopener">
                <i class="fab fa-whatsapp"></i> Escríbenos por WhatsApp
            </a>
        @endif
    </section>


    <div class="inst-divider"></div>

    {{-- ── Información de contacto ──────────────────────────────────── --}}
    <section class="inst-section">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="pilar-card info">
                        <span class="pilar-num">✉</span>
                        <span class="pilar-tag">Contacto</span>
                        <h4>{{ $configuracion->nombre }}</h4>

                        <div class="contacto-items">

                            <div class="contacto-item">
                                <span class="contacto-label">Dirección</span>
                                <span class="contacto-valor">{{ $configuracion->direccion }}</span>
                            </div>

                            <div class="contacto-item">
                                <span class="contacto-label">Teléfono</span>
                                <span class="contacto-valor">{{ $configuracion->telefono1 }}</span>
                            </div>

                            <div class="contacto-item">
                                <span class="contacto-label">Correo electrónico</span>
                                <a class="contacto-valor contacto-link"
                                    href="mailto:{{ $configuracion->correo_electronico }}">
                                    {{ $configuracion->correo_electronico }}
                                </a>
                            </div>

                            <div class="contacto-item">
                                <span class="contacto-label">Página web</span>
                                <a class="contacto-valor contacto-link" href="{{ $configuracion->web }}" target="_blank"
                                    rel="noopener">
                                    {{ $configuracion->web }}
                                </a>
                            </div>

                        </div>

                        @php
                            $telefonoWhatsapp = preg_replace('/\D/', '', $configuracion->telefono1 ?? '');
                            if (strlen($telefonoWhatsapp) === 10) {
                                $telefonoWhatsapp = '57' . $telefonoWhatsapp; // Colombia
                            }
                            $mensajeWhatsapp = urlencode(
                                'Hola, me gustaría más información sobre ' .
                                    ($configuracion->nombre ?? 'la institución') .
                                    '.',
                            );
                        @endphp
                        @if ($telefonoWhatsapp)
                            <a href="https://wa.me/{{ $telefonoWhatsapp }}?text={{ $mensajeWhatsapp }}"
                                class="btn btn-success mt-3" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp"></i> Escríbenos por WhatsApp
                            </a>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
