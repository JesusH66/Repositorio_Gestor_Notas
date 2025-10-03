<?php

namespace App\Facades;

use App\Adapters\GoogleKeepAdapter;
use App\Adapters\EvernoteAdapter;

class SubirNotas
{
    // Llamo a los adaptadores
    protected static $adapters = [
        'google_keep' => GoogleKeepAdapter::class,
        'evernote' => EvernoteAdapter::class,
    ];

    // Transformo la nota al formato del servicio y retorno el Json
    public static function enviar(array $noteData): string
    {
        // Obtengo el servicio de la nota
        $service = $noteData['service'] ?? 'google_keep';
        
        // Selecciono la clase del adaptador correspondiente al servicio
        if (!isset(self::$adapters[$service])) {
            throw new \Exception("Servicio no válido: $service");
        }
        
        // Retorno los datos como Json
        $adapterClass = self::$adapters[$service];
        $adapter = new $adapterClass();
        $adaptedData = $adapter->adaptador($noteData);
        
        return json_encode($adaptedData, JSON_PRETTY_PRINT);
    }

    public static function registerAdapter(string $service, string $adapterClass): void
    {
        self::$adapters[$service] = $adapterClass;
    }
}