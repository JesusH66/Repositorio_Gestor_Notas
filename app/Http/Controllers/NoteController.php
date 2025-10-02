<?php

namespace App\Http\Controllers;

use Illuminate\Bus\UpdatedBatchJobCounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\NotaMail;
use Carbon\Carbon;
use App\Builders\NoteJsonDirector;
use App\Builders\NoteJson;
use App\Factories\NoteFactoryInterface;
use App\Adapters\EvernoteAdapter;
use App\Adapters\GoogleKeepAdapter;
use App\Adapters\NoteAdapterInterface;
use App\Facades\SubirNotas;
use App\Factories\NoteFactory;

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

    public function __construct(NoteFactory $noteFactory)
    {
        $this->noteFactory = $noteFactory;
    }

    public function store(Request $request)
    {
        $type = $request->has('important') && $request->input('important') ? 'important' :
                ($request->filled('date') ? 'regular' : 'simple');

        $noteData = $this->noteFactory->create($request, $type);

        DB::table('notes')->insert($noteData);

        Mail::to('pruebaNotas@prueba.com')->send(new NotaMail());

        return redirect()->route('notes.index')->with('success', 'Nota creada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return redirect()->route('notes.index')->with('error', 'Nota no encontrada');
        }

        $data = $this->noteFactory->update($request, $id);
        DB::table('notes')
            ->where('id', $id)
            ->update($data['note_data']);

        DB::table('note_edits')->insert($data['note_edit_data']);

        return redirect()->route('notes.index')->with('success', 'Nota actualizada con éxito.');
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
        // Recupero la nota que se necesita
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

        // Creo el director y selecciono el builder a utilizar
        $director = new NoteJsonDirector();
        switch ($style) {
            case 'simple':
                $builder = new NoteJson();
                $method = 'buildSimpleJson';
                break;
            case 'intermedio':
                $builder = new NoteJson();
                $method = 'buildIntermediateJson';
                break;
            case 'avanzado':
                $builder = new NoteJson();
                $method = 'buildAdvancedJson';
                break;
            default:
                return response()->json(['error' => 'Estilo de exportación no válido.'], 400);
        }

        $director->setBuilder($builder);

        // Genero el JSON
        $json = $style === 'avanzado'
            ? $director->$method((array) $note, $wasEdited)
            : $director->$method((array) $note);

        // Retorno el Json que hemos creado en base al tipo
        return response()->json(['json' => $json]);
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


    public function sync(Request $request, $id)
    {
        // Busco la nota en la base de datos por ID y usuario
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        // Verifico si existe la nota
        if (!$note) {
            return response()->json(['error' => 'Nota no encontrada.'], 404);
        }

        // Llamo la fachada para transformar la nota al formato del servicio en concreto
        $data = SubirNotas::enviar((array) $note);
        // Retorno la nota como Json
        return response()->json(['data' => $data]);
    }

    public function updateService(Request $request, $id)
    {
        // Obtengo el servicio de la solicitud
        $service = $request->input('service');
        
        // Verifico si el servicio es válido
        if (!in_array($service, ['google_keep', 'evernote'])) {
            return response()->json(['error' => 'Servicio no válido.'], 400);
        }

        // Busco la nota en la base de datos
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        // Verifico si la nota existe
        if (!$note) {
            return response()->json(['error' => 'Nota no encontrada o no autorizada.'], 404);
        }

        // Actualizo el metadato del servicio
        DB::table('notes')
            ->where('id', $id)
            ->update(['service' => $service]);

        return response()->json(['message' => 'Servicio de sincronización actualizado.']);
    }
}