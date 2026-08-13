<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\Escuela;
use App\Models\Empresa;

class NormalizadorService
{
    /**
     * Estandariza un dato comparándolo con la base de datos o listas por defecto.
     */
    public function normalizar($entrada, $tipo, $umbral = 60)
    {
        $entrada = trim($entrada);
        $opciones = $this->obtenerOpcionesDesdeBD($tipo);

        // Si es materia, aplicamos los casos específicos
        if ($tipo === 'materia') {
            $casos = $this->manejarCasosEspecificosMaterias($entrada);
            if ($casos['mejor_coincidencia']) {
                return $casos;
            }
        }

        $resultados = [];
        foreach ($opciones as $opcion) {
            // Calculamos el porcentaje de similitud usando similar_text nativo de PHP
            similar_text(strtolower($entrada), strtolower($opcion), $porcentaje);
            $resultados[$opcion] = $porcentaje;
        }

        // Ordenamos de mayor a menor coincidencia
        arsort($resultados);

        // Obtenemos las mejores coincidencias
        $mejores = array_slice(array_keys($resultados), 0, 4); 
        $puntajeMayor = array_values($resultados)[0] ?? 0;

        $mejorCoincidencia = $puntajeMayor >= $umbral ? $mejores[0] : null;
        $opcionesSugeridas = array_slice($mejores, 1, 3); // Las siguientes 3 opciones

        return [
            'entrada' => $entrada,
            'mejor_coincidencia' => $mejorCoincidencia,
            'opciones' => $mejorCoincidencia ? $opcionesSugeridas : array_slice($mejores, 0, 3)
        ];
    }

    /**
     * Obtiene el catálogo desde la BD. Si está vacía, usa los de reserva.
     */
    private function obtenerOpcionesDesdeBD($tipo)
    {
        switch ($tipo) {
            case 'materia':
                // Lee las materias directo de la BD, si no hay, usa estas por defecto
                $opciones = Materia::pluck('nombre')->toArray();
                return count($opciones) > 0 ? $opciones : ["Química A", "Cálculo A", "Estructuras de Datos I"];
            case 'escuela':
                $opciones = Escuela::pluck('nombre')->toArray();
                return count($opciones) > 0 ? $opciones : ["COBACH 01", "CBTIS 121", "UANL"];
            case 'empresa':
                $opciones = Empresa::pluck('nombre')->toArray();
                return count($opciones) > 0 ? $opciones : ["ABB", "Google", "Bosch"];
            default:
                return [];
        }
    }

    /**
     * Casos de corrección manual para las materias.
     */
    private function manejarCasosEspecificosMaterias($entrada)
    {
        $entradaLower = strtolower($entrada);
        
        if (str_starts_with($entradaLower, "edo") || str_starts_with($entradaLower, "estru")) {
            if ($entradaLower == "edo a") return ['mejor_coincidencia' => "Estructuras de Datos I", 'opciones' => []];
            if (in_array($entradaLower, ["edo 2", "estructuras ii", "estructuras 2"])) return ['mejor_coincidencia' => "Estructuras de Datos II", 'opciones' => []];
            if (in_array($entradaLower, ["edo c", "edo avanzadas"])) return ['mejor_coincidencia' => "Estructuras de Datos Avanzadas", 'opciones' => []];
            return ['mejor_coincidencia' => null, 'opciones' => ["Estructuras de Datos I", "Estructuras de Datos II", "Estructuras de Datos Avanzadas"]];
        }

        if (str_starts_with($entradaLower, "proyectos") || str_starts_with($entradaLower, "pc")) {
            if (in_array($entradaLower, ["proyectos 2", "proyectos ii", "pc ii", "pc 2", "pc2"])) return ['mejor_coincidencia' => "Proyectos Computacionales II", 'opciones' => []];
            return ['mejor_coincidencia' => "Proyectos Computacionales I", 'opciones' => ["Proyectos Computacionales II", "Proyectos Computacionales III"]];
        }

        return ['mejor_coincidencia' => null, 'opciones' => []];
    }
}