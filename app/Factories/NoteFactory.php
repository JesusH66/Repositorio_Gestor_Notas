<?php

namespace App\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NoteFactory implements NoteFactoryInterface
{
    public function create(Request $request, string $type): array
    {
        $noteData = [
            'user_id' => Session::get('user_id'),
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'created_at' => now(),
            'updated_at' => now(),
            'export_style' => 'simple',
            'service' => null,
        ];

        switch ($type) {
            case 'regular':
                $noteData['date'] = $request->input('date');
                $noteData['important'] = false;
                break;
            case 'important':
                $noteData['important'] = $request->input('important', true) ? true : false;
                $noteData['date'] = null;
                break;
            case 'simple':
            default:
                $noteData['important'] = false;
                $noteData['date'] = null;
                break;
        }

        return $noteData;
    }

    public function update(Request $request, int $id): array
    {
        $noteData = [
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'updated_at' => now(),
        ];

        $noteEditData = [
            'note_id' => $id,
            'user_id' => Session::get('user_id'),
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'edit_date' => now(),
        ];

        if ($request->has('date')) {
            $noteData['date'] = $request->input('date');
        }
        if ($request->has('important')) {
            $noteData['important'] = $request->input('important') ? true : false;
        }

        return [
            'note_data' => $noteData,
            'note_edit_data' => $noteEditData
        ];
    }
}