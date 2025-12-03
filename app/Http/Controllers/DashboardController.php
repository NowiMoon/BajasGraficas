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

    public function destroy($id): RedirectResponse
    {
        $alumno = Alumno::where('Id_Registro', $id)->first();
        if (! $alumno) {
            return redirect()->back()->with('error', 'Registro no encontrado.');
        }

        $alumno->delete();

        return redirect()->route('dashboard')->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Elimina todos los registros de la tabla alumnos.
     */
    public function destroyAll(Request $request): RedirectResponse
    {
        // protección extra en el controlador (middleware ya debe validar)
        if (!Auth::check() || Auth::user()->user_type !== 1) {
            return redirect()->back()->with('error', 'No autorizado.');
        }

        try {
            DB::transaction(function () {
                // Usar delete() para respetar eventos Eloquent; si quieres truncate, usar DB::table('alumnos')->truncate();
                Alumno::query()->delete();
            });

            return redirect()->route('dashboard')->with('success', 'Todos los alumnos han sido eliminados.');
        } catch (\Throwable $e) {
        //\Log::error('Error eliminando todos los alumnos: '.$e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar los registros.');
        }
    }

    /**
     * Valida la contraseña del usuario autenticado (AJAX).
     */
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