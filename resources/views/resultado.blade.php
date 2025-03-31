<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Normalización</title>
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
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #333;
        }
        label {
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        select {
            height: 100px;
        }
        .entry {
            padding: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .entry:last-child {
            border-bottom: none;
        }
        .button-container {
            margin-top: 20px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Resultados de Normalización</h1>

        @if(empty($resultados))
            <p>No se encontraron resultados.</p>
        @else
            @foreach($resultados as $resultado)
                <div class="entry">
                    <label>Entrada Original:</label>
                    <p><strong>{{ $resultado['entrada'] }}</strong></p>

                    <label>Mejor Coincidencia:</label>
                    <input type="text" value="{{ $resultado['mejor_coincidencia'] }}" readonly>

                    <label>Opciones Similares:</label>
                    <select size="3">
                        @foreach($resultado['opciones'] as $opcion)
                            <option>{{ $opcion }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        @endif

        <div class="button-container">
            <button onclick="window.history.back()">Volver</button>
        </div>
    </div>
</body>
</html>

