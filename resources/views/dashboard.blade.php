@extends('layouts.app')
@section('content')
    <div class="dashboard-welcome">
        <h2>Bienvenido al gestor de notas, {{ $user->name }}</h2>
        <p>Email: {{ $user->email }}</p>
        <div class="dashboard-actions">
            <a href="{{ route('notes.index') }}">Ver mis notas</a>
        </div>
    </div>
@endsection