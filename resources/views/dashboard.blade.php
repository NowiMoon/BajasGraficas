@extends('layouts.app')
@section('content')

<div style="display: flex;">
    <div class="d-flex flex-column bg-secondary bg-opacity-10 align-items-center justify-content-evenly"
         style="width: 80px; z-index: 1000; position: fixed; top: 80px; left: 0; height: calc(100vh - 80px);">
        @if ( Auth::user()->user_type == 1 )
        <a class="d-block icon-item text-center" style="text-decoration: none; color: black;" href="{{ route('register') }}"><img src="{{ asset('images/users_icon.svg') }}" alt="Usuarios">Gestion de Usuarios</a>
        <hr style="width: 80%; border: 3px solid #00B2E3; margin: 0 auto;">
        @endif
        @if ( Auth::user()->user_type == 2 )
        <a class="d-block icon-item text-center" style="text-decoration: none; color: black;" href=" {{ route('gestion_materias') }} "><img src="{{ asset('images/uploadSubjects_icon.svg') }}" alt="Subir materias">Subir materias</a>
        <hr style="width: 80%; border: 3px solid #00B2E3; margin: 0 auto;">
        <a class="d-block icon-item text-center" style="text-decoration: none; color: black;"><img src="{{ asset('images/addData_icon.svg') }}" alt="Upload file" data-bs-toggle="modal" data-bs-target="#uploadFile" style="display: block; margin: 0 auto;">Subir datos</a>
        <hr style="width: 80%; border: 3px solid #00B2E3; margin: 0 auto;">
        @endif
        <!--<a class="d-block icon-item text-center" style="text-decoration: none; color: black;" href="{{ route('downloadPDF') }}"><img src="{{ asset('images/download_icon.svg') }}" alt="Download analytics" style="display: block; margin: 0 auto;">Generar Reporte</a>-->
        <button id="btnDownloadPDF" style="background:none; border:none; cursor:pointer; color:black; text-align:center;">
            <img src="{{ asset('images/download_icon.svg') }}" alt="Download analytics" style="display: block; margin: 0 auto;">
            Generar Reporte
        </button>
    </div>
    <div style="margin-left: 80px; margin-top: 80px; width: 100%;">
        <div class="container">
            @yield('content')
        </div>
    </div>
</div>

@section('content')
<div class="d-flex my-0 py-0">
    <!-- Sidebar -->`
    
    <!-- Contenido principal INGRESAR AQUI LO QUE FALTE DE DASHBOARD -->
    <div class="container flex-grow-1 col-11 mx-auto mt-10">
        <!-- Contenedor para mensajes AJAX -->
        <div id="ajaxMessages" class="mt-3"></div>
        <!-- TABLA CON RESULTADOS REALES -->
        <!-- Contenedor para resultados - Fuera del modal -->
        <div class="container mt-4 pt-4" id="resultadosContainer"> <!--style="display: none;"-->
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #004A98;">
                    <h3 class="card-title mb-0">Datos</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered table-striped table-hover mb-0" id="resultadosFinalesTable">
                            <thead class="table-dark sticky-top" style="top: 0;">
                                <tr>
                                    <th style="width: 4%; background-color: #00B2E3">Id</th>
                                    <th style="width: 4%; background-color: #00B2E3">Año</th>
                                    <th style="width: 4%; background-color: #00B2E3">Id Reg</th>
                                    <th style="width: 5%; background-color: #00B2E3">Clave</th>
                                    <th style="width: 8%; background-color: #00B2E3">Nombre</th>
                                    <th style="width: 5%; background-color: #00B2E3">Generación</th>
                                    <th style="width: 8%; background-color: #00B2E3">Carrera</th>
                                    <th style="width: 5%; background-color: #00B2E3">Email</th>
                                    <th style="width: 5%; background-color: #00B2E3">Mat1</th>
                                    <th style="width: 5%; background-color: #00B2E3">Mat2</th>
                                    <th style="width: 5%; background-color: #00B2E3">Mat3</th>
                                    <th style="width: 5%; background-color: #00B2E3">Escuela</th>
                                    <th style="width: 5%; background-color: #00B2E3">Tipo Baja</th>
                                    <th style="width: 5%; background-color: #00B2E3">Inconveniente</th>
                                    <th style="width: 5%; background-color: #00B2E3">Empresa</th>
                                    <th style="width: 5%; background-color: #00B2E3">Titulación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alumnos as $alumno)
                                    <tr>
                                        <td>{{ $alumno->Id_Registro }}</td>
                                        <td>{{ $alumno->Anio }}</td>
                                        <td>{{ $alumno->Id_Reg_A }}</td>
                                        <td>{{ $alumno->Cv_Alumno }}</td>
                                        <td>{{ $alumno->Nombre_Alumno }}</td>
                                        <td>{{ $alumno->Gen }}</td>
                                        <td>{{ $alumno->Carrera }}</td>
                                        <td>{{ $alumno->email }}</td>
                                        <td>{{ $alumno->Mat_1 }}</td>
                                        <td>{{ $alumno->Mat_2 }}</td>
                                        <td>{{ $alumno->Mat_3 }}</td>
                                        <td>{{ $alumno->Escuela }}</td>
                                        <td>{{ $alumno->TBaja }}</td>
                                        <td>{{ $alumno->Inc_Carr }}</td>
                                        <td>{{ $alumno->Empresa }}</td>
                                        <td>{{ $alumno->Titulacion }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            <div class="card-footer text-end">
                <!-- Footer vacío -->
                <!-- Botones movidos debajo de la tabla y centrados -->
                <p id="mensaje"></p>

                <div class="card-footer text-end">
                <div class="d-flex flex-wrap align-items-center p-3">
                    <!-- Primera fila de opciones -->
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-4 mb-3">
                        <!-- Sección Tipo de Gráfica (sin checkbox) -->
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0">Tipo de gráfica:</label>
                                <select class="form-select" id="tipoGrafica" style="width: 170px;">
                                    <option value="pie">Gráfica de Pie</option>
                                    <option value="bar">Gráfica de Barras</option>
                                    <option value="line">Gráfica de Líneas</option>
                                </select>
                            </div>

                            <!-- Sección Tipo de Baja con checkbox -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input filtro-unico" type="checkbox" id="bajaCheckbox">
                                    <label class="form-check-label" for="bajaCheckbox">Tipo de baja:</label>
                                </div>
                                <select class="form-select" id="baja" style="width: 150px;">
                                    <option value="todas">Todas</option>
                                    <option value="Trámite de Pasantía">Trámite de Pasantía</option>
                                    <option value="Baja Temporal o Definitiva">Baja Temporal o Definitiva</option>
                                    <option value="Cambio de Carrera">Cambio de Carrera</option>
                                </select>
                            </div>

                            <!-- Sección Generación (ya tiene checkbox) -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input filtro-unico" type="checkbox" id="generacion">
                                    <label class="form-check-label" for="generacion">Generación:</label>
                                </div>
                                <input type="text" class="form-control" id="anio_1" style="width: 70px;">
                                <span class="mx-1">a</span>
                                <input type="text" class="form-control" id="anio_2" style="width: 70px;">
                            </div>

                            <!-- Sección Carrera con checkbox -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input filtro-unico" type="checkbox" id="carreraCheckbox" >
                                      <label class="form-check-label" for="carreraCheckbox">Carrera:</label>
                                </div>
                                <select class="form-select" id="Carrera" style="width: 150px;">
                                    <option value="todas">Todas</option>
                                    <option value="Ingeniero en Computación">Ingeniero en Computación</option>
                                    <option value="Ingeniero en Sistemas Inteligentes">Ingeniero en Sistemas Inteligentes</option>
                                    <option value="Ingeniero en Informática">Ingeniero en Informática</option>
                                </select>
                            </div>
                        </div>

                        <!-- Segunda fila de opciones -->
                        <div class="d-flex flex-wrap align-items-center justify-content-center gap-4 mb-3">
                            <!-- Sección Escuela con checkbox -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input filtro-unico" type="checkbox" id="escuelaCheckbox">
                                    <label class="form-check-label" for="escuelaCheckbox">Escuela de procedencia:</label>
                                </div>
                                <select class="form-select" id="escuela" style="width: 170px;">
                                    <option value="todas">Todas</option>
                                </select>
                            </div>

                            <!-- Sección Materias Dificiles con checkbox -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input filtro-unico" type="checkbox" id="materiaCheckbox">
                                    <label class="form-check-label" for="materiaCheckbox">Materia difícil:</label>
                                </div>
                                <select class="form-select" id="materia" style="width: 150px;">
                                    <option value="todas">Todas</option>
                                </select>
                            </div>

                            <!-- Sección Tipo de titulación con checkbox -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input filtro-unico" type="checkbox" id="titulacionCheckbox">
                                    <label class="form-check-label" for="titulacionCheckbox">Tipo de titulación:</label>
                                </div>
                                <select class="form-select" id="tipo_titulacion" style="width: 150px;">
                                    <option value="todas">Todas</option>
                                    <option value="Trabajo recepcional">Trabajo recepcional</option>
                                    <option value="Trabajo por excelencia">Trabajo por excelencia</option>
                                    <option value="Trabajo colectivo">Trabajo colectivo</option>
                                    <option value="Memorias de actividad profesional">Memorias de actividad profesional</option>
                                    <option value="Examen General de Conocimientos">Examen General de Conocimientos</option>
                                    <option value="Curso de opción a No trabajo recepcional (Diplomado)">Curso de opción a No trabajo recepcional (Diplomado)</option>
                                    <option value="Un semestre de maestría">Un semestre de maestría</option>
                                    <option value="Dos semestres de maestría">Dos semestres de maestría</option>
                                    <option value="Exención de Examen Promedio mayor a 9">Exención de Examen Promedio mayor a 9</option>
                                    <option value="Examen General de Egreso de la Licenciatura (EGEL)">Examen General de Egreso de la Licenciatura (EGEL)</option>
                                    <option value="Por artículo científico">Por artículo científico</option>
                                    <option value="Ninguna">Ninguna</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center justify-content-center gap-4 mb-3">
                            <!-- Sección Trabajo con checkbox -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input filtro-unico" type="checkbox" id="escuelaCheckbox">
                                    <label class="form-check-label" for="trabajoCheckbox">Lugar donde labura:</label>
                                </div>
                                <select class="form-select" id="trabajo" style="width: 150px;">
                                    <option value="todas">Todas</option>
                                </select>
                            </div>
                        <button class="btn btn-success ms-3" id="generarGraficaBtn">Generar Gráfica</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- GRÁFICA DE EJEMPLO -->
    <div class="card-footer text-end">
        <!-- Agregar contenedor para la gráfica -->
        <div id="chartContainer" style="display: none; width: 80%; margin: 20px auto;">
            <canvas id="dataChart"></canvas>
        </div>
    </div>


<!-- Modal de carga -->
<div class="modal fade" id="uploadFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"><!-- Clase para tamaño extra grande -xl lg-->
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Carga de Información</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="modalMessages"></div>

            <div class="rounded-lg shadow-lg p-4" style="background-color: #ffffff; border: 1px solid #e2e8f0;">
                <h2 class="text-center mb-6" style="color: #2c3e50; font-size: 1.5rem; font-weight: 600; letter-spacing: 0.5px; padding: 0.5rem 0; border-bottom: 2px solid #3490dc; display: inline-block; margin: 0 auto 1.5rem; display: block;">SUBIR ARCHIVO EXCEL (.XLSX, .XLX)</h2>
                
                <div class="row align-items-center">
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('images/document_search.png') }}" alt="Select File" style="height: 120px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                    </div>

                    <div class="col-md-6">
                        <form id="ajaxUploadForm" method="POST" enctype="multipart/form-data">   
                            <!-- POST PARA UPLOAD NO MOVER-->
                            @csrf
                            <div class="mb-4">
                                <label for="file" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4a5568;">Selecciona un archivo .xlsx:</label>
                                <div style="position: relative;">
                                    <input type="file" id="file" name="file" accept=".xlsx" required 
                                           style="width: 100%; padding: 0.75rem; font-size: 1rem; color: #4a5568; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.375rem; transition: all 0.2s ease;"
                                           onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                    <div id="file-name" style="margin-top: 0.5rem; font-size: 0.875rem; color: #718096;"></div>
                                </div>
                            </div>

                            <!-- Control de umbral 0 A 100 FUNCIONA NO MOVER-->
                            <div class="mb-4" style="position: relative;">
                                <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                    <label for="umbral" style="font-weight: 500; color: #4a5568; margin-right: 0.5rem;">Umbral de similitud:</label>
                                    <span id="valorUmbral" style="font-weight: 600; color: #3490dc;">60%</span>
                                    <!-- Botón de información -->
                                    <button type="button" id="infoUmbralBtn" class="btn btn-sm btn-link p-0 ms-2" style="color: #6c757d;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                        </svg>
                                    </button>
                                </div>
                                <input type="range" id="umbral" name="umbral" min="1" max="100" value="60" oninput="document.getElementById('valorUmbral').textContent = this.value + '%'" 
                                       style="width: 100%; height: 6px; border-radius: 3px; background: #e2e8f0; outline: none; appearance: none;">
                                <div style="display: flex; justify-content: space-between; margin-top: 0.25rem;">
                                    <small style="color: #718096;">Bajo</small>
                                    <small style="color: #718096;">Alto</small>
                                </div>
                            </div>

                            <button type="submit" id="submitBtn" style="background-color: #3490dc; color: white; border: none; padding: 0.75rem 1.5rem; font-size: 1rem; font-weight: 500; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; width: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Subir Archivo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>


<!-- SCRIPTS AGREGAR LOS SCRIPTS EN ESTA SECCION -->
@section('scripts')
<script>
    const uploadUrl = "{{ route('upload') }}";
    const prepararDatos = "{{ route('Preparar_Datos') }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 para popups bonitos -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

$(document).ready(function() {
//----------------------------------------------------------------------------------------------------------------------------
    $('#materia').on('focus', function () {
        $.ajax({
            url: '/api/materias',
            type: 'GET',
            success: function (data) {
                const select = $('#materia');
                select.find('option:not([value="todas"])').remove();

                data.forEach(function (materia) {
                    select.append('<option value="' + materia + '">' + materia + '</option>');
                });
            }
        });
    });

    $('#trabajo').on('focus', function () {
        $.ajax({
            url: '/api/trabajos',
            type: 'GET',
            success: function (data) {
                const select = $('#trabajo');
                select.find('option:not([value="todas"])').remove();

                data.forEach(function (trabajo) {
                    select.append('<option value="' + trabajo + '">' + trabajo + '</option>');
                });
            }
        });
    });

    $('#escuela').on('focus', function () {
        $.ajax({
            url: '/api/escuelas',
            type: 'GET',
            success: function (data) {
                const select = $('#escuela');
                select.find('option:not([value="todas"])').remove();

                data.forEach(function (escuela) {
                    select.append('<option value="' + escuela + '">' + escuela + '</option>');
                });
            }
        });
    });
//----------------------------------------------------------------------------------------------------------------------------
    let filtroActivo = null;
    let tipoGraficaSeleccionada = 'pie'; // Valor por defecto

    // Manejar cambio en checkboxes
    $('.filtro-unico').change(function() {
        if ($(this).is(':checked')) {
            // Deshabilitar otros checkboxes
            $('.filtro-unico').not(this).prop('disabled', true);
            filtroActivo = $(this).attr('id');
        } else {
            // Habilitar todos si se desmarca
            $('.filtro-unico').prop('disabled', false);
            filtroActivo = null;
        }
    });    

    $('#tipoGrafica').change(function() {
        tipoGraficaSeleccionada = $(this).val();
    });

    $('#generarGraficaBtn').on('click', function() {
    // Verificar si hay algún checkbox de filtro seleccionado
    let filtroSeleccionado = false;
    $('.filtro-unico').each(function() {
        if ($(this).is(':checked')) {
            filtroSeleccionado = true;
            return false; // Salir del each si encuentra uno seleccionado
        }
    });

    if (!filtroSeleccionado) {
        Swal.fire({
            title: 'Error',
            text: 'Debes seleccionar al menos un tipo de filtro para generar la gráfica',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return; // Detener la ejecución
    }

    // Obtener el tipo de filtro activo (checkbox seleccionado)
    let tipoFiltro = null;
    $('.filtro-unico').each(function() {
        if ($(this).is(':checked')) {
            tipoFiltro = $(this).attr('id').replace('Checkbox', '').toLowerCase();
        }
    });

        // Construir objeto de filtros dinámicamente
        const filtros = {
            tipo_grafica: tipoGraficaSeleccionada,
            tipo_filtro: tipoFiltro // Agregamos el tipo de filtro seleccionado
        };

        filtros.baja = $('#baja').val();
        filtros.generacion_desde = $('#anio_1').val();
        filtros.generacion_hasta = $('#anio_2').val();
        filtros.carrera = $('#Carrera').val();
        filtros.escuela = $('#escuela').val();
        filtros.materia = $('#materia').val();
        filtros.trabajo = $('#trabajo').val();
        filtros.tipo_titulacion = $('#tipo_titulacion').val();

        $.ajax({
             url: "{{ route('get.data') }}",
            method: 'GET',
            data: filtros,
            success: function(response) {
                // Destruir gráfica anterior si existe
                if(typeof window.myChart !== 'undefined') {
                    window.myChart.destroy();
                }

                // Mostrar contenedor de gráfica
                $('#chartContainer').show();

                const ctx = document.getElementById('dataChart').getContext('2d');
                let chartData;
                let chartOptions = {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            //text: `Total de ${categoriaSeleccionada.charAt(0).toUpperCase() + categoriaSeleccionada.slice(1)}: ${response.total}`
                            text: `Total de ${tipoFiltro.charAt(0).toUpperCase() + tipoFiltro.slice(1)}: ${response.total}`
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} registros`;
                                }
                            }
                        }
                    }
                };
                if (tipoGraficaSeleccionada === 'pie') {
                    chartData = {
                        labels: Object.keys(response.data),
                        datasets: [{
                            data: Object.values(response.data),
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56',
                                '#4BC0C0', '#9966FF', '#FF9F40',
                                '#E7E9ED', '#C9CBCF', '#28B463',
                                '#A569BD', '#F1948A', '#F39C12'
                            ],
                        }]
                    };
                } else if (tipoGraficaSeleccionada === 'bar') {
                    chartData = {
                        labels: Object.keys(response.data),
                        datasets: [{
                            label: `Total de ${tipoFiltro}`,
                            data: Object.values(response.data),
                            backgroundColor: 'rgba(54, 162, 235, 0.8)'
                        }]
                    };
                    chartOptions.scales = {
                        y: {
                            beginAtZero: true
                        }
                    };
                } else if (tipoGraficaSeleccionada === 'line') {
                    chartData = {
                        labels: Object.keys(response.data),
                        datasets: [{
                            label: `Total de ${tipoFiltro}`,
                            data: Object.values(response.data),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            fill: false
                        }]
                    };
                    chartOptions.scales = {
                        y: {
                            beginAtZero: true
                        }
                    };
                }
                window.myChart = new Chart(ctx, {
                    type: tipoGraficaSeleccionada,
                    data: chartData,
                    options: chartOptions
                });
            }
        });
    });
//----------------------------------------------------------------------------------------------------------------------------
    $('#btnDownloadPDF').on('click', function () {
    // Obtener el canvas y la imagen base64
    let canvas = document.getElementById('dataChart');
    let base64Image = canvas.toDataURL('image/png');

    // Verificar si hay datos en el gráfico (asumiendo que usas Chart.js)
    const chartInstance = Chart.getChart(canvas);
    if (!chartInstance || chartInstance.data.datasets.every(dataset => dataset.data.length === 0)) {
        Swal.fire({
            title: 'Error',
            text: 'No hay datos en el gráfico para generar el PDF.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    fetch("{{ route('downloadPDF') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            periodoConsulta: "Enero - Abril",
            nombreGrafica: "Comparativa de Bajas",
            descripcionGrafica: "Comparación por semestre",
            imagenGrafica: base64Image,
        }),
    })
    .then(response => {
        if (!response.ok) return response.json().then(err => { throw new Error(err.detalle || 'Error al generar el PDF'); });
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "reporte.pdf";
        a.click();
        window.URL.revokeObjectURL(url);

        // Notificación de éxito
        Swal.fire({
            title: '¡PDF descargado!',
            text: 'El reporte se descargó correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    })
    .catch(error => {
        // Notificación de error
        Swal.fire({
            title: 'Error',
            text: error.message || 'No se pudo generar el archivo PDF',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        console.error('Error:', error);
    });
});


    
//------------------------------------------------------------------------------------------------------------------------------

    // Explicación del umbral con SweetAlert2
    $('#infoUmbralBtn').click(function() {
        Swal.fire({
            title: 'Umbral de Similitud',
            html: `
                <div class="text-start">
                    <p>El umbral determina qué tan estricto es el sistema al buscar coincidencias:</p>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-danger me-3" style="width: 80px;">1-40%</span>
                        <span>Más resultados, menos precisión</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-3" style="width: 80px;">40-70%</span>
                        <span>Balance recomendado (valor actual: <strong>${$('#umbral').val()}%</strong>)</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-3" style="width: 80px;">70-100%</span>
                        <span>Menos resultados, máxima precisión</span>
                    </div>
                    <p class="mt-3"><i class="fas fa-lightbulb text-warning"></i> <em>Sugerencia: Comienza con 60% y ajusta según los resultados obtenidos.</em></p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3490dc',
            width: '600px'
        });
    });

    // AJAX para subir archivo POST A UPLOAD CON AJAX
    $('#ajaxUploadForm').on('submit', function(e) {
        e.preventDefault();

        $('#submitBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        //procesando ...
        $('#submitBtn').prop('disabled', true);
        $('#modalMessages').html('');

        let formData = new FormData(this);
        formData.append('umbral', $('#umbral').val());

        $.ajax({
            url: "{{ route('upload') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.codigo && response.codigo === 'DUPLICADOS') {
                    $('#uploadFile').modal('hide');
                    $('#ajaxMessages').html(`
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    return; 
                }

                if (response.success) {
                    $('#modalMessages').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);

                    //banner de regreso de la API 
                      // Construir tabla de resultados
                    let resultadosHTML = `
                        <div class="mt-4">
                            <h5>Sugerencias encontradas:</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="resultadosTable">
                                    <thead>
                                        <tr>
                                            <th>Entrada</th>
                                            <th>Mejor coincidencia</th>
                                            <th>Opciones sugeridas</th>
                                            <th>Corrección manual</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                    // Contador único para IDs
                    let rowCounter = 0;

                    response.data.forEach(item => {
                        item.resultados.forEach(res => {
                            const currentIndex = rowCounter++;
                            
                            // Opciones como botones seleccionables
                            let sugerencias = '';
                            if (res.opciones && res.opciones.length > 0) {
                                sugerencias = '<div class="list-group">';
                                res.opciones.forEach(opcion => {
                                    sugerencias += `
                                        <button type="button" 
                                            class="list-group-item list-group-item-action" 
                                            onclick="document.getElementById('mejor-coincidencia-${currentIndex}').value = '${opcion.replace(/'/g, "\\'")}'">
                                            ${opcion}
                                        </button>`;
                                });
                                sugerencias += '</div>';
                            } else {
                                sugerencias = '<em>Sin sugerencias</em>';
                            }

                            resultadosHTML += `
                                <tr>
                                    <td>${res.entrada}</td>
                                    <td>
                                        <input type="text" 
                                            class="form-control" 
                                            id="mejor-coincidencia-${currentIndex}" 
                                            value="${res.mejor_coincidencia || ''}">
                                    </td>
                                    <td>${sugerencias}</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" 
                                                class="form-control" 
                                                id="correccion-manual-${currentIndex}" 
                                                placeholder="Escribe corrección">
                                            <button class="btn btn-outline-primary" 
                                                type="button"
                                                onclick="document.getElementById('mejor-coincidencia-${currentIndex}').value = document.getElementById('correccion-manual-${currentIndex}').value">
                                                →
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                    });
                    resultadosHTML += `
                                    </tbody>
                                </table>
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-primary" id="guardarCambios">
                                        Guardar cambios
                                    </button>
                                </div>
                            </div>
                        </div>`;
                            
                    $('#modalMessages').append(resultadosHTML);


                     // Evento para guardar cambios MODIFICAR PARA IMPLEMENTAR ID UNICO Y BASE DE DATOS --------------------------------
                        $(document).on('click', '#guardarCambios', function() {
    let resultados = [];//dfghjkjhgfdsdfghjkjhgfdeertgyhjkjhgfddfghjhgfdfg
    $('#resultadosTable tbody tr').each(function() {
        const entrada = $(this).find('td:eq(0)').text();
        const coincidencia = $(this).find('input[type="text"]:first').val();
        
        resultados.push({
            entrada: entrada,
            mejor_coincidencia: coincidencia
        });
        if(typeof window.myChart !== 'undefined') {
                    window.myChart.destroy();
                }
    });

    $.ajax({
            url: "{{ route('Preparar_Datos') }}",
            type: 'POST',
            data: {
                resultados: resultados,
                _token: '{{ csrf_token() }}'
            },
             headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (respuesta) {
            $('#mensaje').text(respuesta.mensaje);
            
            // Mostrar notificación con SweetAlert2 (incluyendo el mensaje)
            Swal.fire({
                title: '¡Datos guardados!',
                text: respuesta.mensaje, // Usa el mensaje de la respuesta
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#uploadFile').modal('hide');
                    $('#ajaxMessages').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${respuesta.mensaje} <!-- Mismo mensaje aquí -->
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    location.reload();
                }
            });
        },
            error: function () {
            $('#mensaje').text("Error al guardar los datos");
            
            // Mostrar error en SweetAlert2
            Swal.fire({
                title: 'Error',
                text: "Error al guardar los datos",
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    })
});

//----------------------------------------------------------------------------------------------------------------------------
                } else {
                    $('#modalMessages').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> ${response.message}
                            ${response.error ? '<br>' + response.error : ''}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Ocurrió un error al procesar el archivo';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                $('#modalMessages').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            },
            complete: function() {
                $('#submitBtn').html(`
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Subir Archivo
                `);
                $('#submitBtn').prop('disabled', false);
            }
        });
    });
});
</script>

@endsection
@endsection