<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados JSON</title>
</head>
<body>
    <h1>Lista de Materias Recibidas</h1>

    <ul>
        @if($errors->any())
            @foreach($data as $item)
            <li>{{ $item }}</li>
            @endforeach
        @endif
    </ul>

    @if($valor)
        <p>{{ $valor }}</p>
    @endif

</body>
</html>
