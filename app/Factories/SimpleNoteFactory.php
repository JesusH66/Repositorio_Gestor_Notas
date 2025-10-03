<?php

namespace App\Factories;

use Illuminate\Http\Request;

class SimpleNoteFactory extends AbstractNoteFactory
{
    public function create(Request $request, string $type): array
    {
        $noteData = $this->getBaseNoteData($request);
        $noteData['important'] = false;
        $noteData['date'] = null;
        return $noteData;
    }

    public function update(Request $request, int $id): array
    {
        $updateData = $this->getBaseUpdateData($request, $id);
        $updateData['note_data']['important'] = false;
        $updateData['note_data']['date'] = null;
        return $updateData;
    }
}