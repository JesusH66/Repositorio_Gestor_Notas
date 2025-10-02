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
use App\Builders\SimpleNoteJson;
use App\Builders\IntermediateNoteJson;
use App\Builders\AdvancedNoteJson;
use App\Factories\ImportantNoteFactory;
use App\Factories\RegularNoteFactory;
use App\Factories\CommonNoteFactory;
use App\Factories\NoteFactoryInterface;

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

    // Almaceno una instancia de un factory del tipo de nota que se trate
    protected $regularNoteFactory;
    protected $importantNoteFactory;
    protected $commonNoteFactory;

    // Constructor de Note_Controller
    public function __construct(
        RegularNoteFactory $regularNoteFactory,
        ImportantNoteFactory $importantNoteFactory,
        CommonNoteFactory $commonNoteFactory
    ) {
        $this->regularNoteFactory = $regularNoteFactory;
        $this->importantNoteFactory = $importantNoteFactory;
        $this->commonNoteFactory = $commonNoteFactory;
    }
     
    // Determino que factory utilizar en base a los datos enviados 
    protected function getNoteFactory(Request $request): NoteFactoryInterface
    {
        if ($request->has('important') && $request->important) {
            return $this->importantNoteFactory;
        } elseif ($request->filled('date')) {
            return $this->regularNoteFactory;
        }
        return $this->commonNoteFactory;
    }

    public function store(Request $request)
    {
        // Selecciono el factory para el tipo de nota que se trata
        $noteFactory = $this->getNoteFactory($request);

        // Creo los datos para la elaboración de la nota usanso el factory
        $noteData = $noteFactory->create($request);

        // Inserto la nota en la base de datos
        DB::table('notes')->insert($noteData);

        // Envío un correo (curso)
        Mail::to('pruebaNotas@prueba.com')->send(new NotaMail());

        // Redirijo con mensaje de éxito
        return redirect()->route('notes.index')->with('success', 'Nota creada exitosamente.');
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

    public function update(Request $request, $id)
    {
        // Selecciono el factory adecuado
        $noteFactory = $this->getNoteFactory($request);

        // Obtengo los datos de la nota y del registro de actualización usando factory
        $data = $noteFactory->update($request, $id);

        // Actualizo la nota en la base de datos
        DB::table('notes')
            ->where('id', $id)
            ->update($data['note_data']);

        // Registro la actualización en la tabla note_edits
        DB::table('note_edits')->insert($data['note_edit_data']);

        // Redirijo con un mensaje de éxito
        return redirect()->route('notes.index')->with('success', 'Nota actualizada con éxito.');
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

        // Creo el director y selecciono el builder a utilizar
        $director = new NoteJsonDirector();
        switch ($style) {
            case 'simple':
                $builder = new SimpleNoteJson();
                $method = 'buildSimpleJson';
                break;
            case 'intermedio':
                $builder = new IntermediateNoteJson();
                $method = 'buildIntermediateJson';
                break;
            case 'avanzado':
                $builder = new AdvancedNoteJson();
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
}