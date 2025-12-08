<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Alumno;
use Illuminate\Support\Facades\Log;

class GraficoController extends Controller
{
    public function getData(Request $request)
    {
        Log::info('Parámetros recibidos:', $request->all());
        $data = $request->all();

        // Obtener valores individuales
        $tipoFiltro = $request->input('tipo_filtro'); // 'baja', 'generacion', 'carrera', etc.
        
        // Obtener datos de generación (si existen)
        $generacionDesde = $request->input('generacion_desde');
        $generacionHasta = $request->input('generacion_hasta');
        
        // Obtener otros filtros
        $carrera = $request->input('carrera');
        $trabajo = $request->input('trabajo');
        $baja = $request->input('baja');
        $escuela = $request->input('escuela');
        $materia = $request->input('materia');
        $tipoTitulacion = $request->input('tipo_titulacion');

        switch ($tipoFiltro){
            case 'baja':
                $registros = Alumno::query();
                if (!empty($baja) && $baja !== 'todas') {
                    $bajaTrim = mb_strtolower(trim($baja));
                    if (mb_strpos($bajaTrim, 'cambio de carrera') === 0) {
                        $registros->whereRaw('LOWER(TBaja) LIKE ?', [$bajaTrim . '%']);
                    } else {
                        $registros->where('TBaja', $baja);
                    }
                }

                if (!empty($generacionDesde) && !empty($generacionHasta)) {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);
                }
                if (!empty($materia) && $materia !== 'todas') {
                    $registros->where(function($q) use ($materia) {
                        $q->where('Mat_1', $materia)
                          ->orWhere('Mat_2', $materia)
                          ->orWhere('Mat_3', $materia);
                    });
                }
                if (!empty($escuela) && $escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);
                }
                if (!empty($carrera) && $carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);
                }
                if (!empty($tipoTitulacion) && $tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);
                }
                if (!empty($trabajo) && $trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);
                }
                
                $valores = $registros->pluck('TBaja')->all();
                $valores = array_filter($valores);

                $data = array_count_values($valores);
                $total = count($valores);
                break;
            case 'trabajo':
                if (empty($trabajo) || $trabajo === 'todas') {
                    $registros = Alumno::query();
                } else {
                    $registros = Alumno::query()->where('Empresa', $trabajo);
                }

                if (!empty($generacionDesde) && !empty($generacionHasta)) {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);
                }
                if (!empty($materia) && $materia !== 'todas') {
                    $registros->where(function($q) use ($materia) {
                        $q->where('Mat_1', $materia)
                          ->orWhere('Mat_2', $materia)
                          ->orWhere('Mat_3', $materia);
                    });
                }
                if (!empty($escuela) && $escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);
                }
                if (!empty($carrera) && $carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);
                }
                if (!empty($tipoTitulacion) && $tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);
                }
                if (!empty($baja) && $baja !== 'todas') {
                    $bajaTrim = mb_strtolower(trim($baja));
                    if (mb_strpos($bajaTrim, 'cambio de carrera') === 0) {
                        $registros->whereRaw('LOWER(TBaja) LIKE ?', [$bajaTrim . '%']);
                    } else {
                        $registros->where('TBaja', $baja);
                    }
                }
                
                $valores = $registros->pluck('Empresa')->all();
                $valores = array_filter($valores);

                $data = array_count_values($valores);
                $total = count($valores);
                break;
            case 'generacion':
                $registros = Alumno::query();
                
                if (!empty($generacionDesde) && !empty($generacionHasta)) {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);
                }
                if (!empty($materia) && $materia !== 'todas') {
                    $registros->where(function($q) use ($materia) {
                        $q->where('Mat_1', $materia)
                          ->orWhere('Mat_2', $materia)
                          ->orWhere('Mat_3', $materia);
                    });
                }
                if (!empty($escuela) && $escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);
                }
                if (!empty($baja) && $baja !== 'todas') {
                    $bajaTrim = mb_strtolower(trim($baja));
                    if (mb_strpos($bajaTrim, 'cambio de carrera') === 0) {
                        $registros->whereRaw('LOWER(TBaja) LIKE ?', [$bajaTrim . '%']);
                    } else {
                        $registros->where('TBaja', $baja);
                    }
                }
                if (!empty($tipoTitulacion) && $tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);
                }
                if (!empty($trabajo) && $trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);
                }
                if (!empty($carrera) && $carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);
                }
                
                $valores = $registros->pluck('Gen')->all();
                $valores = array_filter($valores);

                $data = array_count_values($valores);
                $total = count($valores);
                break;
            case 'carrera':
                if (empty($carrera) || $carrera === 'todas') {
                    $registros = Alumno::query();
                } else {
                    $registros = Alumno::query()->where('Carrera', $carrera);
                }

                if (!empty($generacionDesde) && !empty($generacionHasta)) {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);
                }
                if (!empty($materia) && $materia !== 'todas') {
                    $registros->where(function($q) use ($materia) {
                        $q->where('Mat_1', $materia)
                          ->orWhere('Mat_2', $materia)
                          ->orWhere('Mat_3', $materia);
                    });
                }
                if (!empty($escuela) && $escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);
                }
                if (!empty($baja) && $baja !== 'todas') {
                    $bajaTrim = mb_strtolower(trim($baja));
                    if (mb_strpos($bajaTrim, 'cambio de carrera') === 0) {
                        $registros->whereRaw('LOWER(TBaja) LIKE ?', [$bajaTrim . '%']);
                    } else {
                        $registros->where('TBaja', $baja);
                    }
                }
                if (!empty($tipoTitulacion) && $tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);
                }
                if (!empty($trabajo) && $trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);
                }
                
                $valores = $registros->pluck('Carrera')->all();
                $valores = array_filter($valores);

                $data = array_count_values($valores);
                $total = count($valores);
                break;
            case 'escuela':
                if (empty($escuela) || $escuela === 'todas') {
                    $registros = Alumno::query();
                } else {
                    $registros = Alumno::query()->where('Escuela', $escuela);
                }

                if (!empty($generacionDesde) && !empty($generacionHasta)) {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);
                }
                if (!empty($materia) && $materia !== 'todas') {
                    $registros->where(function($q) use ($materia) {
                        $q->where('Mat_1', $materia)
                          ->orWhere('Mat_2', $materia)
                          ->orWhere('Mat_3', $materia);
                    });
                }
                if (!empty($escuela) && $escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);
                }
                if (!empty($baja) && $baja !== 'todas') {
                    $bajaTrim = mb_strtolower(trim($baja));
                    if (mb_strpos($bajaTrim, 'cambio de carrera') === 0) {
                        $registros->whereRaw('LOWER(TBaja) LIKE ?', [$bajaTrim . '%']);
                    } else {
                        $registros->where('TBaja', $baja);
                    }
                }
                if (!empty($tipoTitulacion) && $tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);
                }
                if (!empty($trabajo) && $trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);
                }
                if (!empty($carrera) && $carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);
                }
                
                $valores = $registros->pluck('Escuela')->all();
                $valores = array_filter($valores);

                $data = array_count_values($valores);
                $total = count($valores);
                break;
            case 'materia':
                if (empty($materia) || $materia === 'todas') {
                    $registros = Alumno::query();
                } else {
                    $registros = Alumno::query()->where(function($q) use ($materia) {
                        $q->where('Mat_1', $materia)
                          ->orWhere('Mat_2', $materia)
                          ->orWhere('Mat_3', $materia);
                    });
                }
                if (!empty($generacionDesde) && !empty($generacionHasta)) {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);
                }
                if (!empty($baja) && $baja !== 'todas') {
                    $bajaTrim = mb_strtolower(trim($baja));
                    if (mb_strpos($bajaTrim, 'cambio de carrera') === 0) {
                        $registros->whereRaw('LOWER(TBaja) LIKE ?', [$bajaTrim . '%']);
                    } else {
                        $registros->where('TBaja', $baja);
                    }
                }
                if (!empty($escuela) && $escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);
                }
                if (!empty($carrera) && $carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);
                }
                if (!empty($tipoTitulacion) && $tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);
                }
                if (!empty($trabajo) && $trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);
                }

                $valores = $registros->pluck('Mat_1')->all();
                $valores = array_filter($valores);

                $data = array_count_values($valores);
                $total = count($valores);
                break;
            case 'titulacion':
                if (empty($tipoTitulacion) || $tipoTitulacion === 'todas') {
                    $registros = Alumno::query();
                } else {
                    $registros = Alumno::query()->where('Titulacion', $tipoTitulacion);
                }

                if (!empty($generacionDesde) && !empty($generacionHasta)) {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);
                }
                if (!empty($baja) && $baja !== 'todas') {
                    $bajaTrim = mb_strtolower(trim($baja));
                    if (mb_strpos($bajaTrim, 'cambio de carrera') === 0) {
                        $registros->whereRaw('LOWER(TBaja) LIKE ?', [$bajaTrim . '%']);
                    } else {
                        $registros->where('TBaja', $baja);
                    }
                }
                if (!empty($escuela) && $escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);
                }
                if (!empty($carrera) && $carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);
                }
                if (!empty($tipoTitulacion) && $tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);
                }
                if (!empty($trabajo) && $trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);
                }
                
                $valores = $registros->pluck('Titulacion')->all();
                $valores = array_filter($valores);

                $data = array_count_values($valores);
                $total = count($valores);

                break;
            default:
                return response()->json(['error' => 'No hay datos disponibles para este tipo'], 404);
        }

        arsort($data);
        //Esta linea ordena de mayor a menor uwu
        
        Log::info('Respuesta enviada:', ['data' => $data, 'total' => $total]);
        return response()->json([
            'data' => $data,
            'total' => $total,
        ]);
    }
}