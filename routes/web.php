<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AseguradorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PermisoController;



Route::get('/', function () {

    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('login');
});

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect('/aseguradores');
    }
    return view('login');
})->name('login');

Route::post('/check', [LoginController::class, 'check'])->name('login.check');


Route::get('/register', function () {
     if (auth()->check()) {
        return redirect('/aseguradores');
    }
    return view('register');
})->name('register');

Route::post('/register', [LoginController::class, 'register'])->name('register.store');

Route::middleware(['auth', 'permission'])->group(function () {

    // Ruta principal después del login (ejemplo)
    // La ruta 'dashboard' está en la lista blanca del middleware CheckPermission, por lo que no se bloqueará aquí.
    Route::get('/dashboard', function () {
        return redirect('aseguradores'); // Redirige a aseguradores como página principal del dashboard
    })->name('dashboard');

    // Aseguradores
    Route::get('aseguradores/trash', [AseguradorController::class, 'trash'])->name('aseguradores.trash');
    Route::resource('aseguradores', AseguradorController::class);

    // Categorías
    Route::get('categorias/trash', [CategoriaController::class, 'trash'])->name('categorias.trash');
    Route::resource('categorias', CategoriaController::class);

    // Sedes
    Route::get('sedes/trash', [SedeController::class, 'trash'])->name('sedes.trash');
    Route::resource('sedes', SedeController::class);

    // Usuarios
    Route::get('usuarios/trash', [UserController::class, 'trash'])->name('usuarios.trash');
    Route::resource('usuarios', UserController::class); 

    // Pacientes
    Route::get('pacientes/trash', [PacienteController::class, 'trash'])->name('pacientes.trash');
    Route::resource('pacientes', PacienteController::class);

    // La ruta 'logout' está en la lista blanca del middleware CheckPermission.
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

});
