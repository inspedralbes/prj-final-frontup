@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Tots els Projectes</h2>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Crear Projecte</a>

    <a href="{{ route('projects.all') }}" class="btn btn-info mb-3">Veure Tots els Projectes</a>
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar projecte" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

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
                    <td>{{ $project->statuts == 0 ? 'Públic' : 'Privat' }}</td>
                    <td>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Estàs segur?');">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
