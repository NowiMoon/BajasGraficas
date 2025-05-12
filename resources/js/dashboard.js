/*
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
});*/

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    // Explicación del umbral con SweetAlert2
    $('#infoUmbralBtn').click(function() {
        Swal.fire({
            title: 'Umbral de Similitud',
            html: `
                <div class="text-start">
                    <p>El umbral determina qué tan estricto es el sistema al buscar coincidencias:</p>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-danger me-3" style="width: 80px;">1-40%</span>
                        <span>Más resultados, menos precisión</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-3" style="width: 80px;">40-70%</span>
                        <span>Balance recomendado (valor actual: <strong>${$('#umbral').val()}%</strong>)</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-3" style="width: 80px;">70-100%</span>
                        <span>Menos resultados, máxima precisión</span>
                    </div>
                    <p class="mt-3"><i class="fas fa-lightbulb text-warning"></i> <em>Sugerencia: Comienza con 60% y ajusta según los resultados obtenidos.</em></p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3490dc',
            width: '600px'
        });
    });

    // AJAX para subir archivo POST A UPLOAD CON AJAX
    $('#ajaxUploadForm').on('submit', function(e) {
        e.preventDefault();

        $('#submitBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
        //procesando ...
        $('#submitBtn').prop('disabled', true);
        $('#modalMessages').html('');

        let formData = new FormData(this);
        formData.append('umbral', $('#umbral').val());

        $.ajax({
            url: "{{ route('upload') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#modalMessages').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);

                    //banner de regreso de la API 
                      // Construir tabla de resultados
                    let resultadosHTML = `
                        <div class="mt-4">
                            <h5>Sugerencias encontradas:</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="resultadosTable">
                                    <thead>
                                        <tr>
                                            <th>Entrada</th>
                                            <th>Mejor coincidencia</th>
                                            <th>Opciones sugeridas</th>
                                            <th>Corrección manual</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                    // Contador único para IDs
                    let rowCounter = 0;

                    response.data.forEach(item => {
                        item.resultados.forEach(res => {
                            const currentIndex = rowCounter++;
                            
                            // Opciones como botones seleccionables
                            let sugerencias = '';
                            if (res.opciones && res.opciones.length > 0) {
                                sugerencias = '<div class="list-group">';
                                res.opciones.forEach(opcion => {
                                    sugerencias += `
                                        <button type="button" 
                                            class="list-group-item list-group-item-action" 
                                            onclick="document.getElementById('mejor-coincidencia-${currentIndex}').value = '${opcion.replace(/'/g, "\\'")}'">
                                            ${opcion}
                                        </button>`;
                                });
                                sugerencias += '</div>';
                            } else {
                                sugerencias = '<em>Sin sugerencias</em>';
                            }

                            resultadosHTML += `
                                <tr>
                                    <td>${res.entrada}</td>
                                    <td>
                                        <input type="text" 
                                            class="form-control" 
                                            id="mejor-coincidencia-${currentIndex}" 
                                            value="${res.mejor_coincidencia || ''}">
                                    </td>
                                    <td>${sugerencias}</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" 
                                                class="form-control" 
                                                id="correccion-manual-${currentIndex}" 
                                                placeholder="Escribe corrección">
                                            <button class="btn btn-outline-primary" 
                                                type="button"
                                                onclick="document.getElementById('mejor-coincidencia-${currentIndex}').value = document.getElementById('correccion-manual-${currentIndex}').value">
                                                →
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                    });
                    resultadosHTML += `
                                    </tbody>
                                </table>
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-primary" id="guardarCambios">
                                        Guardar cambios
                                    </button>
                                </div>
                            </div>
                        </div>`;
                            
                    $('#modalMessages').append(resultadosHTML);


                     // Evento para guardar cambios MODIFICAR PARA IMPLEMENTAR ID UNICO Y BASE DE DATOS --------------------------------
                     $('#guardarCambios').click(function() {
    let resultados = [];//dfghjkjhgfdsdfghjkjhgfdeertgyhjkjhgfddfghjhgfdfg
    $('#resultadosTable tbody tr').each(function() {
        const entrada = $(this).find('td:eq(0)').text();
        const coincidencia = $(this).find('input[type="text"]:first').val();
        
        resultados.push({
            entrada: entrada,
            mejor_coincidencia: coincidencia
        });

        $.ajax({
            url: "{{ route('Preparar_Datos') }}",
            type: 'POST',
            data: {
                resultados: resultados,
                _token: '{{ csrf_token() }}'
            }
        })
    });
    
    console.log('JSON generado:', JSON.stringify(resultados, null, 2));
    
    // Actualizar tabla externa
    const tablaBody = $('#resultadosFinalesTable tbody');
    tablaBody.empty();
    
    resultados.forEach(item => {
        tablaBody.append(`
            <tr>
                <td>${item.entrada}</td>
                <td>${item.mejor_coincidencia}</td>
            </tr>
        `);
    });
    
    // Mostrar el contenedor de resultados
    $('#resultadosContainer').show();
    
    // Mostrar notificación con SweetAlert2
    Swal.fire({
        title: '¡Datos guardados!',
        text: 'Los resultados se han guardado correctamente',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    }).then((result) => {
        // Cerrar el modal clic en Aceptar BUG"
        if (result.isConfirmed) {
            $('#uploadFile').modal('hide');
            
            // mensaje en el contenedor principal
            $('#ajaxMessages').html(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Los datos han sido procesados(Normalizados) y guardados exitosamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        }
    });
});
$(document).ready(function() {//---------------------------------------------------------
    let tipoGraficaSeleccionada = 'pie'; // Valor por defecto
    let categoriaSeleccionada = null;

    $('#tipoGrafica').change(function() {
        tipoGraficaSeleccionada = $(this).val();
        if (categoriaSeleccionada) {
            $('#generarGraficaBtn').prop('disabled', false);
        }
    });

    $('#resultadosContainer .btn-primary').on('click', function() {
        categoriaSeleccionada = $(this).data('tipo-grafica');
        if (tipoGraficaSeleccionada) {
            $('#generarGraficaBtn').prop('disabled', false);
        }
    });

    $('#generarGraficaBtn').on('click', function() {
        if (!categoriaSeleccionada) {
            Swal.fire('Error', 'Por favor, selecciona una categoría (Materias, Carreras o Generación).', 'error');
            return;
        }

        $.ajax({
            url: `/get-data/${categoriaSeleccionada}`,
            method: 'GET',
            success: function(response) {
                // Destruir gráfica anterior si existe
                if(typeof window.myChart !== 'undefined') {
                    window.myChart.destroy();
                }

                // Mostrar contenedor de gráfica
                $('#chartContainer').show();

                const ctx = document.getElementById('dataChart').getContext('2d');
                let chartData;
                let chartOptions = {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: `Total de ${categoriaSeleccionada.charAt(0).toUpperCase() + categoriaSeleccionada.slice(1)}: ${response.total}`
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} registros`;
                                }
                            }
                        }
                    }
                };
                if (tipoGraficaSeleccionada === 'pie') {
                    chartData = {
                        labels: Object.keys(response.data),
                        datasets: [{
                            data: Object.values(response.data),
                            backgroundColor: [
                                '#FF0000', '#0000FF', '#00FF00',
                                '#FFFF00', '#800080', '#FFA500',
                                '#00FFFF', '#32CD32', '#FF00FF',
                                '#FF4500', '#9400D3', '#00FF7F'
                            ],
                        }]
                    };
                } else if (tipoGraficaSeleccionada === 'bar') {
                    chartData = {
                        labels: Object.keys(response.data),
                        datasets: [{
                            label: `Total de ${categoriaSeleccionada}`,
                            data: Object.values(response.data),
                            backgroundColor: [
                                '#FF0000', '#0000FF', '#00FF00',
                                '#FFFF00', '#800080', '#FFA500',
                                '#00FFFF', '#32CD32', '#FF00FF',
                                '#FF4500', '#9400D3', '#00FF7F'
                            ],
                        }]
                    };
                    chartOptions.scales = {
                        y: {
                            beginAtZero: true
                        }
                    };
                } else if (tipoGraficaSeleccionada === 'line') {
                    chartData = {
                        labels: Object.keys(response.data),
                        datasets: [{
                            label: `Total de ${categoriaSeleccionada}`,
                            data: Object.values(response.data),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            fill: false
                        }]
                    };
                    chartOptions.scales = {
                        y: {
                            beginAtZero: true
                        }
                    };
                }
                window.myChart = new Chart(ctx, {
                    type: tipoGraficaSeleccionada,
                    data: chartData,
                    options: chartOptions
                });
            }
        });
    });
});
//----------------------------------------------------------------------------------------------------------------------------
                } else {
                    $('#modalMessages').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> ${response.message}
                            ${response.error ? '<br>' + response.error : ''}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Ocurrió un error al procesar el archivo';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                $('#modalMessages').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            },
            complete: function() {
                $('#submitBtn').html(`
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Subir Archivo
                `);
                $('#submitBtn').prop('disabled', false);
            }
        });
    });
});