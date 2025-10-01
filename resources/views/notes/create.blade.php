@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <div style="text-align: right; margin-bottom: 10px;">
        <a href="{{ route('notes.index') }}" class="close-button">X</a>
    </div>
    <center><span class="material-symbols-outlined">sticky_note_2</span>
        <h2>Crear nota</h2></center>
    <form action="{{ route('notes.store') }}" method="POST">
        @csrf
        <div>
            <label>Título de la nota</label>
            <input type="text" name="title" required>
        </div>
        <div>
            <label>Descripción de la nota</label>
            <textarea name="content"></textarea>
        </div>
        <div>
            <label align="right">
                <input type="checkbox" name="important" value="1" id="important-checkbox">
                Prioridad alta
            </label>
        </div>
        <div id="date-field" style="display: none;">
            <label>Fecha de recordatorio de la nota</label>
            <input type="datetime-local" name="date">
        </div>
        <button type="submit">Crear nota</button>
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