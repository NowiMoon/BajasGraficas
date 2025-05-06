<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Materia; 

class PrepararDatosController extends Controller
{
    public function Preparar_Datos(Request $request)
    {
        $Datos_Insersion = [];
        $Datos_Insersion2 = [];
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
        $Datos_Actualizados[] = $this->PreparaDatosActualizados($coincidencia,$count);

        //----------- Ejemplo ------------       

        //Id registro se coloca desde el seeder
        $aux = substr($datos[0][0], 0, 4);
        $lista = array_fill(0, $count, $aux);

        $Datos_Insersion[] = $lista;        //Año
        $Datos_Insersion[] = $datos[0];   //Id por años
        $Datos_Insersion[] = $datos[1];   //Clave
        $Datos_Insersion[] = $datos[2];   //Nombre

        $lista = $datos[3];
        $resultado = array_map(function($item) {
            return substr($item, 0, 4);
        }, $lista);
        $Datos_Insersion[] = $resultado;   //Generacion

        //Obtener clave de la base de datos
        $Datos_Insersion[] = $datos[4];   //carrera
        $Datos_Insersion[] = $datos[5];   //email

        $Datos_Insersion[] = array_slice($coincidencia, 0, $count); //mat 1

        $jason = json_encode($Datos_Insersion, JSON_PRETTY_PRINT);
        $ruta = 'json/Datos1.json'; 
        Storage::put($ruta, $jason);
        
        $this->GuardaDatos2($datos, $Datos_Actualizados); 
    }

    public function GuardaDatos2($datos, $Datos_Actualizados)
    {
        $Datos_Insersion2 = [];

        $Datos_Insersion2[] = $Datos_Actualizados[0];   //materias 1
        $Datos_Insersion2[] = $datos[12];  //materias 2
        $Datos_Insersion2[] = $datos[13];  //materias 3

        //Obtener clave de la escuela de la base de datos
        $Datos_Insersion2[] = $Datos_Actualizados[1];   //escuela

        //Obtener el tipo de baja de la base de datos
        $Datos_Insersion2[] = $datos[8];   //baja

        $Datos_Insersion2[] = $datos[9];   //Inconveniente

        //Obetner el id de la empresa
        $Datos_Insersion2[] = $Datos_Actualizados[2];   //Empresa

        //Obtener el id de la titulacion
        $Datos_Insersion2[] = $datos[11];   //Titulacion
 
        $jas = json_encode($Datos_Insersion2, JSON_PRETTY_PRINT);
        $r = 'json/Datos2.json'; 
        Storage::put($r, $jas);
    }

    public function PreparaDatosActualizados($coincidencia,$count)
    {
        $Datos_Actualizados = [];

        $Datos_Actualizados[] = array_slice($coincidencia, 0, $count);
        $Datos_Actualizados[] = array_slice($coincidencia, $count, ($count));
        $Datos_Actualizados[] = array_slice($coincidencia, ($count*2), $count);

        return $Datos_Actualizados;
    }

    public function InsertarDatos($mat)
    {
        $aux = []; // Array para almacenar los datos a insertar

        $aux = array_map(function($materia) {
            return [
                'nombre_materia' => $materia
            ];
        }, $mat);
        
        Materia::insert($aux);

        // Insertar todos los registros en una sola operación
       
    }
}
