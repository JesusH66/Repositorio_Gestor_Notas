<?php

namespace App\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NoteFactory implements NoteFactoryInterface
{
    public function create(Request $request, string $type): array
    {
        // Inicializo los datos básicos de la nota
        $noteData = [
            'user_id' => Session::get('user_id'),
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'created_at' => now(),
            'updated_at' => now(),
            'export_style' => 'simple',
            'service' => null,
        ];


        // Configuro campos especificos según el tipo de nota que se trata
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

    // Genero datos para actualizar una nota existente y registrar su edición
    public function update(Request $request, int $id): array
    {
        // Datos actualizados de la nota
        $noteData = [
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'updated_at' => now(),
        ];

        // Datos para registrar la edición en note_edits
        $noteEditData = [
            'note_id' => $id,
            'user_id' => Session::get('user_id'),
            'title' => $request->input('title', ''),
            'content' => $request->input('content', ''),
            'edit_date' => now(),
        ];

        //Actualizamos fecha e imporrtant si están presentes en la solicitud
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