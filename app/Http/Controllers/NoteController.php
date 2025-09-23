<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Session; 

class NoteController extends Controller
{
    public function index()
    {
        // Obtengo notas del usuario con Query Builder: filtro por user_id y ejecuto con get()
        $notes = DB::table('notes')
            ->where('user_id', Session::get('user_id'))
            ->get();
        
        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        // Muestro la vista para crear notas
        return view('notes.create');
    }

    public function store(Request $request)
    {
        // Valido los datos de entrada del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Inserto nueva nota con insert() con user_id, título, contenido y fechas
        DB::table('notes')->insert([
            'user_id' => Session::get('user_id'),
            'title' => $request->title,
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirijo con un mensaje de éxito si es que se completó sin problema 
        return redirect()->route('notes.index')->with('success', 'Nota creada exitosamente.');
    }

    public function edit($id)
    {
        // Busco nota con con un filtro de id y user_id
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return redirect()->route('notes.index')->with('error', 'Nota no encontrada.');
        }

        return view('notes.edit', compact('note'));
    }

    
}