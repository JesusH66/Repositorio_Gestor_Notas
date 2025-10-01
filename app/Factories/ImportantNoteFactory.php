<?php

namespace App\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ImportantNoteFactory implements NoteFactoryInterface
{
    public function create(Request $request): array
    {
        // Valido los datos de entrada 
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Regresamos el array de la nota
        return [
            'user_id' => Session::get('user_id'),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'important' => true,
            'date' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function update(Request $request, $id): array
    {
        // Valido los datos de entrada
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Regreso el array de la nota
        return [
            'note_data' => [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'important' => true,
                'date' => null,
                'updated_at' => now(),
            ],
            'note_edit_data' => [
                'note_id' => $id,
                'user_id' => Session::get('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }
}