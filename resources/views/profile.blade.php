@extends('layouts.app')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="text-center mb-5">
                    <span class="material-symbols-outlined display-3 text-secondary">account_circle</span>
                    <h2 class="mt-2 fw-bold text-decoration-underline">PERFIL DE USUARIO</h2>
                </div>

                <div class="card shadow-sm mb-5 border-0 rounded-3">
                    <div class="card-header bg-primary text-white fw-bold rounded-top-3">
                        <span class="material-symbols-outlined align-middle me-2">person</span>
                        Detalles de la Cuenta
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <span class="fw-bold me-2">Nombre:</span> {{ $user->name }}
                        </p>
                        <p class="mb-0">
                            <span class="fw-bold me-2">Email:</span> {{ $user->email }}
                        </p>
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-warning fw-bold rounded-top-3">
                        <span class="material-symbols-outlined align-middle me-2">lock_reset</span>
                        Actualizar Contraseña
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update-password') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-bold">Contraseña anterior</label>
                                <input type="password" name="current_password" id="current_password" class="form-control" required placeholder="Ingresa tu contraseña actual">
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label fw-bold">Nueva contraseña</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required placeholder="Mínimo 8 caracteres">
                            </div>
                            
                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label fw-bold">Confirmar nueva contraseña</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required placeholder="Repite la nueva contraseña">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold">
                                    <span class="material-symbols-outlined align-middle me-2">save</span>
                                    Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div> 

            </div> 
        </div> 
    </div> 

@endsection