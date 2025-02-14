@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Llista de Projectes</h2>

    <!-- Botón para crear un nuevo proyecto -->
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Crear Projecte</a>

    <!-- Formulario de búsqueda -->
    <form action="{{ route('projects.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar projecte" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Cercar</button>
            </div>
        </div>
    </form>

    <!-- Mensaje si no hay proyectos -->
    @if($projects->isEmpty())
        <p>No hi ha projectes disponibles.</p>
    @else
        <!-- Tabla de proyectos -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Status</th>
                    <th>Accions</th>
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
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Segur que vols eliminar aquest projecte?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</div>
@endsection
