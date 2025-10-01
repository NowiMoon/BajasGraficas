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