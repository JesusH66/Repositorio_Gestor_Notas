@extends('layouts.app')
@section('content')
    <h2>Mis notas</h2>
    <a href="{{ route('notes.create') }}">Crear nuevas notas</a>
    @if ($notes->isEmpty())
        <p>No se han creado notas.</p>
    @else
        @foreach ($notes as $note)
            <div class="note">
                <h3>{{ $note->title }}</h3>
                <p>{{ $note->content }}</p>
                <a href="{{ route('notes.edit', $note->id) }}">Editar notas</a>
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar la nota?')">Eliminar nota</button>
                </form>
            </div>
        @endforeach
    @endif
@endsection