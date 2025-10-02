<?php

namespace App\Facades;

use App\Adapters\GoogleKeepAdapter;
use App\Adapters\EvernoteAdapter;

class SubirNotas
{
    protected static $adapters = [
        'google_keep' => GoogleKeepAdapter::class,
        'evernote' => EvernoteAdapter::class,
    ];

    public static function enviar(array $noteData): string
    {
        $service = $noteData['service'] ?? 'google_keep';

        $adapterClass = self::$adapters[$service];
        $adapter = new $adapterClass();
        $adaptedData = $adapter->adapt($noteData);
        
        return json_encode($adaptedData, JSON_PRETTY_PRINT);
    }

    public static function registerAdapter(string $service, string $adapterClass): void
    {
        self::$adapters[$service] = $adapterClass;
    }
}