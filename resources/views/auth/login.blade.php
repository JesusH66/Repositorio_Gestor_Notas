@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
<center><span class="material-symbols-outlined">account_box</span>
    <h2>Inicio de sesión</h2>
    <h3>Accede con tu cuenta para gestionar cada una de tus notas personales.</h3></center>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Ingresar</button>
    </form>
    <p>¿No tienes una cuenta? <a href="{{ route('register') }}">Registrarse</a></p>
@endsection