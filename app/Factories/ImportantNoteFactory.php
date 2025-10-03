<?php

namespace App\Factories;

use Illuminate\Http\Request;

class ImportantNoteFactory extends AbstractNoteFactory
{
    // Cada clase concreta produce datos para un tipo específico de nota
    public function create(Request $request, string $type): array
    {
        $noteData = $this->getBaseNoteData($request);
        $noteData['important'] = $request->input('important', true) ? true : false;
        $noteData['date'] = null;
        return $noteData;
    }

    public function update(Request $request, int $id): array
    {
        $updateData = $this->getBaseUpdateData($request, $id);
        if ($request->has('important')) {
            $updateData['note_data']['important'] = $request->input('important') ? true : false;
        }
        $updateData['note_data']['date'] = null;
        return $updateData;
    }
}