@extends('layouts.app')

@section('content')
<!-- Contenedor general con margen superior suficiente para que no lo tape la barra fija -->
<div class="container-fluid px-0" style="margin-top: 115px;">
    <!-- Encabezado superior adaptado -->
    <div class="bg-white border-bottom shadow-sm px-4 py-3 mb-4">
        <div class="container d-flex align-items-center">
            <a class="btn btn-outline-secondary btn-sm me-3 rounded-circle d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}" style="width: 38px; height: 38px;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Gestión de Materias</h4>
                <p class="text-muted small mb-0">Administra el catálogo de materias del sistema de forma manual o masiva mediante archivos CSV.</p>
            </div>
        </div>
    </div>

    <!-- Cuerpo principal -->
    <div class="container pb-5">
        <div class="row g-4">
            <!-- Columna Izquierda: Registro Manual y Carga Masiva -->
            <div class="col-lg-7">
                <!-- Tarjeta: Registrar Materia Individual -->
                <div class="card border-0 shadow-sm mb-4 rounded-3">
                    <div class="card-header bg-gradient text-white py-3" style="background-color: #004A98;">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="fas fa-book-medical me-2"></i>Registrar Materia Individual
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('gestion_materias.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="clave_materia" class="form-label fw-medium text-secondary">Clave de la materia: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-key text-muted"></i></span>
                                    <input class="form-control" type="text" id="clave_materia" name="clave_materia" placeholder="Ej. 2151" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_materia" class="form-label fw-medium text-secondary">Nombre de la materia: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-signature text-muted"></i></span>
                                    <input class="form-control" type="text" id="nombre_materia" name="nombre_materia" placeholder="Ej. Matemáticas Discretas I" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 fw-semibold" style="background-color: #004A98; border-color: #004A98;">
                                    <i class="fas fa-plus-circle me-1"></i> Añadir Materia
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tarjeta: Subir Archivo CSV -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-gradient text-white py-3" style="background-color: #00B2E3;">
                        <h5 class="card-title mb-0 fw-semibold text-white">
                            <i class="fas fa-file-upload me-2"></i>Carga Masiva de Materias (.csv)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('upload_subjects') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex align-items-center gap-3 p-3 border border-dashed rounded-3 bg-light mb-3">
                                <img src="{{ asset('images/document_search.png') }}" alt="Subir archivo" style="height: 60px;">
                                <div class="flex-grow-1">
                                    <label for="file" class="form-label fw-semibold mb-1 text-dark">Selecciona tu archivo CSV:</label>
                                    <input type="file" id="file" name="file" accept=".csv,.txt" required class="form-control form-control-sm">
                                    <div class="form-text text-muted small mt-1">El archivo debe contener las columnas <code class="text-primary">clave_c</code> y <code class="text-primary">nombre_c</code>.</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-info text-white px-4 fw-semibold" style="background-color: #00B2E3; border-color: #00B2E3;">
                                    <i class="fas fa-upload me-1"></i> Subir y Procesar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Tabla de Materias Registradas con Búsqueda Integrada -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-semibold text-dark">
                            <i class="fas fa-list-alt text-primary me-2"></i>Materias Registradas
                        </h5>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            {{ isset($materias) ? $materias->count() : 0 }} total
                        </span>
                    </div>
                    <div class="card-body p-3 d-flex flex-column">
                        @if(!isset($materias) || $materias->isEmpty())
                            <div class="text-center py-5 my-auto text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">No hay materias registradas en el sistema.</p>
                            </div>
                        @else
                            <!-- Buscador rápido en vivo -->
                            <div class="mb-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" id="buscadorTablaMaterias" class="form-control" placeholder="Filtrar por clave o nombre...">
                                </div>
                            </div>

                            <div class="table-responsive flex-grow-1 rounded border" style="max-height: 420px; overflow-y: auto;">
                                <table id="materias-table" class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="py-2 px-3">Clave</th>
                                            <th class="py-2 px-3">Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($materias as $materia)
                                            <tr>
                                                <td class="clave-cell fw-semibold text-primary py-2 px-3">{{ $materia->clave_materia }}</td>
                                                <td class="nombre-cell py-2 px-3">{{ $materia->nombre_materia }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="no-results-materias" class="text-center text-muted small py-3" style="display: none;">
                                No se encontraron coincidencias para tu búsqueda.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#004A98'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: "{{ session('error') }}",
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc3545'
            });
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            const buscador = document.getElementById('buscadorTablaMaterias');
            const table = document.getElementById('materias-table');
            if (!buscador || !table) return;
            
            const tbody = table.tBodies[0];
            const noResults = document.getElementById('no-results-materias');

            buscador.addEventListener('input', function (e) {
                const q = (e.target.value || '').toLowerCase().trim();
                let visible = 0;

                Array.from(tbody.rows).forEach(row => {
                    const clave = (row.querySelector('.clave-cell')?.textContent || '').toLowerCase();
                    const nombre = (row.querySelector('.nombre-cell')?.textContent || '').toLowerCase();

                    if (q === '' || clave.includes(q) || nombre.includes(q)) {
                        row.style.display = '';
                        visible++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (noResults) {
                    noResults.style.display = (visible === 0 ? 'block' : 'none');
                }
            });
        });
    </script>
@endsection