<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AseguradorController;
use App\Http\Controllers\LoginController;

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

    Route::get('logout', [LoginController::class, 'logout']);

});
