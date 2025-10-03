@extends('layouts.app')

@section('content')
    <center><span class="material-symbols-outlined">add_notes</span>
    <h2 style="text-decoration: underline">MIS NOTAS</h2>
    <a href="{{ route('notes.create') }}" class="btn" style="color:blue; text-decoration: underline">Crear Nueva Nota</a></center>
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
                    $noteBorderClass = 'normal-note';
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
                                @if($note->important)
                                    <small class="text-muted">NOTA IMPORTANTE.</small>
                                @endif
                
                                @if($note->date)
                                    <!-- Si queremos colocar también la hora solo colocamos H:i pero de momento solo quiero la fecha-->
                                    <small class="text-muted"> Recordatorio de la nota: {{ date('d/m/Y', strtotime($note->date)) }}.</small>
                                @endif

                                @if($noteType=='normal')
                                    <small class="text-muted">Nota normal.</small>
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
                                <button class="btn btn-sm btn-outline-success" onclick="openExportModal('{{ $note->id }}')" title="Exportar">
                                    <span class="material-symbols-outlined">file_download</span>
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="openSyncModal('{{ $note->id }}')" title="Enviar nota">
                                    <span class="material-symbols-outlined">sync</span>
                                </button>
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

    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exportar Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>Selecciona el nivel de exportación:</p>
                    <div class="d-flex flex-column gap-2">
                        <button class="btn btn-primary" onclick="exportNote(currentNoteId, 'simple')">Simple</button>
                        <button class="btn btn-primary" onclick="exportNote(currentNoteId, 'intermedio')">Intermedio</button>
                        <button class="btn btn-primary" onclick="exportNote(currentNoteId, 'avanzado')">Avanzado</button>
                    </div>
                    <pre id="jsonContent" class="mt-3 border p-3 bg-light" style="max-height: 300px; overflow-y: auto;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="syncModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir Nota a servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>Selecciona el servicio:</p>
                    <div class="d-flex flex-column gap-2">
                        <button class="btn btn-primary" onclick="syncNote(currentNoteId, 'google_keep')">Google Keep</button>
                        <button class="btn btn-primary" onclick="syncNote(currentNoteId, 'evernote')">Evernote</button>
                    </div>
                    <pre id="syncContent" class="mt-3 border p-3 bg-light" style="max-height: 300px; overflow-y: auto;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentNoteId = null;

        function openExportModal(noteId) {
            currentNoteId = noteId;
            $('#jsonContent').text('Selecciona un estilo para exportar');
            new bootstrap.Modal(document.getElementById('exportModal')).show();
        }

        function exportNote(noteId, style) {
            $.ajax({
                url: `/notes/${noteId}/export?style=${style}`,
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                success: function(data) {
                    $('#jsonContent').text(data.json);
                    $.ajax({
                        url: `/notes/${noteId}/exportarEstilo`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({ export_style: style }),
                        success: function() {
                            console.log('Estilo de exportación actualizado');
                        },
                        error: function(xhr) {
                            console.error('Error al actualizar estilo:', xhr.responseJSON.error);
                        }
                    });
                },
                error: function(xhr) {
                    $('#jsonContent').text('Error: ' + xhr.responseJSON.error);
                }
            });
        }

        function openSyncModal(noteId) {
            currentNoteId = noteId;
            $('#syncContent').text('Selecciona un servicio');
            new bootstrap.Modal(document.getElementById('syncModal')).show();
        }

        function syncNote(noteId, service) {
            $.ajax({
                url: `/notes/${noteId}/sync?service=${service}`,
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                success: function(data) {
                    $('#syncContent').text(data.data);
                    $.ajax({
                        url: `/notes/${noteId}/service`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({ service: service }),
                        success: function() {
                            console.log('Servicio actualizado');
                        },
                        error: function(xhr) {
                            console.error('Error al actualizar servicio:', xhr.responseJSON.error);
                        }
                    });
                },
                error: function(xhr) {
                    $('#syncContent').text('Error: ' + xhr.responseJSON.error);
                }
            });
        }
    </script>
@endsection