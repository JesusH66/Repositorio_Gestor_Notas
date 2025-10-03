<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas porque requiere autenticación
Route::middleware('auth.custom')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update-password', [AuthController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes', [NoteController::class, 'list'])->name('notes.index');
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::get('/notes/{id}/export', [NoteController::class, 'export'])->name('notes.export');
    Route::post('/notes/{id}/exportarEstilo', [NoteController::class, 'updateExportStyle'])->name('notes.export-style');
    Route::post('/notes/{id}/sync', [NoteController::class, 'sync'])->name('notes.sync');
});

// Redirige al root
Route::get('/', fn () => redirect()->route('login'));
