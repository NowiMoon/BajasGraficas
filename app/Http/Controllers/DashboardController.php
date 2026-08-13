<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Alumno;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::query();
        
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
        
        if ($request->ajax()) {
            return response()->json([
                'alumnos' => $alumnos,
                'total' => $alumnos->count()
            ]);
        }
        
        // Obtener listas únicas directamente de la base de datos
        $materias = collect()
            ->merge(Alumno::distinct()->pluck('Mat_1'))
            ->merge(Alumno::distinct()->pluck('Mat_2'))
            ->merge(Alumno::distinct()->pluck('Mat_3'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $trabajos = Alumno::distinct()->whereNotNull('Empresa')->pluck('Empresa')->sort()->values();
        $escuelas = Alumno::distinct()->whereNotNull('Escuela')->pluck('Escuela')->sort()->values();
        $tiposBaja = Alumno::distinct()->whereNotNull('TBaja')->pluck('TBaja')->sort()->values();
        $generaciones = Alumno::distinct()->whereNotNull('Gen')->pluck('Gen')->sort(function ($a, $b) {
            return $b <=> $a;
        })->values();

        return view('dashboard', compact('alumnos', 'materias', 'trabajos', 'escuelas', 'tiposBaja', 'generaciones'));
    }

    public function destroy($id): RedirectResponse
    {
        $alumno = Alumno::where('Id_Registro', $id)->first();
        if (! $alumno) {
            return redirect()->back()->with('error', 'Registro no encontrado.');
        }
        $alumno->delete();
        return redirect()->route('dashboard')->with('success', 'Registro eliminado correctamente.');
    }

    public function destroyAll(Request $request): RedirectResponse
    {
        if (!Auth::check() || Auth::user()->user_type !== 1) {
            return redirect()->back()->with('error', 'No autorizado.');
        }

    try {
    // Truncate vacía la tabla y reinicia el ID en 0 automáticamente
    Alumno::truncate();
    
    return redirect()->route('dashboard')->with('success', 'Todos los alumnos han sido eliminados y el ID se ha reiniciado.');
} catch (\Throwable $e) {
    return redirect()->back()->with('error', 'Ocurrió un error al eliminar los registros.');
}}

    public function validateAdminPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if (! $user || $user->user_type !== 1) {
            return response()->json(['valid' => false, 'message' => 'No autorizado.'], 403);
        }

        $password = $request->input('password', '');
        if (! Hash::check($password, $user->password)) {
            return response()->json(['valid' => false, 'message' => 'Contraseña administrativa incorrecta.'], 200);
        }

        return response()->json(['valid' => true], 200);
    }
}