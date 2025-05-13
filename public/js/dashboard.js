$(document).ready(function() {
//----------------------------------------------------------------------------------------------------------------------------
    let tipoGraficaSeleccionada = 'pie'; // Valor por defecto
    let categoriaSeleccionada = null;

        $('#tipoGrafica').change(function() {
        tipoGraficaSeleccionada = $(this).val();
        if (categoriaSeleccionada) {
            $('#generarGraficaBtn').prop('disabled', false);
        }
    });

    $('button[data-tipo-grafica]').on('click', function() { 
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
                                '#FF6384', '#36A2EB', '#FFCE56',
                                '#4BC0C0', '#9966FF', '#FF9F40',
                                '#E7E9ED', '#C9CBCF', '#28B463',
                                '#A569BD', '#F1948A', '#F39C12'
                            ],
                        }]
                    };
                } else if (tipoGraficaSeleccionada === 'bar') {
                    chartData = {
                        labels: Object.keys(response.data),
                        datasets: [{
                            label: `Total de ${categoriaSeleccionada}`,
                            data: Object.values(response.data),
                            backgroundColor: 'rgba(54, 162, 235, 0.8)'
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
//----------------------------------------------------------------------------------------------------------------------------

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
            url: uploadUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.codigo && response.codigo === 'DUPLICADOS') {
                    $('#uploadFile').modal('hide');
                    $('#ajaxMessages').html(`
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    return; 
                }

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
                        $(document).on('click', '#guardarCambios', function() {
    let resultados = [];//dfghjkjhgfdsdfghjkjhgfdeertgyhjkjhgfddfghjhgfdfg
    $('#resultadosTable tbody tr').each(function() {
        const entrada = $(this).find('td:eq(0)').text();
        const coincidencia = $(this).find('input[type="text"]:first').val();
        
        resultados.push({
            entrada: entrada,
            mejor_coincidencia: coincidencia
        });
        if(typeof window.myChart !== 'undefined') {
                    window.myChart.destroy();
                }
    });

    $.ajax({
            url: prepararDatos,
            type: 'POST',
            data: {
                resultados: resultados,
                _token: '{{ csrf_token() }}'
            },
             headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                success: function (respuesta) {
                    $('#mensaje').text(respuesta.mensaje);
                },
                error: function () {
                    $('#mensaje').text("Error al guardar los datos");
                }
        })
    
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