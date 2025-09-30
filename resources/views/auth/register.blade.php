@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
<center><span class="material-symbols-outlined">person_add</span>
    <h2>Crear cuenta</h2>
    <h3>Crea una cuenta para acceder y gestionar cada una de tus notas personales.</h3></center>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div>
            <label>Nombre completo</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit">Registrarse</button>
    </form>
    <p>¿Tienes una cuenta? <a href="{{ route('login') }}">Login</a></p>
@endsection