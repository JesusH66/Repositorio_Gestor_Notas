<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Notas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 5px; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 10px; text-decoration: none; color: #333; }
        .error { color: red; }
        .success { color: green; }
        form { margin: 20px 0; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; }
        button:hover { background: #555; }
        .note { border-bottom: 1px solid #ddd; padding: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        @if (auth()->check())
            <div class="nav">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('profile') }}">Perfil</a>
                <a href="{{ route('notes.index') }}">Notas</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>