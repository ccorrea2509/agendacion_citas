<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ServicioController;


Route::resource('Servicios', ServicioController::class)->middleware('auth');

use App\Http\Controllers\PuestoTrabajoController;

Route::Resource('Personal', PersonalController::class)->middleware('auth');
Route::resource('propietarios', PropietarioController::class)
    ->except('show')
    ->middleware(['auth', 'role:Administrador']);
Route::Resource('PuestoTrabajo', PuestoTrabajoController::class)->middleware('auth');

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
