<?php

namespace App\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

abstract class AbstractNoteFactory implements NoteFactoryInterface
{
    protected function getBaseNoteData(Request $request): array
    {
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