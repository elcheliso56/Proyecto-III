function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function cargarMonedas() {
    var datos = new FormData();
    datos.append('accion', 'obtenerMonedasActivas');
    
    $.ajax({
        async: true,
        url: "?pagina=monedas",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function(respuesta) {
            try {
                var data = JSON.parse(respuesta);
                if (data && data.length > 0) {
                    // Buscar la moneda principal
                    var monedaPrincipal = null;
                    data.forEach(function(moneda) {
                        if (moneda.es_principal == 1) {
                            monedaPrincipal = moneda;
                        }
                    });

                    // Llenar select de moneda destino (excluyendo la principal)
                    var selectDestino = $("#moneda_destino");
                    selectDestino.empty();
                    selectDestino.append('<option value="" selected disabled>Seleccione moneda destino</option>');
                    data.forEach(function(moneda) {
                        if (!monedaPrincipal || moneda.id !== monedaPrincipal.id) {
                            var option = '<option value="' + moneda.id + '">' + moneda.codigo + ' - ' + moneda.nombre + '</option>';
                            selectDestino.append(option);
                        }
                    });

                    // Llenar filtros normalmente
                    var filtroOrigen = $("#filtro_moneda_origen");
                    filtroOrigen.empty();
                    filtroOrigen.append('<option value="">Todas las monedas origen</option>');
                    var filtroDestino = $("#filtro_moneda_destino");
                    filtroDestino.empty();
                    filtroDestino.append('<option value="">Todas las monedas destino</option>');
                    data.forEach(function(moneda) {
                        var option = '<option value="' + moneda.id + '">' + moneda.codigo + ' - ' + moneda.nombre + '</option>';
                        filtroOrigen.append(option);
                        filtroDestino.append(option);
                    });

                    // Establecer la moneda principal automáticamente
                    if (monedaPrincipal) {
                        $("#moneda_origen").val(monedaPrincipal.id);
                        $("#moneda_origen_display").val(monedaPrincipal.codigo + ' - ' + monedaPrincipal.nombre);
                    }

                    // Reinicializar Select2
                    $('.select2').select2({
                        placeholder: "Seleccione una opción",
                        allowClear: true,
                        width: '100%',
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                }
            } catch (e) {
                console.error("Error al cargar monedas:", e);
            }
        },
        error: function() {
            console.error("Error de conexión al cargar monedas");
        }
    });
}

function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablatiposcambio")) {
        $("#tablatiposcambio").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablatiposcambio")) {
        $("#tablatiposcambio").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron tipos de cambio",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay tipos de cambio registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar tipo de cambio...",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            autoWidth: false,
            scrollX: true,
            fixedHeader: false,
            order: [[4, "desc"]], // Ordenar por fecha descendente
        });
    }
}

$(document).ready(function () {
    cargarMonedas();
    consultar();

    // Validaciones para moneda destino
    $("#moneda_destino").on("change", function () {
        if ($(this).val() === null) {
            $("#smoneda_destino").text("La moneda destino es obligatoria");
        } else {
            $("#smoneda_destino").text("");
        }
    });

    // Validaciones para tipo de cambio
    $("#tipo_cambio").on("keypress", function (e) {
        validarkeypress(/^[0-9.]*$/, e);
    });

    $("#tipo_cambio").on("keyup", function () {
        validarkeyup(/^[0-9]+(\.[0-9]{1,4})?$/, $(this), $("#stipo_cambio"), "Solo números con máximo 4 decimales");
    });

    // Validaciones para fecha
    $("#fecha").on("change", function () {
        if ($(this).val() === "") {
            $("#sfecha").text("La fecha es obligatoria");
        } else {
            $("#sfecha").text("");
        }
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar este tipo de cambio?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, registrar",
                    cancelButtonText: "No, Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (validarenvio()) {
                            var datos = new FormData();
                            datos.append('accion', 'incluir');
                            datos.append('moneda_origen', $("#moneda_origen").val());
                            datos.append('moneda_destino', $("#moneda_destino").val());
                            datos.append('tipo_cambio', $("#tipo_cambio").val());
                            datos.append('fecha', $("#fecha").val());
                            enviaAjax(datos);
                        }
                    }
                });
            }
        } else if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas modificar este tipo de cambio?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Sí, modificar",
                    cancelButtonText: "No, cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (validarenvio()) {
                            var datos = new FormData();
                            datos.append('accion', 'modificar');
                            datos.append('id', $("#id").val());
                            datos.append('moneda_origen', $("#moneda_origen").val());
                            datos.append('moneda_destino', $("#moneda_destino").val());
                            datos.append('tipo_cambio', $("#tipo_cambio").val());
                            datos.append('fecha', $("#fecha").val());
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "El tipo de cambio no ha sido modificado",
                            icon: "error"
                        });
                    }
                });
            }
        } else if ($(this).text() == "ELIMINAR") {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro?",
                text: "No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar!",
                cancelButtonText: "No, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var datos = new FormData();
                    datos.append('accion', 'eliminar');
                    datos.append('id', $("#id").val());
                    enviaAjax(datos);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "Tipo de cambio no eliminado",
                        icon: "error"
                    });
                }
            });
        }
    });

    // Manejo del clic en el botón incluir
    $("#incluir").on("click", function () {
        limpia();
        $("#proceso").text("INCLUIR");
        $("#modal1").modal("show");
    });

    // Aplicar filtros
    $("#aplicar_filtros").on("click", function() {
        aplicarFiltros();
    });

    // Limpiar filtros
    $("#limpiar_filtros").on("click", function() {
        $("#filtro_fecha").val(new Date().toISOString().split('T')[0]);
        $("#filtro_moneda_origen").val("").trigger('change');
        $("#filtro_moneda_destino").val("").trigger('change');
        consultar();
    });

    // Inicializar Select2 en los selects
    $('.select2').select2({
        placeholder: "Seleccione una opción",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "No se encontraron resultados";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
});

function aplicarFiltros() {
    var fecha = $("#filtro_fecha").val();
    var monedaOrigen = $("#filtro_moneda_origen").val();
    var monedaDestino = $("#filtro_moneda_destino").val();
    
    var datos = new FormData();
    datos.append('accion', 'consultar');
    if (fecha) datos.append('fecha', fecha);
    if (monedaOrigen) datos.append('moneda_origen', monedaOrigen);
    if (monedaDestino) datos.append('moneda_destino', monedaDestino);
    
    enviaAjax(datos);
}

function validarenvio() {
    var respuesta = true;
    var moneda_destino = $("#moneda_destino").val();
    var tipo_cambio = $("#tipo_cambio").val();
    var fecha = $("#fecha").val();

    if (moneda_destino == null || moneda_destino == "") {
        $("#smoneda_destino").text("La moneda destino es obligatoria");
        respuesta = false;
    } else {
        $("#smoneda_destino").text("");
    }

    if (tipo_cambio == null || tipo_cambio == 0 || tipo_cambio.trim() == "" || parseFloat(tipo_cambio) <= 0) {
        $("#stipo_cambio").text("El tipo de cambio debe ser mayor a 0");
        respuesta = false;
    } else {
        $("#stipo_cambio").text("");
    }

    if (fecha == null || fecha == "") {
        $("#sfecha").text("La fecha es obligatoria");
        respuesta = false;
    } else {
        $("#sfecha").text("");
    }

    return respuesta;
}

function validarkeypress(er, e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    a = er;
    tecla_code = tecla.charCodeAt(0);
    if (a == 8 || a == 32) return true;
    patron = new RegExp(a);
    if (patron.test(tecla)) {
        return true;
    } else {
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er;
    if (a.test(etiqueta.val())) {
        etiquetamensaje.text("");
        etiqueta.removeClass("border border-danger");
        etiqueta.addClass("border border-success");
        return true;
    } else {
        etiquetamensaje.text(mensaje);
        etiqueta.removeClass("border border-success");
        etiqueta.addClass("border border-danger");
        return false;
    }
}

function pone(pos, accion) {
    linea = $(pos).closest('tr');
    $("#id").val($(linea).attr("data-id"));
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        // No deshabilitar moneda_origen ya que es automática
        $("#moneda_destino").prop("disabled", true);
        $("#tipo_cambio").prop("disabled", false);
        $("#fecha").prop("disabled", true);
    } else {
        $("#proceso").text("ELIMINAR");
        // No deshabilitar moneda_origen ya que es automática
        $("#moneda_destino").prop("disabled", true);
        $("#tipo_cambio").prop("disabled", true);
        $("#fecha").prop("disabled", true);
    }

    // Obtener los valores de las celdas de la tabla
    var monedaOrigen = $(linea).find("td:eq(1)").text().split(" (")[0]; // Extraer solo el código
    var monedaDestino = $(linea).find("td:eq(2)").text().split(" (")[0]; // Extraer solo el código
    var tipoCambio = $(linea).find("td:eq(3)").text();
    var fecha = $(linea).find("td:eq(4)").text();

    // Establecer la moneda origen (siempre será la principal)
    $("#moneda_origen_display").val(monedaOrigen + " - " + $(linea).find("td:eq(1)").text().split(" (")[1].replace(")", ""));
    
    // Buscar el ID de la moneda destino por código
    $("#moneda_destino option").each(function() {
        if ($(this).text().includes(monedaDestino)) {
            $("#moneda_destino").val($(this).val()).trigger('change');
        }
    });
    
    $("#tipo_cambio").val(tipoCambio);
    $("#fecha").val(fecha);
    
    $("#modal1").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () {
            $("#loader").show();
        },
        timeout: 10000,
        success: function (respuesta) {
            try {
                // Verificar si la respuesta está vacía
                if (!respuesta || respuesta.trim() === '') {
                    throw new Error('La respuesta del servidor está vacía');
                }

                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar") {
                    destruyeDT();
                    $("#resultadoconsulta").html(lee.mensaje);
                    crearDT();
                } else if (lee.resultado == "incluir" || lee.resultado == "modificar") {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Éxito!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje.includes('éxito') ? "success" : "error"
                    });
                    if (lee.mensaje.includes('éxito')) {
                        $("#modal1").modal("hide");
                        consultar();
                    }
                } else if (lee.resultado == "eliminar") {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje.includes('éxito') ? "success" : "error"
                    });
                    if (lee.mensaje.includes('éxito')) {
                        $("#modal1").modal("hide");
                        consultar();
                    }
                } else if (lee.resultado == "error") {
                    Swal.fire({
                        title: "Error",
                        text: lee.mensaje,
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error('Error al procesar la respuesta:', e);
                console.error('Respuesta recibida:', respuesta);
                Swal.fire({
                    title: "Error",
                    text: "Error al procesar la respuesta del servidor: " + e.message,
                    icon: "error"
                });
            }
        },
        error: function (request, status, err) {
            console.error('Error en la petición AJAX:', {request, status, err});
            if (status == "timeout") {
                Swal.fire({
                    title: "Error",
                    text: "Servidor ocupado, intente de nuevo",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Error en la comunicación con el servidor: " + err,
                    icon: "error"
                });
            }
        },
        complete: function () {
            $("#loader").hide();
        }
    });
}

function limpia() {
    $("#id").val("");
    // No limpiar moneda_origen ya que es automática
    $("#moneda_destino").val("").trigger('change');
    $("#tipo_cambio").val("");
    // Siempre poner la fecha de hoy
    var hoy = new Date().toISOString().split('T')[0];
    $("#fecha").val(hoy);
    
    // Limpiar mensajes de error
    $("#smoneda_destino").text("");
    $("#stipo_cambio").text("");
    $("#sfecha").text("");
    
    // Limpiar clases de validación
    $("#moneda_destino, #tipo_cambio, #fecha").removeClass("border border-danger border-success");
    
    // Habilitar todos los campos
    $("#moneda_destino").prop("disabled", false);
    $("#tipo_cambio").prop("disabled", false);
    // El campo fecha queda readonly
} 