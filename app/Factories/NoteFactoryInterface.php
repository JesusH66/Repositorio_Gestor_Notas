<?php

namespace App\Factories;

use Illuminate\Http\Request;

interface NoteFactoryInterface
{
    public function create(Request $request, string $type): array;
    public function update(Request $request, int $id): array;
}