<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Alumno;

class GraficoController extends Controller
{
    public function getData($tipo)
    {
        $rutaArchivo = 'json/Datos1.json';
        $json = Storage::get($rutaArchivo);
        $datos = json_decode($json, true);

        $total = 0;

        // Aquí tu lógica para obtener los datos desde el array
        switch ($tipo) {
            case 'materias':
                //$materias = Alumno::pluck('Mat_1')->toArray();
                //$data = array_count_values($materias); // Cuenta las ocurrencias de cada materia
                //$total = count($materias);
                $materias = Alumno::pluck('Mat_1')->toArray();
                $materias = array_filter($materias); // Elimina nulls, strings vacíos y falsy values
                $data = array_count_values($materias);
                $total = count($materias);
                break;
            case 'carreras':
                $carreras = Alumno::pluck('Carrera')->toArray();
                $data = array_count_values($carreras);
                $total = count($carreras);
                break;
            case 'generacion':
                $generaciones = Alumno::pluck('Gen')->toArray();
                $data = array_count_values($generaciones);
                $total = count($generaciones);
                break;

                /*$startYear = request('start_year');
            $endYear = request('end_year');
            
            // Ejemplo de consulta filtrada
            $data = DB::table('alumnos')
                ->whereBetween('anio', [$startYear, $endYear])
                ->select('generacion', DB::raw('count(*) as total'))
                ->groupBy('generacion')
                ->pluck('total', 'generacion')
                ->toArray();
                
            $total = array_sum($data);
            break;*/

            default:
                return response()->json(['error' => 'No hay datos disponibles para este tipo'], 404);
        }

        return response()->json([
            'data' => $data,
            'total' => $total,
        ]);
    }
}
