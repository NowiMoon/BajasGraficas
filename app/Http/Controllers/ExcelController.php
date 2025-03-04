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
    public $c_clave = [];
    public $c_nombre = [];

    public $c_materias = [];
    public $c_escuelas = [];
    public $c_trabajos = [];


    public function upload(Request $request)
    {
        // Validar que se haya subido un archivo
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048', // Solo archivos .xlsx de máximo 2MB
        ]);
    
        // Obtener el archivo subido
        $file = $request->file('file');
    
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
                $headers[] = trim($cell->getValue()); // Dentro del bucle foreach
            }
        }

        // Obtener las columnas de la primera fila (encabezados)
        // Viejo modelo
        $rows = [];
        foreach ($sheet->getRowIterator(2,2) as $row) { // Comienza desde la segunda fila (datos)
            //$rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                //$rowData[] = $cell->getValue();
                $rows[] = trim($cell->getValue()); // Dentro del bucle foreach
            }
            //$rows[] = $rowData;
        }
    
        // Contar el número de columnas
        $columnCount = count($headers);

        //--------------------------materias-----------------------------
        if($columnCount === 17)
        {
            $c_materias = array_column($data, 10);
            array_shift($c_materias);
            array_shift($c_materias); 

            $c_escuelas = array_column($data, 12);
            array_shift($c_escuelas);
            array_shift($c_escuelas); 

            $c_trabajos = array_column($data, 13);
            array_shift($c_trabajos);
            array_shift($c_trabajos); 
        }
        elseif ($columnCount === 20) {
            $c_materias = array_column($data, 17);
            array_shift($c_materias); 

            $c_escuelas = array_column($data, 11);
            array_shift($c_escuelas); 

            $c_trabajos = array_column($data, 16);
            array_shift($c_trabajos); 
        }
        
        $json_a = json_encode($c_escuelas, JSON_PRETTY_PRINT);
        $ruta = 'json/lista_materias.json';
        Storage::put($ruta, $json_a);

        

        //--------------------------materias-----------------------------
    
        // Redirigir la lógica según el número de columnas
        if ($columnCount === 17) {
            return $this->process17Columns($rows,$fechas,$columnCount,$c_materias,$c_escuelas,$c_trabajos);
        } elseif ($columnCount === 20) {
            return $this->process20Columns($headers,$fechas,$columnCount,$c_materias,$c_escuelas,$c_trabajos);
        } else {
            return back()->withErrors(['file' => 'Archivo no aceptado: ' . $columnCount]);
        }
    }
    
    private function process17Columns($headers,$fechas,$columnCount,$c_materias,$c_escuelas,$c_trabajos)
    {
        // Comparar los nombres de las columnas con los esperados
        $missingColumns = array_diff($this->expectedColumns17, $headers);
        $extraColumns = array_diff($headers, $this->expectedColumns17);
    
        if (!empty($missingColumns) || !empty($extraColumns)) {
            $errorMessage = 'El archivo no cumple con las columnas esperadas.';
            if (!empty($missingColumns)) {
                $errorMessage .= ' Faltan columnas';
            }
            if (!empty($extraColumns)) {
                $errorMessage .= ' Columnas adicionales: ' . implode(', ', $extraColumns);
            }
            return back()->withErrors(['file' => $errorMessage]);
        }
    
        $columna_fecha = $this->ObtenFecha($fechas,$columnCount);
        // Si todo está bien, procesar el archivo

        //return back()->with('success', 'Archivo aceptado: ' . implode(', ', $columna_fecha));
        return redirect()->route('resultados')->with('materias', $c_escuelas);
        
    }
    
    private function process20Columns($headers,$fechas,$columnCount,$c_materias,$c_escuelas,$c_trabajos)
    {
        // Comparar los nombres de las columnas con los esperados
        $missingColumns = array_diff($this->expectedColumns20, $headers);
        $extraColumns = array_diff($headers, $this->expectedColumns20);
    
        if (!empty($missingColumns) || !empty($extraColumns)) {
            $errorMessage = 'El archivo no cumple con las columnas esperadas.';
            if (!empty($missingColumns)) {
                $errorMessage .= ' Faltan: ' . implode(', ', $missingColumns);
            }
            if (!empty($extraColumns)) {
                $errorMessage .= ' Columnas adicionales: ' . implode(', ', $extraColumns);
            }
            return back()->withErrors(['file' => $errorMessage]);
        }
        
        $columna_fecha = $this->ObtenFecha($fechas,$columnCount);
        // Si todo está bien, procesar el archivo
        //return back()->with('success', 'Archivo aceptado: ' . implode(', ', $fechas));

        return redirect()->route('resultados')->with('materias', $c_escuelas);
    }

    //----------------------------------------------------------------------------------------------------------------
    
    private function ObtenFecha($fechas,$columnCount)
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

        return $col_fecha;
    }
}
