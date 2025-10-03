<?php

namespace App\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

abstract class AbstractNoteFactory implements NoteFactoryInterface
{
    // Proporciono lógica común en los métodos protegidos getBaseNoteData y getBaseUpdateData
    protected function getBaseNoteData(Request $request): array
    {
        // Represento los datos de la nota, que es el resultado del proceso de creación o actualización.
        return [
            'user_id' => Session::get('user_id'),
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'created_at' => now(),
            'updated_at' => now(),
            'export_style' => 'simple',
            'service' => null,
        ];
    }

    protected function getBaseUpdateData(Request $request, int $id): array
    {
        $noteData = [
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'updated_at' => now(),
        ];

        $noteEditData = [
            'note_id' => $id,
            'user_id' => Session::get('user_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return [
            'note_data' => $noteData,
            'note_edit_data' => $noteEditData
        ];
    }

    abstract public function create(Request $request, string $type): array;
    abstract public function update(Request $request, int $id): array;
}