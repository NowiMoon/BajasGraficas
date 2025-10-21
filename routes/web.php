<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\PrepararDatosController;
use App\Http\Controllers\RecibirJsonController;
use App\Http\Controllers\GraficoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\BotonesController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::User()->hasRole('admin')) {
            return redirect()->route('dashboard');
        } elseif (Auth::User()->hasRole('coordinador')) {
            return redirect()->route('dashboard');
        } elseif (Auth::User()->hasRole('trabajador')) {
            return redirect()->route('dashboard');
        }
        // Otros roles si aplica
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/descargar-pdf', [PDFController::class, 'downloadPDF'])->name('downloadPDF');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    // Rutas de administrador aquí
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});


// Rutas del Coordinador
Route::group(['middleware' => ['auth', 'coordinador']], function () {
    //Ruta vista Gestion de materias

    Route::get('gestion_materias', [MateriaController::class, 'index'])->name('gestion_materias');
    Route::post('gestion_materias', [MateriaController::class,'store'])->name('gestion_materias.store');
    Route::post('/upload_subjects', [ExcelController::class, 'uploadSubjects'])->name('upload_subjects');
    Route::post('/upload', [ExcelController::class, 'upload'])->name('upload');
    Route::post('/Preparar-datos', [PrepararDatosController::class, 'Preparar_Datos'])->name('Preparar_Datos');
});

/******************************************************************************************************************************************************************************/


Route::post('/upload', [ExcelController::class, 'upload'])->name('upload');
Route::get('/upload-form', function () {
    return view('dashboard');
});

Route::get('/recibir-json', [RecibirJsonController::class, 'recibirJson'])->name('recibirJson');

Route::get('/enviar', [ExcelController::class, 'enviarLista']);
Route::get('/recibir', [RecibirJsonController::class, 'recibirJson'])->name('recibirJson');

//-----------------------------------------------------------------------------------------------------------------
Route::post('/Preparar-datos', [PrepararDatosController::class, 'Preparar_Datos'])->name('Preparar_Datos');
Route::get('/get-data', [GraficoController::class, 'getData'])->name('get.data');
Route::get('/api/materias', [BotonesController::class, 'materias']);
Route::get('/api/trabajos', [BotonesController::class, 'trabajos']);
Route::get('/api/escuelas', [BotonesController::class, 'escuelas']);
Route::get('/api/generaciones', [BotonesController::class, 'generation']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
require __DIR__.'/auth.php';
