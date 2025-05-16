<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;

class BotonesController extends Controller
{
    public function getSelectores(){
        $materias = Alumno::pluck('Mat_1')->unique()->values()->all();
        $trabajos = Alumno::pluck('Empresa')->unique()->values()->all();
        $escuelas = Alumno::pluck('Escuela')->unique()->values()->all();

        return view('dashboard', [
            'escuelas' => $escuelas,
            'materias' => $materias,
            'trabajos' => $trabajos,
        ]);
    }
}
