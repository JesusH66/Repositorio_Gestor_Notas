<?php

namespace App\Http\Controllers;

use Illuminate\Bus\UpdatedBatchJobCounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Session; 

class NoteController extends Controller
{
    public function index(){
        // Obtengo notas del usuario con Query Builder: filtro por user_id y ejecuto con get()
        $notes = DB::table('notes')
            ->where('user_id', Session::get('user_id'))
            ->get();
        
        return view('notes.index', compact('notes'));
    }

    public function create(){
        // Muestro la vista para crear notas
        return view('notes.create');
    }


    public function store(Request $request){
        // Valido lso datos de entrada del formulario
        $request->validate([
            'title'=>'required|string|max:255',
            'content'=>'required|string',
        ]);

        // Inserto nueva nota con insert() con user_id, título, contenido y fechas
        DB::table('notes')->insert([
            'user_id'=>Session::get('user_id'),
            'title'=>$request->title,
            'content'=>$request->content,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

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
        ]);

        $note = DB::table('notes')
            ->where('id',$id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        // Si no existe, redirijo con error
        if(!$note){
            return redirect()->route('notes.index')->with('error', 'Nota no encontrada.');
        }

        // Actualizo nota con Query Builder: uso update() para modificar solamente el título y contenido
        DB::table('notes')
            ->where('id', $id)
            ->update([
                'title'=>$request->title,
                'content'=>$request->content,
                'updated_at'=>now(),
            ]);
        
        // Redirijo con mensaje de éxito
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

        //Redirijo con un mensaje de éxito
        return redirect()->route('notes.index')->with('success','Nota eliminada exitosamente');
    }

}