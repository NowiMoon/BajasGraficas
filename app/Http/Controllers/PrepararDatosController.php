<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;

class PrepararDatosController extends Controller
{
    public function Preparar_Datos(Request $request)
    {
        $Datos_Insersion = [];
        $rutaArchivo = 'json/lista_sin_duplicados.json';
        $json = Storage::get($rutaArchivo);
        $datos = json_decode($json, true);

        $todosLosResultados = $request->resultados;
        $cont_actualizado = 0;

        $jason = json_encode($todosLosResultados, JSON_PRETTY_PRINT);
        Storage::put('Datos.json', $jason);

        $entrada = [];
        $coincidencia = [];
        foreach ($todosLosResultados as $item) {
            $entrada[] = $item['entrada'];
            $coincidencia[] = $item['mejor_coincidencia'];
            $cont_actualizado = $cont_actualizado + 1;
        }

        $count = count($datos[0]);
        $u = 'Datos que deben de ser: ' . $count . ' y Datos actualizados: ' . $cont_actualizado;
       
        //llamar la funcion de datos actualizados
        $Datos_Actualizados[] = $this->PreparaDatosActualizados($coincidencia,$count);

        //$jason = json_encode($Datos_Insersion, JSON_PRETTY_PRINT);
        //$ruta = 'json/Datos1.json'; 
        //Storage::put($ruta, $jason);

        //----------- Ejemplo ------------    

        //Id registro se coloca desde el seeder
        $aux = substr($datos[0][0], 0, 4);
        $lista = array_fill(0, $count, $aux);

        //Generacion
        $lista = $datos[3];
        $resultado = array_map(function($item) {
            return substr($item, 0, 4);
        }, $lista);

        for($i = 0; $i < 3; $i++)
        {
            $Auxiliar =
            [
                'Anio' => $lista[$i],
                'Id_Reg_A' => $datos[0][$i],
                'Cv_Alumno' => $datos[1][$i],
                'Nombre_Alumno' => $datos[2][$i],
                'Gen' => $resultado,
                'Carrera' => $datos[4][$i],
                'email' => $datos[5][$i],
                'Mat_1' => $Datos_Actualizados[0][$i],
                'Mat_2' => $datos[12],//-------------------------checar mat 2
                'Mat_3' => $datos[13],
                'Escuela' => $Datos_Actualizados[1][$i],
                'TBaja' => $datos[8],
                'Inc_Carr' => $datos[9],
                'Empresa' => $Datos_Actualizados[2][$i],
                'Titulacion' => $datos[11],
                'created_at' => now(), // Timestamps manuales para insert()
                'updated_at' => now()
            ];
            $Datos_Insersion[] = $Auxiliar;
        }
        
        Alumno::insert($Datos_Insersion);          

        //$Datos_Insersion[] = $lista;        //Año
        //$Datos_Insersion[] = $datos[0];   //Id por años
        //$Datos_Insersion[] = $datos[1];   //Clave
        //$Datos_Insersion[] = $datos[2];   //Nombre
        //$Datos_Insersion[] = $datos[4];   //carrera
        //$Datos_Insersion[] = $datos[5];   //email
        //$Datos_Insersion[] = $Datos_Actualizados[0];   //materias 1
        //$Datos_Insersion[] = $datos[12];  //materias 2
        //$Datos_Insersion[] = $datos[13];  //materias 3
        //$Datos_Insersion[] = $Datos_Actualizados[1];   //escuela
        //$Datos_Insersion[] = $datos[8];   //baja
        //$Datos_Insersion2[] = $datos[9];   //Inconveniente
        //$Datos_Insersion2[] = $Datos_Actualizados[2];   //Empresa
        //$Datos_Insersion2[] = $datos[11];   //Titulacion      
    }

    public function PreparaDatosActualizados($coincidencia,$count)
    {
        $Datos_Actualizados = [];

        $Datos_Actualizados[] = array_slice($coincidencia, 0, $count);
        $Datos_Actualizados[] = array_slice($coincidencia, $count, ($count));
        $Datos_Actualizados[] = array_slice($coincidencia, ($count*2), $count);

        return $Datos_Actualizados;
    }
}
