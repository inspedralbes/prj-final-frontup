@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Llista d'Usuaris</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Crear Usuari</a>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Segur que vols eliminar aquest usuari?');">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
