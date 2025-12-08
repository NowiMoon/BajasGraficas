<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;
use App\Models\Materia;
use Illuminate\Support\Facades\Log;

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
        Log::info("DEBUG: Conteo inicial de $count registros desde JSON");
       
        //llamar la funcion de datos actualizados, la api lo devuelve en una lista y hay que separarlo
        $Datos_Actualizados = $this->PreparaDatosActualizados($coincidencia,$count);
        
        // DEBUG: Verificar longitudes de datos actualizados
        Log::info("DEBUG: Longitud Datos_Actualizados[0] (materias): " . count($Datos_Actualizados[0]));
        Log::info("DEBUG: Longitud Datos_Actualizados[1] (escuelas): " . count($Datos_Actualizados[1]));
        Log::info("DEBUG: Longitud Datos_Actualizados[2] (empresas): " . count($Datos_Actualizados[2]));

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
        } else {
            $mat3 = array_pad($mat3, $count, null);
            $mat3 = array_slice($mat3, 0, $count);
        }

        // Validar y sincronizar longitudes de todos los arrays para evitar "Undefined array key"
        // IMPORTANTE: Usar referencias y reasignación para que los cambios persistan
        if (count($lista_anio) < $count) $lista_anio = array_pad($lista_anio, $count, null);
        elseif (count($lista_anio) > $count) $lista_anio = array_slice($lista_anio, 0, $count);
        
        if (count($reg_anio) < $count) $reg_anio = array_pad($reg_anio, $count, null);
        elseif (count($reg_anio) > $count) $reg_anio = array_slice($reg_anio, 0, $count);
        
        if (count($clave) < $count) $clave = array_pad($clave, $count, null);
        elseif (count($clave) > $count) $clave = array_slice($clave, 0, $count);
        
        if (count($nombre) < $count) $nombre = array_pad($nombre, $count, null);
        elseif (count($nombre) > $count) $nombre = array_slice($nombre, 0, $count);
        
        if (count($generacion) < $count) $generacion = array_pad($generacion, $count, null);
        elseif (count($generacion) > $count) $generacion = array_slice($generacion, 0, $count);
        
        if (count($carrera) < $count) $carrera = array_pad($carrera, $count, null);
        elseif (count($carrera) > $count) $carrera = array_slice($carrera, 0, $count);
        
        if (count($email) < $count) $email = array_pad($email, $count, null);
        elseif (count($email) > $count) $email = array_slice($email, 0, $count);
        
        if (count($mat1) < $count) $mat1 = array_pad($mat1, $count, null);
        elseif (count($mat1) > $count) $mat1 = array_slice($mat1, 0, $count);
        
        if (count($mat2) < $count) $mat2 = array_pad($mat2, $count, null);
        elseif (count($mat2) > $count) $mat2 = array_slice($mat2, 0, $count);
        
        if (count($mat3) < $count) $mat3 = array_pad($mat3, $count, null);
        elseif (count($mat3) > $count) $mat3 = array_slice($mat3, 0, $count);
        
        if (count($escuela) < $count) $escuela = array_pad($escuela, $count, null);
        elseif (count($escuela) > $count) $escuela = array_slice($escuela, 0, $count);
        
        if (count($baja) < $count) $baja = array_pad($baja, $count, null);
        elseif (count($baja) > $count) $baja = array_slice($baja, 0, $count);
        
        if (count($inconveniente) < $count) $inconveniente = array_pad($inconveniente, $count, null);
        elseif (count($inconveniente) > $count) $inconveniente = array_slice($inconveniente, 0, $count);
        
        if (count($empresa) < $count) $empresa = array_pad($empresa, $count, null);
        elseif (count($empresa) > $count) $empresa = array_slice($empresa, 0, $count);
        
        if (count($titulacion) < $count) $titulacion = array_pad($titulacion, $count, null);
        elseif (count($titulacion) > $count) $titulacion = array_slice($titulacion, 0, $count);

        // DEBUG: Log de longitudes finales
        Log::info("DEBUG: Longitudes finales ANTES del loop:");
        Log::info("  lista_anio: " . count($lista_anio));
        Log::info("  reg_anio: " . count($reg_anio));
        Log::info("  clave: " . count($clave));
        Log::info("  nombre: " . count($nombre));
        Log::info("  generacion: " . count($generacion));
        Log::info("  carrera: " . count($carrera));
        Log::info("  email: " . count($email));
        Log::info("  mat1: " . count($mat1));
        Log::info("  mat2: " . count($mat2));
        Log::info("  mat3: " . count($mat3));
        Log::info("  escuela: " . count($escuela));
        Log::info("  baja: " . count($baja));
        Log::info("  inconveniente: " . count($inconveniente));
        Log::info("  empresa: " . count($empresa));
        Log::info("  titulacion: " . count($titulacion));
        Log::info("  Count esperado: $count");

        //registro por registro insertamos en la bd
        //sin duplicados, ya con la materia, escuela y trabajo normalizado
        /*for ($i = 0; $i < $count; $i++) 
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
        }*/
        $insertados = 0;
$errores = [];

for ($i = 0; $i < $count; $i++) 
{
    try {
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
            'Titulacion' => $titulacion[$i],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('alumnos')->insert($datos);
        $insertados++;
        
        Log::info("✓ Insertado registro $i: " . $nombre[$i]);
        
    } catch (\Exception $e) {
        $errores[$i] = [
            'error' => $e->getMessage(),
            'datos' => isset($datos) ? $datos : [],
            'indice' => $i
        ];
        
        Log::error("✗ ERROR en registro $i: " . $e->getMessage());
        Log::error("Datos del registro $i: " . json_encode(isset($datos) ? $datos : []));
        
        // Continuar con los siguientes registros
        continue;
    }
}

// Resumen
Log::info("=== RESUMEN DE INSERCIÓN ===");
Log::info("Total registros a insertar: $count");
Log::info("Registros insertados exitosamente: $insertados");
Log::info("Errores: " . count($errores));

if (!empty($errores)) {
    Log::error("Registros con errores:", $errores);
    
    // Mostrar el primer error para diagnóstico
    $primerError = reset($errores);
    Log::error("Primer error en índice: " . $primerError['indice']);
    Log::error("Mensaje error: " . $primerError['error']);
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
