<?php

use App\Http\Controllers\CumpleaniosController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ExcepcionController;
use App\Http\Controllers\HorarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [EmployeeController::class, 'getAll'])->name('employeers.index');

Route::post('/employees/save',[EmployeeController::class, 'save']);
Route::get('/employee/find/{id}', [EmployeeController::class, 'find']);
Route::delete('/employee/delete/{id}', [EmployeeController::class, 'delete']);
Route::get('/employee/filter', [EmployeeController::class, 'filtrar']);


Route::get('/horarios/get/{id}', [HorarioController::class, 'getHorario'])->name('horarios.index');


Route::post('/excepciones/save', [ExcepcionController::class, 'saveExcepcion']);
Route::get('/excepciones/find/{id}', [ExcepcionController::class, 'verDetalle']);
Route::delete('/excepciones/delete/{id}', [ExcepcionController::class, 'eliminar']);


Route::get('/cumpleanios/getAllMesActual', [CumpleaniosController::class, 'getCumplesMesActual'])->name('cumpleanios.index');
Route::get('/cumpleanios/getCumples/{mes}', [CumpleaniosController::class, 'getCumplesPorMes']);


Route::get('/eventos/index', [EventoController::class, 'index'])->name('eventos.index');
Route::POST('/eventos/save', [EventoController::class, 'save']);
Route::get('/eventos/find/{id}', [EventoController::class, 'find']);
Route::delete('/eventos/delete/{id}', [EventoController::class, 'delete']);

