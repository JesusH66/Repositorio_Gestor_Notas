<?php

namespace App\Factories;

use Illuminate\Http\Request;

interface NoteFactoryInterface
{
    // Defino los métodos create y update que todas las fábricas concretas deben implementar
    public function create(Request $request, string $type): array;
    public function update(Request $request, int $id): array;
}