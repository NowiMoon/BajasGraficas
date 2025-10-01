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

        if($datos[13] === 0)
        {
            $mat3 = array_fill(0, $count, null);
        }

        //$jason = json_encode($Datos_Insersion, JSON_PRETTY_PRINT);
        //Storage::put('Datos.json', $jason);  

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
        
        /*$alumno = new Alumno();
        $alumno->Anio = (int)$lista_anio[$i];
        $alumno->Id_Reg_A = (int)$datos[0][$i];
        $alumno->Cv_Alumno = (int)$datos[1][$i];
        $alumno->Nombre_Alumno = $datos[2][$i];
        $alumno->Gen = (int)$resultado[$i];
        $alumno->Carrera = $datos[4][$i];
        $alumno->email = $datos[5][$i];
        $alumno->Mat_1 = $Datos_Actualizados[0][$i];
        $alumno->Mat_2 = $datos[12][$i]; 
        $alumno->Mat_3 = $datos[13][$i];
        $alumno->Escuela = $Datos_Actualizados[1][$i];
        $alumno->TBaja = $datos[8][$i];
        $alumno->Inc_Carr = $datos[9][$i];
        $alumno->Empresa = $Datos_Actualizados[2][$i];
        $alumno->Titulacion = $datos[11][$i];
        $alumno->created_at = now();
        $alumno->updated_at = now();
        $alumno->save();*/

         /*DB::table('alumnos')->insert([
            'Anio' => (int)$lista_anio[$i],
            'Id_Reg_A' => (int)$datos[0][$i],
            'Cv_Alumno' => (int)$datos[1][$i],
            'Nombre_Alumno' => $datos[2][$i],
            'Gen' => (int)$resultado[$i],
            'Carrera' => $datos[4][$i],
            'email' => $datos[5][$i],
            'Mat_1' => $Datos_Actualizados[0][$i],
            'Mat_2' => $datos[12][$i], 
            'Mat_3' => $datos[13][$i],
            'Escuela' => $Datos_Actualizados[1][$i],
            'TBaja' => $datos[8][$i],
            'Inc_Carr' => $datos[9][$i],
            'Empresa' => $Datos_Actualizados[2][$i],
            'Titulacion' => $datos[11][$i],
            'created_at' => now(),
            'updated_at' => now(),
        ]);    */
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
