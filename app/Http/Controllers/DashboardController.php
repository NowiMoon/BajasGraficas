<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Alumno;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::query();
        
        // Aplicar filtros si están presentes en la request
        if ($request->has('baja') && $request->baja !== 'todas') {
            $query->where('TBaja', $request->baja);
        }
        
        if ($request->has('generacion_desde') && $request->generacion_desde) {
            $query->where('Gen', '>=', $request->generacion_desde);
        }
        
        if ($request->has('generacion_hasta') && $request->generacion_hasta) {
            $query->where('Gen', '<=', $request->generacion_hasta);
        }
        
        if ($request->has('carrera') && $request->carrera !== 'todas') {
            $query->where('Carrera', $request->carrera);
        }
        
        if ($request->has('escuela') && $request->escuela !== 'todas') {
            $query->where('Escuela', $request->escuela);
        }
        
        if ($request->has('materia') && $request->materia !== 'todas') {
            $query->where(function($q) use ($request) {
                $q->where('Mat_1', $request->materia)
                  ->orWhere('Mat_2', $request->materia)
                  ->orWhere('Mat_3', $request->materia);
            });
        }
        
        if ($request->has('trabajo') && $request->trabajo !== 'todas') {
            $query->where('Empresa', $request->trabajo);
        }
        
        if ($request->has('tipo_titulacion') && $request->tipo_titulacion !== 'todas') {
            $query->where('Titulacion', $request->tipo_titulacion);
        }

        $alumnos = $query->get();
        
        // Si es una petición AJAX, retornar JSON
        if ($request->ajax()) {
            return response()->json([
                'alumnos' => $alumnos,
                'total' => $alumnos->count()
            ]);
        }
        
        return view('dashboard', compact('alumnos'));
    }
}