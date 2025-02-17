@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<style>
    /* Fondo degradado similar al navbar */
    .hero {
        background: linear-gradient(90deg, #1a1a2e, #16213e);
        color: white;
        text-align: center;
        padding: 80px 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        animation: fadeIn 1s ease-in-out;
    }

    /* Animación de aparición */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero h1 {
        font-size: 48px;
        font-weight: bold;
    }

    .hero p {
        font-size: 18px;
        margin-top: 10px;
    }

    .btn-main {
        background-color: #f8b400;
        color: #1a1a2e;
        font-size: 18px;
        padding: 12px 24px;
        font-weight: bold;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
    }

    .btn-main:hover {
        background-color: #e09f3e;
        color: white;
    }

    /* Estilos de tarjetas */
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="hero">
    <h1>Bienvenido a Mi Aplicación</h1>
    <p>Administra usuarios y proyectos de manera sencilla y eficiente.</p>
    <a href="{{ route('projects.index') }}" class="btn btn-main mt-3">Ver Proyectos</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h3>Usuarios</h3>
            <p>Gestiona los usuarios de tu aplicación de forma sencilla.</p>
            <a href="{{ route('users.index') }}" class="btn btn-dark">Ver Usuarios</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h3>Proyectos</h3>
            <p>Administra tus proyectos con facilidad y rapidez.</p>
            <a href="{{ route('projects.index') }}" class="btn btn-dark">Ver Proyectos</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h3>Crear Proyecto</h3>
            <p>Empieza un nuevo proyecto con un solo clic.</p>
            <a href="{{ route('projects.create') }}" class="btn btn-dark">Crear</a>
        </div>
    </div>
</div>

@endsection
