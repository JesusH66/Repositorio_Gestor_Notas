@extends('layouts.app')
@section('content')
    <h2>Welcome, {{ $user->name }}</h2>
    <p>Email: {{ $user->email }}</p>
    <a href="{{ route('notes.index') }}">Ver mis notas</a>
@endsection