<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Reporte de Gráficas UASLP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 5px 0;
        }
        .date-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .date-field {
            border-bottom: 1px solid #000;
            width: 30%;
            padding: 5px 0;
        }
        .chart-section {
            margin-bottom: 20px;
        }
        .chart-name {
            margin-bottom: 10px;
        }
        .chart-container {
            border: 1px solid #000;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .description-section {
            margin-bottom: 20px;
        }
        .description-field {
            width: 100%;
            border-bottom: 1px solid #000;
            height: 40px;
        }
        .user-section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        td {
            border-bottom: 1px solid #000;
        }
        .footer {
            font-size: 12px;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Facultad de Ingeniería</h1>
        <h1>Área de Ciencias de la Computación</h1>
        <h1>Reporte de Gráficas</h1>
    </div>

    <div class="date-section">
        <div>
            <label>Periodo de consulta:</label>
            <div class="date-field" id="periodoConsulta">{{ $periodoConsulta ?? '' }}</div>
        </div>
        <div>
            <label>Fecha:</label>
            <div class="date-field" id="fecha">{{ $fecha ?? '' }}</div>
        </div>
        <div>
            <label>Hora:</label>
            <div class="date-field" id="hora">{{ $hora ?? '' }}</div>
        </div>
    </div>

    <!--<div class="chart-section">
        <div class="chart-name">
            <label>Nombre de la Gráfica</label>
            <div class="date-field" id="nombreGrafica">{{ $nombreGrafica ?? '' }}</div>
        </div>
        <div class="chart-container">
            GRÁFICA AQUÍ
        </div>
    </div>-->
    <div class="chart-section">
        <div class="chart-name">
            <label>Nombre de la Gráfica</label>
            <div class="date-field">{{ $nombreGrafica ?? '' }}</div>
        </div>
        <div class="chart-container">
            @if(!empty($imagenGrafica))
                <img src="{{ $imagenGrafica }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
            @else
                <span>No se encontró la imagen de la gráfica</span>
            @endif
        </div>
    </div>

    <div class="description-section">
        <label>Breve descripción de la gráfica:</label>
        <div class="description-field" id="descripcionGrafica">{{ $descripcionGrafica ?? '' }}</div>
    </div>

    <div class="user-section">
        <h3>Datos del Usuario</h3>
        <table>
            <tr>
                <th>Nombre del usuario</th>
                <th>Rol del usuario</th>
            </tr>
            <tr>
                <td id="nombreUsuario">{{ $nombreUsuario ?? '' }}<</td>
                <td id="rolUsuario">{{ $rolUsuario ?? '' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Formato generado por el Área de Ciencias de la Computación, UASLP. Uso exclusivo para reportes académicos.</p>
    </div>
</body>