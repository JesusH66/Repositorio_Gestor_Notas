<?php

namespace App\Factories;

use Illuminate\Http\Request;

class RegularNoteFactory extends AbstractNoteFactory
{
    public function create(Request $request, string $type): array
    {
        $noteData = $this->getBaseNoteData($request);
        $noteData['date'] = $request->input('date');
        $noteData['important'] = false;
        return $noteData;
    }

    public function update(Request $request, int $id): array
    {
        $updateData = $this->getBaseUpdateData($request, $id);
        if ($request->has('date')) {
            $updateData['note_data']['date'] = $request->input('date');
        }
        $updateData['note_data']['important'] = false;
        return $updateData;
    }
}