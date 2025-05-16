function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablaegresos")) {
        $("#tablaegresos").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablaegresos")) {
        $("#tablaegresos").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron egresos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay egresos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar egreso...",
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
    cargarOpciones();

    // Validaciones para el campo de descripción
    $("#descripcion").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#descripcion").on("keyup", function () {
        validarkeyup(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]{3,90}$/, $(this), $("#sdescripcion"), "Solo letras entre 3 y 90 caracteres");
    });

    // Validaciones para el campo de monto
    $("#monto").on("keypress", function (e) {
        validarkeypress(/^[0-9.]*$/, e);
    });

    $("#monto").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,9}$/, $(this), $("#smonto"), "Solo números, máximo 9 dígitos");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar este nuevo egreso?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, registrar",
                    cancelButtonText: "No, Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (validarenvio()) {
                            var datos = new FormData();
                            datos.append('accion', 'incluir');
                            datos.append('descripcion', $("#descripcion").val());
                            datos.append('monto', $("#monto").val());
                            datos.append('fecha', $("#fecha").val());
                            datos.append('origen', $("#origen").val());
                            enviaAjax(datos);
                        }
                    }
                });
            }
        }
        else if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-danger",
                        cancelButton: "btn btn-secondary"
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas modificar este egreso?",
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
                            datos.append('descripcion', $("#descripcion").val());
                            datos.append('monto', $("#monto").val());
                            datos.append('origen', $("#origen").val());
                            datos.append('fecha', $("#fecha").val());
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "El registro no ha sido modificado",
                            icon: "error"
                        });
                    }
                });
            }
        }
        else if ($(this).text() == "ELIMINAR") {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary"
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
                        text: "Registro no eliminado",
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

    // Inicializar Select2 en el select de origen
    $('#origen').select2({
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
    return true;
}

function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

function pone(pos, accion) {
    linea = $(pos).closest('tr');
    $("#id").val($(linea).attr("data-id"));
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#id").prop("disabled", true);
        $("#descripcion").prop("disabled", false);
        $("#monto").prop("disabled", false);
        $("#fecha").prop("disabled", false);       
        $("#origen").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#id").prop("disabled", true);
        $("#descripcion").prop("disabled", true);
        $("#monto").prop("disabled", true);
        $("#fecha").prop("disabled", true);
        $("#origen").prop("disabled", true);
    }

    $("#descripcion").val($(linea).find("td:eq(1)").text());
    $("#monto").val($(linea).find("td:eq(2)").text().replace(/\D/g, ''));
    $("#origen").val($(linea).find("td:eq(4)").text()).trigger('change');
    $("#fecha").val($(linea).find("td:eq(3)").text());
    
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
            console.log("Respuesta cruda del servidor:", respuesta);

            try {
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
                        title: lee.mensaje == '¡Registro eliminado con éxito!' ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro eliminado con éxito!' ? "success" : "error"
                    });
                    if (lee.mensaje == '¡Registro eliminado con éxito!') {
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
                Swal.fire({
                    title: "Error",
                    text: "Error en JSON: " + e.name,
                    icon: "error"
                });
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                Swal.fire({
                    title: "Error",
                    text: "Servidor ocupado, intente de nuevo",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "ERROR: " + request + status + err,
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
    $("#descripcion").val("");
    $("#monto").val("");
    $("#fecha").val("");
    $("#origen").val("");

    $("#descripcion").prop("disabled", false);
    $("#monto").prop("disabled", false);
    $("#fecha").prop("disabled", false);
    $("#origen").prop("disabled", false);
}

function cargarOpciones() {
    // Función simplificada ya que no necesitamos cargar categorías ni ubicaciones
    $('#origen').select2({
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