<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');



use App\Http\Controllers\AreaController;

Route::get('/areas', [AreaController::class,'index'])->name('areas.index');

Route::post('/areas', [AreaController::class,'store'])->name('areas.store');

use App\Http\Controllers\CargoController;

Route::get('/cargos', [CargoController::class,'index'])->name('cargos.index');
Route::post('/cargos', [CargoController::class,'store'])->name('cargos.store');

use App\Http\Controllers\EmpleadoController;

Route::get('/empleados', [EmpleadoController::class,'index'])->name('empleados.index');
Route::post('/empleados', [EmpleadoController::class,'store'])->name('empleados.store');
Route::put('/empleados/{id}', [EmpleadoController::class,'update'])->name('empleados.update');


use App\Http\Controllers\EmpleadoDiaController;

// Guardar día
Route::post('/empleados/dias', [EmpleadoDiaController::class, 'store'])
    ->name('empleados.dias.store');

// Obtener días por empleado
Route::get('/empleados/dias/{id}', [EmpleadoDiaController::class, 'obtenerPorEmpleado'])
    ->name('empleados.dias.obtener');


    Route::get('/nominas', function () {
    return view('nominas.index');
})->name('nominas.index');

Route::get('/nominas/preview', function () {
    return view('nominas.preview');
})->name('nominas.preview');