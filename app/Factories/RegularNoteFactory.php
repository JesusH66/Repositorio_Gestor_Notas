<?php

namespace App\Factories;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegularNoteFactory implements NoteFactoryInterface
{
    public function create(Request $request): array
    {
        // Validamos los datos
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'nullable|date',
        ]);

        // Determinamos la fecha de recordatorio
        $reminderDate = $request->filled('date') ? Carbon::parse($request->date) : null;

        // Regresamos el array de la nota
        return [
            'user_id' => Session::get('user_id'),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'important' => false,
            'date' => $reminderDate,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function update(Request $request, $id): array
    {
        // Validamos los datos para actualizar la nota
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'nullable|date',
        ]);

        // Determinamos la fecha de recordatorio
        $reminderDate = $request->filled('date') ? Carbon::parse($request->date) : null;

        //Regresamos el array de la nota
        return [
            'note_data' => [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'important' => false,
                'date' => $reminderDate,
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