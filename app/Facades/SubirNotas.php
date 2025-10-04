<?php
namespace App\Facades;

use App\Adapters\GoogleKeepAdapter;
use App\Adapters\EvernoteAdapter;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class SubirNotas extends Facade
{
    protected static $adapters = [
        'google_keep' => GoogleKeepAdapter::class,
        'evernote' => EvernoteAdapter::class,
    ];

    protected static function getFacadeAccessor()
    {
        return 'subir-notas';
    }

    public static function enviar(string $noteId, ?string $service = 'google_keep', ?string $userId = null)
    {
        try {
            // Valido el servicio
            if (!array_key_exists($service, self::$adapters)) {
                throw new InvalidArgumentException('Servicio no válido: ' . $service);
            }

            // Obtengo el user_id desde la sesión si no se proporciona
            $userId = $userId ?? session('user_id');
            if (!$userId) {
                throw new InvalidArgumentException('No se proporcionó un ID de usuario válido');
            }

            // Busco la nota en la base de datos
            $note = DB::table('notes')
                ->where('id', $noteId)
                ->where('user_id', $userId)
                ->first();

            if (!$note) {
                throw new InvalidArgumentException('Nota no encontrada.');
            }

            // Actualizo el metadato 'service' en la base de datos
            DB::table('notes')
                ->where('id', $noteId)
                ->update(['service' => $service]);

            // Preparo los datos para el adaptador
            $noteData = [
                'user_id' => $note->user_id,
                'title' => $note->title ?? '',
                'content' => $note->content ?? '',
                'created_at' => $note->created_at ?? now()->toDateTimeString(),
                'service' => $service,
            ];

            // Obtengo el adaptador y transformo los datos del formato que es necesario
            $adapterClass = self::$adapters[$service];
            $adapter = new $adapterClass();
            $adaptedData = $adapter->adaptador($noteData);

            // Retorno una respuesta JSON
            return response()->json([
                'success' => true,
                'message' => 'Servicio de sincronización actualizado.',
                'data' => $adaptedData,
            ], 200);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al sincronizar la nota: ' . $e->getMessage(),
            ], 500);
        }
    }
}