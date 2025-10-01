@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary" title="Volver a Notas">
                <span class="material-symbols-outlined align-middle">close</span>
            </a>
            <div>
                <center>
                    <span class="material-symbols-outlined display-4 text-primary">sticky_note_2</span>
                    <h2 class="mt-2 text-decoration-underline">CREAR NUEVA NOTA</h2>
                </center>
            </div>
            <div style="width: 50px;"></div>
        </div>

        <form action="{{ route('notes.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-12">
                    <label for="title" class="form-label fw-bold">Título de la nota</label>
                    <input type="text" name="title" id="title" class="form-control" required placeholder="Título de tu nota">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="content" class="form-label fw-bold">Descripción de la nota</label>
                    <textarea name="content" id="content" class="form-control" rows="5" placeholder="Contenido de tu nota"></textarea>
                </div>
            </div>

            <div class="row mb-4 align-items-end">

                <div class="col-md-6">
                    <div class="form-check pt-3">
                        <input type="checkbox" name="important" value="1" class="form-check-input" id="important-checkbox">
                        <label class="form-check-label fw-bold" for="important-checkbox">
                            NOTA IMPORTANTE. <span class="material-symbols-outlined align-middle" style="font-size: 16px; color: red;">assignment_late</span>
                        </label>
                        <small class="form-text d-block text-muted">Marca para indicar que es una nota importante (oculta la fecha).</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="date-field">
                        <label for="date" class="form-label fw-bold">Fecha de recordatorio</label>
                        <input type="date" name="date" id="date" class="form-control">
                        <small class="form-text d-block text-muted">Establece la fecha de recordatorio.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary btn-lg mt-3">
                        <span class="material-symbols-outlined align-middle me-2">note_add</span>
                        Crear Nota
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const importantCheckbox = document.getElementById('important-checkbox');
            const dateField = document.getElementById('date-field');
            const dateInput = dateField.querySelector('input');

            function toggleDateField() {
                if (importantCheckbox.checked) {
                    dateField.style.display = 'none';
                    dateInput.value = '';
                } else {
                    dateField.style.display = 'block';
                }
            }
            toggleDateField();
            importantCheckbox.addEventListener('change', toggleDateField);
        });
    </script>
@endsection