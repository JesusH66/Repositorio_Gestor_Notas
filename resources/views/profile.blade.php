@extends('layouts.app')
@section('content')
    <div class="profile-info">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <center><span class="material-symbols-outlined">account_circle</span>
        <h2>PERFIL</h2></center>
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