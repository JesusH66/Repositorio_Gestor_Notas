<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\NoteEdit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactoryExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        User::factory()->count(10)->has(Note::factory()->count(3)) ->create();

        $user = User::factory()->create([
            'name' => 'Prueba de factories',
            'email' => 'test@example.com',
        ]);

        Note::factory()->count(5)->forUser($user)->create();

        Note::factory()->count(3)->shortTitle()->create();

        Note::factory()->count(2)->longContent()->create();
    }
}
