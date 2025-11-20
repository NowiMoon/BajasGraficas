<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;
use App\Models\Materia;

class PrepararDatosController extends Controller
{
    public function Preparar_Datos(Request $request)
    {
        $Datos_Insersion = 0;
        $rutaArchivo = 'json/lista_sin_duplicados.json';
        $json = Storage::get($rutaArchivo);
        $datos = json_decode($json, true);

        $todosLosResultados = $request->resultados;
        $cont_actualizado = 0;
        //guardamos los registros en una lista para manejarlos

        $entrada = [];
        $coincidencia = [];
        foreach ($todosLosResultados as $item) {
            $entrada[] = $item['entrada'];
            $coincidencia[] = $item['mejor_coincidencia'];
            $cont_actualizado = $cont_actualizado + 1;
        }

        $count = count($datos[0]);
       
        //llamar la funcion de datos actualizados, la api lo devuelve en una lista y hay que separarlo
        $Datos_Actualizados = $this->PreparaDatosActualizados($coincidencia,$count);

        //----------- Ejemplo ------------    

        //Id registro se coloca desde el seeder
        $aux = substr($datos[0][0], 0, 4);
        $lista_anio = array_fill(0, $count, $aux);

        //Generacion
        $lista = $datos[3];
        $resultado = array_map(function($item) {
            return substr($item, 0, 4);
        }, $lista);

        //Registro por año
        $reg_anio = $datos[0];

        //clave de alumnos
        $clave = $datos[1];
        $nombre = $datos[2];
        $generacion = $resultado;
        $carrera = $datos[4];
        $email = $datos[5];
        $mat1 = $Datos_Actualizados[0];
        $mat2 = $datos[12];
        $mat3 = $datos[13];
        $escuela = $Datos_Actualizados[1];
        $baja = $datos[8];

        $inc = $datos[9];
        $res = array_map(function($item) {
            return substr($item, 0, 100);
        }, $inc);

        $inconveniente = $res;
        $empresa = $Datos_Actualizados[2];
        $titulacion = $datos[11];

        //preparamos en el orden de la tabla los valores para insertarlos en la bd

        if($datos[13] === 0)
        {
            $mat3 = array_fill(0, $count, null);
        }

        //registro por registro insertamos en la bd
        //sin duplicados, ya con la materia, escuela y trabajo normalizado
        for ($i = 0; $i < $count; $i++) 
        {
            $datos = [
                'Anio' => $lista_anio[$i],
                'Id_Reg_A' => $reg_anio[$i],
                'Cv_Alumno' => $clave[$i],
                'Nombre_Alumno' => $nombre[$i],
                'Gen' => $resultado[$i],
                'Carrera' => $carrera[$i],
                'email' => $email[$i],
                'Mat_1' => $mat1[$i],
                'Mat_2' => $mat2[$i],
                'Mat_3' => $mat3[$i],
                'Escuela' => $escuela[$i],
                'TBaja' => $baja[$i],
                'Inc_Carr' => $inconveniente[$i],
                'Empresa' => $empresa[$i],
                'Titulacion' =>$titulacion[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('alumnos')->insert($datos);
        }


        return response()->json(['mensaje' => $count . ' registros guardados correctamente']);
        //le notificamos al usuario la cantidad de registros insertados
    }

    public function PreparaDatosActualizados($coincidencia,$count)
    {
        $Datos_Actualizados = [];

        $Datos_Actualizados[] = array_slice($coincidencia, 0, $count);
        $Datos_Actualizados[] = array_slice($coincidencia, $count, ($count));
        $Datos_Actualizados[] = array_slice($coincidencia, ($count*2), $count);

        return $Datos_Actualizados;

        //separamos en 3 la lista, cada lista es de un valor diferente
    }
}
