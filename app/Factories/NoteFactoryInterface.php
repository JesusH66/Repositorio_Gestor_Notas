<?php

namespace App\Factories;

use Illuminate\Http\Request;

interface NoteFactoryInterface
{
    public function create(Request $request): array;
    public function update(Request $request, $id): array;
}