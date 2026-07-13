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


use App\Http\Controllers\NominaController;

// Listado de nóminas (index)
Route::get('/nominas', [NominaController::class, 'index'])->name('nominas.index');

Route::patch('/nominas/{id}/pagar', [NominaController::class, 'pagar'])
->name('nominas.pagar');

// Generar preview (POST desde el form de fechas)
Route::post('/nominas/preview', [NominaController::class, 'preview'])->name('nominas.preview');

// Guardar nómina final (POST desde el botón "Guardar Nómina")
Route::post('/nominas/store', [NominaController::class, 'store'])->name('nominas.store');

Route::get('/nominas/{id}', [NominaController::class, 'show'])->name('nominas.show');

use App\Http\Controllers\ConfiguracionNominaController;

Route::prefix('configuracion-nomina')->group(function () {

    Route::get('/', [ConfiguracionNominaController::class, 'index'])
        ->name('config.index');

    Route::post('/parametros', [ConfiguracionNominaController::class, 'guardarParametros'])
        ->name('config.nomina.parametros');

    Route::post('/ir', [ConfiguracionNominaController::class, 'guardarIR'])
        ->name('config.nomina.ir');

    Route::delete('/ir/{id}', [ConfiguracionNominaController::class, 'eliminarIR'])
        ->name('config.nomina.ir.delete');

});

use App\Http\Controllers\NominaExportController;

Route::get('/nominas/{id}/pdf', [NominaExportController::class, 'pdf'])
    ->name('nominas.pdf');


Route::get('/nominas/{id}/csv', [NominaExportController::class, 'csv'])
->name('nominas.csv');

Route::get('/nominas/comprobante/{id}', [NominaExportController::class, 'comprobante'])
    ->name('nominas.comprobante');



//Reportes

use App\Http\Controllers\ReporteController;


Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

Route::get('/reportes/nomina/{id}', [ReporteController::class, 'empleadosPorNomina']);

Route::post('/reportes/solvencias', [ReporteController::class, 'generarSolvencias']);