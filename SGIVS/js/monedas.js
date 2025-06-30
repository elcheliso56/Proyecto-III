function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablamonedas")) {
        $("#tablamonedas").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablamonedas")) {
        $("#tablamonedas").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron monedas",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay monedas registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar moneda...",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            autoWidth: false,
            scrollX: true,
            fixedHeader: false,
            order: [[0, "asc"]],
        });
    }
}

$(document).ready(function () {
    consultar();

    // Validaciones para el campo de código
    $("#codigo").on("keypress", function (e) {
        validarkeypress(/^[A-Z]*$/, e);
    });

    $("#codigo").on("keyup", function () {
        validarkeyup(/^[A-Z]{3}$/, $(this), $("#scodigo"), "Solo letras mayúsculas, exactamente 3 caracteres");
    });

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombre"), "Solo letras entre 3 y 50 caracteres");
    });

    // Validaciones para el campo de símbolo
    $("#simbolo").on("keyup", function () {
        validarkeyup(/^.{0,5}$/, $(this), $("#ssimbolo"), "Máximo 5 caracteres");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar esta moneda?",
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
                            datos.append('codigo', $("#codigo").val());
                            datos.append('nombre', $("#nombre").val());
                            datos.append('simbolo', $("#simbolo").val());
                            datos.append('activa', $("#activa").val());
                            datos.append('es_principal', $("#es_principal").val());
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
                    text: "¿Deseas modificar esta moneda?",
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
                            datos.append('codigo', $("#codigo").val());
                            datos.append('nombre', $("#nombre").val());
                            datos.append('simbolo', $("#simbolo").val());
                            datos.append('activa', $("#activa").val());
                            datos.append('es_principal', $("#es_principal").val());
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La moneda no ha sido modificada",
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
                        text: "Moneda no eliminada",
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

function validarenvio() {
    var respuesta = true;
    var codigo = $("#codigo").val();
    var nombre = $("#nombre").val();

    if (codigo == null || codigo == 0 || codigo.trim() == "") {
        $("#scodigo").text("El código es obligatorio");
        respuesta = false;
    } else {
        $("#scodigo").text("");
    }

    if (nombre == null || nombre == 0 || nombre.trim() == "") {
        $("#snombre").text("El nombre es obligatorio");
        respuesta = false;
    } else {
        $("#snombre").text("");
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
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#simbolo").prop("disabled", true);
        $("#activa").prop("disabled", false);
        $("#es_principal").prop("disabled", true);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#simbolo").prop("disabled", true);
        $("#activa").prop("disabled", true);
        $("#es_principal").prop("disabled", true);
    }

    $("#codigo").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#simbolo").val($(linea).find("td:eq(3)").text());
    $("#activa").val($(linea).find("td:eq(4)").text() === "Activa" ? "1" : "0").trigger('change');
    $("#es_principal").val($(linea).find("td:eq(5)").text() === "Principal" ? "1" : "0").trigger('change');
    
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
    $("#codigo").val("");
    $("#nombre").val("");
    $("#simbolo").val("");
    $("#activa").val("1");
    $("#es_principal").val("0");
    
    // Limpiar mensajes de error
    $("#scodigo").text("");
    $("#snombre").text("");
    $("#ssimbolo").text("");
    
    // Limpiar clases de validación
    $("#codigo, #nombre, #simbolo").removeClass("border border-danger border-success");
    
    // Habilitar todos los campos
    $("#codigo").prop("disabled", false);
    $("#nombre").prop("disabled", false);
    $("#simbolo").prop("disabled", false);
    $("#activa").prop("disabled", false);
    $("#es_principal").prop("disabled", false);
} 