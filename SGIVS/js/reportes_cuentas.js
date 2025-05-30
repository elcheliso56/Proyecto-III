$(document).ready(function() {
    // Cargar las cuentas al iniciar
    cargarCuentas();

    // Manejar el cambio en el tipo de reporte
    $("#tipo_reporte").on("change", function() {
        var tipo = $(this).val();
        if (tipo === 'movimientos') {
            $("#fecha_inicio").prop("required", true);
            $("#fecha_fin").prop("required", true);
        } else {
            $("#fecha_inicio").prop("required", false);
            $("#fecha_fin").prop("required", false);
        }
    });

    // Manejar el envío del formulario
    $("#formReporte").on("submit", function(e) {
        e.preventDefault();
        
        // Validar campos requeridos
        if (!validarFormulario()) {
            return false;
        }

        // Mostrar loader
        $("#loader").show();

        // Enviar datos por AJAX
        $.ajax({
            url: "",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.resultado === 'generar_reporte') {
                    // Generar el PDF con los datos recibidos
                    generarPDF(response.mensaje);
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.mensaje,
                        icon: "error"
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error",
                    text: "Error al procesar la solicitud: " + error,
                    icon: "error"
                });
            },
            complete: function() {
                $("#loader").hide();
            }
        });
    });
});

// Función para cargar las cuentas
function cargarCuentas() {
    $.ajax({
        url: "",
        type: "POST",
        data: { accion: 'consultar' },
        dataType: "json",
        success: function(response) {
            if (response.resultado === 'consultar') {
                var cuentas = response.mensaje;
                var select = $("#cuenta_id");
                select.empty();
                select.append('<option value="" selected>Todas las cuentas</option>');
                cuentas.forEach(function(cuenta) {
                    select.append('<option value="' + cuenta.id + '">' + cuenta.nombre + '</option>');
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar cuentas:", error);
        }
    });
}

// Función para validar el formulario
function validarFormulario() {
    var tipo = $("#tipo_reporte").val();
    if (!tipo) {
        Swal.fire({
            title: "Error",
            text: "Debe seleccionar un tipo de reporte",
            icon: "error"
        });
        return false;
    }

    if (tipo === 'movimientos') {
        var fechaInicio = $("#fecha_inicio").val();
        var fechaFin = $("#fecha_fin").val();
        
        if (!fechaInicio || !fechaFin) {
            Swal.fire({
                title: "Error",
                text: "Para reportes de movimientos, debe seleccionar un rango de fechas",
                icon: "error"
            });
            return false;
        }

        if (new Date(fechaInicio) > new Date(fechaFin)) {
            Swal.fire({
                title: "Error",
                text: "La fecha de inicio no puede ser mayor que la fecha fin",
                icon: "error"
            });
            return false;
        }
    }

    return true;
}

// Función para generar el PDF
function generarPDF(datos) {
    // Crear una ventana nueva para el PDF
    var ventana = window.open('', '_blank');
    
    // Construir el HTML del reporte
    var html = construirHTMLReporte(datos);
    
    // Enviar el HTML a la ventana nueva
    ventana.document.write(html);
    ventana.document.close();
    
    // Imprimir el reporte
    ventana.print();
}

// Función para construir el HTML del reporte
function construirHTMLReporte(datos) {
    var tipo = $("#tipo_reporte").val();
    var titulo = $("#tipo_reporte option:selected").text();
    var fecha = new Date().toLocaleDateString();
    var descripcion = obtenerDescripcionReporte(tipo);
    
    var html = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>${titulo}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 20px;
                    border-bottom: 2px solid #ddd;
                    padding-bottom: 20px;
                }
                .header h2 {
                    color: #333;
                    margin: 10px 0;
                }
                .descripcion {
                    color: #666;
                    font-style: italic;
                    margin: 10px 0;
                    text-align: center;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin: 20px 0;
                    font-size: 12px;
                }
                th, td { 
                    border: 1px solid #ddd; 
                    padding: 8px; 
                    text-align: left; 
                }
                th { 
                    background-color: #f5f5f5;
                    font-weight: bold;
                }
                .footer { 
                    text-align: right; 
                    font-size: 11px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                    margin-top: 20px;
                }
                .fecha-generacion {
                    color: #666;
                    font-size: 11px;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 20px;
                    }
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>${titulo}</h2>
                <p class="descripcion">${descripcion}</p>
                <p class="fecha-generacion">Fecha de generación: ${fecha}</p>
            </div>
            <table>
                <thead>
                    ${generarEncabezadoTabla(tipo)}
                </thead>
                <tbody>
                    ${generarFilasTabla(datos, tipo)}
                </tbody>
            </table>
            <div class="footer">
                <p>SGIVS - Sistema de Gestión Integral Vital Sonrisas</p>
                <p>Este es un documento generado automáticamente por el sistema</p>
            </div>
        </body>
        </html>
    `;
    
    return html;
}

// Función para obtener la descripción del reporte según su tipo
function obtenerDescripcionReporte(tipo) {
    var descripciones = {
        'estado_cuentas': 'Muestra el estado actual de todas las cuentas, incluyendo saldos, ingresos y egresos totales.',
        'movimientos': 'Detalla todos los movimientos (ingresos y egresos) realizados en las cuentas durante el período seleccionado.',
        'cuentas_cobrar': 'Lista todas las cuentas por cobrar a pacientes, mostrando montos pendientes y fechas de vencimiento.'
    };
    return descripciones[tipo] || '';
}

// Función para generar el encabezado de la tabla según el tipo de reporte
function generarEncabezadoTabla(tipo) {
    var headers = {
        'estado_cuentas': ['Cuenta', 'Tipo', 'Moneda', 'Saldo Actual', 'Total Ingresos', 'Total Egresos'],
        'movimientos': ['Tipo', 'Fecha', 'Descripción', 'Monto', 'Cuenta'],
        'cuentas_cobrar': ['ID', 'Paciente', 'Fecha Emisión', 'Fecha Vencimiento', 'Monto Total', 'Monto Pendiente', 'Estado']
    };
    
    var html = '<tr>';
    headers[tipo].forEach(function(header) {
        html += '<th>' + header + '</th>';
    });
    html += '</tr>';
    
    return html;
}

// Función para generar las filas de la tabla según el tipo de reporte
function generarFilasTabla(datos, tipo) {
    var html = '';
    
    datos.forEach(function(row) {
        html += '<tr>';
        switch(tipo) {
            case 'estado_cuentas':
                html += `
                    <td>${row.nombre}</td>
                    <td>${row.tipo}</td>
                    <td>${row.moneda}</td>
                    <td>${formatearMoneda(row.saldo_actual)}</td>
                    <td>${formatearMoneda(row.total_ingresos)}</td>
                    <td>${formatearMoneda(row.total_egresos)}</td>
                `;
                break;
            case 'movimientos':
                html += `
                    <td>${row.tipo}</td>
                    <td>${formatearFecha(row.fecha)}</td>
                    <td>${row.descripcion}</td>
                    <td>${formatearMoneda(row.monto)}</td>
                    <td>${row.cuenta}</td>
                `;
                break;
            case 'cuentas_cobrar':
                html += `
                    <td>${row.id}</td>
                    <td>${row.paciente}</td>
                    <td>${formatearFecha(row.fecha_emision)}</td>
                    <td>${formatearFecha(row.fecha_vencimiento)}</td>
                    <td>${formatearMoneda(row.monto_total)}</td>
                    <td>${formatearMoneda(row.monto_pendiente)}</td>
                    <td>${row.estado}</td>
                `;
                break;
        }
        html += '</tr>';
    });
    
    return html;
}

// Función para formatear moneda
function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'VES'
    }).format(valor);
}

// Función para formatear fecha
function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString();
} 