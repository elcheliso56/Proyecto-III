$(document).ready(function() {
    // Inicializar la tabla de bitácora
    crearDT();
    consultar();

    // Función para mostrar/ocultar el loader
    function toggleLoader(mostrar) {
        if (mostrar) {
            $("#loader").show();
        } else {
            $("#loader").hide();
        }
    }

    // Función para consultar los registros de la bitácora
    function consultar() {
        toggleLoader(true);
        $.ajax({
            type: "POST",
            url: "?pagina=bitacora",
            data: {
                accion: "consultar"
            },
            dataType: "json",
            success: function(r) {
                console.log("Respuesta del servidor:", r);
                if (r && r.resultado === "consultar") {
                    destruyeDT();
                    $("#resultadoconsulta").html(r.mensaje || '');
                    crearDT();
                } else {
                    console.error("Error en la respuesta:", r);
                    alert(r && r.mensaje ? r.mensaje : "Error al consultar la bitácora");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX:", {xhr: xhr, status: status, error: error});
                alert("Error al consultar la bitácora: " + error);
            },
            complete: function() {
                toggleLoader(false);
            }
        });
    }

    // Función para destruir la tabla DataTable
    function destruyeDT() {
        if ($.fn.DataTable.isDataTable("#tablaBitacora")) {
            $("#tablaBitacora").DataTable().destroy();
        }
    }

    // Función para crear la tabla DataTable
    function crearDT() {
        if (!$.fn.DataTable.isDataTable("#tablaBitacora")) {
            $("#tablaBitacora").DataTable({
                language: {
                    lengthMenu: "Mostrar _MENU_ por página",
                    zeroRecords: "No se encontraron registros",
                    info: "Página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "<i class='bi bi-search'></i>",
                    searchPlaceholder: "Buscar en bitácora...",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior",
                    },
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true,
                fixedHeader: false,
                order: [[1, "desc"], [2, "desc"]], // Ordenar por fecha y hora descendente
                responsive: true,
            });
        }
        $(window).resize(function() {
            $('#tablaBitacora').DataTable().columns.adjust().draw();
        });
    }

    // Evento para generar reporte PDF
    $("#generar_reporte").click(function() {
        toggleLoader(true);
        $.ajax({
            type: "POST",
            url: "?pagina=bitacora",
            data: {
                accion: "generar_reporte"
            },
            dataType: "json",
            success: function(r) {
                console.log("Respuesta del reporte:", r);
                if (r && r.resultado === "reporte") {
                    // Crear una nueva ventana para el reporte
                    var ventana = window.open("", "_blank");
                    ventana.document.write(r.mensaje || '');
                    ventana.document.close();
                    // Esperar a que se cargue el contenido
                    ventana.onload = function() {
                        ventana.print();
                    };
                } else {
                    console.error("Error en la respuesta del reporte:", r);
                    alert(r && r.mensaje ? r.mensaje : "Error al generar el reporte");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX en reporte:", {xhr: xhr, status: status, error: error});
                alert("Error al generar el reporte: " + error);
            },
            complete: function() {
                toggleLoader(false);
            }
        });
    });
}); 