<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de normalización</title>
</head>
<body>
    <h1>Resultado de normalización</h1>
    <p>Mejor coincidencia: {{ $mejor_coincidencia ?? 'Ninguna' }}</p>
    @if (!empty($opciones))
        <h2>Otras opciones:</h2>
        <ul>
            @foreach ($opciones as $opcion)
                <li>{{ $opcion }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>