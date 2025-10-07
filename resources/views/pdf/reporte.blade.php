<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Reporte de Gráficas UASLP</title>
    <style>
        body {
            font-family: "Times New Roman", Georgia, serif;
            width: 18cm;              
            margin: 0 auto;       
            padding: 1cm 1cm;
            background: #fff;
            font-size: 12px;
        }

        .header-banner {
            background-color: #004A98;
            color: white;
            padding: 15px 0; /* Aumenté el padding vertical */
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center; /* Centra el contenido */
            text-align: center; /* Asegura que el texto esté centrado */
            width: 100%; /* Ocupa todo el ancho disponible */
        }

        .header-banner img {
            height: 80px; /* Aumenté la altura de la imagen */
            margin: 0 auto; /* Centra la imagen */
            display: block;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 16px;
            margin: 3px 0;
        }

        .chart-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
        }

        .info-section {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
            gap: 20px;
        }
        .info-group {
            flex: 0 0 auto;
            width: 45%;
        }
        .info-label {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 11px;
            text-align: center;
        }
        .info-value {
            border: 1px solid #000;
            border-radius: 3px;
            padding: 4px;
            background-color: #f8f8f8;
            text-align: center;
            min-height: 18px;
            font-size: 11px;
        }

        .chart-section {
            margin-bottom: 15px;
        }
        .chart-container {
            border: 1px solid #333;
            border-radius: 5px;
            box-shadow: 1px 1px 3px rgba(0,0,0,0.1);
            padding: 8px;
            background: #fff;
            height: 10cm;
            width: 16cm;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        .chart-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .data-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .data-section h3 {
            text-align: center;
            margin-bottom: 8px;
            font-size: 13px;
            background: #f0f0f0;
            padding: 5px;
            border-radius: 3px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        .data-table th {
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }
        .data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .total-row {
            background: #d0d0d0 !important;
            font-weight: bold;
        }

        .footer {
            font-size: 9px;
            font-style: italic;
            text-align: center;
            color: #555;
            border-top: 1px solid #aaa;
            margin-top: 20px;
            padding-top: 3px;
        }

        .compact-layout {
            margin-left: 0;
            margin-right: 0;
        }
    </style>
</head>
<body class="compact-layout">
    <!-- Banner azul con logo UASLP -->
    <div class="header-banner">
        <img src="images/banner.png" alt="Logo UASLP">
    </div>


    <div class="info-section">
        <div class="info-group">
            <div class="info-label">Fecha</div>
            <div class="info-value">{{ $fecha ?? '' }}</div>
        </div>
        <div class="info-group">
            <div class="info-label">Hora</div>
            <div class="info-value">{{ $hora ?? '' }}</div>
        </div>
    </div>

    <div class="chart-title">
        {{ $nombreGrafica ?? 'Nombre de la Gráfica' }}
    </div>

    <div class="chart-section">
        <div class="chart-container">
            @if(!empty($imagenGrafica))
                <img src="{{ $imagenGrafica }}" />
            @else
                <span>No se encontró la imagen de la gráfica</span>
            @endif
        </div>
    </div>

    <div class="data-section">
        <h3>Datos Detallados de la Gráfica</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 70%">Concepto</th>
                    <th style="width: 30%">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($datosGrafica))
                    @foreach($datosGrafica as $concepto => $cantidad)
                    <tr>
                        <td>{{ $concepto }}</td>
                        <td style="text-align: center;">{{ $cantidad }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" style="text-align: center;">No hay datos disponibles</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td><strong>Total General</strong></td>
                    <td style="text-align: center;"><strong>{{ $totalGrafica ?? 0 }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p>Formato generado por el Área de Ciencias de la Computación, UASLP. Uso exclusivo para reportes académicos.</p>
    </div>
</body>
</html>