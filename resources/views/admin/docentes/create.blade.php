@extends('adminlte::page')

@section('content')

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Nuevo Docente
            </h3>

        </div>

        <div class="card-body">

            <form action="{{ route('admin.docentes.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                @include('admin.docentes.form')

            </form>

        </div>

    </div>

@stop
