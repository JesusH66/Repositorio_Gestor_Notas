@extends('layouts.app')
@section('content')
    <center><h2>Crear cuenta</h2>
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