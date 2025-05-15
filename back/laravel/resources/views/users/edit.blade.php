@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Editar Usuario</h2>
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
