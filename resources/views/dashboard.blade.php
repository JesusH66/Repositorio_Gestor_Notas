@extends('layouts.app')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <div class="card shadow-lg p-4 text-center border-0 rounded-4">
                    <div class="card-body">
                        
                        <span class="material-symbols-outlined display-1 text-info mb-3">dashboard</span>

                        <h2 class="card-title fw-bold mb-3">
                            Bienvenido al gestor de notas, <span class="text-primary">{{ $user->name }}</span>
                        </h2>

                        <p class="card-text text-muted mb-4">
                            <span class="fw-semibold me-2">Tu Email:</span> {{ $user->email }}
                        </p>

                        <div class="dashboard-actions mt-4">
                            <a href="{{ route('notes.index') }}" class="btn btn-primary btn-lg rounded-pill">
                                <span class="material-symbols-outlined align-middle me-2">assignment</span>
                                Ver mis notas
                            </a>
                        </div>
                        
                    </div>
                </div> 
                
            </div> 
        </div> 
    </div> 

@endsection