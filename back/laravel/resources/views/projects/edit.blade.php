@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Editar Proyecto</h2>
    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Proyecto</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $project->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="html_code" class="form-label">Código HTML</label>
            <textarea id="html_code" name="html_code" class="form-control" rows="5">{{ old('html_code', $project->html_code) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="css_code" class="form-label">Código CSS</label>
            <textarea id="css_code" name="css_code" class="form-control" rows="5">{{ old('css_code', $project->css_code) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="js_code" class="form-label">Código JS</label>
            <textarea id="js_code" name="js_code" class="form-control" rows="5">{{ old('js_code', $project->js_code) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a>
    </form><br><br>
</div>
@endsection
