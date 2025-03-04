<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecibirJsonController extends Controller
{
    public function recibirJson(Request $request)
    {
     // Obtener la lista de materias
     $materias = $request->input('materias');

     // Retornar la vista con los datos recibidos
     return view('resultados', compact('materias'));
    }
}
