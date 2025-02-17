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
        transform: scale(1.05);
        transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
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
    <h1>Benvingut al CRUD</h1>
    <p>Administra usuaris i projectes de manera sencilla i eficient.</p>
    <a href="{{ route('projects.index') }}" class="btn btn-main mt-3">Veure Projectes</a>
</div>

<!-- Centrado de las columnas -->
<div class="row d-flex justify-content-center">
    <div class="col-md-4 mb-4">
        <div class="card p-3 text-center">
            <h3>Usuaris</h3>
            <p>Gestiona els usuaris de la teva aplicació de forma sencilla.</p>
            <a href="{{ route('users.index') }}" class="btn btn-dark">Veure Usuaris</a>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card p-3 text-center">
            <h3>Projectes</h3>
            <p>Administra els teus projectes amb facilitat i rapidesa.</p>
            <a href="{{ route('projects.index') }}" class="btn btn-dark">Veure Projectes</a>
        </div>
    </div>
</div>

@endsection
