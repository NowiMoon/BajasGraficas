<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecibirJsonController extends Controller
{
    public function recibirJson()
    {
        $rutaArchivo = 'json/lista.json';
    
        if (Storage::exists($rutaArchivo)) {
            $json = Storage::get($rutaArchivo);
            $datos = json_decode($json, true);
        }

        if (isset($datos[0])) {
            $lista_deseada = $datos[0];
        }

        //Verifica datos ya guardados
        $aux_id_anio = [20251, 0];
        $cont = 0;

        for ($i = 0; $i < count($lista_deseada); $i++) {
            for($j = 0; $j < count($aux_id_anio); $j++)
            {
                if($lista_deseada[$i] == $aux_id_anio[$j])
                {
                    array_splice($lista_deseada, $i, 1);
                    for($k = 0; $k < count($datos); $k++)
                    {
                        $list_aux = $datos[$k];
                        array_splice($list_aux, $i, 1);
                        $datos[$k] = $list_aux;
                        //dd($list_aux);
                    }

                    $cont = $cont + 1;
                    $i = $i - 1;
                    break;
                }
            }
            
        }

        $json = json_encode($datos, JSON_PRETTY_PRINT);
        $ruta = 'json/lista_sin_duplicados.json';
        Storage::put($ruta, $json);

        $mi_valor = 'Se ingresaron: ' . count($lista_deseada) . ' registros nuevos de ' . $cont;
        
        return view('resultados', ['valor' => $mi_valor]); 
    }
}