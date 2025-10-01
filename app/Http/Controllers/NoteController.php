<?php

namespace App\Http\Controllers;

use Illuminate\Bus\UpdatedBatchJobCounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\NotaMail;
use Carbon\Carbon;

class NoteController extends Controller
{
    public function index(){
        // Obtengo notas del usuario con paginación de 6 por página
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

    public function store(Request $request){
        // Valido los datos de entrada del formulario
        
        $request->validate([
            'title'=>'required|string|max:255',
            'content'=>'required|string|',
            'important'=>'boolean',
            'date'=>'nullable|date',
        ]);

        // Determino si es importante
        $isImportant = $request->has('important') && $request->important;

        // Determino la fecha de recordatorio
        $reminderDate = null;
        if (!$isImportant && $request->filled('date')) {
            $reminderDate = Carbon::parse($request->date);
        }

        DB::table('notes')->insert([
            'user_id'=>Session::get('user_id'),
            'title'=>$request->title,
            'content'=>$request->content,
            'important'=>$isImportant,
            'date'=>$reminderDate,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        
        // Envío de correo electrónicopara prueba
        Mail::to('pruebaNotas@prueba.com')->send(new NotaMail());

        //Redirijo con un mensaje de éxito si es que se hizo la nota sin ningún problema
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

    public function update(Request $request, $id){
        // Valido los datos de entrada
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'important'=>'boolean',
            'date'=>'nullable|date',
        ]);

        $note = DB::table('notes')
            ->where('id',$id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        // Determino si es importante
        $isImportant = $request->has('important') && $request->important;
        
        $reminderDate = null;
        if (!$isImportant && $request->filled('date')) {
            $reminderDate = Carbon::parse($request->date);
        }
        DB::table('notes')
            ->where('id', $id)
            ->update([
                'title'=>$request->title,
                'content'=>$request->content,
                'important'=>$isImportant,
                'date'=>$reminderDate,
                'updated_at'=>now(),
            ]);

        DB::table('note_edits')->insert([
            'note_id' => $id,
            'user_id' => Session::get('user_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Redirijo con mensaje de éxito
        return redirect()->route('notes.index')->with('success', 'Nota actualizada con exito.');
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
}