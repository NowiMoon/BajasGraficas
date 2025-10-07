<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Alumno;
use App\Models\Materia;
use Illuminate\Support\Facades\Log;

class RecibirJsonController extends Controller
{
    public function recibirJson($umbral)
    {
        try {
            Log::info('estoy dentro');
            $Datos_Nuevos = [];
            $rutaArchivo = 'json/lista.json';

            if (!Storage::exists($rutaArchivo)) {
                Log::info('error al leer json');
                return response()->json([
                    'success' => false,
                    'message' => 'Archivo lista.json no encontrado'
                ], 404);
            }

            $json = Storage::get($rutaArchivo);
            $datos = json_decode($json, true);

            if (!isset($datos[0])) {
                Log::info($datos);
                return response()->json([
                    'success' => false,
                    'message' => 'Datos en formato incorrecto'
                ], 400);
            }

            $lista_deseada = $datos[0];
            $aux_id_anio = Alumno::pluck('Id_Reg_A')->toArray();
            $cont = 0;

            if (!is_array($datos[13])) {
                $datos[13] = array_fill(0, count($datos[0]), null);
            }

            for ($i = 0; $i < count($lista_deseada); $i++) {
                for($j = 0; $j < count($aux_id_anio); $j++) {
                    if($lista_deseada[$i] == $aux_id_anio[$j]) {
                        array_splice($lista_deseada, $i, 1);
                        for($k = 0; $k < count($datos); $k++) {
                            $list_aux = $datos[$k];
                            array_splice($list_aux, $i, 1);
                            $datos[$k] = $list_aux;
                        }
                        $cont++;
                        $i--;
                        break;
                    }
                }
            }

            Log::info('recibir jason');

            $json = json_encode($datos, JSON_PRETTY_PRINT);
            $ruta = 'json/lista_sin_duplicados.json';
            Storage::put($ruta, $json);

            if (count($datos[0]) === 0){
                return response()->json([
                    'success' => true,
                    'message' => 'Los valores son válidos, pero ya existen en la base de datos',
                    'codigo' => 'DUPLICADOS' // Nuevo campo identificador
                ]);
            }

            $mi_valor = 'Se ingresaron: ' . count($lista_deseada) . ' registros nuevos de ' . $cont;

            if (!Storage::exists($ruta)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear archivo sin duplicados'
                ], 500);
            }

            $json = Storage::get($ruta);
            $datos = json_decode($json, true);

            $entradas_Materias = $datos[6] ?? [];
            $entradas_Escuelas = $datos[7] ?? [];
            $entradas_Trabajos = $datos[10] ?? [];

            $materias_bd = Materia::pluck('nombre_materia')->toArray();
            Log::info($materias_bd);

            for($tipo = 1; $tipo < 4; $tipo++) {
                $mController = new MateriaController();
                $datosRequest = ['umbral' => $umbral];

                switch($tipo) {
                    case 1:
                        $datosRequest['datos'] = $entradas_Materias;
                        $datosRequest['tipo'] = $tipo; 
                        $datosRequest['mat_bd'] = $materias_bd;
                        break;
                    case 2:
                        $datosRequest['datos'] = $entradas_Escuelas;
                        $datosRequest['tipo'] = $tipo;
                        break;
                    case 3:
                        $datosRequest['datos'] = $entradas_Trabajos;
                        $datosRequest['tipo'] = $tipo;
                        break;
                }

                $request = new Request($datosRequest);
                $response = $mController->normalizar_datos($request);
                
                if (!$response->getData()->success) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error en normalización de datos',
                        'details' => $response->getData()
                    ], 500);
                }

                $Datos_Nuevos[] = $response->getData()->resultado;
            }

            return $this->guardar_datos($Datos_Nuevos);

        } catch (\Exception $e) {
            Log::info('error');
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function guardar_datos($Datos)
    {
        try {
            $json = json_encode($Datos, JSON_PRETTY_PRINT);
            $ruta = 'json/ejemplo.json';
            
            if (!Storage::put($ruta, $json)) {
                throw new \Exception("Error al guardar los datos");
            }

            return response()->json([
                'success' => true,
                'message' => 'Archivo aceptado y procesado correctamente',
                'data' => $Datos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar los datos',
                'error' => $e->getMessage()
            ], 500);
        }

        return app('App\Http\Controllers\PrepararDatosController')->Preparar_Datos();
    }
}