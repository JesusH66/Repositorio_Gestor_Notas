<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NoteController extends Controller
{
    public function index()
    {
        $notes = DB::table('notes')
            ->where('user_id', Session::get('user_id'))
            ->get();
        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        DB::table('notes')->insert([
            'user_id' => Session::get('user_id'),
            'title' => $request->title,
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    public function edit($id)
    {
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return redirect()->route('notes.index')->with('error', 'Note not found.');
        }

        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return redirect()->route('notes.index')->with('error', 'Note not found.');
        }

        DB::table('notes')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'content' => $request->content,
                'updated_at' => now(),
            ]);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy($id)
    {
        $note = DB::table('notes')
            ->where('id', $id)
            ->where('user_id', Session::get('user_id'))
            ->first();

        if (!$note) {
            return redirect()->route('notes.index')->with('error', 'Note not found.');
        }

        DB::table('notes')->where('id', $id)->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully.');
    }
}