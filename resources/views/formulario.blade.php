<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normalización de Datos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 660px;
            margin: auto;
        }
        h1 {
            color: #333;
        }
        label {
            font-weight: bold;
        }
        select, textarea, input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: blue;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background-color: blue ;
        }
        .slider-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .slider-container span {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Normalización de Datos</h1>
        
        @if(session('error'))
            <div style="color:red;">
                {{ session('error') }}
            </div>
        @endif

        <form action="/normalizar" method="POST">
            @csrf
            <label for="tipo">Seleccione el tipo de normalización:</label>
            <select id="tipo" name="tipo" required>
                <option value="1">Materias</option>
                <option value="2">Preparatorias</option>
                <option value="3">Trabajos</option>
            </select>

            <label for="entradas">Ingrese los nombres (separados por comas):</label>
            <textarea id="entradas" name="entradas" rows="4" required="" style="height: 62px; width: 640px;"></textarea>

            <label for="umbral">Seleccione el umbral:</label>
            <div class="slider-container">
                <input type="range" id="umbral" name="umbral" min="1" max="100" value="60" oninput="actualizarValor()">
                <span id="valorUmbral">60</span>
            </div>

            <button type="submit">Normalizar</button>
        </form>
    </div>

    <script>
        function actualizarValor() {
            document.getElementById("valorUmbral").textContent = document.getElementById("umbral").value;
        }
    </script>
</body>
</html>


