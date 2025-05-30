function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablacuentas")) {
        $("#tablacuentas").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablacuentas")) {
        $("#tablacuentas").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron cuentas",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay cuentas registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar cuenta...",
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

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombre"), "Solo letras y números entre 3 y 50 caracteres");
    });

    // Manejo del tipo de cuenta
    $("#tipo").on("change", function() {
        var tipo = $(this).val();
        if (tipo === 'bancaria') {
            $("#entidad_bancaria_group").show();
            $("#numero_cuenta_group").show();
        } else {
            $("#entidad_bancaria_group").hide();
            $("#numero_cuenta_group").hide();
            $("#entidad_bancaria").val("");
            $("#numero_cuenta").val("");
        }
    });

    // Validaciones para entidad bancaria
    $("#entidad_bancaria").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#entidad_bancaria").on("keyup", function () {
        validarkeyup(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#sentidad_bancaria"), "Solo letras entre 3 y 50 caracteres");
    });

    // Validaciones para número de cuenta
    $("#numero_cuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    $("#numero_cuenta").on("keyup", function () {
        validarkeyup(/^[0-9]{10,20}$/, $(this), $("#snumero_cuenta"), "Solo números entre 10 y 20 dígitos");
    });

    // Validaciones para moneda
    $("#moneda").on("change", function () {
        if ($(this).val() === null) {
            $("#smoneda").text("La moneda es obligatoria");
        } else {
            $("#smoneda").text("");
        }
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar esta cuenta?",
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
                            datos.append('nombre', $("#nombre").val());
                            datos.append('tipo', $("#tipo").val());
                            datos.append('moneda', $("#moneda").val());
                            datos.append('activa', $("#activa").val());
                            datos.append('entidad_bancaria', $("#entidad_bancaria").val());
                            datos.append('numero_cuenta', $("#numero_cuenta").val());
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
                    text: "¿Deseas modificar esta cuenta?",
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
                            datos.append('nombre', $("#nombre").val());
                            datos.append('tipo', $("#tipo").val());
                            datos.append('moneda', $("#moneda").val());
                            datos.append('activa', $("#activa").val());
                            datos.append('entidad_bancaria', $("#entidad_bancaria").val());
                            datos.append('numero_cuenta', $("#numero_cuenta").val());
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La cuenta no ha sido modificada",
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
                        text: "Cuenta no eliminada",
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
    let valido = true;

    // Validar nombre
    if ($("#nombre").val() === "") {
        $("#snombre").text("El nombre es obligatorio");
        valido = false;
    }

    // Validar tipo
    if ($("#tipo").val() === null) {
        $("#stipo").text("El tipo de cuenta es obligatorio");
        valido = false;
    }

    // Validar moneda
    if ($("#moneda").val() === null) {
        $("#smoneda").text("La moneda es obligatoria");
        valido = false;
    }

    // Validar campos bancarios si el tipo es bancaria
    if ($("#tipo").val() === 'bancaria') {
        if ($("#entidad_bancaria").val() === "") {
            $("#sentidad_bancaria").text("La entidad bancaria es obligatoria");
            valido = false;
        }
        if ($("#numero_cuenta").val() === "") {
            $("#snumero_cuenta").text("El número de cuenta es obligatorio");
            valido = false;
        }
    }

    return valido;
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
        $("#nombre").prop("disabled", false);
        $("#tipo").prop("disabled", false);
        $("#moneda").prop("disabled", false);
        $("#activa").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#id").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#tipo").prop("disabled", true);
        $("#moneda").prop("disabled", true);
        $("#activa").prop("disabled", true);
    }

    $("#nombre").val($(linea).find("td:eq(1)").text());
    $("#tipo").val($(linea).find("td:eq(2)").text()).trigger('change');
    $("#moneda").val($(linea).find("td:eq(3)").text()).trigger('change');
    $("#activa").val($(linea).find("td:eq(4)").text() === "Activa" ? "1" : "0").trigger('change');
    $("#entidad_bancaria").val($(linea).find("td:eq(5)").text());
    $("#numero_cuenta").val($(linea).find("td:eq(6)").text());
    
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
    $("#nombre").val("");
    $("#tipo").val("").trigger('change');
    $("#moneda").val("");
    $("#activa").val("1");
    $("#entidad_bancaria").val("");
    $("#numero_cuenta").val("");

    $("#nombre").prop("disabled", false);
    $("#tipo").prop("disabled", false);
    $("#moneda").prop("disabled", false);
    $("#activa").prop("disabled", false);
} 