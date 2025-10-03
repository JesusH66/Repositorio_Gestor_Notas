<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Realizo un registro con Query Builder donde debe introducir: nombre, email y contraseña
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Se ha completado el registro de manera éxitosa, Inicia sesión!.');
    }

    // Muestra el formulario de Login
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Datos incorrectos.');
    }

    // Muestro el dashboard principal 

    public function dashboard(){
        $user = DB::table('users')->where('id', Session::get('user_id'))->first();
        return view('dashboard', compact('user'));
    }

    public function profile(){
        $user = DB::table('users')->where('id', Session::get('user_id'))->first();
        return view('profile', compact('user'));
    }

    // Actualizo la contraseña mediante Query Builder pasando como parámetros la contraseña actual con la nueva
    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::table('users')->where('id', Session::get('user_id'))->first();

        if(!Hash::check($request->current_password, $user->password)){
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        DB::table('users')
            ->where('id', Session::get('user_id'))
            ->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success','Se ha actualizado la contraseña de manera exitosa.');    
    }

    // Función para poder cerrar sesión del sistema de notas
    public function logout(){
        Session::flush();
        return redirect()->route('login')->with('success','Ha cerrado sesión.');
    }

}