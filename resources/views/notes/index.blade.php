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
            @php
                $noteType = '';
                $noteBorderClass = '';
                if ($note->important) {
                    $noteType = 'important';
                    $noteBorderClass = 'important-note';
                } elseif ($note->date) {
                    $noteType = 'reminder';
                    $noteBorderClass = 'reminder-note';
                } else {
                    $noteType = 'normal';
                    $noteBorderClass = '';
                }
            @endphp
                <div class="card shadow-sm mb-3 {{ $noteBorderClass }} {{ $note->important ? 'border border-2 border-danger' : ($note->date ? 'border border-2 border-warning' : '') }}">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <h5 class="card-title mb-1 d-flex align-items-center gap-2">
                                    <span>{{ $note->title }}</span>
                                    @if($note->important)
                                        <span class="material-symbols-outlined" style="color: red;">assignment_late</span>
                                    @elseif($note->date)
                                        <span class="material-symbols-outlined" style="color: orange;">nest_clock_farsight_analog</span>
                                    @endif
                                </h5>
                                @if($note->date)
                                    <small class="text-muted">{{ date('d/m/Y H:i', strtotime($note->date)) }}</small>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('notes.edit', $note->id) }}" method="GET" style="display:inline-block;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                </form>
                                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <span class="material-symbols-outlined">delete_forever</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="card-text mt-1 mb-0">{{ $note->content }}</p>
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