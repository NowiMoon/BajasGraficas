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
                if($baja === 'todas')
                {
                    $registros = Alumno::query();
                }
                else
                {
                    $registros = Alumno::query()
                        ->where('TBaja', $baja);
                }

                if($generacionDesde !== null && $generacionHasta !== null)
                {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);}
                if ($materia !== 'todas') {
                    $registros->where('Mat_1', $materia);}
                if ($escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);}
                if ($carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);}
                if ($tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);}
                if ($trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);}
                
                $valoresBaja = $registros->pluck('TBaja')->all(); // Convertir a array
                $valoresBaja = array_filter($valoresBaja); // Elimina null, false, '', 0, etc.

                $data = array_count_values($valoresBaja);
                $total = count($valoresBaja);
                break;
            case 'trabajo':
                if($trabajo === 'todas')
                {
                    $registros = Alumno::query();
                }
                else
                {
                    $registros = Alumno::query()
                        ->where('Empresa', $trabajo);
                }

                if($generacionDesde !== null && $generacionHasta !== null)
                {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);}
                if ($materia !== 'todas') {
                    $registros->where('Mat_1', $materia);}
                if ($escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);}
                if ($carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);}
                if ($tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);}
                if ($baja !== 'todas') {
                    $registros->where('Tbaja', $baja);}
                
                $valoresBaja = $registros->pluck('Empresa')->all(); // Convertir a array
                $valoresBaja = array_filter($valoresBaja); // Elimina null, false, '', 0, etc.

                $data = array_count_values($valoresBaja);
                $total = count($valoresBaja);
                break;
            case 'generacion':
                $registros = Alumno::query();
                
                if($generacionDesde !== null && $generacionHasta !== null)
                {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);}
                if ($materia !== 'todas') {
                    $registros->where('Mat_1', $materia);}
                if ($escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);}
                if ($baja !== 'todas') {
                    $registros->where('Tbaja', $baja);}
                if ($tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);}
                if ($trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);}
                if ($carrera !== 'todas') {
                    $registros->where('Carrera', $carrera);}
                
                $valoresBaja = $registros->pluck('Gen')->all(); // Convertir a array
                $valoresBaja = array_filter($valoresBaja); // Elimina null, false, '', 0, etc.

                $data = array_count_values($valoresBaja);
                $total = count($valoresBaja);
                break;
            case 'carrera':
                if($carrera === 'todas')
                {
                    $registros = Alumno::query();
                }
                else
                {
                    $registros = Alumno::query()
                        ->where('Carrera', $carrera);
                }

                if($generacionDesde !== null && $generacionHasta !== null)
                {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);}
                if ($materia !== 'todas') {
                    $registros->where('Mat_1', $materia);}
                if ($escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);}
                if ($baja !== 'todas') {
                    $registros->where('Tbaja', $baja);}
                if ($tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);}
                if ($trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);}
                
                $valoresBaja = $registros->pluck('Carrera')->all(); // Convertir a array
                $valoresBaja = array_filter($valoresBaja); // Elimina null, false, '', 0, etc.

                $data = array_count_values($valoresBaja);
                $total = count($valoresBaja);
                break;
            case 'escuela':
                if($escuela === 'todas')
                {
                    $registros = Alumno::query();
                }
                else
                {
                    $registros = Alumno::query()
                        ->where('Escuela', $escuela);
                }

                if($generacionDesde !== null && $generacionHasta !== null)
                {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);}
                if ($materia !== 'todas') {
                    $registros->where('Mat_1', $materia);}
                if ($escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);}
                if ($baja !== 'todas') {
                    $registros->where('Carrera', $carrera);}
                if ($tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);}
                if ($trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);}
                
                $valoresBaja = $registros->pluck('Escuela')->all(); // Convertir a array
                $valoresBaja = array_filter($valoresBaja); // Elimina null, false, '', 0, etc.

                $data = array_count_values($valoresBaja);
                $total = count($valoresBaja);
                break;
            case 'materia':
                if($materia === 'todas')
                {
                    $registros = Alumno::query();
                }
                else
                {
                    $registros = Alumno::query()
                        ->where('Mat_1', $materia);
                }

                if($generacionDesde !== null && $generacionHasta !== null)
                {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);}
                if ($baja !== 'todas') {
                    $registros->where('Tbaja', $baja);}
                if ($escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);}
                if ($baja !== 'todas') {
                    $registros->where('Carrera', $carrera);}
                if ($tipoTitulacion !== 'todas') {
                    $registros->where('Titulacion', $tipoTitulacion);}
                if ($trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);}

                $valoresBaja = $registros->pluck('Mat_1')->all(); // Convertir a array
                $valoresBaja = array_filter($valoresBaja); // Elimina null, false, '', 0, etc.

                $data = array_count_values($valoresBaja);
                $total = count($valoresBaja);
                break;
            case 'titulacion':
                if($tipoTitulacion === 'todas')
                {
                    $registros = Alumno::query();
                }
                else
                {
                    $registros = Alumno::query()
                        ->where('Titulacion', $tipoTitulacion);
                }

                if($generacionDesde !== null && $generacionHasta !== null)
                {
                    $registros->whereBetween('Gen', [$generacionDesde, $generacionHasta]);}
                if ($baja !== 'todas') {
                    $registros->where('Tbaja', $baja);}
                if ($escuela !== 'todas') {
                    $registros->where('Escuela', $escuela);}
                if ($baja !== 'todas') {
                    $registros->where('Carrera', $carrera);}
                if ($baja !== 'todas') {
                    $registros->where('Tbaja', $baja);}
                if ($trabajo !== 'todas') {
                    $registros->where('Empresa', $trabajo);}
                
                $valoresBaja = $registros->pluck('Titulacion')->all(); // Convertir a array
                $valoresBaja = array_filter($valoresBaja); // Elimina null, false, '', 0, etc.

                $data = array_count_values($valoresBaja);
                $total = count($valoresBaja);
                break;
            default:
                return response()->json(['error' => 'No hay datos disponibles para este tipo'], 404);
        }
        
        Log::info('Respuesta enviada:', ['data' => $data, 'total' => $total]);
        return response()->json([
            'data' => $data,
            'total' => $total,
        ]);
    }
}
