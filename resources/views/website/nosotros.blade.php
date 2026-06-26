@extends('layouts.website')

@section('content')
    {{-- ── Hero de sección ──────────────────────────────────────────── --}}
    <section class="hero-section">
        <span class="hero-badge">{{ $configuracion->nombre ?? 'Sistema Web' }}</span>
        <h1>Quiénes <span class="accent">somos</span></h1>
        <p class="hero-desc">
            Conoce nuestra identidad institucional, propósito y trayectoria.
        </p>
    </section>

    <div class="inst-divider"></div>

    {{-- ── Historia ─────────────────────────────────────────────────── --}}
    @if (!empty($configuracion->historia))
        <section class="inst-section">
            <div class="container">

                <div class="section-header">
                    <div>
                        <span class="section-eyebrow">Trayectoria</span>
                        <h2 class="section-title">Historia Institucional</h2>
                    </div>
                </div>

                <p class="section-desc">
                    {!! nl2br(e($configuracion->historia)) !!}
                </p>

            </div>
        </section>

        <div class="inst-divider"></div>
    @endif

    {{-- ── Misión · Visión ──────────────────────────────────────────── --}}
    <section class="inst-section">
        <div class="container">

            <div class="section-header">
                <div>
                    <span class="section-eyebrow">Identidad</span>
                    <h2 class="section-title">Nuestra razón de ser</h2>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="pilar-card mision h-100">
                        <span class="pilar-num">01</span>
                        <span class="pilar-tag">Misión</span>
                        <h4>¿Para qué existimos?</h4>
                        <p>{!! nl2br(e($configuracion->mision ?? 'La misión institucional no ha sido definida aún.')) !!}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="pilar-card vision h-100">
                        <span class="pilar-num">02</span>
                        <span class="pilar-tag">Visión</span>
                        <h4>¿Hacia dónde vamos?</h4>
                        <p>{!! nl2br(e($configuracion->vision ?? 'La visión institucional no ha sido definida aún.')) !!}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
