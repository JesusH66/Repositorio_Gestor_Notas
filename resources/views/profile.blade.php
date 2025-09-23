@extends('layouts.app')
@section('content')
    <h2>Profile</h2>
    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
    <h3>Update Password</h3>
    <form action="{{ route('profile.update-password') }}" method="POST">
        @csrf
        <div>
            <label>Contraseña anterior</label>
            <input type="password" name="current_password" required>
        </div>
        <div>
            <label>Nueva contraseña</label>
            <input type="password" name="new_password" required>
        </div>
        <div>
            <label>Confirmar nueva contraseña</label>
            <input type="password" name="new_password_confirmation" required>
        </div>
        <button type="submit">Actualizar Contraseña</button>
    </form>
@endsection