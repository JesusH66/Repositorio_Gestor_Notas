<?php
namespace App\Builders;

use InvalidArgumentException;

class NoteJsonFactory
{
    public static function getBuilder(string $type): NoteJsonInterface
    {
        switch ($type) {
            case 'simple':
                return new NoteJsonSimpleBuilder();
            case 'intermedio':
                return new NoteJsonNormalBuilder();
            case 'avanzado':
                return new NoteJsonAdvanceBuilder();
            default:
                throw new InvalidArgumentException("Tipo de nota no válido: $type");
        }
    }
}