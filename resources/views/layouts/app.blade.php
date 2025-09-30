<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Notas</title>
    <link rel="stylesheet" href="{{ asset('css/enhanced-styles.css') }}">
</head>
<body>
    <div class="container">
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        @if (Session::has('user_id') && !Request::is('inicio', 'registro'))
            <div class="nav">
                <a href="{{ route('dashboard') }}">Panel</a>
                <a href="{{ route('profile') }}">Perfil</a>
                <a href="{{ route('notes.index') }}">Notas</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Cerrar Sesión</button>
        </form>
    </div>
@endif
        @yield('content')
    </div>
</body>
</html>