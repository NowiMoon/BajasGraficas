<nav class="navbar navbar-expand-md text-white shadow-sm" style="background-color:#004A98; z-index: 1050; position: fixed; top: 0; left: 0; width: 100%;">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/UASLP.png') }}" style="height: 80px" alt="UASLP Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto"></ul>
            <ul class="navbar-nav ms-auto">
                <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <img src="{{ asset('images/exit_icon.svg') }}" alt="Exit">
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
                </form>
            </ul>
        </div>  
    </div>
</nav>

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
        <a class="d-block icon-item text-center" style="text-decoration: none; color: black;"><img src="{{ asset('images/download_icon.svg') }}" alt="Download analytics" style="display: block; margin: 0 auto;">Generar Reporte</a>
    </div>
    <div style="margin-left: 80px; margin-top: 80px; width: 100%;">

    </div>
</div>