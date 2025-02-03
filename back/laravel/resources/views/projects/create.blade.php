@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Crear Proyecto</h2>
    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Proyecto</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="html_code" class="form-label">Código HTML</label>
            <textarea id="html_code" name="html_code" class="form-control" rows="5"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="css_code" class="form-label">Código CSS</label>
            <textarea id="css_code" name="css_code" class="form-control" rows="5"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="js_code" class="form-label">Código JS</label>
            <textarea id="js_code" name="js_code" class="form-control" rows="5"></textarea>
        </div>
        
        <!-- Si deseas que el usuario se asocie automáticamente al proyecto, 
             podrías ocultar este campo y asignarlo desde el controlador usando Auth::id() -->
        <div class="mb-3">
            <label for="user_id" class="form-label">ID de Usuario</label>
            <input type="number" id="user_id" name="user_id" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a>
    </form><br><br>
</div>
@endsection
