<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Código para crear un seeder

        $userId = DB::table('users')->insertGetId([
            'name' => 'Juanito Robles',
            'email' => 'juanito@gmail.com',
            'password' => bcrypt('665601ceab'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('notes')->insert([
            'title' => 'Creación de seeders',
            'content' => 'No olvidar crear los seeders en el proyecto',
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => 'Jesús Hernández Marin',
            'email' => '665601ceab',
            'password' => bcrypt('123456789'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('notes')->insert([
            'title' => 'Validación de creación de seeders',
            'content' => 'Segunda prueba de seeders',
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => 'Enrique Bonilla',
            'email' => 'Tercera prueba de seeders',
            'password' => bcrypt('123456789'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('notes')->insert([
            'title' => 'Segunda Validación de creación de seeders',
            'content' => 'Segunda prueba de seeders',
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
 
    }
}
