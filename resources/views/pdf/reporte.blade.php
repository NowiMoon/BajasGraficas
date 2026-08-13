<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Académico UASLP</title>
    <style>
        /* Reseteos generales para asegurar que el banner toque el borde */
        @page {
            margin: 0; 
        }
        
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #333333;
            font-size: 12px;
        }

        /* Banner pegado completamente al margen superior */
        .header-banner {
            background-color: #004A98;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            border-bottom: 3px solid #002D62;
        }

        .header-banner img {
            height: 75px;
            display: inline-block;
        }

        /* Contenedor principal para márgenes internos (sin afectar el banner) */
        .container {
            width: 18cm;
            margin: 0 auto;
            padding: 20px 0;
        }

        /* Metadatos (Fecha y Hora) perfectamente alineados */
        .meta-info {
            width: 100%;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 8px;
            margin-bottom: 25px;
            font-size: 11px;
            color: #555;
        }
        .meta-info td {
            vertical-align: middle;
        }

        /* Título del reporte */
        .chart-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #004A98;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 25px 0;
        }

        /* Contenedor de la Gráfica */
        .chart-section {
            margin-bottom: 30px;
        }
        .chart-container {
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            padding: 15px;
            background: #fbfbfb;
            text-align: center;
            min-height: 8cm;
        }
        .chart-container img {
            max-width: 100%;
            height: auto;
            max-height: 10cm;
            display: block;
            margin: 0 auto;
        }

        /* Sección de datos */
        .data-section h3 {
            color: #004A98;
            font-size: 14px;
            margin-bottom: 12px;
            border-left: 4px solid #004A98;
            padding-left: 8px;
        }
        
        /* Estilos modernos y académicos para la tabla */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            padding: 10px;
            border: 1px solid #e0e0e0;
        }
        .data-table th {
            background-color: #004A98;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        .data-table th.col-cantidad {
            text-align: center;
        }
        .data-table td.col-cantidad {
            text-align: center;
            font-weight: bold;
            color: #444;
        }
        /* Color alternado para facilitar la lectura */
        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        /* Resaltado de la fila de totales */
        .total-row {
            background-color: #eef2f5 !important;
        }
        .total-row td {
            border-top: 2px solid #004A98;
            border-bottom: 2px solid #004A98;
            font-weight: bold;
            font-size: 12px;
            color: #000;
        }

        /* Pie de página */
        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #dcdcdc;
            padding-top: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <!-- Banner superior a ancho completo -->
    <div class="header-banner">
        <img src="images/banner.png" alt="Logo UASLP">
    </div>

    <div class="container">
        <!-- Barra de fecha y hora alineada con tabla (Excelente para PDFs) -->
        <table class="meta-info">
            <tr>
                <td style="text-align: left;"><strong>Fecha de generación:</strong> {{ $fecha ?? '' }}</td>
                <td style="text-align: right;"><strong>Hora:</strong> {{ $hora ?? '' }}</td>
            </tr>
        </table>

        <!-- Título -->
        <h1 class="chart-title">
            {{ $nombreGrafica ?? 'Reporte de Gráfica' }}
        </h1>

        <!-- Gráfica -->
        <div class="chart-section">
            <div class="chart-container">
                @if(!empty($imagenGrafica))
                    <img src="{{ $imagenGrafica }}" alt="Gráfica generada" />
                @else
                    <span style="color: #999; font-style: italic; display: block; padding-top: 3.5cm;">
                        No se encontró la imagen de la gráfica
                    </span>
                @endif
            </div>
        </div>

        <!-- Datos -->
        <div class="data-section">
            <h3>Desglose de Datos</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 75%">DATOS</th>
                        <th style="width: 25%" class="col-cantidad">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($datosGrafica))
                        @foreach($datosGrafica as $concepto => $cantidad)
                        <tr>
                            <td>{{ $concepto }}</td>
                            <td class="col-cantidad">{{ $cantidad }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" style="text-align: center; font-style: italic; color: #777;">
                                No hay datos disponibles para mostrar
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td style="text-align: right;">TOTAL GENERAL</td>
                        <td class="col-cantidad">{{ $totalGrafica ?? 0 }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Documento generado por el Área de Ciencias de la Computación, UASLP. Uso exclusivo para control y reportes académicos.</p>
        </div>
    </div>
</body>
</html>