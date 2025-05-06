<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;


class ExcelController extends Controller
{
    private $expectedColumns17 = [
        'Consecutivo',
        'Por Año',
        'Fecha',
        'Generación',
        'Alumno',
        'clave',
        'correo',
        'Carta',
        'Carrera',
        'Tipo',
        'Materia_Dificil',
        'Materia Difícil 2',
        'Escuela de Procedencia',
        'Empresa Laboral',
        'Inconveniente',
        '', // Columna vacía
        ''  // Columna vacía
    ];
    
    private $expectedColumns20 = [
        "ID",
        'Hora de inicio',
        'Hora de finalización',
        'Correo electrónico',
        'Nombre',
        'Hora de la última modificación',
        "Clave de Alumno",
        'Nombre Completo',
        'Generación',
        'Carrera del Alumno',
        'Correo Electrónico (Que se utilice frecuentemente, que no sea de la UASLP)',
        '¿De qué preparatoria egresaste?',
        'Motivo real de la Baja',
        '¿Se tuvo algún problema en la carrera?, describa',
        'Forma de Titulación',
        'Si la titulación fue por EGEL. ¿En qué fecha presentaste el examen?',
        'Si trabaja, cual es el nombre de la empresa',
        'Materia Difícil 1',
        'Materia Difícil 2',
        'Materia Difícil 3'
    ];

    public $c_id_anio = [];
    public $c_anio_baja = []; //-----------------------------------
    public $c_clave = [];
    public $c_nombre = [];
    public $c_generacion = [];
    public $c_carrera = [];
    public $c_email = [];
    public $c_materias = [];
    public $c_escuelas = [];
    public $c_baja = [];
    public $c_inconveniente = [];
    public $c_trabajos = [];
    public $c_titulacion = [];
    public $c_fecha_egel = [];
    public $c_mat2 = [];
    public $c_mat3 = [];

    public $DATOS = [];

    public function upload(Request $request)
    {
        // Validamos que se haya subido un archivo
        $request->validate([
            'file' => 'required|file|max:2048', // Solo verifica que sea un archivo (no el tipo)
        ]);
        //dd('El método upload se está ejecutando.');
        // Obtenemos el archivo subido
        $file = $request->file('file');

        $umbral = $request->input('umbral', 60);
    
        // Cargar el archivo usando PhpSpreadsheet
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        
        $data = $sheet->toArray(); // Convierte la hoja en un array de filas
        $columnIndex = 2; // Las columnas en arrays comienzan desde 0
        $fechas = array_column($data, $columnIndex);

        // Obtener las columnas de la primera fila (encabezados)
        // Nuevo modelo
        $headers = [];
        foreach ($sheet->getRowIterator(1, 1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $headers[] = trim($cell->getValue()); 
            }
        }

        // Obtener las columnas de la primera fila (encabezados)
        // Viejo modelo
        $rows = [];
        foreach ($sheet->getRowIterator(2,2) as $row) { // Comienza desde la segunda fila (datos)
            foreach ($row->getCellIterator() as $cell) {
                $rows[] = trim($cell->getValue()); 
            }
        }
    
        // Contar el número de columnas
        $columnCount = count($headers);
        //dd($columnCount);

        if($columnCount != 17 & $columnCount != 20)
        {
            return back()->withErrors(['file' => 'Archivo no aceptado: ' . 'no cuenta con las columnas esperadas']);
        }
        

        //--------------------------datos basura-----------------------------
        if($columnCount === 17)
        {
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
            $c_mat3 = 0;

            for ($i = 0; $i < 2; $i++) {
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
            }
 
        }
        elseif ($columnCount === 20) {
            $c_clave = array_column($data, 6);
            array_shift($c_clave); 

            $c_nombre = array_column($data, 4);
            array_shift($c_nombre); 
            
            $c_generacion = array_column($data, 8);
            array_shift($c_generacion); 

            $c_carrera = array_column($data, 9);
            array_shift($c_carrera); 

            $c_email = array_column($data, 10);
            array_shift($c_email); 

            $c_materias = array_column($data, 17);
            array_shift($c_materias); 

            $c_escuelas = array_column($data, 11);
            array_shift($c_escuelas); 

            $c_baja = array_column($data, 12);
            array_shift($c_baja); 

            $c_inconveniente = array_column($data, 13);
            array_shift($c_inconveniente); 

            $c_trabajos = array_column($data, 16);
            array_shift($c_trabajos); 

            $c_titulacion = array_column($data, 14);
            array_shift($c_titulacion); 

            $c_mat2 = array_column($data, 18);
            array_shift($c_mat2);

            $c_mat3 = array_column($data, 19);
            array_shift($c_mat3);
        }

        $c_id_anio = $this->ObtenFecha($fechas,$columnCount,$c_generacion);

        $DATOS[] = $c_id_anio;
        $DATOS[] = $c_clave;
        $DATOS[] = $c_nombre;
        $DATOS[] = $c_generacion;
        $DATOS[] = $c_carrera;
        $DATOS[] = $c_email;
        $DATOS[] = $c_materias;
        $DATOS[] = $c_escuelas;
        $DATOS[] = $c_baja;
        $DATOS[] = $c_inconveniente;
        $DATOS[] = $c_trabajos;
        $DATOS[] = $c_titulacion;
        $DATOS[] = $c_mat2;
        $DATOS[] = $c_mat3;

        //--------------------------materias-----------------------------

        // Redirigir la lógica según el número de columnas
        if ($columnCount === 17) {
            return $this->process17Columns($rows,$DATOS,$umbral);
        } elseif ($columnCount === 20) {
           return $this->process20Columns($headers,$DATOS,$umbral);
        } else {
            return back()->withErrors(['file' => 'Archivo no aceptado: ' . $columnCount]);
        }
    }
    
    private function process17Columns($headers,$DATOS,$umbral)
    {
        // Comparar los nombres de las columnas con los esperados
        $missingColumns = array_diff($this->expectedColumns17, $headers);
        $extraColumns = array_diff($headers, $this->expectedColumns17);
    
        if (!empty($missingColumns) || !empty($extraColumns)) {
            $errorMessage = 'El archivo no cumple con las columnas esperadas.';
            return back()->withErrors(['file' => $errorMessage]);
        }

        $json = json_encode($DATOS, JSON_PRETTY_PRINT);
        $rutaArchivo = 'json/lista.json';
        Storage::put($rutaArchivo, $json);
 
        return app('App\Http\Controllers\RecibirJsonController')->recibirJson($umbral);
    }
    
    private function process20Columns($headers,$DATOS,$umbral)
    {
        // Comparar los nombres de las columnas con los esperados
        $missingColumns = array_diff($this->expectedColumns20, $headers);
        $extraColumns = array_diff($headers, $this->expectedColumns20);
    
        if (!empty($missingColumns) || !empty($extraColumns)) {
            $errorMessage = 'El archivo no cumple con las columnas esperadas.';
            return back()->withErrors(['file' => $errorMessage]);
        }

        $json = json_encode($DATOS, JSON_PRETTY_PRINT);
        $rutaArchivo = 'json/lista.json';
        Storage::put($rutaArchivo, $json);
 
        return app('App\Http\Controllers\RecibirJsonController')->recibirJson($umbral);
    }

    //----------------------------------------------------------------------------------------------------------------

    private function ObtenFecha($fechas,$columnCount,$c_generacion)
    {
        $col_fecha = [];
        $count = 1;

        if($columnCount == 17)
        {
            array_shift($fechas);
            array_shift($fechas); 
        } 
        if($columnCount == 20)
        {
            array_shift($fechas);
        } 
        
        $col_fecha = array_map(function($fecha) {
            return date('Y', strtotime($fecha));
        }, $fechas);
        
        foreach($col_fecha as &$aux)
        {
            $aux = $aux. $count;
            $count++; 
        }
        
        $json = json_encode($col_fecha, JSON_PRETTY_PRINT);
        $rutaArchivo = 'json/lista_fecha.json'; 
        Storage::put($rutaArchivo, $json);

        foreach($c_generacion as &$c_generacion)
        {
            $c_generacion = substr($c_generacion, 0, 4);
        }

        return $col_fecha;
    }
}