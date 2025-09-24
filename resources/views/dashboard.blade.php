@extends('layouts.app')
@section('content')
    <div class="dashboard-welcome">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <center><span class="material-symbols-outlined">dashboard</span></center>
        <h2>Bienvenido al gestor de notas, {{ $user->name }}</h2>
        <p>Email: {{ $user->email }}</p>
        <div class="dashboard-actions">
            <a href="{{ route('notes.index') }}">Ver mis notas</a>
        </div>
    </div>
@endsection