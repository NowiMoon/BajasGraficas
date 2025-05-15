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