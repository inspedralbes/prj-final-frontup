@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Editar Projecte</h2>
    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nom del Projecte</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $project->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="html_code" class="form-label">Codi HTML</label>
            <textarea id="html_code" name="html_code" class="form-control" rows="5">{{ old('html_code', $project->html_code) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="css_code" class="form-label">Codi CSS</label>
            <textarea id="css_code" name="css_code" class="form-control" rows="5">{{ old('css_code', $project->css_code) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="js_code" class="form-label">Codi JS</label>
            <textarea id="js_code" name="js_code" class="form-control" rows="5">{{ old('js_code', $project->js_code) }}</textarea>
        </div>

        <select id="statuts" name="statuts" class="form-control">
            <option value="0" {{ old('statuts', $project->statuts) == 0 ? 'selected' : '' }}>Públic</option>
            <option value="1" {{ old('statuts', $project->statuts) == 1 ? 'selected' : '' }}>Privat</option>
        </select><br>


        <button type="submit" class="btn btn-success">Actualtizar</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a>
    </form><br><br>
</div>
@endsection
