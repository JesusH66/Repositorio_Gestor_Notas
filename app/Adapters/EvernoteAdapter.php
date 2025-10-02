<?php

    namespace App\Adapters;
 
    class EvernoteAdapter implements NoteAdapterInterface
    {
        public function adaptador(array $noteData): array
        {
            return [
                'usuario_id' => $noteData['user_id'],
                'nota' => [
                    'descripcion' => $noteData['content'],
                    'titulo' => $noteData['title']
                ]
            ];
        }
    }