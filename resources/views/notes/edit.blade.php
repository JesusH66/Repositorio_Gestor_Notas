@extends('layouts.app')
@section('content')
    <h2>Editar nota</h2>
    <form action="{{ route('notes.update', $note->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Título de la nota</label>
            <input type="text" name="title" value="{{ $note->title }}" required>
        </div>
        <div>
            <label>Descripción de la nota</label>
            <textarea name="content" required>{{ $note->content }}</textarea>
        </div>
        <button type="submit">Actualizar nota</button>
    </form>
@endsection