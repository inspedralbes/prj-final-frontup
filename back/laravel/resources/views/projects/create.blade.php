@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Crear Projecte</h2>
    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nom del Projecte</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="html_code" class="form-label">Codi HTML</label>
            <textarea id="html_code" name="html_code" class="form-control" rows="5"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="css_code" class="form-label">Codi CSS</label>
            <textarea id="css_code" name="css_code" class="form-control" rows="5"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="js_code" class="form-label">Codi JS</label>
            <textarea id="js_code" name="js_code" class="form-control" rows="5"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="user_id" class="form-label">ID d'Usuari</label>
            <input type="number" id="user_id" name="user_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="statuts" class="form-label">Estat del Projecte</label>
            <select id="statuts" name="statuts" class="form-control">
                <option value="0">Públic</option>
                <option value="1">Privat</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a>
    </form><br><br>
</div>
@endsection
