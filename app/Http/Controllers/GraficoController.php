<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                $filtrado = array_filter($datos[7]);
                $materias = $filtrado;
                $data = array_count_values($materias); // Cuenta las ocurrencias de cada materia
                $total = count($materias);
                break;
            case 'carreras':
                $carreras = $datos[5];
                $data = array_count_values($carreras);
                $total = count($carreras);
                break;
            case 'generacion':
                $generaciones = $datos[4];
                $data = array_count_values($generaciones);
                $total = count($generaciones);
                break;
            default:
                return response()->json(['error' => 'No hay datos disponibles para este tipo'], 404);
        }

        return response()->json([
            'data' => $data,
            'total' => $total,
        ]);
    }
}
