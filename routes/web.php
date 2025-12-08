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
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    // si es admin (user_type === 1) redirige a register
    /*
    if (Auth::check() && Auth::user()->user_type === 1) {
        return redirect()->route('register');
    }*/

    // pasar el Request al controlador
    return app(\App\Http\Controllers\DashboardController::class)->index($request);
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::delete('/alumnos/{id}', [DashboardController::class, 'destroy'])->name('alumnos.destroy');
    Route::post('/alumnos/destroy-all', [DashboardController::class, 'destroyAll'])->name('alumnos.destroyAll');
    Route::post('/admin/validate-password', [DashboardController::class, 'validateAdminPassword'])->name('admin.validate_password');
});

Route::patch('/users/{user}/toggle', [RegisteredUserController::class, 'toggle'])
    ->middleware(['auth','admin'])
    ->name('users.toggle');

Route::post('/users/{user}/reset-password', [RegisteredUserController::class, 'resetPassword'])
    ->middleware(['auth','admin'])
    ->name('users.reset');

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
Route::get('/api/tipos', [BotonesController::class, 'tipos']);
Route::get('/api/generaciones', [BotonesController::class, 'generation']);
Route::get('/api/suggestions', [DashboardController::class, 'getSuggestions'])->name('suggestions');
//Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
require __DIR__.'/auth.php';
