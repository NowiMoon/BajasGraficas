@extends('layouts.app')

@section('content')
<div class="d-flex ps-3 align-items-center bg-secondary bg-opacity-25 vw-100" style="height: 50px">
    <h3 class="fw-bold">Gestión de materias</h1>
</div>

<div class="py-4 px-5">
    <div class="d-flex flex-column bg-secondary bg-opacity-25" style="padding:50px 200px 50px 200px">
        <div>
            <h4 class="fw-bold">Registrar materia</h4>
            <div class="">
                <form method="POST" action="{{ route('gestion_materias.store') }} ">
                    @csrf
                    <div class="d-flex flex-column">
                        <div class="py-4 d-flex align-items-center">
                            <label for="clave_materia" class="w-25">Clave materia: *</label>
                            <input class="form-control w-50" type="number" id="clave_materia" name="clave_materia" placeholder="Clave de la materia" required>
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
        <hr>
        <div class="py-4">
            <form method="POST" action="{{ 'upload_subjects' }}" enctype="multipart/form-data">
                @csrf
                <h4 class="fw-bold">Subir archivo .csv</h4>
                <div class="d-flex align-items-center py-2">
                    <img src="{{ asset('images/document_search.png') }}" alt="" style="height: 80px">
                    <div class="d-flex flex-column">
                        <label for="file" class="fs-4">Selecciona un archivo .csv:</label>
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
@endsection