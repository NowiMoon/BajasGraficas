@extends('layouts.app')

@section('content')
<div class="d-flex ps-3 align-items-center bg-secondary bg-opacity-25 vw-100" style="height: 50px; margin-top: 108px">
    <a class="pe-3" href="{{ route('dashboard') }}"><img src="{{ asset('images/back.svg') }}" alt="Regresar"></a>
    <h3 class="fw-bold mb-0">Gestión de materias</h1>
</div>

<div class="py-4 px-5">
    <div class="d-flex flex-column bg-secondary bg-opacity-25" style="padding:50px 200px 50px 200px">
        <div class="container">
            <div class="d-flex" style="gap:1rem;">
                <!-- Registrar materia: 80% -->
                <div style="flex: 0 0 80%;">
                    <h4 class="fw-bold">Registrar materia</h4>
                    <div class="">
                        <form method="POST" action="{{ route('gestion_materias.store') }} ">
                            @csrf
                            <div class="d-flex flex-column">
                                <div class="py-4 d-flex align-items-center">
                                    <label for="clave_materia" class="w-25">Clave materia: *</label>
                                    <input class="form-control w-50" type="text" id="clave_materia" name="clave_materia" placeholder="Clave de la materia" required>
                                </div>
                                <div class="pb-4 align-items-center d-flex">
                                    <label for="nombre_materia" class="w-25">Nombre materia: *</label>
                                    <input class="form-control w-50" type="text" id="nombre_materia" name="nombre_materia" placeholder="Nombre de la materia" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Añadir') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Materias registradas: 20% -->
                <div style="flex: 0 0 20%; m-0; p-0;">
                    <h4 class="fw-bold pt-0">Materias registradas</h4>

                    @if(!isset($materias) || $materias->isEmpty())
                        <div class="bg-white rounded shadow-sm">
                            <p class="mb-0">No hay materias registradas.</p>
                        </div>
                    @else
                        <div class="table-responsive" style="max-height:200px; overflow:auto;">
                            <table id="materias-table" class="table table-bordered mb-0">
                                 <thead>
                                     <tr>
                                         <th>Clave</th>
                                         <th>Nombre</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach($materias as $materia)
                                         <tr>
                                            <td class="clave-cell">{{ $materia->clave_materia }}</td>
                                             <td>{{ $materia->nombre_materia }}</td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     @endif
                 </div>
            </div>
        </div>
        <hr>
        <div class="py-4">
            <form method="POST" action="{{ 'upload_subjects' }}" enctype="multipart/form-data">
                @csrf
                <h4 class="fw-bold">Subir archivo .csv</h4>
                <div class="d-flex align-items-center py-2">
                    <img src="{{ asset('images/document_search.png') }}" alt="" style="height: 80px">
                    <div class="d-flex flex-column">
                        <label for="file" class="fs-4">Selecciona un archivo  sdfsds .csv:</label>
                        <input type="file" id="file" name="file" accept=".csv" required class="fs-4">
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ __('Añadir') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if(session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('clave_materia'); // input existente
            const table = document.getElementById('materias-table');
            if (!input || !table) return;
            const tbody = table.tBodies[0];
            // crear indicador "no resultados" si no existe
            let noResults = document.getElementById('no-results-materias');
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.id = 'no-results-materias';
                noResults.className = 'text-center small mt-2';
                noResults.style.display = 'none';
                noResults.textContent = 'No se encontraron resultados.';
                table.parentElement.appendChild(noResults);
            }

            input.addEventListener('input', function (e) {
                const q = (e.target.value || '').trim();
                let visible = 0;
                Array.from(tbody.rows).forEach(row => {
                    const clave = (row.querySelector('.clave-cell')?.textContent || '').trim();
                    if (q === '' || clave.indexOf(q) !== -1) {
                        row.style.display = '';
                        visible++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                noResults.style.display = (visible === 0 ? 'block' : 'none');
            });
        });
    </script>
@endsection
@endsection