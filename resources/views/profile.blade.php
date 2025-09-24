@extends('layouts.app')
@section('content')
    <div class="profile-info">
        <h2>Perfil</h2>
        <p>Nombre: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
    </div>
    <div class="profile-form">
        <h3>Actualizar contraseña</h3>
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
    </div>
@endsection