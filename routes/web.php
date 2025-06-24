<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AseguradorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UserController; // Corregido de UsuarioController a UserController
use App\Http\Controllers\PacienteController;

Route::get('/', function () {
    // Si el usuario está autenticado, redirigir a una página de dashboard, sino al login
    if (auth()->check()) {
        return redirect('/aseguradores'); // O cualquier otra ruta principal post-login
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

Route::middleware(['auth'])->group(function () {

    // Ruta principal después del login (ejemplo)
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

    // Usuarios (recordar que el controlador se llama UserController)
    Route::get('usuarios/trash', [UserController::class, 'trash'])->name('usuarios.trash');
    Route::post('usuarios/{user}/permissions', [UserController::class, 'updatePermissions'])->name('usuarios.updatePermissions'); // Ruta para actualizar permisos
    Route::resource('usuarios', UserController::class); // El recurso debe coincidir con el nombre base de las rutas

    // Pacientes
    Route::get('pacientes/trash', [PacienteController::class, 'trash'])->name('pacientes.trash');
    Route::resource('pacientes', PacienteController::class);

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

});
