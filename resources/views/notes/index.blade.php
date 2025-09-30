@extends('layouts.app')
@section('content')
    <center><span class="material-symbols-outlined">add_notes</span>
    <h2>MIS NOTAS</h2>
    <a href="{{ route('notes.create') }}" class="btn">Crear Nueva Nota</a></center>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <div class="note-stats">
            <p>Total de notas: <span class="stat-counter">{{ $totalNotes }}</span></p>
            <p>Notas de hoy: <span class="stat-counter">{{ $notesToday }}</span></p>
            <p>Notas editadas: <span class="stat-counter">{{ $editedNotes }}</span></p>
            <p>Número de ediciones: <span class="stat-counter">{{ $totalEditions }}</span></p>

        </div>

    @if ($notes->count() > 0)
        @foreach ($notes as $note)
            <div class="note-item">
                <div class="note-content">
                    <p>{{ $note->title }}</p>
                    <small>{{ $note->content }}</small>
                </div>
                <div class="actions">
                    <form action="{{ route('notes.edit', $note->id) }}" method="post style:inline">
                    <button><span class="material-symbols-outlined">edit</span></button>
                    </a>
                    </form>
                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button><span class="material-symbols-outlined">delete_forever</span></button>
                    </form>
                </div>
            </div>
        @endforeach
        <div class="pagination">
            {{ $notes->links() }}
        </div>
    @else
        <p>No hay notas disponibles.</p>
    @endif
@endsection