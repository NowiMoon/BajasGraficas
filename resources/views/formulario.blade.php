<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de normalización primera base</title>
</head>
<body>
    <h1>Normalizar nombre de materia [TEST]</h1>
    <form action="/normalizar" method="POST">
        @csrf
        <label for="entrada">Ingrese el nombre de la materia:</label>
        <input type="text" id="entrada" name="entrada" required>
        <button type="submit">Normalizar</button>
    </form>
</body>
</html>