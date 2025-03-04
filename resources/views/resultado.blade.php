<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Normalización</title>
</head>
<body>
    <h1>Resultados de Normalización</h1>

    @if(empty($resultados))
        <p>No se encontraron resultados.</p>
    @else
        @foreach($resultados as $resultado)
            <div style="margin-bottom:20px; padding:10px; border-bottom:1px solid #ccc;">
                <p><strong>Entrada:</strong> {{ $resultado['entrada'] }}</p>
                <p><strong>Mejor coincidencia:</strong> {{ $resultado['mejor_coincidencia'] }}</p>
                <p><strong>Opciones similares:</strong></p>
                <ul>
                    @foreach($resultado['opciones'] as $opcion)
                        <li>{{ $opcion }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endif
</body>
</html>
