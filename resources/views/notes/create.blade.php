@extends('layouts.app')
@section('content')
    <h2>Crear nota</h2>
    <form action="{{ route('notes.store') }}" method="POST">
        @csrf
        <div>
            <label>Título de la nota</label>
            <input type="text" name="title" required>
        </div>
        <div>
            <label>Descripción de la nota</label>
            <textarea name="content" required></textarea>
        </div>
        <button type="submit">Crear nota</button>
    </form>
@endsection