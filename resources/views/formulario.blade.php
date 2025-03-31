<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Normalización</title>
</head>
<body>
    <h1>Normalización de Materias, Preparatorias y Trabajos</h1>
    
    @if(session('error'))
        <div style="color:red;">
            {{ session('error') }}
        </div>
    @endif

    <form action="/normalizar" method="POST">
        @csrf
        <label for="tipo">Seleccione el tipo de normalización:</label><br>
        <select id="tipo" name="tipo" required>
            <option value="1">Materias</option>
            <option value="2">Preparatorias</option>
            <option value="3">Trabajos</option>
        </select>
        <br><br>
        <label for="entradas">Ingrese los nombres (separados por comas):</label><br>
        <textarea id="entradas" name="entradas" rows="5" cols="50" required></textarea>
        <br><br>
        <label for="umbral">Seleccione el umbral (1-100):</label><br>
        <input type="range" id="umbral" name="umbral" min="1" max="100" value="60" oninput="actualizarValor()">
        <span id="valorUmbral">60</span>
        <br><br>
        <button type="submit">Normalizar</button>
    </form>

    <script>
        function actualizarValor() {
            document.getElementById("valorUmbral").textContent = document.getElementById("umbral").value;
        }
    </script>
</body>
</html>

