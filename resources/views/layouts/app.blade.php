<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <style>
    /* ESTILOS PARA AUTOCOMPLETADO */
    .autocomplete-suggestions {
        position: absolute;
        z-index: 1050;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
        margin-top: 2px;
    }
    
    .autocomplete-suggestions .dropdown-item {
        padding: 8px 12px;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background-color 0.15s;
    }
    
    .autocomplete-suggestions .dropdown-item:hover,
    .autocomplete-suggestions .dropdown-item:focus {
        background-color: #e9ecef;
        color: #495057;
        outline: none;
    }
    
    .autocomplete-suggestions .dropdown-item:last-child {
        border-bottom: none;
    }
    
    .autocomplete-suggestions .dropdown-item.active {
        background-color: #007bff;
        color: white;
    }
    
    .position-relative {
        position: relative;
    }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <div id="app">
        @include('layouts.navigation')
        
        <div class="d-flex">
            <!-- Main content -->
            <div class="flex-grow-1" style="">
                <main class="py-0">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
