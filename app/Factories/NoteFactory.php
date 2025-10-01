<?php

namespace App\Factories;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NoteFactory
{
    public function create(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'important' => 'boolean',
            'date' => 'nullable|date',
        ]);

        $isImportant = $request->has('important') && $request->important;

        $reminderDate = null;
        if (!$isImportant && $request->filled('date')) {
            $reminderDate = Carbon::parse($request->date);
        }

        return [
            'user_id' => Session::get('user_id'),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'important' => $isImportant,
            'date' => $reminderDate,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function update(Request $request, $id): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'important' => 'boolean',
            'date' => 'nullable|date',
        ]);

        $isImportant = $request->has('important') && $request->important;

        $reminderDate = null;
        if (!$isImportant && $request->filled('date')) {
            $reminderDate = Carbon::parse($request->date);
        }

        return [
            'note_data' => [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'important' => $isImportant,
                'date' => $reminderDate,
                'updated_at' => now(),
            ],
            'note_edit_data' => [
                'note_id' => $id,
                'user_id' => Session::get('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }
}