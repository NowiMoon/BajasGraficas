<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MateriaController extends Controller
{
    public function normalizar(Request $request)
    {
        // Obtener la entrada del usuario   
        $entrada = $request->input('entrada');

        // Crear un cliente HTTP
        $client = new Client();

        // Hacer la solicitud al microservicio de Python
        $response = $client->post('http://127.0.0.1:5000/normalizar', [
            'json' => [
                'entrada' => $entrada,
            ],
        ]);

        // Obtener la respuesta JSON
        $resultado = json_decode($response->getBody(), true);

        // Devolver la respuesta a la vista
        return view('resultado', [
            'mejor_coincidencia' => $resultado['mejor_coincidencia'] ?? null,
            'opciones' => $resultado['opciones'] ?? [],
        ]);
    }

    public function store(Request $request){
        if (Materia::where('clave_materia', $request->clave_materia)->exists()) {
            return redirect()->route('gestion_materias')->with('error', 'La materia ya existe');
        }

        $materia = new Materia();
        $materia->clave_materia = $request->clave_materia;
        $materia->nombre_materia = $request->nombre_materia;
        $materia->save();
        return redirect()->route('gestion_materias')->with('status', 'Materia creada correctamente');
    }
}