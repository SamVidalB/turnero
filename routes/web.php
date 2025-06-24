<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AseguradorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermisoController; // Añadir PermisoController

Route::get('/', function () {
    return view('login');
});


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/check', [LoginController::class, 'check']);


Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [LoginController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {

    Route::get('aseguradores/trash', [AseguradorController::class, 'trash   ']);
    Route::resource('aseguradores', AseguradorController::class);

    // Rutas para la administración de permisos
    Route::get('permisos', [PermisoController::class, 'index'])->name('permisos.index');
    Route::get('permisos/{user}/acciones', [PermisoController::class, 'getUserAcciones'])->name('permisos.user.acciones');
    Route::post('permisos/{user}', [PermisoController::class, 'update'])->name('permisos.update');


    Route::get('logout', [LoginController::class, 'logout']);

});
