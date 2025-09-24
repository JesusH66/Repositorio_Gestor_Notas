@extends('layouts.app')
@section('content')
    <h2>Mis Notas</h2>
    <a href="{{ route('notes.create') }}" class="btn">Crear Nueva Nota</a>

        <div class="note-stats">
            <p>Total de notas: <span class="stat-counter">{{ $totalNotes }}</span></p>
            <p>Notas de hoy: <span class="stat-counter">{{ $notesToday }}</span></p>
            <p>Notas editadas: <span class="stat-counter">{{ $editedNotes }}</span></p>
        </div>

    @if (count($notes) > 0)
        @foreach ($notes as $note)
            <div class="note-item">
                <div class="note-content">
                    <p>{{ $note->title }}</p>
                    <small>{{ $note->content }}</small>
                </div>
                <div class="actions">
                    <a href="{{ route('notes.edit', $note->id) }}">
                        <button>✏️</button>
                    </a>
                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button>🗑️</button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <p>No hay notas disponibles.</p>
    @endif
@endsection