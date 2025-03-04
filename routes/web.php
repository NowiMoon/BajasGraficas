<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\RecibirJsonController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para mostrar el formulario
Route::get('/upload-form', function () {
    return view('upload-excel'); // Asegúrate de que la vista exista
});

// Ruta para procesar la subida del archivo
Route::post('/upload', [ExcelController::class, 'upload'])->name('upload');
Route::get('/upload-form', function () {
    return view('upload-excel');
});

Route::post('/recibir-json', [RecibirJsonController::class, 'recibirJson']);

Route::get('/resultados', function () {
    return view('resultados')->with('materias', session('materias', []));
})->name('resultados');
