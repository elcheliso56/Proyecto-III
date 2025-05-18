$(document).ready(function() {
    cargarDatos();
});

function cargarDatos() {
    var datos = new FormData();
    datos.append('accion', 'obtenerDatos');
    
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function() {
            $("#loader").show();
        },
        success: function(respuesta) {
            try {
                // Verificar si la respuesta ya es un objeto
                var datos = typeof respuesta === 'string' ? JSON.parse(respuesta) : respuesta;
                
                // Verificar si hay error en la respuesta
                if (datos.error) {
                    Swal.fire({
                        title: "Error",
                        text: datos.mensaje,
                        icon: "error"
                    });
                    return;
                }

                // Verificar si hay datos de egresos
                if (datos.egresosPorMes.length === 0 && datos.egresosPorOrigen.length === 0) {
                    console.log("No hay datos de egresos disponibles");
                }
                
                // Actualizar totales
                $("#totalIngresos").text("Bs. " + formatearNumero(datos.totalIngresos));
                $("#totalEgresos").text("Bs. " + formatearNumero(datos.totalEgresos));
                
                // Crear gráficos
                crearGraficoComparativo(datos.ingresosPorMes, datos.egresosPorMes);
                crearGraficoIngresosOrigen(datos.ingresosPorOrigen);
                crearGraficoEgresosOrigen(datos.egresosPorOrigen);
                
                // Actualizar tablas
                actualizarUltimosIngresos(datos.ultimosIngresos);
                actualizarUltimosEgresos(datos.ultimosEgresos);
                
            } catch (e) {
                console.error("Error al procesar los datos:", e);
                Swal.fire({
                    title: "Error",
                    text: "Error al procesar los datos: " + e.message,
                    icon: "error"
                });
            }
        },
        error: function(request, status, err) {
            console.error("Error en la petición AJAX:", err);
            Swal.fire({
                title: "Error",
                text: "Error al cargar los datos: " + err,
                icon: "error"
            });
        },
        complete: function() {
            $("#loader").hide();
        }
    });
}

function crearGraficoComparativo(ingresos, egresos) {
    const ctx = document.getElementById('graficoComparativo').getContext('2d');
    
    // Preparar datos para el gráfico
    const meses = ingresos.map(item => item.mes);
    const valoresIngresos = ingresos.map(item => item.total);
    const valoresEgresos = egresos && egresos.length > 0 ? egresos.map(item => item.total) : Array(ingresos.length).fill(0);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Ingresos',
                    data: valoresIngresos,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'Egresos',
                    data: valoresEgresos,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Comparativa Mensual'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Bs. ' + formatearNumero(value);
                        }
                    }
                }
            }
        }
    });
}

function crearGraficoIngresosOrigen(datos) {
    const ctx = document.getElementById('graficoIngresosOrigen').getContext('2d');
    
    // Verificar si hay datos
    if (!datos || datos.length === 0) {
        // Mostrar mensaje en el canvas
        const canvas = document.getElementById('graficoIngresosOrigen');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = '14px Arial';
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.fillText('No hay ingresos registrados', canvas.width / 2, canvas.height / 2);
        return;
    }
    
    // Preparar datos para el gráfico
    const labels = datos.map(item => item.origen);
    const valores = datos.map(item => item.total);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: [
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Distribución por Origen'
                }
            }
        }
    });
}

function crearGraficoEgresosOrigen(datos) {
    const ctx = document.getElementById('graficoEgresosCategoria').getContext('2d');
    
    // Verificar si hay datos
    if (!datos || datos.length === 0) {
        // Mostrar mensaje en el canvas
        const canvas = document.getElementById('graficoEgresosCategoria');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = '14px Arial';
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.fillText('No hay egresos registrados', canvas.width / 2, canvas.height / 2);
        return;
    }
    
    // Preparar datos para el gráfico
    const labels = datos.map(item => item.origen);
    const valores = datos.map(item => item.total);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Distribución por Origen'
                }
            }
        }
    });
}

function actualizarUltimosIngresos(datos) {
    let html = '';
    if (datos && datos.length > 0) {
        datos.forEach(ingreso => {
            html += `
                <tr>
                    <td>${ingreso.descripcion}</td>
                    <td>Bs. ${formatearNumero(ingreso.monto)}</td>
                    <td>${ingreso.fecha}</td>
                    <td>${ingreso.origen}</td>
                </tr>
            `;
        });
    } else {
        html = '<tr><td colspan="4" class="text-center">No hay ingresos registrados</td></tr>';
    }
    $("#ultimosIngresos").html(html);
}

function actualizarUltimosEgresos(datos) {
    let html = '';
    if (datos && datos.length > 0) {
        datos.forEach(egreso => {
            html += `
                <tr>
                    <td>${egreso.descripcion}</td>
                    <td>Bs. ${formatearNumero(egreso.monto)}</td>
                    <td>${egreso.fecha}</td>
                    <td>${egreso.origen}</td>
                </tr>
            `;
        });
    } else {
        html = `
            <tr>
                <td colspan="4" class="text-center">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> No hay egresos registrados en el sistema
                    </div>
                </td>
            </tr>
        `;
    }
    $("#ultimosEgresos").html(html);
}

function formatearNumero(numero) {
    return new Intl.NumberFormat('es-VE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(numero);
} 