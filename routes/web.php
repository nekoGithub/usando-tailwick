<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rutas para la lista de usuarios
Route::middleware(['auth'])->group(function () {
    // Vista principal
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    // Obtener datos para DataTables
    Route::get('/users/data', [UserController::class, 'getUsersData'])->name('users.data');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Actualizar estado (PUT)
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

    // Eliminar usuario (DELETE)
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__ . '/auth.php';
