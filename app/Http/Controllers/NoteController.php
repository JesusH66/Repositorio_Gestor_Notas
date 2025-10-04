<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\NotaMail;
use App\Builders\NoteJsonDirector;
use App\Builders\NoteJsonAdvance;
use App\Builders\NoteJsonSimple;
use App\Builders\NoteJsonNormal;
use App\Factories\NoteFactoryInterface;
use InvalidArgumentException;
use App\Adapters\EvernoteAdapter;
use App\Adapters\GoogleKeepAdapter;
use App\Facades\SubirNotas;
use App\Builders\NoteJsonFactory;
use Exception;

class NoteController extends Controller
{
    public function index(){
        // Paginamos la cantidad de notas a 5
        $notes = DB::table('notes')
            ->where('user_id', Session::get('user_id'))
            ->orderByDesc('created_at')
            ->paginate(5);
        
        return view('notes.index', compact('notes'));
    }

    public function create(){
        // Muestro la vista para crear notas
        return view('notes.create');
    }

    protected $noteFactory;

    public function __construct(NoteFactoryInterface $noteFactory)
    {
        $this->noteFactory = $noteFactory;
    }

    public function store(Request $request)
    {
        // Valido los datos de entrada
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'nullable|date',
            'important' => 'nullable|boolean',
        ]);

        // Determino el tipo de nota
        $type = $request->has('important') && $request->input('important') ? 'important' :
                ($request->filled('date') ? 'regular' : 'simple');

        try {
            // Creo los datos de la nota usando la fábrica
            $noteData = $this->noteFactory->create($request, $type);

            // Inserto en la base de datos
            DB::table('notes')->insert($noteData);

            // Envío correo
            Mail::to('pruebaNotas@prueba.com')->send(new NotaMail());

            return redirect()->route('notes.index')->with('success', 'Nota creada exitosamente.');
        } catch (InvalidArgumentException $e) {
            return redirect()->route('notes.index')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Valido los datos de entrada
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'nullable|date',
            'important' => 'nullable|boolean',
        ]);

        // Verifico que la nota existe y pertenece al usuario
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return redirect()->route('notes.index')->with('error', 'Nota no encontrada.');
        }

        try {
            // Obtengo los datos de actualización
            $data = $this->noteFactory->update($request, $id);

            // Actualizo la tabla 'notes'
            DB::table('notes')
                ->where('id', $id)
                ->update($data['note_data']);

            // Inserto en la tabla 'note_edits'
            DB::table('note_edits')->insert($data['note_edit_data']);

            return redirect()->route('notes.index')->with('success', 'Nota actualizada con éxito.');
        } catch (InvalidArgumentException $e) {
            return redirect()->route('notes.index')->with('error', $e->getMessage());
        }
    }

    public function edit($id){
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note){
            return redirect()->route('notes.index')->with('error', 'Nota no encontrada.');
        }
        return view('notes.edit', compact('note'));
    }

    public function destroy($id){
        //Verifico si la nota que se eliminará existe con Query Builder
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        // Si no existe la nota redirigimos con un error
        if(!$note){
            return redirect()->route('notes.index')->with('error', 'No se encuentra la nota.');
        }

        // Elimino nota usando delete() para borrar por id
        DB::table('notes')->where('id', $id)->delete();

        // Redirijo con un mensaje de éxito
        return redirect()->route('notes.index')->with('success','Nota eliminada exitosamente');
    }

    public function list(){
        $userId = Session::get('user_id');

        // Contamos las notas totales que se han hecho
        $totalNotes = DB::table('notes')->where('user_id', $userId)->count();

        // Contamos notas creadas hoy 
        $today = now()->toDateString();
        $notesToday = DB::table('notes')->where('user_id', $userId)->whereDate('created_at', '=', $today)->count();

        $editedNotes = DB::table('notes')
            ->where('user_id', $userId)
            ->whereRaw('updated_at > created_at')
            ->count();

        $totalEditions = DB::table('note_edits')
            ->where('user_id', $userId)
            ->count();
            
        $notes = DB::table('notes')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('notes.index', compact('notes', 'totalNotes', 'notesToday', 'editedNotes', 'totalEditions'));
    }

    
    public function export(Request $request, $id)
    {
        try {
            // Recupero la nota
            $note = DB::table('notes')
                ->where('id', $id)
                ->where('user_id', Session::get('user_id'))
                ->first();

            if (!$note) {
                return response()->json(['error' => 'Nota no encontrada'], 404);
            }

            // Verifico si fue editada
            $wasEdited = DB::table('note_edits')->where('note_id', $id)->exists();

            // Obtengo el estilo de exportación
            $style = $request->query('style', $note->export_style ?? 'simple');

            // Obtengo el builder usando el factory
            $builder = NoteJsonFactory::getBuilder($style);

            // Preparo los datos de la nota
            $noteData = [
                'title' => $note->title ?? '',
                'content' => $note->content ?? '',
                'user_id' => $note->user_id,
                'created_at' => $note->created_at ?? now()->toDateTimeString(),
                'updated_at' => $note->updated_at ?? null,
                'important' => $note->important ?? false,
                'date' => $note->reminder ?? null,
                'was_edited' => $wasEdited
            ];

            

            // Obtengo el JSON
            $json = $builder->getResult($noteData);

            // Actualizo el metadato export_style
            DB::table('notes')
                ->where('id', $id)
                ->update(['export_style' => $style]);

            // Retorno el JSON
            return response()->json(['json' => $json]);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al exportar la nota: ' . $e->getMessage()], 500);
        }
    }

    // Función para actualizar el campo style (hace referencia al tipo de exportación Json)
    public function updateExportStyle(Request $request, $id)
    {
        $request->validate([
            'export_style' => 'required|in:simple,intermedio,avanzado',
        ]);

        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return response()->json(['error' => 'Nota no encontrada'], 404);
        }

        DB::table('notes')
            ->where('id', $id)
            ->update(['export_style' => $request->export_style]);

        return response()->json(['message' => 'Estilo de exportación actualizado.']);
    }


    public function sync(Request $request, string $id)
    {
        // Obtener el servicio de la solicitud
        $service = $request->input('service', 'google_keep');

        // Llamar a la facade para procesar la sincronización
        return SubirNotas::enviar($id, $service);
    }
    
    /*
        public function sync(Request $request, $id)
    {
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return response()->json(['error' => 'Nota no encontrada o no autorizada.'], 404);
        }

        $service = $request->query('service', $note->service ?? 'google_keep');

        switch ($service) {
            case 'google_keep':
                $adapter = new GoogleKeepAdapter();
                break;
            case 'evernote':
                $adapter = new EvernoteAdapter();
                break;
            default:
                return response()->json(['error' => 'Servicio no válido.'], 400);
        }

        $adaptedData = $adapter->adapt((array) $note);
        return response()->json(['data' => json_encode($adaptedData, JSON_PRETTY_PRINT)]);
    }

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'service' => 'required|in:google_keep,evernote',
        ]);

        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return response()->json(['error' => 'Nota no encontrada o no autorizada.'], 404);
        }

        DB::table('notes')
            ->where('id', $id)
            ->update(['service' => $request->service]);

        return response()->json(['message' => 'Servicio de sincronización actualizado.']);
    }

    */
        
}