<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados JSON</title>
</head>
<body>
    <h1>Lista de Materias Recibidas</h1>

    @if(isset($materias) && count($materias) > 0)
        <ul>
            @foreach($materias as $materia)
                <li>{{ $materia }}</li>
            @endforeach
        </ul>
    @else
        <p>No se recibieron materias.</p>
    @endif

    <a href="{{ url('/') }}">Volver al inicio</a>
</body>
</html>
