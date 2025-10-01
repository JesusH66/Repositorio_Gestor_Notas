<?php

namespace App\Http\Controllers;

use Illuminate\Bus\UpdatedBatchJobCounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\NotaMail;
use Carbon\Carbon;
use App\Factories\ImportantNoteFactory;
use App\Factories\RegularNoteFactory;
use App\Factories\NoteFactoryInterface;
use App\Factories\CommonNoteFactory;

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

    protected $regularNoteFactory;
    protected $importantNoteFactory;
    protected $commonNoteFactory;

    public function __construct(
        RegularNoteFactory $regularNoteFactory,
        ImportantNoteFactory $importantNoteFactory,
        CommonNoteFactory $commonNoteFactory
    ) {
        $this->regularNoteFactory = $regularNoteFactory;
        $this->importantNoteFactory = $importantNoteFactory;
        $this->commonNoteFactory = $commonNoteFactory;
    }

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

    
    // Función para exportar en formato JSON
    public function NotesJson($id){
        $type1 = DB::table('notes')
            ->where('id', $id)
            ->select([
                'title',
                'content',   
            ])
            ->get();

            return response()->json($type1);

        /*$type2 = DB::table('notes')
            ->where('id', $id)
            ->select([
                'user_id',
                'title',
                'content',
                'created_at'
            ])
            ->get();
            
            return response()->json($type2);

        $type3 = DB::table('notes')
            ->where('id', $id)
            ->select([
                'user_id',
                'title',
                'created_at',
                'updated_at',

            ])
            ->get();

            return response()->json($type3);*/

    }
}