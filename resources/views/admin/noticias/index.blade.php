@extends('adminlte::page')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Noticias
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.noticias.create') }}" class="btn btn-primary">
                    Nueva Noticia
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th width="180">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($noticias as $noticia)
                        <tr>
                            <td>
                                {{ $noticia->id }}
                            </td>
                            <td>
                                <img src="{{ asset($noticia->imagen) }}" width="120">
                            </td>
                            <td>
                                {{ $noticia->titulo }}
                            </td>
                            <td>
                                {{ $noticia->fecha_publicacion }}
                            </td>
                            <td>
                                {{ $noticia->estado }}
                            </td>
                            <td>
                                <a href="{{ route('admin.noticias.edit', $noticia->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('admin.noticias.destroy', $noticia->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="noticia" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop
