@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row gx-0">
        <!-- Sidebar -->
        <nav class="col-auto d-none d-md-flex flex-column bg-secondary bg-opacity-10 align-items-center justify-content-evenly"
             style="width: 80px; z-index: 1000; position: fixed; top: 80px; left: 0; height: calc(100vh - 80px);">
            @if ( Auth::user()->user_type == 1 )
            <a class="d-block icon-item text-center text-dark text-decoration-none py-2" href="{{ route('register') }}">
                <img src="{{ asset('images/users_icon.svg') }}" alt="Usuarios" style="width: 32px; height: 32px;" class="mb-1">
                <span style="font-size: 0.7rem; display: block;">Usuarios</span>
            </a>
            @endif
            @if ( Auth::user()->user_type == 2 )
            <a class="d-block icon-item text-center text-dark text-decoration-none py-2" href="{{ route('gestion_materias') }}">
                <img src="{{ asset('images/uploadSubjects_icon.svg') }}" alt="Subir materias" style="width: 32px; height: 32px;" class="mb-1">
                <span style="font-size: 0.7rem; display: block;">Materias</span>
            </a>
            <hr style="width: 80%; border: 2px solid #00B2E3; margin: 4px auto;">
            <a class="d-block icon-item text-center text-dark text-decoration-none py-2" style="cursor: pointer;">
                <img src="{{ asset('images/addData_icon.svg') }}" alt="Upload file" data-bs-toggle="modal" data-bs-target="#uploadFile" style="display: block; margin: 0 auto; width: 32px; height: 32px;" class="mb-1">
                <span style="font-size: 0.7rem; display: block;">Subir datos</span>
            </a>
            @endif
        </nav>
        
        <!-- Contenido principal (Interfaz ampliada) -->
        <main style="margin-left: 80px; margin-top: 80px; width: calc(100% - 80px);">
            <div class="container-fluid px-4 mt-4 mb-5">
                <!-- Contenedor para mensajes AJAX -->
                <div id="ajaxMessages" class="mt-3"></div>
                
                <!-- TABLA CON RESULTADOS REALES / GRÁFICA -->
                <div class="container-fluid mt-2 pt-2 px-0" id="resultadosContainer">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                        <!-- TÍTULO: Generador de Gráfica -->
                        <div class="card-header text-white py-3 px-4 d-flex align-items-center" style="background: linear-gradient(135deg, #004A98, #002D62);">
                            <i class="fas fa-chart-pie me-2 fs-5"></i>
                            <h3 class="card-title mb-0 fs-5 fw-semibold">Generador de Gráficas</h3>
                        </div>
                        
                        <!-- CONTROLES Y GRÁFICA -->
                        <div class="card-body p-4 bg-light bg-opacity-50">
                            <div class="row g-4">
                                <!-- Columna de controles -->
                                <div class="col-md-3">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                                            <h6 class="fw-semibold mb-2 text-dark"><i class="fas fa-sliders-h text-primary me-1"></i> Tipo de Gráfica</h6>
                                            <select class="form-select form-select-sm" id="tipoGrafica">
                                                <option value="pie">Gráfica de Pie</option>
                                                <option value="doughnut">Gráfica de Dona</option>
                                                <option value="bar">Gráfica de Barras</option>
                                                <option value="line">Gráfica de Líneas</option>
                                            </select>
                                        </div>

                                        <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                                            <h6 class="fw-semibold mb-2 text-dark"><i class="fas fa-calendar-alt text-primary me-1"></i> Rango de Generación</h6>
                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                <select class="form-select form-select-sm" id="anio_1">
                                                    <option value="" disabled selected>Desde</option>
                                                    @foreach($generaciones as $gen)
                                                        <option value="{{ $gen }}">{{ $gen }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="small text-muted fw-semibold px-1">a</span>
                                                <select class="form-select form-select-sm" id="anio_2">
                                                    <option value="" disabled selected>Hasta</option>
                                                    @foreach($generaciones as $gen)
                                                        <option value="{{ $gen }}">{{ $gen }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                                            <h6 class="fw-semibold mb-3 text-dark"><i class="fas fa-filter text-primary me-1"></i> Tema de la gráfica</h6>
                                            <div class="d-flex flex-column gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="generacion" value="generacion">
                                                    <label class="form-check-label fw-medium small ms-1 text-secondary" for="generacion">Generación</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="bajaCheckbox" value="baja">
                                                    <label class="form-check-label fw-medium small ms-1 text-secondary" for="bajaCheckbox">Tipo de baja</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="carreraCheckbox" value="carrera">
                                                    <label class="form-check-label fw-medium small ms-1 text-secondary" for="carreraCheckbox">Carrera</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="escuelaCheckbox" value="escuela">
                                                    <label class="form-check-label fw-medium small ms-1 text-secondary" for="escuelaCheckbox">Escuela</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="materiaCheckbox" value="materia">
                                                    <label class="form-check-label fw-medium small ms-1 text-secondary" for="materiaCheckbox">Materia difícil</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="titulacionCheckbox" value="titulacion">
                                                    <label class="form-check-label fw-medium small ms-1 text-secondary" for="titulacionCheckbox">Tipo de titulación</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input filtro-unico" type="radio" name="temaGrafica" id="trabajoCheckbox" value="trabajo">
                                                    <label class="form-check-label fw-medium small ms-1 text-secondary" for="trabajoCheckbox">Lugar donde labora</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                                            <h6 class="fw-semibold mb-2 text-dark"><i class="fas fa-search-plus text-primary me-1"></i> Filtros adicionales</h6>
                                            <div class="d-flex flex-column gap-2">
                                                <div>
                                                    <label for="baja" class="form-label small text-muted mb-1">Tipos de baja</label>
                                                    <select class="form-select form-select-sm" id="baja">
                                                        <option value="todas">Todas</option>
                                                        @foreach($tiposBaja as $tipo)
                                                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="Carrera" class="form-label small text-muted mb-1">Carreras</label>
                                                    <select class="form-select form-select-sm" id="Carrera">
                                                        <option value="todas">Todas</option>
                                                        <option value="Ingeniería en Computación">Ingeniería en Computación</option>
                                                        <option value="Ingeniería en Sistemas Inteligentes">Ingeniería en Sistemas Inteligentes</option>
                                                        <option value="Ingeniería en Informática">Ingeniería en Informática</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="escuela" class="form-label small text-muted mb-1">Escuelas</label>
                                                    <select class="form-select form-select-sm" id="escuela">
                                                        <option value="todas">Todas</option>
                                                        @foreach($escuelas as $escuela)
                                                            <option value="{{ $escuela }}">{{ $escuela }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="materia" class="form-label small text-muted mb-1">Materias difíciles</label>
                                                    <input type="text" id="buscadorMateria" class="form-control form-control-sm mb-1" placeholder="Buscar materia...">
                                                    <select class="form-select form-select-sm" id="materia">
                                                        <option value="todas">Todas</option>
                                                        @foreach($materias as $materia)
                                                            <option value="{{ $materia }}">{{ $materia }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="tipo_titulacion" class="form-label small text-muted mb-1">Tipos de titulación</label>
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

                                                <div>
                                                    <label for="trabajo" class="form-label small text-muted mb-1">Trabajos</label>
                                                    <select class="form-select form-select-sm" id="trabajo">
                                                        <option value="todas">Todas</option>
                                                        @foreach($trabajos as $trabajo)
                                                            <option value="{{ $trabajo }}">{{ $trabajo }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <p id="mensaje" class="small text-muted text-center mt-1 mb-0 fw-medium"></p>
                                    </div>
                                </div>

                                <!-- Columna de visualización -->
                                <div class="col-md-9">
                                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3 mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <button class="btn btn-primary btn-sm w-100 fw-semibold py-2 shadow-sm" id="generarGraficaBtn" style="background-color: #004A98; border-color: #004A98;">
                                                    <i class="fas fa-chart-bar me-1"></i> Generar Gráfica
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-outline-secondary btn-sm w-100 fw-semibold py-2 shadow-sm" id="limpiarFiltrosBtn">
                                                    <i class="fas fa-times me-1"></i> Limpiar Filtros
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-outline-danger btn-sm w-100 fw-semibold py-2 shadow-sm" id="btnDownloadPDF">
                                                    <i class="fas fa-file-pdf me-1"></i> Generar Reporte
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-0 shadow-sm bg-white rounded-3 p-3">
                                        <div id="chartContainer" style="display: none; width: 100%; height: 68vh; position: relative;">
                                            <div class="d-flex justify-content-center align-items-center h-100">
                                                <canvas id="dataChart" style="max-width: 100%; max-height: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div id="noChartMessage" class="text-center text-muted" style="height: 68vh; background-color: #fcfdfd; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 2px dashed #e9ecef;">
                                            <div>
                                                <i class="fas fa-chart-pie fa-3x mb-3 text-secondary opacity-25"></i>
                                                <p class="mb-0 text-muted fw-medium">La gráfica se mostrará aquí después de generar los datos</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TÍTULO Y CONTROLES: Datos de Alumnos Registrados -->
                        <div class="card-header text-white py-3 px-4 mt-4 d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3" style="background: linear-gradient(135deg, #004A98, #002D62);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-2 fs-5"></i>
                                <h3 class="card-title mb-0 fs-5 fw-semibold">Datos de Alumnos Registrados</h3>
                            </div>
                            
                            <!-- BUSCADOR INDEPENDIENTE POR COLUMNAS -->
                            <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                                <input type="text" id="filtroClave" class="form-control form-control-sm border-0" placeholder="Clave..." style="width: 110px;">
                                <input type="text" id="filtroNombre" class="form-control form-control-sm border-0" placeholder="Nombre..." style="width: 180px;">
                                <input type="text" id="filtroGen" class="form-control form-control-sm border-0" placeholder="Gen..." style="width: 90px;">
                                
                                <button type="button" id="btnBuscarTabla" class="btn btn-warning btn-sm fw-semibold shadow-sm text-dark px-3" title="Buscar en tabla">
                                    <i class="fas fa-search me-1"></i> Buscar
                                </button>
                                <button type="button" id="btnQuitarFiltrosTabla" class="btn btn-light btn-sm text-dark fw-semibold px-3 shadow-sm" title="Mostrar todos los registros">
                                    <i class="fas fa-redo-alt me-1"></i> Restablecer tabla
                                </button>
                                <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-semibold shadow-sm ms-1" style="font-size: 0.8rem;" id="contadorRegistrosBadge">
                                    {{ count($alumnos) }} total
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-0 bg-white">
                            <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0" id="resultadosFinalesTable" style="border-collapse: separate; border-spacing: 0; font-size: 0.85rem;">
                                    <thead class="sticky-top shadow-sm" style="top: 0; z-index: 10;">
                                        <tr style="background-color: #f1f5f9;">
                                            <th class="py-3 px-3 text-secondary fw-bold text-center border-bottom">Id</th>
                                            <th class="py-3 px-3 text-secondary fw-bold text-center border-bottom">Año</th>
                                            <th class="py-3 px-3 text-secondary fw-bold text-center border-bottom">Id Reg</th>
                                            <th class="py-3 px-3 text-secondary fw-bold text-center border-bottom">Clave</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Nombre</th>
                                            <th class="py-3 px-3 text-secondary fw-bold text-center border-bottom">Generación</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Carrera</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Email</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Mat1</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Mat2</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Mat3</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Escuela</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Tipo Baja</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Inconveniente</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Empresa</th>
                                            <th class="py-3 px-3 text-secondary fw-bold border-bottom">Titulación</th>
                                            @if(Auth::user()->user_type == 1)
                                            <th class="py-3 px-3 text-secondary fw-bold text-center border-bottom">Acción</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="tablaAlumnosBody">
                                        @foreach($alumnos as $alumno)
                                            @php
                                                $tbajaLower = mb_strtolower(trim($alumno->TBaja ?? ''));
                                                $badgeClass = 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25';
                                                if (str_contains($tbajaLower, 'pasantía') || str_contains($tbajaLower, 'pasantia')) {
                                                    $badgeClass = 'bg-success bg-opacity-25 text-success border border-success border-opacity-50 fw-bold';
                                                } elseif (str_contains($tbajaLower, 'cambio de carrera')) {
                                                    $badgeClass = 'bg-warning bg-opacity-25 text-dark border border-warning border-opacity-50 fw-bold';
                                                } elseif (str_contains($tbajaLower, 'baja temporal') || str_contains($tbajaLower, 'definitiva')) {
                                                    $badgeClass = 'bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 fw-bold';
                                                }
                                            @endphp
                                            <tr style="transition: all 0.15s ease;" class="border-bottom alumno-row" 
                                                data-clave="{{ mb_strtolower(trim($alumno->Cv_Alumno ?? '')) }}" 
                                                data-nombre="{{ mb_strtolower(trim($alumno->Nombre_Alumno ?? '')) }}" 
                                                data-gen="{{ mb_strtolower(trim($alumno->Gen ?? '')) }}"
                                                data-carrera="{{ mb_strtolower(trim($alumno->Carrera ?? '')) }}"
                                                data-tbaja="{{ mb_strtolower(trim($alumno->TBaja ?? '')) }}"
                                                data-escuela="{{ mb_strtolower(trim($alumno->Escuela ?? '')) }}"
                                                data-mat1="{{ mb_strtolower(trim($alumno->Mat_1 ?? '')) }}"
                                                data-mat2="{{ mb_strtolower(trim($alumno->Mat_2 ?? '')) }}"
                                                data-mat3="{{ mb_strtolower(trim($alumno->Mat_3 ?? '')) }}"
                                                data-trabajo="{{ mb_strtolower(trim($alumno->Empresa ?? '')) }}"
                                                data-titulacion="{{ mb_strtolower(trim($alumno->Titulacion ?? '')) }}"
                                            >
                                                <td class="text-center text-muted fw-medium py-3 px-3 bg-light bg-opacity-50">{{ $alumno->Id_Registro }}</td>
                                                <td class="text-center py-3 px-3">{{ $alumno->Anio }}</td>
                                                <td class="text-center text-muted fw-medium py-3 px-3 bg-light bg-opacity-50">{{ $alumno->Id_Reg_A }}</td>
                                                <td class="text-primary fw-bold text-center py-3 px-3">{{ $alumno->Cv_Alumno }}</td>
                                                <td class="fw-semibold text-dark py-3 px-3">{{ $alumno->Nombre_Alumno }}</td>
                                                <td class="text-center py-3 px-3"><span class="badge bg-light text-dark border px-2 py-1">{{ $alumno->Gen }}</span></td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Carrera }}</td>
                                                <td class="text-muted text-truncate py-3 px-3 copy-email" style="max-width: 160px; cursor: pointer;" title="Haz clic para copiar email" data-email="{{ $alumno->email }}">
                                                    <span class="text-primary text-decoration-underline">{{ $alumno->email }}</span> <small class="text-muted ms-1" style="font-size: 0.75rem;">(Haz clic para copiar)</small>
                                                </td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Mat_1 }}</td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Mat_2 }}</td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Mat_3 }}</td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Escuela }}</td>
                                                <td class="py-3 px-3"><span class="badge px-2.5 py-1.5 {{ $badgeClass }}">{{ $alumno->TBaja }}</span></td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Inc_Carr }}</td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Empresa }}</td>
                                                <td class="text-secondary py-3 px-3">{{ $alumno->Titulacion }}</td>
                                                @if(Auth::user()->user_type == 1)
                                                <td class="text-center py-3 px-3">
                                                    <form method="POST" action="{{ route('alumnos.destroy', $alumno->Id_Registro) }}"
                                                          class="delete-form d-inline"
                                                          data-id="{{ $alumno->Id_Registro }}"
                                                          data-name="{{ $alumno->Nombre_Alumno }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Eliminar registro">
                                                            <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de carga masiva -->
<div class="modal fade" id="uploadFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-light py-3 px-4">
          <h1 class="modal-title fs-5 fw-bold text-dark" id="exampleModalLabel"><i class="fas fa-file-excel text-success me-2"></i>Carga de Información Masiva</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
            <div id="modalMessages"></div>
            <div class="bg-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center mb-3 mb-md-0">
                        <img src="{{ asset('images/document_search.png') }}" alt="Select File" style="height: 140px;" class="opacity-75">
                    </div>
                    <div class="col-md-6">
                        <form id="ajaxUploadForm" action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">   
                            @csrf
                            <div class="mb-4">
                                <label for="file" class="form-label fw-semibold text-secondary">Selecciona un archivo .xlsx:</label>
                                <input type="file" id="file" name="file" accept=".xlsx" required class="form-control" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                <div id="file-name" class="form-text text-muted mt-1 small"></div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label for="umbral" class="form-label fw-semibold text-secondary mb-0">Umbral de similitud:</label>
                                    <span id="valorUmbral" class="badge bg-primary px-2 py-1">60%</span>
                                </div>
                                <input type="range" id="umbral" name="umbral" min="1" max="100" value="60" oninput="document.getElementById('valorUmbral').textContent = this.value + '%'" class="form-range">
                            </div>
                            <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" style="background-color: #004A98; border-color: #004A98;">
                                <i class="fas fa-cloud-upload-alt me-1"></i> Subir Archivo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>

@section('scripts')
<script>
    const uploadUrl = "{{ route('upload') }}";
    const prepararDatos = "{{ route('Preparar_Datos') }}";
    const listadoGlobalUrl = "{{ route('api.listado.global') }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.datosGrafica = null;
    window.totalGrafica = null;
    let Nombre_de_la_grafica = null;
    window.titulo = null;
    window.subtitulo = null;
    let cacheListadoGlobal = null;
    const isAdmin = {{ Auth::check() && Auth::user()->user_type == 1 ? 'true' : 'false' }};

    function obtenerListadoGlobalBD(callback) {
        if (cacheListadoGlobal !== null) {
            callback(cacheListadoGlobal);
            return;
        }
        $.get(listadoGlobalUrl, function(data) {
            cacheListadoGlobal = data;
            callback(cacheListadoGlobal);
        }).fail(function() {
            callback([]);
        });
    }

    $(document).ready(function() {
        // --- COPIAR EMAIL ---
        $(document).on('click', '.copy-email', function() {
            const email = $(this).data('email');
            if (!email) return;
            navigator.clipboard.writeText(email).then(() => {
                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true })
                    .fire({ icon: 'success', title: `¡Email copiado: ${email}!` });
            });
        });

        // BÚSQUEDA DE TABLA INDEPENDIENTE (CLAVE, NOMBRE, GEN)
        $('#btnBuscarTabla').on('click', function() {
            const fClave = $('#filtroClave').val().toLowerCase().trim();
            const fNombre = $('#filtroNombre').val().toLowerCase().trim();
            const fGen = $('#filtroGen').val().toLowerCase().trim();
            
            let visibles = 0;
            $('#no-results-row').remove();

            $('#tablaAlumnosBody tr.alumno-row').each(function() {
                const clave = String($(this).data('clave') || '');
                const nombre = String($(this).data('nombre') || '');
                const gen = String($(this).data('gen') || '');

                let match = true;
                if (fClave !== '' && !clave.includes(fClave)) match = false;
                if (fNombre !== '' && !nombre.includes(fNombre)) match = false;
                if (fGen !== '' && !gen.includes(fGen)) match = false;

                if (match) {
                    $(this).show();
                    visibles++;
                } else {
                    $(this).hide();
                }
            });

            $('#contadorRegistrosBadge').text(`${visibles} mostrados`);
            
            if (visibles === 0) {
                const colSpan = isAdmin ? 17 : 16;
                $('#tablaAlumnosBody').append(`<tr id="no-results-row"><td colspan="${colSpan}" class="text-center text-muted py-5"><i class="fas fa-search fa-2x mb-2 opacity-50"></i><p class="mb-0">No se encontraron registros con los criterios de búsqueda introducidos</p></td></tr>`);
            }
        });

        $('#btnQuitarFiltrosTabla').on('click', function() {
            $('#filtroClave, #filtroNombre, #filtroGen').val('');
            $('#no-results-row').remove();
            $('#tablaAlumnosBody tr.alumno-row').show();
            const total = $('#tablaAlumnosBody tr.alumno-row').length;
            $('#contadorRegistrosBadge').text(`${total} total`);
        });

        // Búsqueda del Modal
        $('#buscadorMateria').on('input', function() {
            let valorBusqueda = $(this).val().toLowerCase().trim();
            $('#materia option').each(function() {
                let textoOption = $(this).text().toLowerCase();
                if (textoOption === 'todas' || valorBusqueda === '' || textoOption.includes(valorBusqueda)) $(this).show(); else $(this).hide();
            });
        });

        $(document).on('input', '.filtro-opcion-modal', function() {
            let valorBusqueda = $(this).val().toLowerCase().trim();
            let targetSelectId = $(this).data('target');
            let $select = $(`#${targetSelectId}`);
            obtenerListadoGlobalBD(function(listaGlobal) {
                if ($select.data('cargado-global') !== true) {
                    let htmlOpciones = '<option value="" disabled selected>Selecciona...</option><option value="Ninguna">Ninguna</option>';
                    listaGlobal.forEach(opt => { htmlOpciones += `<option value="${opt}">${opt}</option>`; });
                    $select.html(htmlOpciones);
                    $select.data('cargado-global', true);
                }
                $select.find('option').each(function() {
                    let textoOption = $(this).text().toLowerCase();
                    if (textoOption.includes('selecciona') || textoOption.includes('ninguna') || valorBusqueda === '' || textoOption.includes(valorBusqueda)) $(this).show(); else $(this).hide();
                });
            });
        });

        $('#anio_2, #anio_1').on('change', function() {
            const desde = $('#anio_1').val();
            const hasta = $('#anio_2').val();
            if (desde && hasta && hasta < desde) {
                Swal.fire({ title: 'Error de rango', text: 'El año "Hasta" no puede ser menor que el año "Desde"', icon: 'warning', confirmButtonText: 'Aceptar' });
                $(this).val('');
            }
        });

        let tipoGraficaSeleccionada = 'pie';
        $('#tipoGrafica').change(function() { tipoGraficaSeleccionada = $(this).val(); });

        $('#generarGraficaBtn').on('click', function() {
            let filtroSeleccionado = $('input[name="temaGrafica"]:checked').length > 0;
            if (!filtroSeleccionado) {
                Swal.fire({ title: 'Error', text: 'Debes seleccionar al menos un tipo de filtro para generar la gráfica', icon: 'error', confirmButtonText: 'Aceptar' });
                return;
            }

            let tipoFiltro = $('input[name="temaGrafica"]:checked').val();
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

            let descripcionFiltros = [];
            if (filtros.generacion_desde && filtros.generacion_hasta) {
                descripcionFiltros.push(filtros.generacion_desde === filtros.generacion_hasta ? `Generación ${filtros.generacion_desde}` : `Generaciones ${filtros.generacion_desde} - ${filtros.generacion_hasta}`);
            } else if (filtros.generacion_desde) { descripcionFiltros.push(`Desde generación ${filtros.generacion_desde}`); } 
            else if (filtros.generacion_hasta) { descripcionFiltros.push(`Hasta generación ${filtros.generacion_hasta}`); }

            if (filtros.carrera && filtros.carrera !== 'todas') descripcionFiltros.push(`Carrera: ${filtros.carrera}`);
            if (filtros.baja && filtros.baja !== 'todas') descripcionFiltros.push(`Baja: ${filtros.baja}`);
            if (filtros.escuela && filtros.escuela !== 'todas') descripcionFiltros.push(`Escuela: ${filtros.escuela}`);
            if (filtros.materia && filtros.materia !== 'todas') descripcionFiltros.push(`Materia: ${filtros.materia}`);
            if (filtros.tipo_titulacion && filtros.tipo_titulacion !== 'todas') descripcionFiltros.push(`Titulación: ${filtros.tipo_titulacion}`);
            if (filtros.trabajo && filtros.trabajo !== 'todas') descripcionFiltros.push(`Trabajo: ${filtros.trabajo}`);

            if (descripcionFiltros.length === 0) descripcionFiltros.push('Todos los registros');

            Nombre_de_la_grafica = `Gráfica de ${tipoFiltro.charAt(0).toUpperCase() + tipoFiltro.slice(1)}`;
            window.titulo = Nombre_de_la_grafica;
            window.subtitulo = descripcionFiltros.join(', ');

            Object.keys(filtros).forEach(key => { if (filtros[key] === '' || filtros[key] == null) delete filtros[key]; });

            // Petición AJAX para la gráfica
            $.ajax({
                url: "{{ route('get.data') }}",
                method: 'GET',
                data: filtros,
                success: function(response) {
                    if (response.total === 0 || Object.keys(response.data).length === 0) {
                        Swal.fire({ title: 'Sin datos', text: 'No se encontraron datos para generar la gráfica con los filtros seleccionados.', icon: 'warning', confirmButtonText: 'Aceptar' });
                        $('#chartContainer').hide();
                        $('#noChartMessage').show();
                        return;
                    }

                    actualizarTablaConFiltrosClientSide(filtros);

                    $('#noChartMessage').hide();
                    $('#chartContainer').show();

                    if (typeof window.myChart !== 'undefined') window.myChart.destroy();

                    window.datosGrafica = response.data;
                    window.totalGrafica = response.total;
                    
                    const datosFiltrados = Object.entries(response.data).reduce((acc, [key, value]) => { if (value >= 3) acc[key] = value; return acc; }, {});
                    const sumaOtros = Object.entries(response.data).filter(([key, value]) => value < 3).reduce((sum, [key, value]) => sum + value, 0);
                    if (sumaOtros > 0) datosFiltrados['Otros'] = sumaOtros;
                    window.datosGraficaFiltrados = datosFiltrados;

                    const ctx = document.getElementById('dataChart').getContext('2d');
                    const labels = Object.keys(window.datosGraficaFiltrados);
                    const dataValues = Object.values(window.datosGraficaFiltrados);
                    const total = response.total;
                    const percentages = dataValues.map(value => ((value / total) * 100).toFixed(1) + '%');

                    let chartOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: true, text: window.titulo, font: { size: 18, weight: 'bold' } },
                            subtitle: { display: true, text: window.subtitulo, font: { size: 14, style: 'italic' } },
                            tooltip: { callbacks: { label: function(context) { return `${context.label}: ${context.raw} registros (${((context.raw / total) * 100).toFixed(1)}%)`; } } },
                            datalabels: { display: true, color: '#fff', font: { weight: 'bold', size: 12 }, formatter: function(value, context) { return percentages[context.dataIndex]; } },
                            legend: { display: true, position: 'bottom' }
                        }
                    };

                    let chartData = {
                        labels: labels,
                        datasets: [{
                            label: `Total de ${tipoFiltro}`,
                            data: dataValues,
                            backgroundColor: generarColores(labels.length),
                            borderWidth: (tipoGraficaSeleccionada === 'pie' || tipoGraficaSeleccionada === 'doughnut') ? 2 : 1,
                            borderColor: (tipoGraficaSeleccionada === 'pie' || tipoGraficaSeleccionada === 'doughnut') ? '#fff' : undefined
                        }]
                    };
                    if (tipoGraficaSeleccionada === 'doughnut') chartOptions.cutout = '45%';

                    window.myChart = new Chart(ctx, { type: tipoGraficaSeleccionada, data: chartData, options: chartOptions, plugins: [ChartDataLabels] });
                }
            });
        });

        // SINCRONIZACIÓN DE LA TABLA CON LOS FILTROS DE LA GRÁFICA
        function actualizarTablaConFiltrosClientSide(filtros) {
            let visibles = 0;
            $('#no-results-row').remove();
            
            $('#filtroClave, #filtroNombre, #filtroGen').val('');

            $('#tablaAlumnosBody tr.alumno-row').each(function() {
                let showRow = true;
                const row = $(this);

                const gen = row.data('gen'); 
                if (filtros.generacion_desde && gen < filtros.generacion_desde.toLowerCase()) showRow = false;
                if (filtros.generacion_hasta && gen > filtros.generacion_hasta.toLowerCase()) showRow = false;

                if (filtros.carrera && filtros.carrera !== 'todas' && row.data('carrera') !== filtros.carrera.toLowerCase()) showRow = false;
                if (filtros.baja && filtros.baja !== 'todas' && row.data('tbaja') !== filtros.baja.toLowerCase()) showRow = false;
                if (filtros.escuela && filtros.escuela !== 'todas' && row.data('escuela') !== filtros.escuela.toLowerCase()) showRow = false;
                if (filtros.trabajo && filtros.trabajo !== 'todas' && row.data('trabajo') !== filtros.trabajo.toLowerCase()) showRow = false;
                if (filtros.tipo_titulacion && filtros.tipo_titulacion !== 'todas' && row.data('titulacion') !== filtros.tipo_titulacion.toLowerCase()) showRow = false;

                if (filtros.materia && filtros.materia !== 'todas') {
                    const mat = filtros.materia.toLowerCase();
                    if (row.data('mat1') !== mat && row.data('mat2') !== mat && row.data('mat3') !== mat) {
                        showRow = false;
                    }
                }

                if (showRow) {
                    row.show();
                    visibles++;
                } else {
                    row.hide();
                }
            });

            $('#contadorRegistrosBadge').text(`${visibles} mostrados`);
            $('#mensaje').text(`Mostrando ${visibles} registros filtrados por los datos de la gráfica.`);
            
            if (visibles === 0) {
                const colSpan = isAdmin ? 17 : 16;
                $('#tablaAlumnosBody').append(`<tr id="no-results-row"><td colspan="${colSpan}" class="text-center text-muted py-5"><i class="fas fa-search fa-2x mb-2 opacity-50"></i><p class="mb-0">No se encontraron registros con los filtros de la gráfica aplicados</p></td></tr>`);
            }
        }

        function generarColores(cantidad) {
            const colores = [];
            const hueStep = 360 / cantidad;
            for (let i = 0; i < cantidad; i++) {
                const hue = (i * hueStep) % 360;
                colores.push(`hsl(${hue}, 70%, 60%)`);
            }
            return colores;
        }
    });

    // SUBIDA AJAX DE EXCEL CON FIX DE ID PARA TABLA RESULTADOS
    $(document).on('submit', '#ajaxUploadForm', function(e) {
        e.preventDefault();

        const $submitBtn = $('#submitBtn');
        $submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        $submitBtn.prop('disabled', true);
        $('#modalMessages').html('');

        let formData = new FormData(this);
        formData.append('umbral', $('#umbral').val());

        $.ajax({
            url: uploadUrl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.codigo && response.codigo === 'DUPLICADOS') {
                    $('#uploadFile').modal('hide');
                    $('#ajaxMessages').html(`
                        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    return; 
                }

                if (response.success) {
                    $('#modalMessages').html(`<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">${response.message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
                    
                    // SE AGREGÓ id="resultadosTable" A LA TABLA
                    let resultadosHTML = `<div class="mt-4"><h5 class="fw-bold mb-3">Sugerencias encontradas:</h5><div class="table-responsive rounded border" style="max-height: 400px; overflow-y: auto;"><table id="resultadosTable" class="table table-bordered mb-0 align-middle"><thead class="table-light sticky-top" style="top: 0;"><tr><th style="width: 25%;">Entrada</th><th style="width: 25%;">Mejor coincidencia</th><th style="width: 25%;">Opciones sugeridas</th><th style="width: 25%;">Corrección manual</th></tr></thead><tbody>`;

                    let rowCounter = 0;
                    let listaResultados = [];
                    if (Array.isArray(response.data)) {
                        response.data.forEach(item => {
                            if (item.resultados && Array.isArray(item.resultados)) { listaResultados = listaResultados.concat(item.resultados); } else { listaResultados.push(item); }
                        });
                    }

                    listaResultados.forEach(res => {
                        const currentIndex = rowCounter++;
                        let sugerencias = '';
                        if (res.opciones && res.opciones.length > 0) {
                            sugerencias = '<div class="list-group" style="max-height: 100px; overflow-y: auto;">';
                            res.opciones.forEach(opcion => {
                                let opcionEscapes = String(opcion).replace(/'/g, "\\'");
                                sugerencias += `<button type="button" class="list-group-item list-group-item-action py-1 px-2 small" onclick="document.getElementById('mejor-coincidencia-${currentIndex}').value = '${opcionEscapes}'">${opcion}</button>`;
                            });
                            sugerencias += '</div>';
                        } else { sugerencias = '<em class="text-muted small">Sin sugerencias</em>'; }

                        let opcionesSelect = '<option value="" disabled selected>Selecciona...</option><option value="Ninguna">Ninguna</option>';
                        if (res.opciones && res.opciones.length > 0) { res.opciones.forEach(opt => { opcionesSelect += `<option value="${opt}">${opt}</option>`; }); }

                        resultadosHTML += `<tr><td class="fw-medium">${res.entrada || ''}</td><td><input type="text" class="form-control form-control-sm mb-1" id="mejor-coincidencia-${currentIndex}" value="${res.mejor_coincidencia || 'Ninguna'}"></td><td>${sugerencias}</td><td><input type="text" class="form-control form-control-sm mb-1 filtro-opcion-modal" placeholder="Buscar en BD..." data-target="select-opcion-${currentIndex}"><select class="form-select form-select-sm" id="select-opcion-${currentIndex}" onchange="document.getElementById('mejor-coincidencia-${currentIndex}').value = this.value">${opcionesSelect}</select></td></tr>`;
                    });

                    resultadosHTML += `</tbody></table><div class="text-end mt-3 mb-3"><button type="button" class="btn btn-primary px-4 fw-semibold shadow-sm" id="guardarCambios" style="background-color: #004A98; border-color: #004A98;">Guardar cambios</button></div></div></div>`;
                    $('#modalMessages').append(resultadosHTML);
                } else {
                    $('#modalMessages').html(`<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert"><strong>Error:</strong> ${response.message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Ocurrió un error al procesar el archivo';
                $('#modalMessages').html(`<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert"><strong>Error:</strong> ${errorMessage}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
            },
            complete: function() {
                $submitBtn.html('Subir Archivo');
                $submitBtn.prop('disabled', false);
            }
        });
    });

    // GUARDAR CAMBIOS DE LA CARGA MASIVA (CORREGIDO)
    $(document).on('click', '#guardarCambios', function() {
        let resultados = [];
        
        $('#resultadosTable tbody tr').each(function() {
            const entrada = $(this).find('td:eq(0)').text().trim();
            const coincidencia = $(this).find('input[id^="mejor-coincidencia-"]').val();
            
            resultados.push({ 
                entrada: entrada, 
                mejor_coincidencia: coincidencia 
            });
        });

        if (resultados.length === 0) {
            Swal.fire({ title: 'Aviso', text: 'No hay datos para guardar', icon: 'warning', confirmButtonText: 'Aceptar' });
            return;
        }

        if(typeof window.myChart !== 'undefined') window.myChart.destroy();

        const $btn = $(this);
        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...').prop('disabled', true);

        $.ajax({
            url: prepararDatos,
            type: 'POST',
            data: { 
                resultados: resultados, 
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function (respuesta) {
                Swal.fire({ 
                    title: '¡Datos guardados!', 
                    text: respuesta.mensaje, 
                    icon: 'success', 
                    confirmButtonText: 'Aceptar', 
                    confirmButtonColor: '#004A98' 
                }).then((result) => {
                    if (result.isConfirmed) { 
                        $('#uploadFile').modal('hide'); 
                        location.reload(); 
                    }
                });
            },
            error: function (xhr) {
                let msg = "Error al guardar los datos";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire({ 
                    title: 'Error', 
                    text: msg, 
                    icon: 'error', 
                    confirmButtonText: 'Aceptar', 
                    confirmButtonColor: '#dc3545' 
                });
                $btn.html('Guardar cambios').prop('disabled', false);
            }
        });
    });

    // Eliminar registro de alumno
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-form button[type="submit"], .delete-form .btn-danger');
        if (!btn) return;
        e.preventDefault();
        const form = btn.closest('.delete-form');
        const name = form?.dataset?.name || 'este registro';

        if (typeof Swal === 'undefined') {
            if (confirm(`¿Deseas eliminar "${name}"?`)) form.submit();
            return;
        }

        Swal.fire({ title: '¿Confirmar eliminación?', text: `¿Deseas eliminar "${name}"? Esta acción no se puede deshacer.`, icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar', confirmButtonColor: '#dc3545', reverseButtons: true }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Generar PDF
    $('#btnDownloadPDF').on('click', function () {
        let canvas = document.getElementById('dataChart');
        let base64Image = canvas.toDataURL('image/png');
        const chartInstance = Chart.getChart(canvas);
        if (!chartInstance || chartInstance.data.datasets.every(dataset => dataset.data.length === 0)) {
            Swal.fire({ title: 'Error', text: 'No hay datos en el gráfico para generar el PDF.', icon: 'error', confirmButtonText: 'Aceptar', confirmButtonColor: '#dc3545' });
            return;
        }

        Swal.fire({ title: 'Generando reporte', text: 'Se está generando su reporte, por favor espere...', icon: 'info', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        fetch("{{ route('downloadPDF') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), },
            body: JSON.stringify({
                Datos: window.datosGrafica, Total: window.totalGrafica, nombreGrafica: Nombre_de_la_grafica,
                fecha: new Date().toLocaleDateString('es-MX'), hora: new Date().toLocaleTimeString('es-MX'), imagenGrafica: base64Image,
            }),
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw new Error(err.detalle || 'Error al generar el PDF'); });
            return response.blob();
        })
        .then(blob => {
            Swal.close();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "reporte.pdf";
            a.click();
            window.URL.revokeObjectURL(url);
            Swal.fire({ title: '¡PDF descargado!', text: 'El reporte se descargó correctamente.', icon: 'success', confirmButtonText: 'Aceptar', confirmButtonColor: '#004A98' });
        })
        .catch(error => {
            Swal.close();
            Swal.fire({ title: 'Error', text: error.message || 'No se pudo generar el archivo PDF', icon: 'error', confirmButtonText: 'Aceptar', confirmButtonColor: '#dc3545' });
        });
    });

    $('#limpiarFiltrosBtn').on('click', function() {
        $('.filtro-unico').prop('checked', false).prop('disabled', false);
        $('#baja, #Carrera, #escuela, #materia, #trabajo, #tipo_titulacion').val('todas');
        $('#anio_1, #anio_2, #buscadorMateria').val('');
        location.reload();
    });
</script>
@endsection