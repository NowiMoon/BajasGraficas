@extends('layouts.app')

@section('content')
    <div class="col-1 d-flex flex-column gap-4 mx-2">
        <a class="d-block icon-item"><img src="{{ asset('images/analytics_pie_icon.png') }}" alt="Analytics Pie"></a>
        <a class="d-block icon-item"><img src="{{ asset('images/addData_icon.png') }}" alt="Upload file" data-bs-toggle="modal" data-bs-target="#uploadFile"></a>
        <a class="d-block icon-item"><img src="{{ asset('images/download_icon.png') }}" alt="Download analytics"></a>
    </div>
    <!-- Contenido principal -->
    <div class="container col-11 mx-auto mt-10">

        <!-- Tabla para mostrar datos -->
        <div id="tableContainer" class="mt-6 hidden">
            <h2 class="text-xl font-bold mb-4">Datos del Excel</h2>
            <table id="excelTable" class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200"></thead>
                <tbody></tbody>
            </table>
            <button id="generateGraph" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Generar Gráfica</button>
            <button id="exportPdf" class="mt-4 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded hidden">Generar Reporte PDF</button>
        </div>

        <!-- Selector de tipo de gráfico -->
        <div class="mt-4 hidden" id="chartOptions">
            <label for="chartType" class="font-bold">Tipo de Gráfica:</label>
            <select id="chartType" class="border p-2 rounded">
                <option value="bar">Barras</option>
                <option value="line">Líneas</option>
                <option value="pie">Pastel</option>
            </select>
        </div>

        <!-- Contenedor de gráficos -->
        <div id="chartsContainer" class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>

<!-- Modal -->
<div class="modal fade" id="uploadFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Carga de Información</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulario de carga de archivo -->
            <div class="rounded rounded-lg shadow-lg" style="background-color: #CDCDCD">
                <h2 class="text-xl font-bold">Subir archivo excel (.xlsx, .xlx)</h2>
                <div class="row">
                    <div class="justify-content-center col-md-6 d-flex">
                        <img src="{{ asset("images/document_search.png") }}" alt="Select File" style="height: 80px">
                    </div>
                    <div class="col-md-6">
                        <input id="fileInput" type="file" accept=".xlsx, .xlx" class="block w-full text-sm text-gray-600 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  @section('scripts')
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>}
  <script>
    document.addEventListener("DOMContentLoaded", function () {
    console.log("DOMContentLoaded");

    document.getElementById('fileInput').addEventListener('change', handleFile);
    document.getElementById('generateGraph').addEventListener('click', generateCharts);
    document.getElementById('exportPdf').addEventListener('click', exportToPdf);

    function handleFile(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();

        reader.onload = (e) => {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: "" });
            displayTable(jsonData);
        };
        reader.readAsArrayBuffer(file);
    }

    function displayTable(data) {
        const table = document.getElementById('excelTable');
        const thead = table.querySelector('thead');
        const tbody = table.querySelector('tbody');
        thead.innerHTML = '';
        tbody.innerHTML = '';

        if (data.length === 0) {
            alert('El archivo Excel está vacío.');
            return;
        }

        const headerRow = document.createElement('tr');
        data[0].forEach(header => {
            const th = document.createElement('th');
            th.textContent = header || 'Columna';
            th.classList.add('border', 'p-2', 'bg-gray-200');
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);

        data.slice(1).forEach(rowData => {
            const row = document.createElement('tr');
            rowData.forEach(cell => {
                const td = document.createElement('td');
                td.textContent = cell;
                td.contentEditable = true;
                td.classList.add('border', 'p-2');
                row.appendChild(td);
            });
            tbody.appendChild(row);
        });
        document.getElementById('tableContainer').classList.remove('hidden');
        document.getElementById('chartOptions').classList.remove('hidden');
        document.getElementById('exportPdf').classList.remove('hidden');
    }

    function exportToPdf() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text("Reporte de Datos y Gráficas", 10, 10);
        doc.save("reporte.pdf");
    }

    function generateCharts() {
        const table = document.getElementById('excelTable');
        const rows = table.querySelectorAll('tbody tr');
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);

        let data = [];
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let rowData = {};
            cells.forEach((cell, index) => {
                rowData[headers[index]] = cell.textContent;
            });
            data.push(rowData);
        });

        const labels = data.map(row => row[headers[0]]);
        const values = data.map(row => parseFloat(row[headers[1]]) || 0);
        const chartType = document.getElementById('chartType').value;

        const container = document.getElementById('chartsContainer');
        container.innerHTML = '';

        const canvas = document.createElement('canvas');
        container.appendChild(canvas);

        new Chart(canvas.getContext('2d'), {
            type: chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: headers[1],
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: `Gráfico de ${headers[1]}` }
                }
            }
        });
    }
});
  </script>
  @endsection
@endsection
