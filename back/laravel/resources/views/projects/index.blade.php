@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Lista de Proyectos</h2>

    <!-- Botón para crear un nuevo proyecto -->
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Crear Proyecto</a>

    <!-- Formulario de búsqueda -->
    <form action="{{ route('projects.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar proyecto" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Mensaje si no hay proyectos -->
    @if($projects->isEmpty())
        <p>No hay proyectos disponibles.</p>
    @else
        <!-- Tabla de proyectos -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Status</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->nombre }}</td>
                        <td>{{ $project->statuts == 0 ? 'Público' : 'Privado' }}</td>
                        <td>
                            <!-- Botón de edición -->
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">Editar</a>
                            
                            <!-- Formulario para eliminar el proyecto -->
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este proyecto?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</div>
@endsection
