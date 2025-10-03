<?php

namespace App\Factories;

use Illuminate\Http\Request;
use InvalidArgumentException;

class NoteFactory implements NoteFactoryInterface
{
    protected array $factories = [
        'simple' => \App\Factories\SimpleNoteFactory::class,
        'regular' => \App\Factories\RegularNoteFactory::class,
        'important' => \App\Factories\ImportantNoteFactory::class,
    ];

    public function create(Request $request, string $type): array
    {
        if (!isset($this->factories[$type])) {
            throw new InvalidArgumentException("Tipo de nota inválido: {$type}");
        }

        $factory = new $this->factories[$type]();
        return $factory->create($request, $type);
    }

    public function update(Request $request, int $id): array
    {
        // Determino el tipo de nota basado en los valores de la solicitud
        $type = $request->has('important') && $request->input('important') ? 'important' :
                ($request->filled('date') ? 'regular' : 'simple');

        if (!isset($this->factories[$type])) {
            throw new InvalidArgumentException("Tipo de nota inválido: {$type}");
        }

        $factory = new $this->factories[$type]();
        return $factory->update($request, $id);
    }
}