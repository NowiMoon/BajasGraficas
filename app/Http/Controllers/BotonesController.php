<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use Illuminate\Support\Facades\Log;
class BotonesController extends Controller
{
        public function materias()
        {
            //consulta para obtener las materias de la base de datos
            return response()->json(Alumno::whereNotNull('Mat_1')
            ->pluck('Mat_1')
            ->unique()
            ->values()
            ->all());
        }

        public function trabajos()
        {
            //consulta para obtener las empresas de la base de datos
            return response()->json(Alumno::whereNotNull('Empresa')
            ->pluck('Empresa')
            ->unique()
            ->values()
            ->all());
        }

        public function escuelas()
        {
            //consulta para obtener las mescuelas de la base de datos
            return response()->json(Alumno::whereNotNull('Escuela')
            ->pluck('Escuela')
            ->unique()
            ->values()
            ->all());
        }

        public function generation()
        {
            //consulta para obtener las generaciones de la base de datos
            $generaciones = Alumno::whereNotNull('Gen')
                ->pluck('Gen')
                ->unique()
                ->values()
                ->all();
            
            // Log para debug
            Log::info('Generaciones encontradas:', $generaciones);
            
            return response()->json($generaciones);
        }
    }
