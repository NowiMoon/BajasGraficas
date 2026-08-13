<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Materia;

class MateriaController extends Controller
{
    public function normalizar_datos(Request $request)
    {
        try {
            // Validar datos de entrada
            $validated = $request->validate([
                'datos' => 'required|array',
                'tipo' => 'required|in:1,2,3',
                'umbral' => 'required|numeric|min:1|max:100'
            ]);

            $datos = $validated['datos'];
            $tipo = $validated['tipo'];
            $umbral = (int)$validated['umbral'];

            // Preparar datos para el microservicio
            $entradasArray = array_map('trim', explode(',', implode(', ', $datos)));

            // Configurar endpoints
            $endpoints = [
                '1' => 'http://127.0.0.1:5000/normalizar/materia',
                '2' => 'http://127.0.0.1:5000/normalizar/escuela',
                '3' => 'http://127.0.0.1:5000/normalizar/trabajos'
            ];

            $client = new Client([
                'connect_timeout' => 5,
                'timeout' => 10,
            ]);

            // Preparar payload; incluir 'opciones_bd' si fue proporcionado
            $payload = [
                'entradas' => $entradasArray,
                'umbral' => $umbral
            ];

            if ($request->has('opciones_bd') && is_array($request->get('opciones_bd'))) {
                $payload['opciones_bd'] = $request->get('opciones_bd');
            }

            $response = $client->post($endpoints[$tipo], [
                'json' => $payload
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['resultados'])) {
                throw new \Exception('Respuesta del microservicio no contiene resultados');
            }

            return response()->json([
                'success' => true,
                'resultado' => $data,
                'message' => 'Datos normalizados correctamente'
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorDetails = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error("Error en microservicio: {$errorDetails}");

            return response()->json([
                'success' => false,
                'message' => 'Error al comunicarse con el servicio de normalización',
                'error' => $errorDetails
            ], 500);

        } catch (\Exception $e) {
            Log::error("Error en normalizar_datos: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request){
        if (Materia::where('clave_materia', $request->clave_materia)->exists()) {
            return redirect()->back()->with('error', 'La materia ya existe');
        }

        $materia = new Materia();
        $materia->clave_materia = $request->clave_materia;
        $materia->nombre_materia = $request->nombre_materia;
        $materia->save();

        try{
            return redirect()->back()->with('success', 'Materia creada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar la materia');
        }
        
    }

    public function index()
    {
        $materias = Materia::all();
        return view('gestion_materias', compact('materias'));
    }
    
}