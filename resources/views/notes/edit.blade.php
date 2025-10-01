@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
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
            <textarea name="content">{{ $note->content }}</textarea>
        </div>
        <div>
            <label>
                <input type="checkbox" name="important" value="1" id="important-checkbox" {{ $note->important ? 'checked' : '' }}>
                Prioridad alta
            </label>
        </div>
        <div id="date-field" @if($note->important) style="display: none;" @else style="display: block;" @endif>
            <label>Fecha de recordatorio</label>
            <input type="date" name="date" value="{{ $note->date ? date('Y-m-d\TH:i', strtotime($note->date)) : '' }}">
        </div>
        <button type="submit">Actualizar nota</button>
    </form>

    <script>
        document.getElementById('important-checkbox').addEventListener('change', function() {
            const dateField = document.getElementById('date-field');
            if (this.checked) {
                dateField.style.display = 'none';
                dateField.querySelector('input').value = '';
            } else {
                dateField.style.display = 'block';
            }
        });
    </script>
@endsection