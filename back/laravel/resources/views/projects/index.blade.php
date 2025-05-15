@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Llista de Projectes</h2>

    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Crear Projecte</a>

    <form action="{{ route('projects.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar projecte" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Cercar</button>
            </div>
        </div>
    </form>

    @if($projects->isEmpty())
        <p>No hi ha projectes disponibles.</p>
    @else
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
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">Editar</a>
                            
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

        <div class="custom-pagination">
            {{ $projects->appends(request()->input())->links() }}
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .custom-pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .custom-pagination .page-item a, 
    .custom-pagination .page-item span {
        color: #fff;
        background-color: #007bff;
        border: 1px solid #007bff;
        padding: 8px 14px;
        margin: 0 5px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
    }
    .custom-pagination .page-item a:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .custom-pagination .page-item.active span {
        background-color: #0056b3;
        border-color: #0056b3;
        font-weight: bold;
    }
    .custom-pagination .page-item.disabled span {
        background-color: #ddd;
        border-color: #ddd;
        color: #666;
        cursor: not-allowed;
    }
</style>
@endsection
