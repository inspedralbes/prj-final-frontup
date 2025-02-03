@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Lista de Proyectos</h2>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Crear Proyecto</a>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <!-- Puedes agregar más columnas si lo deseas, por ejemplo, ID de Usuario -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->nombre }}</td>
                    <td>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">Editar</a>
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
</div>
@endsection
