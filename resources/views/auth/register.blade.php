@extends('layouts.app')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-10">
                
                <div class="text-center mb-5">
                    <span class="material-symbols-outlined display-3 text-primary">person_add</span>
                    <h2 class="mt-2 fw-bold">Crear cuenta</h2>
                    <h3 class="lead text-muted">Crea una cuenta para acceder y gestionar cada una de tus notas personales.</h3>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nombre completo</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Tu nombre y apellido">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required placeholder="tu.correo@ejemplo.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required placeholder="Mínimo 8 caracteres">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Repite tu contraseña">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="material-symbols-outlined align-middle me-2">person_add</span>
                            Registrarse
                        </button>
                    </div>
                </form>

                <p class="text-center mt-4">
                    ¿Tienes una cuenta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Iniciar sesión</a>
                </p>

            </div> 
        </div> 
    </div> 
@endsection