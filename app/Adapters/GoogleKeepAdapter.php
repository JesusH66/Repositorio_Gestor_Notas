<?php
    namespace App\Adapters;

    class GoogleKeepAdapter implements NoteAdapterInterface{
        public function adaptador(array $noteData): array
        {
            return[
                'usuario_id' => $noteData['user_id'],
                'note' => $noteData['content'],
                'title' => $noteData['title'],
                'date' => $noteData['created_at']
            ];
        }
    }