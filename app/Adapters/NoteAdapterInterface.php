<?php

namespace App\Adapters;

interface NoteAdapterInterface
{
    public function adaptador(array $noteData): array;
}