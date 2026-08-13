<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\Materia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ExcelController extends Controller
{
    private $expectedColumns16 = [
        'Consecutivo', 'Por Año', 'Fecha', 'Generación', 'Alumno', 'clave',
        'correo', 'Carta', 'Carrera', 'Tipo', 'Materia_Dificil', 'Materia Difícil 2',
        'Escuela de Procedencia', 'Empresa Laboral', 'Inconveniente', 'Tesis' 
    ];
    
    private $expectedColumns17 = [
        'Consecutivo', 'Por Año', 'Fecha', 'Generación', 'Alumno', 'clave',
        'correo', 'Carta', 'Carrera', 'Tipo', 'Materia_Dificil', 'Materia Difícil 2',
        'Escuela de Procedencia', 'Empresa Laboral', 'Inconveniente', '', ''
    ];
    
    private $expectedColumns20 = [
        "ID", 'Hora de inicio', 'Hora de finalización', 'Correo electrónico', 'Nombre',
        'Hora de la última modificación', "Clave de Alumno", 'Nombre Completo', 'Generación',
        'Carrera del Alumno', 'Correo Electrónico (Que se utilice frecuentemente, que no sea de la UASLP)',
        '¿De qué preparatoria egresaste?', 'Motivo real de la Baja', '¿Se tuvo algún problema en la carrera?, describa',
        'Forma de Titulación', 'Si la titulación fue por EGEL. ¿En qué fecha presentaste el examen?',
        'Si trabaja, cual es el nombre de la empresa', 'Materia Difícil 1', 'Materia Difícil 2', 'Materia Difícil 3'
    ];

    public function upload(Request $request)
    {
        mb_internal_encoding('UTF-8');
        
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);
        
        $file = $request->file('file');
        $umbral = $request->input('umbral', 60);
    
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        $fechas = array_column($data, 2);

        $headers = [];
        foreach ($sheet->getRowIterator(1, 1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $headers[] = trim($cell->getValue()); 
            }
        }

        $columnCount = count($headers);
        $rows = [];
        $primerValor = $sheet->getCell('A1')->getValue();
        $Nombre_header = "Relación de Alumnos que han solicitado carta de no adeudo a los Laboratorio UDICEI";
        $indice_aux = 1;

        if ($columnCount == 17) {
            if ($primerValor == $Nombre_header) {
                $indice_aux = 2;
            }
            foreach ($sheet->getRowIterator($indice_aux, $indice_aux) as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $rows[] = trim($cell->getValue()); 
                }
            }
        }
        
        if ($columnCount != 16 && $columnCount != 17 && $columnCount != 20) {
            return back()->withErrors(['file' => 'Archivo no aceptado: no cuenta con las columnas esperadas']);
        }

        // Mapeo seguro de columnas evitando índices indefinidos
        if ($columnCount === 16 || $columnCount === 17) {
            $c_clave = array_column($data, 5);
            $c_nombre = array_column($data, 4);
            $c_generacion = array_column($data, 3);
            $c_carrera = array_column($data, 8);
            $c_email = array_column($data, 6);
            $c_materias = array_column($data, 10);
            $c_escuelas = array_column($data, 12);
            $c_baja = array_column($data, 9);
            $c_inconveniente = array_column($data, 14);
            $c_trabajos = array_column($data, 13);
            $c_titulacion = array_column($data, 15); 
            $c_mat2 = array_column($data, 11);
            $c_mat3 = array_fill(0, count($c_clave), 'Ninguna');

            $limit = ($columnCount === 16) ? 1 : $indice_aux;
            for ($i = 0; $i < $limit; $i++) {
                array_shift($c_clave);
                array_shift($c_nombre);
                array_shift($c_generacion);
                array_shift($c_carrera);
                array_shift($c_email);
                array_shift($c_materias);
                array_shift($c_escuelas);
                array_shift($c_baja);
                array_shift($c_inconveniente);
                array_shift($c_trabajos);
                array_shift($c_titulacion);
                array_shift($c_mat2);
                array_shift($c_mat3);
            }
        } elseif ($columnCount === 20) {
            $c_clave = array_column($data, 6); array_shift($c_clave); 
            $c_nombre = array_column($data, 7); array_shift($c_nombre); 
            $c_generacion = array_column($data, 8); array_shift($c_generacion); 
            $c_carrera = array_column($data, 9); array_shift($c_carrera); 
            $c_email = array_column($data, 10); array_shift($c_email); 
            $c_materias = array_column($data, 17); array_shift($c_materias); 
            $c_escuelas = array_column($data, 11); array_shift($c_escuelas); 
            $c_baja = array_column($data, 12); array_shift($c_baja); 
            $c_inconveniente = array_column($data, 13); array_shift($c_inconveniente); 
            $c_trabajos = array_column($data, 16); array_shift($c_trabajos); 
            $c_titulacion = array_column($data, 14); array_shift($c_titulacion); 
            $c_mat2 = array_column($data, 18); array_shift($c_mat2);
            $c_mat3 = array_column($data, 19); array_shift($c_mat3);
        }

        // --- VALIDACIÓN Y REEMPLAZO POR "Ninguna" SI ESTÁ VACÍO ---
        $normalizarTexto = function($val) {
            $v = trim((string)$val);
            return ($v === '' || $v === null || strtolower($v) === 'n/a') ? 'Ninguna' : substr($v, 0, 50);
        };

        $materias_truncadas = array_map($normalizarTexto, $c_materias);
        $mat2_normalizado = array_map($normalizarTexto, $c_mat2);
        $mat3_normalizado = array_map($normalizarTexto, $c_mat3);
        $c_escuelas = array_map($normalizarTexto, $c_escuelas);
        $c_trabajos = array_map($normalizarTexto, $c_trabajos);
        $titulaciones = array_map($normalizarTexto, $c_titulacion);

        $claves_nuevas = array_map(function($cadena) {
            $res = preg_replace("/\D/", "", (string)$cadena);
            return $res !== '' ? $res : '0000';
        }, $c_clave);

        $generacion_nueva = array_map(function($cadena) {
            $res = preg_replace("/\D/", "", (string)$cadena);
            return ($res !== '') ? $res : '2024';
        }, $c_generacion);

        foreach ($generacion_nueva as &$valor) {
            if (abs((int)$valor) < 1000) {
                $valor = "20" . $valor;
            }
        }
        unset($valor);

        foreach ($c_carrera as $idx => $valorCarrera) {
            if ($valorCarrera === null || trim((string)$valorCarrera) === '') {
                $c_carrera[$idx] = 'Ingeniería en Computación';
                continue;
            }
            $v = mb_strtolower($valorCarrera);
            if (mb_strpos($v, 'computación') !== false || mb_strpos($v, 'computacion') !== false) {
                $c_carrera[$idx] = 'Ingeniería en Computación';
            } elseif (mb_strpos($v, 'informática') !== false || mb_strpos($v, 'informatica') !== false) {
                $c_carrera[$idx] = 'Ingeniería en Informática';
            } elseif (mb_strpos($v, 'inteligentes') !== false || mb_strpos($v, 'sistemas') !== false) {
                $c_carrera[$idx] = 'Ingeniería en Sistemas Inteligentes';
            } else {
                $c_carrera[$idx] = trim($valorCarrera);
            }
        }

        $inconveniente_normalizado = array_map(function($valor) {
            $v = trim((string)$valor);
            return ($v === '' || $v === null) ? 'Ninguno' : $v;
        }, $c_inconveniente);

        $baja_minusculas = array_map(function($valor) {
            $v = trim((string)$valor);
            return ($v === '' || $v === null) ? 'baja temporal o definitiva' : strtolower($v);
        }, $c_baja);

        $c_id_anio = [];
        $count = 1;
        $fechasSlice = $fechas;
        if ($indice_aux == 2) array_shift($fechasSlice);
        array_shift($fechasSlice);

        foreach ($fechasSlice as $fecha) {
            $year = date('Y', strtotime($fecha));
            $validYear = ($year && $year > 2000) ? $year : '2024';
            $c_id_anio[] = $validYear . $count;
            $count++;
        }

        $DATOS = [
            $c_id_anio,
            $claves_nuevas,
            $c_nombre,
            $generacion_nueva,
            $c_carrera,
            $c_email,
            $materias_truncadas,
            $c_escuelas,
            $baja_minusculas,
            $inconveniente_normalizado,
            $c_trabajos,
            $titulaciones,
            $mat2_normalizado,
            $mat3_normalizado
        ];

        $json = json_encode($DATOS, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
        Storage::put('json/lista.json', $json);

        // Retornar directamente a la vista de resultados internos de Laravel o controlador local
        return app('App\Http\Controllers\RecibirJsonController')->recibirJson($umbral);
    }


    public function uploadSubjects(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            if (empty($data) || count($data) < 2) {
                return redirect()->route('gestion_materias')->with('error', 'El archivo CSV está vacío o no tiene formato válido.');
            }

            // Identificar índices de columnas a partir de la cabecera (fila 0)
            $header = array_map(fn($h) => trim(strtolower($h)), $data[0]);
            $claveIndex = array_search('clave_c', $header);
            $nombreIndex = array_search('nombre_c', $header);

            if ($claveIndex === false || $nombreIndex === false) {
                return redirect()->route('gestion_materias')->with('error', 'El archivo no contiene las columnas requeridas (clave_c, nombre_c).');
            }

            // Omitir cabecera e insertar/actualizar registros
            for ($i = 1; $i < count($data); $i++) {
                $row = $data[$i];
                $clave = trim($row[$claveIndex] ?? '');
                $nombre = trim($row[$nombreIndex] ?? '');

                if (!empty($clave) && !empty($nombre)) {
                    Materia::updateOrCreate(
                        ['clave_materia' => $clave],
                        ['nombre_materia' => $nombre]
                    );
                }
            }

            return redirect()->route('gestion_materias')->with('success', 'Materias importadas correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('gestion_materias')->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

}
