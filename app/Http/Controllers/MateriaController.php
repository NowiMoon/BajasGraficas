<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MateriaController extends Controller
{
    public function normalizar(Request $request)
    {
        // Obtener el tipo (1: Materias, 2: Preparatorias, 3: Trabajos)
        $tipo = $request->input('tipo');
        
        // Obtener la cadena de entradas del formulario
        $entradas = $request->input('entradas');
        if (empty($entradas)) {
            return redirect()->back()->with('error', 'No se recibieron entradas.');
        }
        
        // Convertir la cadena en un array (separado por comas) y quitar espacios
        $entradasArray = explode(',', $entradas);
        $entradasArray = array_map('trim', $entradasArray);

        // Seleccionar el endpoint del microservicio Flask según el tipo seleccionado
        if ($tipo == "1") {
            // Normalización de materias
            $endpoint = 'http://127.0.0.1:5000/normalizar/materia';
        } elseif ($tipo == "2") {
            // Normalización de preparatorias
            $endpoint = 'http://127.0.0.1:5000/normalizar/escuela';
        } elseif ($tipo == "3") {
            // Normalización de trabajos
            $endpoint = 'http://127.0.0.1:5000/normalizar/trabajos';
        } else {
            return redirect()->back()->with('error', 'Tipo de normalización no válido.');
        }
        
        $client = new Client();

        try {
            // Enviar solicitud POST al microservicio Flask
            $response = $client->post($endpoint, [
                'json' => ['entradas' => $entradasArray],
                'connect_timeout' => 5,
                'timeout' => 10,
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['resultados']) || empty($data['resultados'])) {
                return redirect()->back()->with('error', 'La respuesta del servidor no es válida.');
            }

            // Renderizar la vista de resultados con los datos recibidos
            return view('resultado', ['resultados' => $data['resultados']]);
        } catch (\Exception $e) {
            Log::error('Error al comunicarse con el microservicio: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al procesar la solicitud.');
        }
    }
}
