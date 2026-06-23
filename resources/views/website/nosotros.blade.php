@extends('layouts.website')

@section('content')
    <div class="text-center mb-5">

        <h1>
            Nosotros
        </h1>

        <p class="text-muted">
            Conoce nuestra identidad institucional,
            propósito y trayectoria.
        </p>

    </div>

    @if (!empty($configuracion->historia))
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <h3>
                    Historia Institucional
                </h3>

                <hr>

                {!! nl2br(e($configuracion->historia)) !!}

            </div>

        </div>
    @endif

    <div class="row">

        <div class="col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <h3>
                        Misión
                    </h3>

                    <hr>

                    {!! nl2br(e($configuracion->mision ?? '')) !!}

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <h3>
                        Visión
                    </h3>

                    <hr>

                    {!! nl2br(e($configuracion->vision ?? '')) !!}

                </div>

            </div>

        </div>

    </div>
@endsection
