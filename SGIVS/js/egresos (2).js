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
        validarkeypress(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#descripcion").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]{3,90}$/, $(this), $("#sdescripcion"), "Solo letras y números entre 3 y 90 caracteres");
    });

    // Validaciones para el campo de monto
    $("#monto").on("keypress", function (e) {
        validarkeypress(/^[0-9.]*$/, e);
    });

    $("#monto").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,9}$/, $(this), $("#smonto"), "Solo números, máximo 9 dígitos");
    });

    // Validaciones para el campo de fecha
    $("#fecha").on("change", function () {
        if ($(this).val() === "") {
            $("#sfecha").text("La fecha es obligatoria").addClass("text-danger small");
        } else {
            $("#sfecha").text("").removeClass("text-danger small");
        }
    });

    // Validaciones para el campo de origen
    $("#origen").on("change", function () {
        if ($(this).val() === null) {
            $("#sorigen").text("El origen es obligatorio").addClass("text-danger small");
        } else {
            $("#sorigen").text("").removeClass("text-danger small");
        }
    });

    // Validaciones para el campo de cuenta
    $("#cuenta_id").on("change", function () {
        if ($(this).val() === null) {
            $("#scuenta_id").text("La cuenta es obligatoria").addClass("text-danger small");
        } else {
            $("#scuenta_id").text("").removeClass("text-danger small");
        }
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar este egreso?",
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
                            datos.append('cuenta_id', $("#cuenta_id").val());
                            enviaAjax(datos);
                        }
                    }
                });
            }
        } else if ($(this).text() == "MODIFICAR") {
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
                            datos.append('fecha', $("#fecha").val());
                            datos.append('origen', $("#origen").val());
                            datos.append('cuenta_id', $("#cuenta_id").val());
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "El egreso no ha sido modificado",
                            icon: "error"
                        });
                    }
                });
            }
        } else if ($(this).text() == "ELIMINAR") {
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
                        text: "Egreso no eliminado",
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

    // Validar descripción
    if ($("#descripcion").val() === "") {
        $("#sdescripcion").text("La descripción es obligatoria").addClass("text-danger small");
        valido = false;
    }

    // Validar monto
    if ($("#monto").val() === "") {
        $("#smonto").text("El monto es obligatorio").addClass("text-danger small");
        valido = false;
    }

    // Validar fecha
    if ($("#fecha").val() === "") {
        $("#sfecha").text("La fecha es obligatoria").addClass("text-danger small");
        valido = false;
    }

    // Validar origen
    if ($("#origen").val() === null) {
        $("#sorigen").text("El origen es obligatorio").addClass("text-danger small");
        valido = false;
    }

    // Validar cuenta
    if ($("#cuenta_id").val() === null) {
        $("#scuenta_id").text("La cuenta es obligatoria").addClass("text-danger small");
        valido = false;
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
        etiquetamensaje.text("").removeClass("text-danger small");
        return 1;
    } else {
        etiquetamensaje.text(mensaje).addClass("text-danger small");
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
        $("#cuenta_id").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#id").prop("disabled", true);
        $("#descripcion").prop("disabled", true);
        $("#monto").prop("disabled", true);
        $("#fecha").prop("disabled", true);
        $("#origen").prop("disabled", true);
        $("#cuenta_id").prop("disabled", true);
    }

    $("#descripcion").val($(linea).find("td:eq(1)").text());
    $("#monto").val($(linea).find("td:eq(2)").text().replace(/[^0-9.]/g, ''));
    $("#fecha").val($(linea).find("td:eq(3)").text());
    $("#origen").val($(linea).find("td:eq(4)").text()).trigger('change');
    
    // Get the cuenta_id from the data attribute of the 6th cell (index 5)
    var cuentaId = $(linea).find("td:eq(5)").attr("data-cuenta-id");
    $("#cuenta_id").val(cuentaId).trigger('change');
    
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
    $("#origen").val("").trigger('change');
    $("#cuenta_id").val("").trigger('change');

    $("#descripcion").prop("disabled", false);
    $("#monto").prop("disabled", false);
    $("#fecha").prop("disabled", false);
    $("#origen").prop("disabled", false);
    $("#cuenta_id").prop("disabled", false);
}

function cargarOpciones() {
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'cargarOpciones' },
        success: function(respuesta) {
            try {
                var datos = JSON.parse(respuesta);
                console.log("Datos recibidos:", datos);
                
                // Limpiar el select antes de agregar las opciones
                $('#cuenta_id').empty();
                
                // Agregar la opción por defecto
                $('#cuenta_id').append($('<option>', {
                    value: '',
                    text: 'Seleccione una cuenta',
                    selected: true,
                    disabled: true
                }));
                
                // Verificar si hay cuentas y agregarlas al select
                if (datos.cuentas && datos.cuentas.length > 0) {
                    datos.cuentas.forEach(function(cuenta) {
                        $('#cuenta_id').append($('<option>', {
                            value: cuenta.id,
                            text: cuenta.nombre + ' (' + cuenta.tipo + ' - ' + cuenta.moneda + ')'
                        }));
                    });
                    
                    // Inicializar Select2 después de agregar las opciones
                    $('#cuenta_id').select2({
                        placeholder: "Seleccione una cuenta",
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
                } else {
                    console.log("No hay cuentas disponibles");
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
                console.error("Respuesta recibida:", respuesta);
            }
        },
        error: function(request, status, err) {
            console.error("Error en la petición AJAX:", {
                status: status,
                error: err,
                request: request
            });
        }
    });
}

// Reinicializar Select2 cuando se abra el modal
$('#modal1').on('shown.bs.modal', function () {
    $('.select2').select2({
        dropdownParent: $('#modal1')
    });
});