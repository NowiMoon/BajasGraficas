<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo .xlsx</title>
</head>
<body>
    <h1>Subir Archivo Excel (.xlsx)</h1>

    <!-- Mostrar mensajes de éxito o error -->
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario para subir el archivo -->
    <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
        @csrf <!-- Token de seguridad de Laravel -->
        <label for="file">Selecciona un archivo .xlsx:</label>
        <input type="file" id="file" name="file" accept=".xlsx" required>
        <br><br>
        <button type="submit">Subir Archivo</button>
    </form>
    
</body>
</html>