$(document).ready(function() {
    cargarCuentas();
    cargarDatos();
    inicializarFechas();
});

// Variables globales para los gráficos
let graficoComparativo = null;
let graficoIngresosOrigen = null;
let graficoEgresosOrigen = null;

function inicializarFechas() {
    // Establecer fecha fin como hoy
    const hoy = new Date();
    const fechaFin = hoy.toISOString().split('T')[0];
    $("#fechaFin").val(fechaFin);

    // Establecer fecha inicio como hace 6 meses
    const seisMesesAtras = new Date();
    seisMesesAtras.setMonth(hoy.getMonth() - 6);
    const fechaInicio = seisMesesAtras.toISOString().split('T')[0];
    $("#fechaInicio").val(fechaInicio);
}

function cargarCuentas() {
    var datos = new FormData();
    datos.append('accion', 'obtenerCuentas');
    
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
                const datos = typeof respuesta === 'string' ? JSON.parse(respuesta) : respuesta;
                
                if (!datos.error) {
                    const select = $("#cuenta");
                    select.empty(); // Limpiar opciones existentes
                    select.append(new Option("Todas las cuentas", ""));
                    
                    if (datos.cuentas && datos.cuentas.length > 0) {
                        datos.cuentas.forEach(cuenta => {
                            select.append(new Option(cuenta.nombre, cuenta.id));
                        });
                    }
                } else {
                    console.error("Error al cargar cuentas:", datos.mensaje);
                    Swal.fire({
                        title: "Error",
                        text: datos.mensaje || "Error al cargar las cuentas",
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error("Error al procesar la respuesta de cuentas:", e);
                Swal.fire({
                    title: "Error",
                    text: "Error al procesar la respuesta del servidor",
                    icon: "error"
                });
            }
        },
        error: function(request, status, err) {
            console.error("Error en la petición AJAX de cuentas:", err);
            Swal.fire({
                title: "Error",
                text: "Error al cargar las cuentas: " + err,
                icon: "error"
            });
        },
        complete: function() {
            $("#loader").hide();
        }
    });
}

function cargarDatos() {
    var datos = new FormData();
    datos.append('accion', 'obtenerDatos');
    datos.append('cuenta', $("#cuenta").val());
    datos.append('fechaInicio', $("#fechaInicio").val());
    datos.append('fechaFin', $("#fechaFin").val());
    
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
                const datos = typeof respuesta === 'string' ? JSON.parse(respuesta) : respuesta;
                
                if (datos.error) {
                    Swal.fire({
                        title: "Error",
                        text: datos.mensaje || "Error al cargar los datos",
                        icon: "error"
                    });
                    return;
                }
                
                // Actualizar totales
                $("#totalIngresos").text("Bs. " + formatearNumero(datos.totalIngresos || 0));
                $("#totalEgresos").text("Bs. " + formatearNumero(datos.totalEgresos || 0));
                
                // Calcular y actualizar ganancias
                const totalGanancias = (datos.totalIngresos || 0) - (datos.totalEgresos || 0);
                $("#totalGanancias").text("Bs. " + formatearNumero(totalGanancias));

                // Actualizar gráficos
                actualizarGraficoComparativo(datos.ingresosPorMes || [], datos.egresosPorMes || []);
                actualizarGraficoIngresosOrigen(datos.ingresosPorOrigen || []);
                actualizarGraficoEgresosOrigen(datos.egresosPorOrigen || []);
                
                // Actualizar tablas
                actualizarUltimosIngresos(datos.ultimosIngresos || []);
                actualizarUltimosEgresos(datos.ultimosEgresos || []);
                
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

function actualizarGraficoComparativo(ingresos, egresos) {
    const ctx = document.getElementById('graficoComparativo').getContext('2d');
    
    // Destruir gráfico existente si existe
    if (graficoComparativo) {
        graficoComparativo.destroy();
    }
    
    // Preparar datos para el gráfico
    const meses = ingresos.map(item => item.mes);
    const valoresIngresos = ingresos.map(item => item.total);
    // Asegurarse de que egresos tenga la misma longitud que ingresos para la alineación de datos
    const valoresEgresos = meses.map(mes => {
        const egresoMes = egresos.find(item => item.mes === mes);
        return egresoMes ? egresoMes.total : 0;
    });
    
    graficoComparativo = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Ingresos',
                    data: valoresIngresos,
                    backgroundColor: 'rgb(75, 192, 192)', // Color para ingresos
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                },
                {
                    label: 'Egresos',
                    data: valoresEgresos,
                    backgroundColor: 'rgb(255, 99, 132)', // Color para egresos
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1
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
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
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

function actualizarGraficoIngresosOrigen(datos) {
    const ctx = document.getElementById('graficoIngresosOrigen').getContext('2d');
    
    // Destruir gráfico existente si existe
    if (graficoIngresosOrigen) {
        graficoIngresosOrigen.destroy();
    }
    
    // Verificar si hay datos
    if (!datos || datos.length === 0) {
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
    
    graficoIngresosOrigen = new Chart(ctx, {
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

function actualizarGraficoEgresosOrigen(datos) {
    const ctx = document.getElementById('graficoEgresosCategoria').getContext('2d');
    
    // Destruir gráfico existente si existe
    if (graficoEgresosOrigen) {
        graficoEgresosOrigen.destroy();
    }
    
    // Verificar si hay datos
    if (!datos || datos.length === 0) {
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
    
    graficoEgresosOrigen = new Chart(ctx, {
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

function aplicarFiltros() {
    cargarDatos();
}

function formatearNumero(numero) {
    return new Intl.NumberFormat('es-VE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(numero);
} 