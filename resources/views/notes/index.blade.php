@extends('layouts.app')
@section('content')
    <h2>My Notes</h2>
    <a href="{{ route('notes.create') }}">Create New Note</a>
    @if ($notes->isEmpty())
        <p>No notes found.</p>
    @else
        @foreach ($notes as $note)
            <div class="note">
                <h3>{{ $note->title }}</h3>
                <p>{{ $note->content }}</p>
                <a href="{{ route('notes.edit', $note->id) }}">Editar nota</a>
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')">Eliminar nota</button>
                </form>
            </div>
        @endforeach
    @endif
@endsection