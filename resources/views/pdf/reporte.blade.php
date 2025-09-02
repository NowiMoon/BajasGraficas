<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Reporte de Gráficas UASLP</title>
    <style>
        body {
            font-family: "Times New Roman", Georgia, serif;
            width: 18cm;              
            margin: 0 auto;       
            padding: 2cm 1.5cm;      
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
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
        .date-section > div {
            width: 30%;
        }
        .date-field {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 6px;
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            min-height: 20px;
        }

        .chart-section {
            margin-bottom: 25px;
        }
        .chart-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .chart-container {
            border: 1px solid #333;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            padding: 10px;
            background: #fff;
            height: 10cm; 
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .chart-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .description-section {
            margin-bottom: 20px;
        }
        .description-field {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 10px;
            min-height: 2cm;
            background-color: #fafafa;
        }

        .user-section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .footer {
            font-size: 11px;
            font-style: italic;
            text-align: center;
            color: #555;
            border-top: 1px solid #aaa;
            margin-top: 30px;
            padding-top: 5px;
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

    <div class="chart-section">
        <div class="chart-name">
            <label>Nombre de la Gráfica</label>
            <div class="date-field">{{ $nombreGrafica ?? '' }}</div>
        </div>
        <div class="chart-container">
            @if(!empty($imagenGrafica))
                <img src="{{ $imagenGrafica }}" />
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
                <td id="nombreUsuario">{{ $nombreUsuario ?? '' }}</td>
                <td id="rolUsuario">{{ $rolUsuario ?? '' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Formato generado por el Área de Ciencias de la Computación, UASLP. Uso exclusivo para reportes académicos.</p>
    </div>
</body>