<?php
namespace App\Builders;

use InvalidArgumentException;

class NoteBuilder
{
    public static function getBuilder(string $type): NoteJsonInterface
    {
        switch ($type) {
            case 'simple':
                return new NoteJsonSimple();
            case 'intermedio':
                return new NoteJsonNormal();
            case 'avanzado':
                return new NoteJsonAdvance();
            default:
                throw new InvalidArgumentException("Tipo de nota no válido: $type");
        }
    }
}