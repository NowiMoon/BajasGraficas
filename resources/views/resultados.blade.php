@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row gx-0">
        <!-- Sidebar -->
        <nav class="col-auto d-none d-md-flex flex-column bg-secondary bg-opacity-10 align-items-center justify-content-evenly"
             style="width: 80px; z-index: 1000; position: fixed; top: 80px; left: 0; height: calc(100vh - 80px);">
            @if ( Auth::user()->user_type == 1 )
            <a class="d-block icon-item text-center" style="text-decoration: none; color: black;" href="{{ route('register') }}">
                <img src="{{ asset('images/users_icon.svg') }}" alt="Usuarios">Gestion de Usuarios
            </a>
            @endif
            @if ( Auth::user()->user_type == 2 )
            <a class="d-block icon-item text-center" style="text-decoration: none; color: black;" href=" {{ route('gestion_materias') }} ">
                <img src="{{ asset('images/uploadSubjects_icon.svg') }}" alt="Subir materias">Subir materias
            </a>
            <hr style="width: 80%; border: 3px solid #00B2E3; margin: 0 auto;">
            <a class="d-block icon-item text-center" style="text-decoration: none; color: black; cursor: pointer;">
                <img src="{{ asset('images/addData_icon.svg') }}" alt="Upload file" data-bs-toggle="modal" data-bs-target="#uploadFile" style="display: block; margin: 0 auto;">
                Subir datos
            </a>
            @endif
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-10 offset-md-1 col-12" style="margin-top: 80px;">
            <div class="container flex-grow-1 col-11 mx-auto mt-3">
                <!-- Contenedor para mensajes AJAX -->
                <div id="ajaxMessages" class="mt-3"></div>
                
                <!-- TABLA CON RESULTADOS REALES -->
                <div class="container-fluid mt-2 pt-2 px-0" id="resultadosContainer">
                    <div class="card shadow-sm">
                        <!-- TÍTULO: Generador de Gráfica -->
                        <div class="card-header text-white" style="background-color: #004A98;">
                            <h3 class="card-title mb-0">Generador de Gráficas</h3>
                        </div>
                        
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <!-- Columna izquierda - Controles (1/4) -->
                                <div class="col-md-3">
                                    <div class="d-flex flex-column gap-3">
                                        <!-- CUADRO 1: Tipo de Gráfica -->
                                        <div class="border rounded p-3 bg-light">
                                            <h6 class="fw-semibold mb-2">Tipo de Gráfica</h6>
                                            <select class="form-select form-select-sm" id="tipoGrafica">
                                                <option value="pie">Gráfica de Pie</option>
                                                <option value="doughnut">Gráfica de Dona</option>
                                                <option value="bar">Gráfica de Barras</option>
                                                <option value="line">Gráfica de Líneas</option>
                                            </select>
                                        </div>

                                        <!-- CUADRO 2: Generación -->
                                        <div class="border rounded p-3 bg-light">
                                            <h6 class="fw-semibold mb-2">Selecciona la generación a filtrar</h6>
                                            <div class="mb-2">
                                                <div class="d-flex align-items-center gap-1 mt-1">
                                                    <select class="form-select form-select-sm" id="anio_1" style="width: 90px;">
                                                        <option value="" disabled selected>Desde</option>
                                                    </select>
                                                    <span class="small fw-semibold">a</span>
                                                    <select class="form-select form-select-sm" id="anio_2" style="width: 90px;">
                                                        <option value="" disabled selected>Hasta</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CUADRO 3: Tema de la grafica -->
                                        <div class="border rounded p-3 bg-light">
                                            <h6 class="fw-semibold mb-3">Tema de la gráfica</h6>
                                            <div class="d-flex flex-column gap-2">
                                                <div class="mb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="generacion" value="generacion" style="transform: scale(1.2); border: 2px solid #004A98;">
                                                        <label class="form-check-label fw-semibold small ms-2" for="generacion" style="color: #2c3e50;">Generación</label>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="bajaCheckbox" value="baja" style="transform: scale(1.2); border: 2px solid #004A98;">
                                                        <label class="form-check-label fw-semibold small ms-2" for="bajaCheckbox" style="color: #2c3e50;">Tipo de baja</label>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="carreraCheckbox" value="carrera" style="transform: scale(1.2); border: 2px solid #004A98;">
                                                        <label class="form-check-label fw-semibold small ms-2" for="carreraCheckbox" style="color: #2c3e50;">Carrera</label>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="escuelaCheckbox" value="escuela" style="transform: scale(1.2); border: 2px solid #004A98;">
                                                        <label class="form-check-label fw-semibold small ms-2" for="escuelaCheckbox" style="color: #2c3e50;">Escuela</label>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="materiaCheckbox" value="materia" style="transform: scale(1.2); border: 2px solid #004A98;">
                                                        <label class="form-check-label fw-semibold small ms-2" for="materiaCheckbox" style="color: #2c3e50;">Materia difícil</label>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="titulacionCheckbox" value="titulacion" style="transform: scale(1.2); border: 2px solid #004A98;">
                                                        <label class="form-check-label fw-semibold small ms-2" for="titulacionCheckbox" style="color: #2c3e50;">Tipo de titulación</label>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="trabajoCheckbox" value="trabajo" style="transform: scale(1.2); border: 2px solid #004A98;">
                                                        <label class="form-check-label fw-semibold small ms-2" for="trabajoCheckbox" style="color: #2c3e50;">Lugar donde labora</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CUADRO: Filtros adicionales -->
                                        <div class="border rounded p-3 bg-light">
                                            <h6 class="fw-semibold mb-2">Filtros adicionales</h6>
                                            <div class="d-flex flex-column gap-2">
                                                <div class="mb-2">
                                                    <label for="baja" class="form-label small fw-semibold mb-1">Tipos de baja existentes</label>
                                                    <select class="form-select form-select-sm" id="baja">
                                                        <option value="todas">Todas</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="Carrera" class="form-label small fw-semibold mb-1">Carreras del área</label>
                                                    <select class="form-select form-select-sm" id="Carrera">
                                                        <option value="todas">Todas</option>
                                                        <option value="Ingeniería en Computación">Ingeniería en Computación</option>
                                                        <option value="Ingeniería en Sistemas Inteligentes">Ingeniería en Sistemas Inteligentes</option>
                                                        <option value="Ingeniería en Informática">Ingeniería en Informática</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="escuela" class="form-label small fw-semibold mb-1">Escuelas</label>
                                                    <select class="form-select form-select-sm" id="escuela">
                                                        <option value="todas">Todas</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="materia" class="form-label small fw-semibold mb-1">Materias difíciles</label>
                                                    <select class="form-select form-select-sm" id="materia">
                                                        <option value="todas">Todas</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="tipo_titulacion" class="form-label small fw-semibold mb-1">Tipos de titulación</label>
                                                    <select class="form-select form-select-sm" id="tipo_titulacion">
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
                                                <div class="mb-2">
                                                    <label for="trabajo" class="form-label small fw-semibold mb-1">Trabajos</label>
                                                    <select class="form-select form-select-sm" id="trabajo">
                                                        <option value="todas">Todas</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <p id="mensaje" class="small text-muted mt-1 mb-0"></p>
                                    </div>
                                </div>

                                <!-- Columna derecha - Gráfica (3/4) -->
                                <div class="col-md-9">
                                    <div class="border rounded p-3 bg-light mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <button class="btn btn-outline-primary btn-sm w-100 fw-semibold" id="generarGraficaBtn" style="border: 2px solid #004A98; color: #004A98;">
                                                    <i class="fas fa-chart-bar me-1"></i>Generar Gráfica
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-outline-primary btn-sm w-100 fw-semibold" id="limpiarFiltrosBtn" style="border: 2px solid #004A98; color: #004A98;">
                                                    <i class="fas fa-times me-1"></i>Limpiar Filtros
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-outline-primary btn-sm w-100 fw-semibold" id="btnDownloadPDF" style="border: 2px solid #004A98; color: #004A98;">
                                                    <i class="fas fa-file-pdf me-1"></i>Generar Reporte
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="chartContainer" style="display: none; width: 100%; height: 70vh; position: relative;">
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            <canvas id="dataChart" style="max-width: 100%; max-height: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <div id="noChartMessage" class="text-center text-muted" style="height: 70vh; background-color: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <div>
                                            <i class="fas fa-chart-pie fa-3x mb-3" style="color: #dee2e6;"></i>
                                            <p class="mb-0 small">La gráfica se mostrará aquí después de generar los datos</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TÍTULO: Datos de Alumnos Registrados -->
                        <div class="card-header text-white mt-3" style="background-color: #004A98;">
                            <h3 class="card-title mb-0">Datos de Alumnos Registrados</h3>
                        </div>

                        <!-- TABLA -->
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-hover table-striped mb-0" id="resultadosFinalesTable" style="border-collapse: separate; border-spacing: 0;">
                                    <thead class="sticky-top" style="top: 0;">
                                        <tr>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Id</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Año</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Id Reg</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Clave</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Nombre</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Generación</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Carrera</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Email</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Mat1</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Mat2</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Mat3</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Escuela</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Tipo Baja</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Inconveniente</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Empresa</th>
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Titulación</th>
                                            @if(Auth::user()->user_type == 1)
                                            <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">Eliminar</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alumnos as $alumno)
                                            <tr style="transition: all 0.2s ease; border-bottom: 1px solid #e9ecef;">
                                                <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem;">{{ $alumno->Id_Registro }}</td>
                                                <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem;">{{ $alumno->Anio }}</td>
                                                <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem;">{{ $alumno->Id_Reg_A }}</td>
                                                <td class="text-primary fw-bold text-center" style="padding: 10px 8px; font-size: 0.8rem;">{{ $alumno->Cv_Alumno }}</td>
                                                <td class="fw-semibold" style="padding: 10px 8px; font-size: 0.8rem;">{{ $alumno->Nombre_Alumno }}</td>
                                                <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem;">{{ $alumno->Gen }}</td>
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
                                                @if(Auth::user()->user_type == 1)
                                                <td>
                                                    <form method="POST" action="{{ route('alumnos.destroy', $alumno->Id_Registro) }}" class="delete-form d-inline" data-name="{{ $alumno->Nombre_Alumno }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                                    </form>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-5"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de carga -->
<div class="modal fade" id="uploadFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Carga de Información</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="modalMessages"></div>

            <div class="rounded-lg shadow-lg p-4" style="background-color: #ffffff; border: 1px solid #e2e8f0;">
                <h2 class="text-center mb-6" style="color: #2c3e50; font-size: 1.5rem; font-weight: 600;">SUBIR ARCHIVO EXCEL (.XLSX)</h2>
                
                <div class="row align-items-center">
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('images/document_search.png') }}" alt="Select File" style="height: 120px;">
                    </div>

                    <div class="col-md-6">
                        <form id="ajaxUploadForm" method="POST" enctype="multipart/form-data">   
                            @csrf
                            <div class="mb-4">
                                <label for="file" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4a5568;">Selecciona un archivo .xlsx:</label>
                                <input type="file" id="file" name="file" accept=".xlsx" required style="width: 100%; padding: 0.75rem; font-size: 1rem; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.375rem;" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                <div id="file-name" style="margin-top: 0.5rem; font-size: 0.875rem; color: #718096;"></div>
                            </div>

                            <div class="mb-4">
                                <label for="umbral" style="font-weight: 500; color: #4a5568;">Umbral de similitud: <span id="valorUmbral" style="font-weight: 600; color: #3490dc;">60%</span></label>
                                <input type="range" id="umbral" name="umbral" min="1" max="100" value="60" oninput="document.getElementById('valorUmbral').textContent = this.value + '%'" style="width: 100%;">
                            </div>

                            <button type="submit" id="submitBtn" style="background-color: #3490dc; color: white; border: none; padding: 0.75rem 1.5rem; font-size: 1rem; border-radius: 0.375rem; cursor: pointer; width: 100%;">
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    window.datosGrafica = null;
    window.totalGrafica = null;
    let Nombre_de_la_grafica = null;
    window.titulo = null;
    window.subtitulo = null;

    $(document).ready(function() {
        // Carga de selects dinámicos
        $('#materia').on('focus', function () {
            $.ajax({ url: '/api/materias', type: 'GET', success: function (data) {
                const select = $('#materia'); select.find('option:not([value="todas"])').remove();
                data.forEach(m => select.append('<option value="' + m + '">' + m + '</option>'));
            }});
        });

        $('#trabajo').on('focus', function () {
            $.ajax({ url: '/api/trabajos', type: 'GET', success: function (data) {
                const select = $('#trabajo'); select.find('option:not([value="todas"])').remove();
                data.forEach(t => select.append('<option value="' + t + '">' + t + '</option>'));
            }});
        });

        $('#escuela').on('focus', function () {
            $.ajax({ url: '/api/escuelas', type: 'GET', success: function (data) {
                const select = $('#escuela'); select.find('option:not([value="todas"])').remove();
                data.forEach(e => select.append('<option value="' + e + '">' + e + '</option>'));
            }});
        });

        $('#baja').on('focus', function () {
            $.ajax({ url: '/api/tipos', type: 'GET', success: function (data) {
                const select = $('#baja'); select.find('option:not([value="todas"])').remove();
                data.forEach(t => select.append('<option value="' + t + '">' + t + '</option>'));
            }});
        });

        $.ajax({ url: '/api/generaciones', type: 'GET', success: function (data) {
            const desde = $('#anio_1'), hasta = $('#anio_2');
            if (desde.find('option').length <= 1) {
                data.sort((a, b) => b - a);
                data.forEach(g => { desde.append('<option value="' + g + '">' + g + '</option>'); hasta.append('<option value="' + g + '">' + g + '</option>'); });
            }
        }});

        let tipoGraficaSeleccionada = 'pie';
        $('#tipoGrafica').change(function() { tipoGraficaSeleccionada = $(this).val(); });

        $('#generarGraficaBtn').on('click', function() {
            let tipoFiltro = $('input[name="temaGrafica"]:checked').val();
            if (!tipoFiltro) {
                Swal.fire({ title: 'Error', text: 'Debes seleccionar un filtro', icon: 'error' });
                return;
            }

            const filtros = {
                tipo_grafica: tipoGraficaSeleccionada,
                tipo_filtro: tipoFiltro,
                baja: $('#baja').val(),
                generacion_desde: $('#anio_1').val(),
                generacion_hasta: $('#anio_2').val(),
                carrera: $('#Carrera').val(),
                escuela: $('#escuela').val(),
                materia: $('#materia').val(),
                trabajo: $('#trabajo').val(),
                tipo_titulacion: $('#tipo_titulacion').val()
            };

            Object.keys(filtros).forEach(k => { if (!filtros[k] || filtros[k] === 'todas') delete filtros[k]; });

            $.ajax({
                url: "{{ route('get.data') }}",
                method: 'GET',
                data: filtros,
                success: function(response) {
                    if (response.total === 0) {
                        Swal.fire({ title: 'Sin datos', text: 'No se encontraron registros.', icon: 'warning' });
                        $('#chartContainer').hide(); $('#noChartMessage').show();
                        return;
                    }

                    $('#noChartMessage').hide(); $('#chartContainer').show();
                    if (typeof window.myChart !== 'undefined') window.myChart.destroy();

                    const ctx = document.getElementById('dataChart').getContext('2d');
                    window.myChart = new Chart(ctx, {
                        type: tipoGraficaSeleccionada,
                        data: {
                            labels: Object.keys(response.data),
                            datasets: [{ data: Object.values(response.data), backgroundColor: generarColores(Object.keys(response.data).length) }]
                        },
                        options: { responsive: true }
                    });
                }
            });
        });

        function generarColores(cantidad) {
            const colores = [];
            for (let i = 0; i < cantidad; i++) {
                colores.push(`hsl(${i * (360 / cantidad)}, 70%, 60%)`);
            }
            return colores;
        }

        // AJAX UPLOAD Y CREACIÓN DE TABLA CON LISTBOX MANUAL
        $('#ajaxUploadForm').on('submit', function(e) {
            e.preventDefault();
            $('#submitBtn').prop('disabled', true).text('Procesando...');
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
                    if (response.codigo === 'DUPLICADOS') {
                        $('#uploadFile').modal('hide');
                        $('#ajaxMessages').html(`<div class="alert alert-info">${response.message}</div>`);
                        return;
                    }

                    if (response.success) {
                        $('#modalMessages').html(`<div class="alert alert-success">${response.message}</div>`);

                        let resultadosHTML = `
                            <div class="mt-4">
                                <h5>Sugerencias encontradas:</h5>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered" id="resultadosTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Entrada</th>
                                                <th style="width: 30%;">Mejor coincidencia</th>
                                                <th style="width: 25%;">Opciones sugeridas</th>
                                                <th style="width: 20%;">Corrección Manual (BD)</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                        let rowCounter = 0;
                        response.data.forEach(item => {
                            item.resultados.forEach(res => {
                                const currentIndex = rowCounter++;
                                let sugerencias = '';
                                
                                if (res.opciones && res.opciones.length > 0) {
                                    sugerencias = '<div class="list-group">';
                                    res.opciones.forEach(opcion => {
                                        let opcSafe = String(opcion).replace(/'/g, "\\'");
                                        sugerencias += `<button type="button" class="list-group-item list-group-item-action py-1 px-2 text-xs" onclick="document.getElementById('mejor-coincidencia-${currentIndex}').value = '${opcSafe}'">${opcion}</button>`;
                                    });
                                    sugerencias += '</div>';
                                } else {
                                    sugerencias = '<em>Sin sugerencias</em>';
                                }

                                // Opciones para el ListBox de Corrección Manual
                                let opcionesSelect = '<option value="" disabled selected>Selecciona...</option><option value="Ninguna">Ninguna</option>';
                                if (res.opciones && res.opciones.length > 0) {
                                    res.opciones.forEach(opt => {
                                        opcionesSelect += `<option value="${opt}">${opt}</option>`;
                                    });
                                }

                                resultadosHTML += `
                                    <tr>
                                        <td class="fw-bold text-danger">${res.entrada}</td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="mejor-coincidencia-${currentIndex}" value="${res.mejor_coincidencia || 'Ninguna'}">
                                        </td>
                                        <td>${sugerencias}</td>
                                        <td>
                                            <select class="form-select form-select-sm" onchange="document.getElementById('mejor-coincidencia-${currentIndex}').value = this.value">
                                                ${opcionesSelect}
                                            </select>
                                        </td>
                                    </tr>`;
                            });
                        });

                        resultadosHTML += `
                                        </tbody>
                                    </table>
                                    <div class="text-end mt-3">
                                        <button type="button" class="btn btn-primary" id="guardarCambios">Guardar cambios</button>
                                    </div>
                                </div>
                            </div>`;

                        $('#modalMessages').append(resultadosHTML);
                    }
                },
                error: function(xhr) {
                    $('#modalMessages').html(`<div class="alert alert-danger">Error al procesar el archivo.</div>`);
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).text('Subir Archivo');
                }
            });
        });

        // Guardar cambios finales de normalización
        $(document).on('click', '#guardarCambios', function() {
            let resultados = [];
            $('#resultadosTable tbody tr').each(function() {
                const entrada = $(this).find('td:eq(0)').text();
                const coincidencia = $(this).find('input[type="text"]').val();
                resultados.push({ entrada: entrada, mejor_coincidencia: coincidencia });
            });

            $.ajax({
                url: "{{ route('Preparar_Datos') }}",
                type: 'POST',
                data: { resultados: resultados, _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(respuesta) {
                    Swal.fire({ title: '¡Guardado!', text: respuesta.mensaje, icon: 'success' }).then(() => {
                        $('#uploadFile').modal('hide');
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({ title: 'Error', text: 'No se pudieron guardar los datos.', icon: 'error' });
                }
            });
        });
    });
</script>
@endsection