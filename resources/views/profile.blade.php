@extends('layouts.app')
@section('content')
    <h2>Perfil</h2>
    <p>Nombre: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
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
            <label>Confirmar ueva contraseña</label>
            <input type="password" name="new_password_confirmation" required>
        </div>
        <button type="submit">Actualizar Contraseña</button>
    </form>
@endsection