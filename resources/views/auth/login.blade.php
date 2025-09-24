@extends('layouts.app')
@section('content')
    <center><h2>Inicio de sesión</h2>
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