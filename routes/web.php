<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExcepcionController;
use App\Http\Controllers\HorarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [EmployeeController::class, 'getAll']);
Route::post('/employees/save',[EmployeeController::class, 'save']);
Route::get('/employee/find/{id}', [EmployeeController::class, 'find']);
Route::delete('/employee/delete/{id}', [EmployeeController::class, 'delete']);
Route::get('/horarios/get/{id}', [HorarioController::class, 'getHorario'])->name('horarios.index');

Route::post('/excepciones/save', [ExcepcionController::class, 'saveExcepcion']);