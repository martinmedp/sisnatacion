@extends('adminlte::page')

@section('content')

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Editar Docente
            </h3>

        </div>

        <div class="card-body">

            <form action="{{ route('admin.docentes.update', $docente->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @include('admin.docentes.form')

            </form>

        </div>

    </div>

@stop
