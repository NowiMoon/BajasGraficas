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
            <hr style="width: 80%; border: 3px solid #00B2E3; margin: 0 auto;">
            @endif
            @if ( Auth::user()->user_type == 2 )
            <a class="d-block icon-item text-center" style="text-decoration: none; color: black;" href=" {{ route('gestion_materias') }} ">
                <img src="{{ asset('images/uploadSubjects_icon.svg') }}" alt="Subir materias">Subir materias
            </a>
            <hr style="width: 80%; border: 3px solid #00B2E3; margin: 0 auto;">
            <a class="d-block icon-item text-center" style="text-decoration: none; color: black;">
                <img src="{{ asset('images/addData_icon.svg') }}" alt="Upload file" data-bs-toggle="modal" data-bs-target="#uploadFile" style="display: block; margin: 0 auto;">
                Subir datos
            </a>
            @endif
        </nav>
        <!-- Contenido principal -->
        <main class="col-md-10 offset-md-1 col-12" style="margin-top: 80px;">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>
</div>

@section('content')
<div class="d-flex my-0 py-0">
    <!-- Sidebar -->`
    
    <!-- Contenido principal INGRESAR AQUI LO QUE FALTE DE DASHBOARD -->
    <div class="container flex-grow-1 col-11 mx-auto mt-3">
        <!-- Contenedor para mensajes AJAX -->
        <div id="ajaxMessages" class="mt-3"></div>
        
        <!-- TABLA CON RESULTADOS REALES -->
        <!-- Contenedor para resultados - Fuera del modal -->
        <div class="container-fluid mt-2 pt-2 px-0" id="resultadosContainer">
            <div class="card shadow-sm">
                <!-- TÍTULO CAMBIADO: Generador de Gráfica -->
                <div class="card-header text-white" style="background-color: #004A98;">
                    <h3 class="card-title mb-0">Generador de Gráficas</h3>
                </div>
                
                <!-- NUEVA ESTRUCTURA: Controles (1/4) y gráfica (3/4) -->
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

                                <!-- CUADRO 3: Tema de la grafica  -->
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="fw-semibold mb-3">Tema de la gráfica</h6>
                                    <div class="d-flex flex-column gap-2">
                                        <!-- Radio buttons con mejor visibilidad -->
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
                                                <label class="form-check-label fw-semibold small ms-2" for="trabajoCheckbox" style="color: #2c3e50;">Lugar donde labura:</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CUADRO: Filtros adicoonales -->
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="fw-semibold mb-2">Filtros adicionales</h6>
                                    <div class="d-flex flex-column gap-2">
                                        <!-- Solo los selectbox sin checkboxes -->
                                        <div class="mb-2">
                                            <label for="baja" class="form-label small fw-semibold mb-1">Tipos de baja existentes</label>
                                            <select class="form-select form-select-sm" id="baja">
                                                <option value="todas">Todas</option>
                                                <option value="Trámite de Pasantía">Trámite de Pasantía</option>
                                                <option value="Baja Temporal o Definitiva">Baja Temporal o Definitiva</option>
                                                <option value="Cambio de Carrera">Cambio de Carrera</option>
                                            </select>
                                        </div>

                                        <div class="mb-2">
                                            <label for="Carrera" class="form-label small fw-semibold mb-1">Carreras del área</label>
                                            <select class="form-select form-select-sm" id="Carrera">
                                                <option value="todas">Todas</option>
                                                <option value="Ingeniero en Computación">Ingeniería en Computación</option>
                                                <option value="Ingeniero en Sistemas Inteligentes">Ingeniería en Sistemas Inteligentes</option>
                                                <option value="Ingeniero en Informática">Ingeniería en Informática</option>
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
                                <!-- Mensaje fuera de los cuadros -->
                                <p id="mensaje" class="small text-muted mt-1 mb-0"></p>
                            </div>
                        </div>

                        <!-- Columna derecha - Gráfica (3/4) - MÁS GRANDE Y CENTRADA -->
                        <div class="col-md-9">
                            <!-- RECUADRO DE BOTONES HORIZONTAL -->
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

                            <!-- CONTENEDOR DE GRÁFICA -->
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

                <!-- TABLA - Ahora está debajo de los controles y gráfica -->
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover table-striped mb-0" id="resultadosFinalesTable" style="border-collapse: separate; border-spacing: 0;">
                            <thead class="sticky-top" style="top: 0;">
                                <tr>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Id</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Año</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Id Reg</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Clave</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Nombre</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Generación</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Carrera</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Email</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Mat1</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Mat2</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Mat3</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Escuela</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Tipo Baja</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Inconveniente</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none; border-right: 1px solid rgba(255,255,255,0.3);">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Empresa</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                    <th style="background: linear-gradient(135deg, #00B2E3, #0088cc); color: white; font-weight: 600; font-size: 0.85rem; padding: 12px 8px; border: none;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Titulación</span>
                                            <i class="fas fa-sort ms-1 small"></i>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alumnos as $alumno)
                                    <tr style="transition: all 0.2s ease; border-bottom: 1px solid #e9ecef;">
                                        <td class="fw-medium text-center" style="padding: 10px 8px; font-size: 0.8rem; background-color: #f8f9fa; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Id_Registro }}
                                        </td>
                                        <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Anio }}
                                        </td>
                                        <td class="fw-medium text-center" style="padding: 10px 8px; font-size: 0.8rem; background-color: #f8f9fa; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Id_Reg_A }}
                                        </td>
                                        <td class="text-primary fw-bold text-center" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Cv_Alumno }}
                                        </td>
                                        <td class="fw-semibold" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Nombre_Alumno }}
                                        </td>
                                        <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Gen }}
                                        </td>
                                        <td class="fw-medium" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Carrera }}
                                        </td>
                                        <td class="text-truncate" style="padding: 10px 8px; font-size: 0.8rem; max-width: 150px; border-right: 1px solid #e9ecef;" title="{{ $alumno->email }}">
                                            {{ $alumno->email }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Mat_1 }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Mat_2 }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Mat_3 }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Escuela }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->TBaja }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Inc_Carr }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                            {{ $alumno->Empresa }}
                                        </td>
                                        <td style="padding: 10px 8px; font-size: 0.8rem;">
                                            {{ $alumno->Titulacion }}
                                        </td>
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
<!-- Tal vez sirva para los porcentajes (si sirvio :D)-->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

window.datosGrafica = null;
window.totalGrafica = null;
Nombre_de_la_grafica = null;

$(document).ready(function() {
//----------------------------------------------------------------------------------------------------------------------------
//AQUI SE OBTIENE LOS DATOS DE LA BASE DE DATOS    
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

    $.ajax({
        url: '/api/generaciones',
        type: 'GET',
        success: function (data) {
            const selectDesde = $('#anio_1');
            const selectHasta = $('#anio_2');
            
            // Solo cargar si no hay opciones además de la primera
            if (selectDesde.find('option').length <= 1) {
                // Ordenar las generaciones de mayor a menor
                data.sort((a, b) => b - a);
                
                data.forEach(function (generacion) {
                    selectDesde.append('<option value="' + generacion + '">' + generacion + '</option>');
                    selectHasta.append('<option value="' + generacion + '">' + generacion + '</option>');
                });
            }
        }
    });

    // Validación simple para que "Hasta" no sea menor que "Desde"
    $('#anio_2').on('change', function() {
        const desde = $('#anio_1').val();
        const hasta = $(this).val();
        
        if (desde && hasta && hasta < desde) {
            Swal.fire({
                title: 'Error de rango',
                text: 'El año "Hasta" no puede ser menor que el año "Desde"',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            $(this).val(''); // Limpiar solo el que está mal
        }
    });

    $('#anio_1').on('change', function() {
        const desde = $(this).val();
        const hasta = $('#anio_2').val();
        
        if (desde && hasta && hasta < desde) {
            Swal.fire({
                title: 'Error de rango',
                text: 'El año "Hasta" no puede ser menor que el año "Desde"',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            $(this).val(''); // Limpiar la selección que causó el error
        }
    });
//----------------------------------------------------------------------------------------------------------------------------
    let filtroActivo = null;
let tipoGraficaSeleccionada = 'pie'; // Valor por defecto

window.datosGrafica = null;
window.totalGrafica = null;
Nombre_de_la_grafica = null;

// Manejar cambio en radio buttons (ya no necesitamos deshabilitar otros porque son radios)
$('.filtro-unico').change(function() {
    if ($(this).is(':checked')) {
        filtroActivo = $(this).attr('id');
    }
});

$('#tipoGrafica').change(function() {
    tipoGraficaSeleccionada = $(this).val();
});

$('#generarGraficaBtn').on('click', function() {
    // Verificar si hay algún radio button seleccionado
    let filtroSeleccionado = $('input[name="temaGrafica"]:checked').length > 0;

    if (!filtroSeleccionado) {
        Swal.fire({
            title: 'Error',
            text: 'Debes seleccionar al menos un tipo de filtro para generar la gráfica',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return; // Detener la ejecución
    }

    // Obtener el tipo de filtro activo (radio button seleccionado)
    let tipoFiltro = $('input[name="temaGrafica"]:checked').val();

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
                // VALIDACIÓN NUEVA: Verificar si hay datos para mostrar
                if (response.total === 0 || Object.keys(response.data).length === 0) {
                    Swal.fire({
                        title: 'Sin datos',
                        text: 'No se encontraron datos para generar la gráfica con los filtros seleccionados.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                    
                    // Ocultar gráfica si está visible
                    $('#chartContainer').hide();
                    $('#noChartMessage').show();
                    return;
                }

                actualizarTablaConFiltros(filtros);

                // Ocultar mensaje placeholder y mostrar gráfica
                $('#noChartMessage').hide();
                $('#chartContainer').show();

                // Destruir gráfica anterior si existe
                if(typeof window.myChart !== 'undefined') {
                    window.myChart.destroy();
                }

                window.datosGrafica = response.data;
                window.totalGrafica = response.total;
                
                const datosFiltrados = Object.entries(response.data).reduce((acc, [key, value]) => {
                    if (value >= 3) {
                        acc[key] = value;
                    }
                    return acc;
                }, {});

                // Calcular la suma de los valores menores a 3
                const sumaOtros = Object.entries(response.data)
                    .filter(([key, value]) => value < 3)
                    .reduce((sum, [key, value]) => sum + value, 0);

                // Agregar "Otros" solo si hay valores menores a 3
                if (sumaOtros > 0) {
                    datosFiltrados['Otros'] = sumaOtros;
                }
                
                window.datosGraficaFiltrados = datosFiltrados;

                // Mostrar contenedor de gráfica
                $('#chartContainer').show();

                Nombre_de_la_grafica = `Gráfica de ${tipoFiltro.charAt(0).toUpperCase() + tipoFiltro.slice(1)}`;

                const ctx = document.getElementById('dataChart').getContext('2d');
                let chartData;
                const labels = Object.keys(window.datosGraficaFiltrados);
                const dataValues = Object.values(window.datosGraficaFiltrados);
                const total = response.total;

                // Calcular porcentajes
                const percentages = dataValues.map(value => ((value / total) * 100).toFixed(1) + '%');

                let chartOptions = {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        title: {
                            display: true,
                            text: `Total de ${tipoFiltro.charAt(0).toUpperCase() + tipoFiltro.slice(1)}: ${response.total}`,
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} registros (${percentage}%)`;
                                }
                            }
                        },
                        // Plugin para mostrar etiquetas en el gráfico
                        datalabels: {
                            display: true,
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                return percentages[context.dataIndex];
                            }
                        },
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,    // Reducido de 12 a 10
                                padding: 8,      // Reducido de 15 a 8
                                font: {
                                    size: 12     // Texto de leyenda más pequeño
                                }
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 20,
                            left: 20,
                            right: 20
                        }
                    }
                };

                // Configuraciones específicas para cada tipo de gráfica
                if (tipoGraficaSeleccionada === 'pie') {
                    chartData = {
                        labels: labels,
                        datasets: [{
                            data: dataValues,
                            backgroundColor: generarColores(labels.length),
                            borderWidth: 2,
                            borderColor: '#fff',
                        }]
                    };
                    
                    // Configuración específica para gráficos circulares
                    chartOptions.plugins.datalabels = {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            return percentages[context.dataIndex];
                        }
                    };
                    
                    // Ajustes adicionales para pie chart
                    chartOptions.layout.padding = {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    };

                    chartOptions.aspectRatio = 1.2; // Más ancha que alta
                    chartOptions.maintainAspectRatio = true;

                } else if(tipoGraficaSeleccionada === 'doughnut') {
                    chartData = {
                        labels: labels,
                        datasets: [{
                            data: dataValues,
                            backgroundColor: generarColores(labels.length),
                            borderWidth: 2,
                            borderColor: '#fff',
                        }]
                    };
                    
                    chartOptions.cutout = '45%'; // Esto define el tamaño del agujero (50% es el estándar)

                    // Configuración específica para gráficos circulares
                    chartOptions.plugins.datalabels = {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            return percentages[context.dataIndex];
                        }
                    };
                    
                    // Ajustes adicionales para doughnut chart
                    chartOptions.layout.padding = {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    };

                    chartOptions.aspectRatio = 1.2; // Más ancha que alta
                    chartOptions.maintainAspectRatio = true;
                    
                } else if (tipoGraficaSeleccionada === 'bar') {
                    chartData = {
                        labels: labels,
                        datasets: [{
                            label: `Total de ${tipoFiltro}`,
                            data: dataValues,
                            backgroundColor: generarColores(labels.length),
                            borderWidth: 1
                        }]
                    };
                    chartOptions.scales = {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    };
                    
                    // Para gráficos de barra, mostrar porcentaje en tooltip y encima de las barras
                    chartOptions.plugins.datalabels = {
                        display: true,
                        anchor: 'end',
                        align: 'top',
                        color: '#333',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {
                            return percentages[context.dataIndex];
                        }
                    };
                    
                    // Ajustes para bar chart
                    chartOptions.layout.padding = {
                        top: 20,
                        bottom: 20,
                        left: 15,
                        right: 15
                    };
                    
                } else if (tipoGraficaSeleccionada === 'line') {
                    chartData = {
                        labels: labels,
                        datasets: [{
                            label: `Total de ${tipoFiltro}`,
                            data: dataValues,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    };
                    chartOptions.scales = {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    };
                    
                    // Para gráficos de línea, mostrar porcentaje en los puntos
                    chartOptions.plugins.datalabels = {
                        display: true,
                        align: 'top',
                        color: '#333',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {
                            return percentages[context.dataIndex];
                        }
                    };
                    
                    // Ajustes para line chart
                    chartOptions.layout.padding = {
                        top: 20,
                        bottom: 20,
                        left: 15,
                        right: 15
                    };
                }
                
                window.myChart = new Chart(ctx, {
                    type: tipoGraficaSeleccionada,
                    data: chartData,
                    options: chartOptions,
                    plugins: [ChartDataLabels]
                });
            }
        });
    });
    function generarColores(cantidad) {
        const colores = [];
        const hueStep = 360 / cantidad;
        
        for (let i = 0; i < cantidad; i++) {
            const hue = (i * hueStep) % 360;
            colores.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colores;
    }

    // Función para actualizar la tabla con los filtros aplicados
function actualizarTablaConFiltros(filtros) {
    $.ajax({
        url: "{{ route('dashboard') }}",
        method: 'GET',
        data: filtros,
        success: function(response) {
            // Reemplazar el tbody de la tabla con los nuevos datos
            const tbody = $('#resultadosFinalesTable tbody');
            tbody.empty();
            
            if (response.alumnos.length > 0) {
                response.alumnos.forEach(function(alumno) {
                    const row = `
                        <tr style="transition: all 0.2s ease; border-bottom: 1px solid #e9ecef;">
                            <td class="fw-medium text-center" style="padding: 10px 8px; font-size: 0.8rem; background-color: #f8f9fa; border-right: 1px solid #e9ecef;">
                                ${alumno.Id_Registro}
                            </td>
                            <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Anio}
                            </td>
                            <td class="fw-medium text-center" style="padding: 10px 8px; font-size: 0.8rem; background-color: #f8f9fa; border-right: 1px solid #e9ecef;">
                                ${alumno.Id_Reg_A}
                            </td>
                            <td class="text-primary fw-bold text-center" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Cv_Alumno}
                            </td>
                            <td class="fw-semibold" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Nombre_Alumno}
                            </td>
                            <td class="text-center" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Gen}
                            </td>
                            <td class="fw-medium" style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Carrera}
                            </td>
                            <td class="text-truncate" style="padding: 10px 8px; font-size: 0.8rem; max-width: 150px; border-right: 1px solid #e9ecef;" title="${alumno.email}">
                                ${alumno.email}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Mat_1}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Mat_2}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Mat_3}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Escuela}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.TBaja}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Inc_Carr}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem; border-right: 1px solid #e9ecef;">
                                ${alumno.Empresa}
                            </td>
                            <td style="padding: 10px 8px; font-size: 0.8rem;">
                                ${alumno.Titulacion}
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
                
                // Mostrar mensaje de resultados
                $('#mensaje').text(`Mostrando ${response.alumnos.length} registros filtrados`);
            } else {
                tbody.append(`
                    <tr>
                        <td colspan="16" class="text-center text-muted py-4">
                            No se encontraron registros con los filtros aplicados
                        </td>
                    </tr>
                `);
                $('#mensaje').text('No se encontraron registros con los filtros aplicados');
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar los datos filtrados',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}
//----------------------------------------------------------------------------------------------------------------------------
    $('#btnDownloadPDF').on('click', function () {
    // Obtener el canvas y la imagen base64
    let canvas = document.getElementById('dataChart');
    let base64Image = canvas.toDataURL('image/png');

    if (!window.datosGrafica || Object.keys(window.datosGrafica).length === 0 || window.totalGrafica === 0) {
        Swal.fire({
            title: 'Error',
            text: 'No hay datos en el gráfico para generar el PDF. Primero genera una gráfica válida.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

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

    // Mostrar mensaje de "Generando reporte" después de verificar que hay datos
    Swal.fire({
        title: 'Generando reporte',
        text: 'Se está generando su reporte, por favor espere...',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch("{{ route('downloadPDF') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            Datos: window.datosGrafica,
            Total: window.totalGrafica,
            nombreGrafica: Nombre_de_la_grafica,
            fecha: new Date().toLocaleDateString('es-MX'),  // "15/01/2024"
            hora: new Date().toLocaleTimeString('es-MX'),   // "14:30:25"
            imagenGrafica: base64Image,
        }),
    })
    .then(response => {
        if (!response.ok) return response.json().then(err => { throw new Error(err.detalle || 'Error al generar el PDF'); });
        return response.blob();
    })
    .then(blob => {

        // Cerrar el mensaje de "Generando reporte"
        Swal.close();

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
        // Cerrar el mensaje de "Generando reporte"
        Swal.close();

        Swal.fire({
            title: 'Error',
            text: error.message || 'No se pudo generar el archivo PDF',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        console.error('Error:', error);
    });
});

//____________________boton de limpiar-------------------

$('#limpiarFiltrosBtn').on('click', function() {
    // Limpiar todos los filtros
    $('.filtro-unico').prop('checked', false).prop('disabled', false);
    $('#baja, #Carrera, #escuela, #materia, #trabajo, #tipo_titulacion').val('todas');
    $('#anio_1, #anio_2').val('');
    
    // Recargar la tabla con todos los datos
    location.reload();
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