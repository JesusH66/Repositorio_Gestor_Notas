@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <center><span class="material-symbols-outlined">edit_square</span>
    <h2>EDITAR NOTA</h2></center>
    <div style="text-align: right; margin-bottom: 10px;">
        <a href="{{ route('notes.index') }}" class="close-button">X</a>
    </div>
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