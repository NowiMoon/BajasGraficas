<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use Illuminate\Support\Facades\Log;
class BotonesController extends Controller
{
        public function materias()
        {
            return response()->json(Alumno::whereNotNull('Mat_1')
            ->pluck('Mat_1')
            ->unique()
            ->values()
            ->all());
        }

        public function trabajos()
        {
            return response()->json(Alumno::whereNotNull('Empresa')
            ->pluck('Empresa')
            ->unique()
            ->values()
            ->all());
        }

        public function escuelas()
        {
            return response()->json(Alumno::whereNotNull('Escuela')
            ->pluck('Escuela')
            ->unique()
            ->values()
            ->all());
        }

        public function generation()  // ✅ CORRECTO: función separada
        {
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
