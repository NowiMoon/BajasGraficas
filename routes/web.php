<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MateriaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/formulario', function () {
    return view('formulario');
});

Route::post('/normalizar', [MateriaController::class, 'normalizar']);
