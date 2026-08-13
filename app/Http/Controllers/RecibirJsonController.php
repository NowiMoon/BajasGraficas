<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Alumno;
use App\Models\Materia;
use Illuminate\Support\Facades\Log;

class RecibirJsonController extends Controller
{
    public function recibirJson($umbral)
    {
        try {
            Log::info('Procesando JSON localmente con listas maestras y coincidencia inteligente...');
            $Datos_Nuevos = [];
            $rutaArchivo = 'json/lista.json';

            if (!Storage::exists($rutaArchivo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Archivo lista.json no encontrado'
                ], 404);
            }

            $json = Storage::get($rutaArchivo);
            $datos = json_decode($json, true);

            if (!isset($datos[0])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos en formato incorrecto'
                ], 400);
            }

            $lista_deseada = $datos[0];
            $aux_id_anio = Alumno::pluck('Id_Reg_A')->toArray();
            $cont = 0;

            if (!isset($datos[13]) || !is_array($datos[13])) {
                $datos[13] = array_fill(0, count($datos[0]), 'Ninguna');
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

            $json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $ruta = 'json/lista_sin_duplicados.json';
            Storage::put($ruta, $json);

            if (count($datos[0]) === 0){
                return response()->json([
                    'success' => true,
                    'message' => 'Los valores son válidos, pero ya existen en la base de datos',
                    'codigo' => 'DUPLICADOS'
                ]);
            }

            $json = Storage::get($ruta);
            $datos = json_decode($json, true);

            $entradas_Materias = $datos[6] ?? [];
            $entradas_Escuelas = $datos[7] ?? [];
            $entradas_Trabajos = $datos[10] ?? [];

            // Listas maestras con adiciones enfocadas en San Luis Potosí
            $materias_normalizadas = [
                "Química A","Fundamentos de Compiladores","Matemáticas Discretas I","Pensamiento Algorítmico","Temas Selectos de Matemáticas","Herramientas de Software","Metodología de la Investigación",
                "Visual","Pensamiento Computacional","Ninguna","Electrónica B","Electrónica A","Sistemas Operativos B","Sistemas Operativos A","Economía","Diseño de Circuitos","Programación de Sistemas","Grafos","Estructuras de Datos B","Compiladores A","Compiladores B","Seminario de Orientación en Computación","Cálculo A","Matemáticas Discretas II","Estructuras de Datos I","Álgebra B","Fundamentos de Circuitos Eléctricos","Inglés 1","Cálculo B",
                "Ingeniería de Software","Estructuras de Datos A","Estructuras de Datos B","Estructuras de Datos II","Lenguajes de Programación","Dispositivos Semiconductores","Tendencias Sociales","Cálculo D","Tecnología Orientada a Objetos",
                "Algoritmos y complejidad","Base de Datos","Fundamentos de Diseño Digital","Gestión y Desarrollo Social","Inglés 2","Análisis Numérico","Interfaces gráficas con aplicaciones","Gestión de Servidores y Seguridad",
                "Física A","Diseño Digital","Inglés 3","Probabilidad y Estadística","Estructuras de datos Avanzadas","Diseño e Implementación de Redes","Sistemas Operativos","Diseño de Microcomputadoras","Arte, Cultura y Humanidades I","Inglés 4","Seminario de Medio término","Administración de Proyectos I","Fundamentos de Compiladores","Microcontroladores","Técnicas de Comunicación Oral y Escrita","Inglés 5","Proyectos Computacionales I","Administración de Proyectos II","Procesamiento de Señales","Liderazgo","Proyectos Computacionales II","Fundamentos de Software de sistemas","Emprendimiento","Proyectos Computacionales III","Seminario de Egreso","Computación y Sociedad","Actividades Artisticas, Deportivas o de Divulgación","Actividades de aprendizaje I","Actividades de aprendizaje II","Actividades de aprendizaje III","Actividades de aprendizaje IV","Actividades de aprendizaje V","Movilidad I","Movilidad III","Movilidad III","Movilidad IV","Movilidad V","Sistemas Operativos Avanzados","Fundamentos de Inteligencia Artificial","Supercómputo","Administración de Base de Datos","Arte, Cultura y Humanidades II *","Robótica","Sistemas Embebidos","Automatización","Control Digital","Interfaces Digitales de comunicaciones","Principios de Cómputo en la Nube","Administración de Redes","Arquitectura de Nube","Interacción de redes","Servicios en la Nube","Modelado y simulación de redes",
                "Fundamentos de Desarrollo web", "Introducción a la Programación","Fundamentos de Desarrollo móvil","Aplicaciones web interactivas","Diseño de interfaces","Aplicaciones web escalables","Introducción a los sistemas Geoespaciales","Visión Computacional","Base de datos Geoespaciales","Geointeligencia artificial aplicada a la teledetección","Geoaplicaciones web y móviles","Prácticas Profesionales Computación","Programación de Robots","Cómputo Bio-Inspirado","Aprendizaje automático","Robótica inteligente","Ciencia de datos","Representación del conocimiento y ontologías","Programación de videojuegos","Diseño de juegos","Motores gráficos","Arte conceptual para videojuegos","Temas Selectos de videojuegos","Principios de seguridad informática","Criptografía","Anonimato y privacidad","Prácticas Profesionales ISI","Sistemas Interactivos","Arquitectura de Computadoras","Graficación por computadoras","Modelado Matemático"
            ];

            $trabajos_normalizados = [
                "Ninguna","ABB","Daikin","Honeywell","Siemens","Bosch","GE","3M","Rockwell Automation","Schneider Electric","LG","Panasonic","Xerox","Qualcomm","Broadcom","Micron","Texas Instruments","ARM","TSMC","Tesla","Waymo","Ford","GM","Toyota","BMW","Mercedes","Volkswagen","Rivian","Lucid Motors","Canva","Discord","Airbnb","DoorDash","Instacart","Patreon","Reddit","Byju's","Google","Microsoft","Apple","Amazon","Meta","IBM","Oracle","Intel","AMD","NVIDIA","Netflix","Uber","Lyft","OpenAI","SpaceX","Palantir","Stripe","Robinhood","Databricks","Salesforce","Spotify","Twitter","LinkedIn","Snapchat","TikTok","Zoom","Cisco","HP","Dell","Samsung","Goldman Sachs","JPMorgan Chase","PayPal","Coinbase","BBVA","Santander","Citibank","Morgan Stanley","American Express","Visa","Mastercard","Revolut","Nubank","Kavak","Rappi","Accenture","Deloitte","PwC","EY","KPMG","McKinsey","BCG","Bain","Capgemini","Infosys","AT&T","Verizon","Ericsson","Huawei","T-Mobile","Telefónica","Claro","Movistar","America Móvil","EA","Ubisoft","Activision Blizzard","Riot Games","Epic Games","Valve","Nintendo","Sony","Bandai Namco","Square Enix","NASA","Lockheed Martin","Boeing","Raytheon","NSA","CIA","FBI","Walmart","Alibaba","MercadoLibre","Shopify","eBay","Target","Best Buy","Allegro","SEARS","Johnson & Johnson","Pfizer","Moderna","Roche","Merck","Siemens Healthineers","Philips",
                "BMW Group Planta San Luis Potosí", "General Motors San Luis Potosí", "Continental Automotive San Luis Potosí", 
                "Bosch San Luis Potosí", "Cummins S. de R.L. de C.V.", "Dräxlmaier Group San Luis Potosí", "Valeo San Luis Potosí", 
                "Mabe San Luis Potosí", "Goodyear San Luis Potosí", "Whirlpool San Luis Potosí", "Industrial Minera México (IMMSA)"
            ];

            $escuelas_normalizadas = [
                "Ninguna","COBACH 01","COBACH 02","COBACH 03","COBACH 04","COBACH 05","COBACH 06","COBACH 07","COBACH 08","COBACH 09","COBACH 10","COBACH 11","COBACH 12","COBACH 13","COBACH 14","COBACH 15","COBACH 16","COBACH 17","COBACH 18","COBACH 19","COBACH 20","COBACH 21","COBACH 22","COBACH 23","COBACH 24","COBACH 25","COBACH 26","COBACH 27","COBACH 28","COBACH 29","COBACH 30","COBACH 31","COBACH 32","COBACH 33","COBACH 34","COBACH 35","COBACH 36","COBACH 37","COBACH 38","COBACH 39","COBACH 40",
                "CBTIS 131","CBTIS 51","CBTIS 121","CBTIS 128","CBTIS 137","CBTIS 150","CBTIS 168","CBTIS 169","CBTIS 170","CBTIS 171","CBTIS 172","CBTIS 173","CBTIS 174","CBTIS 175","CBTIS 176","CBTIS 177","CBTIS 178","CBTIS 179","CBTIS 180","CBTIS 181","CBTIS 182","CBTIS 183","CBTIS 184","CBTIS 185","CBTIS 186","CBTIS 187","CBTIS 188","CBTIS 189","CBTIS 190","CBTIS 191","CBTIS 192","CBTIS 193","CBTIS 194","CBTIS 195","CBTIS 196","CBTIS 197","CBTIS 198","CBTIS 199","CBTIS 200","CBTIS 201","CBTIS 202","CBTIS 203","CBTIS 204","CBTIS 205","CBTIS 206","CBTIS 207","CBTIS 208","CBTIS 209","CBTIS 210","CBTIS 211","CBTIS 212","CBTIS 213","CBTIS 214","CBTIS 215","CBTIS 216","CBTIS 217","CBTIS 218","CBTIS 219","CBTIS 220",
                "CONALEP 1","CONALEP 2","CONALEP 3","CONALEP 4","CONALEP 5",
                "Preparatoria Central", "Preparatoria Ponciano Arriaga",
                "Preparatoria Enrique Rébsamen", "Instituto Potosino Marista", "Preparatoria del Instituto Potosino",
                "PrepaTec San Luis Potosí", "Preparatoria del Instituto Tecnológico de San Luis Potosí",
                "Preparatoria del Instituto Cultural Tampico", "Preparatoria del Colegio Simón Bolívar",
                "Preparatoria del Colegio Juana de Asbaje", "ENP (Escuela Nacional Preparatoria)",
                "CCH (Colegio de Ciencias y Humanidades)", "CECyT (Centro de Estudios Científicos y Tecnológicos)",
                "Preparatoria 1 - UANL", "UANL", "Preparatoria 3 - UANL",
                "Bachillerato de la UAQ","Alfa y omega",
                "Bachillerato de la UAA", "Bachillerato de la UAZ", "Bachillerato de la UG",
                "Bachillerato de la BUAP","Prepa Anáhuac","Prepa Marista","Prepa Motolinía","Instituto Salesiano",
                "Prepa Cuauhtémoc","Colegio Terranova","Instituto Hispano Inglés","Instituto Potosino","Francisco Martínez",
                "Lic.Antonio Rocha cordero","Rafael Nieto","San Luis Rey","Instituto San Rafael","José Juárez","Celia Fernández","Juana de Asbaje","El Saucito",
                "José Natividad","Luis Medellín","6 Junio","Solidaridad","Juan Salinas","Justo Sierra","Municipal 4","EMSAD 01",
                "Prepa en Línea SEP","BETIS 1","BETIS 2","BETIS 3","BETIS 4","BETIS 5","BETIS 6","BETIS 7","BETIS 8","BETIS 9","BETIS 10",
                "UASLP Preparatoria", "Centro Universitario Potosino (CUP)", "Instituto Carlos Gómez", "CETIS 125", "CBTIS 121 San Luis Potosí", "Colegio San Luis", "Colegio Othón"
            ];

            // Combinar con la base de datos si ya existen registros
            $materias_bd = Materia::pluck('nombre_materia')->toArray();
            $trabajos_bd = Alumno::whereNotNull('Empresa')->distinct()->pluck('Empresa')->toArray();
            $escuelas_bd = Alumno::whereNotNull('Escuela')->distinct()->pluck('Escuela')->toArray();

            $materias_opciones = array_unique(array_merge($materias_bd, $materias_normalizadas));
            $trabajos_opciones = array_unique(array_merge($trabajos_bd, $trabajos_normalizados));
            $escuelas_opciones = array_unique(array_merge($escuelas_bd, $escuelas_normalizadas));

            for($tipo = 1; $tipo < 4; $tipo++) {
                $entradas = [];
                $opciones = [];

                if ($tipo === 1) {
                    $entradas = $entradas_Materias;
                    $opciones = $materias_opciones;
                } elseif ($tipo === 2) {
                    $entradas = $entradas_Escuelas;
                    $opciones = $escuelas_opciones;
                } elseif ($tipo === 3) {
                    $entradas = $entradas_Trabajos;
                    $opciones = $trabajos_opciones;
                }

                $resultadosTipo = [];
                foreach ($entradas as $entrada) {
                    [$mejorCoincidencia, $opcionesSugeridas] = $this->fuzzyMatch($entrada, $opciones, $umbral);
                    $resultadosTipo[] = [
                        'entrada' => $entrada,
                        'mejor_coincidencia' => $mejorCoincidencia,
                        'opciones' => $opcionesSugeridas
                    ];
                }

                $Datos_Nuevos[] = [
                    'resultados' => $resultadosTipo
                ];
            }

            return $this->guardar_datos($Datos_Nuevos);

        } catch (\Exception $e) {
            Log::error('Error local en RecibirJsonController: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor local',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function fuzzyMatch($entrada, $opciones, $umbral)
    {
        $entradaTrim = trim((string)$entrada);
        if ($entradaTrim === '' || strtolower($entradaTrim) === 'ninguna' || strtolower($entradaTrim) === 'n/a') {
            return ['Ninguna', ['Ninguna']];
        }

        $puntuadas = [];
        foreach ($opciones as $opcion) {
            $opcionTrim = trim((string)$opcion);
            if ($opcionTrim === '') continue;

            if (strcasecmp($entradaTrim, $opcionTrim) === 0) {
                return [$opcionTrim, [$opcionTrim]];
            }

            similar_text(strtolower($entradaTrim), strtolower($opcionTrim), $percent);
            $puntuadas[] = ['opcion' => $opcionTrim, 'puntaje' => $percent];
        }

        usort($puntuadas, function($a, $b) {
            return $b['puntaje'] <=> $a['puntaje'];
        });

        $opcionesSugeridas = [];
        foreach (array_slice($puntuadas, 0, 3) as $item) {
            $opcionesSugeridas[] = $item['opcion'];
        }

        if (!empty($puntuadas) && $puntuadas[0]['puntaje'] >= $umbral) {
            $mejorCoincidencia = $puntuadas[0]['opcion'];
        } else {
            $mejorCoincidencia = !empty($opcionesSugeridas) ? $opcionesSugeridas[0] : 'Ninguna';
        }

        return [$mejorCoincidencia, !empty($opcionesSugeridas) ? $opcionesSugeridas : ['Ninguna']];
    }

    private function guardar_datos($Datos)
    {
        try {
            $json = json_encode($Datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $ruta = 'json/ejemplo.json';
            
            if (!Storage::put($ruta, $json)) {
                throw new \Exception("Error al guardar los datos temporales");
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
    }
}