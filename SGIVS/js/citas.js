function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function cargarMedicos() {
    var datos = new FormData();
    datos.append('accion', 'cargarMedicos');
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            try {
                var datos = JSON.parse(respuesta);
                if (datos.medicos) {
                    // Limpiar selects
                    $('#id_medico, #mid_medico').empty();
                    
                    // Agregar opción por defecto
                    $('#id_medico, #mid_medico').append('<option value="" selected disabled>Seleccione un médico</option>');
                    
                    // Agregar médicos
                    datos.medicos.forEach(function(medico) {
                        $('#id_medico, #mid_medico').append(
                            $('<option></option>').val(medico.id_medico).text(medico.nombre_medico)
                        );
                    });
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
            }
        },
        error: function(request, status, err) {
            console.error("Error en la petición AJAX:", err);
        }
    });
}

function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablacitas")) {
        $("#tablacitas").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablacitas")) {
        $("#tablacitas").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron citas",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay citas registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar cita...",
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

$(document).ready(function() {
    consultar();
    cargarMedicos();

    // Validaciones para el campo de nombre
    $("#nombre_paciente, #mnombre_paciente").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre_paciente, #mnombre_paciente").on("keyup", function() {
        validarkeyup(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]{3,90}$/, $(this), $("#snombre_paciente"), "Solo letras entre 3 y 90 caracteres");
    });

    // Validaciones para el campo de teléfono
    $("#numero_contacto, #mnumero_contacto").on("keypress", function(e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    $("#numero_contacto, #mnumero_contacto").on("keyup", function() {
        validarkeyup(/^[0-9]{10,15}$/, $(this), $("#snumero_contacto"), "Solo números entre 10 y 15 dígitos");
    });

    // Validaciones para el campo de fecha
    $("#fecha_cita, #mfecha_cita").on("change", function() {
        if ($(this).val() === "") {
            $("#sfecha_cita").text("La fecha es obligatoria");
        } else {
            $("#sfecha_cita").text("");
        }
    });

    // Validaciones para el campo de hora
    $("#hora_cita, #mhora_cita").on("change", function() {
        if ($(this).val() === "") {
            $("#shora_cita").text("La hora es obligatoria");
        } else {
            $("#shora_cita").text("");
        }
    });

    // Validaciones para el campo de médico
    $("#id_medico, #mid_medico").on("change", function() {
        if ($(this).val() === null) {
            $("#sid_medico").text("El médico es obligatorio");
        } else {
            $("#sid_medico").text("");
        }
    });

    // Validaciones para el campo de motivo
    $("#motivo_cita, #mmotivo_cita").on("change", function() {
        if ($(this).val() === null) {
            $("#smotivo_cita").text("El motivo es obligatorio");
        } else {
            $("#smotivo_cita").text("");
        }
    });

    // Manejo del botón solicitar cita
    $("#solicitarCita").on("click", function() {
        if (validarenvio()) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas solicitar esta cita?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, solicitar",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    var datos = new FormData();
                    datos.append('accion', 'incluir');
                    datos.append('nombre_paciente', $("#nombre_paciente").val());
                    datos.append('numero_contacto', $("#numero_contacto").val());
                    datos.append('id_medico', $("#id_medico").val());
                    datos.append('fecha_cita', $("#fecha_cita").val());
                    datos.append('hora_cita', $("#hora_cita").val());
                    datos.append('motivo_cita', $("#motivo_cita").val());
                    datos.append('observaciones', $("#observaciones").val());
                    
                    enviaAjax(datos);
                }
            });
        }
    });

    // Manejo del botón proceso en el modal
    $("#proceso").on("click", function() {
        if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas modificar esta cita?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, modificar",
                    cancelButtonText: "No, cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var datos = new FormData();
                        datos.append('accion', 'modificar');
                        datos.append('id_cita', $("#id_cita").val());
                        datos.append('nombre_paciente', $("#mnombre_paciente").val());
                        datos.append('numero_contacto', $("#mnumero_contacto").val());
                        datos.append('id_medico', $("#mid_medico").val());
                        datos.append('fecha_cita', $("#mfecha_cita").val());
                        datos.append('hora_cita', $("#mhora_cita").val());
                        datos.append('motivo_cita', $("#mmotivo_cita").val());
                        datos.append('estado_cita', $("#mestado_cita").val());
                        datos.append('observaciones', $("#mobservaciones").val());
                        
                        enviaAjax(datos);
                    }
                });
            }
        } else if ($(this).text() == "ELIMINAR") {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    var datos = new FormData();
                    datos.append('accion', 'eliminar');
                    datos.append('id_cita', $("#id_cita").val());
                    enviaAjax(datos);
                }
            });
        }
    });
});

function validarenvio() {
    var valido = true;
    
    // Validar nombre
    if (!validarkeyup(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]{3,90}$/, $("#nombre_paciente"), $("#snombre_paciente"), "Solo letras entre 3 y 90 caracteres")) {
        valido = false;
    }
    
    // Validar teléfono
    if (!validarkeyup(/^[0-9]{10,15}$/, $("#numero_contacto"), $("#snumero_contacto"), "Solo números entre 10 y 15 dígitos")) {
        valido = false;
    }
    
    // Validar médico
    if ($("#id_medico").val() === null) {
        $("#sid_medico").text("El médico es obligatorio");
        valido = false;
    }
    
    // Validar fecha
    if ($("#fecha_cita").val() === "") {
        $("#sfecha_cita").text("La fecha es obligatoria");
        valido = false;
    }
    
    // Validar hora
    if ($("#hora_cita").val() === "") {
        $("#shora_cita").text("La hora es obligatoria");
        valido = false;
    }
    
    // Validar motivo
    if ($("#motivo_cita").val() === null) {
        $("#smotivo_cita").text("El motivo es obligatorio");
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
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

function pone(pos, accion) {
    linea = $(pos).closest('tr');
    $("#id_cita").val($(linea).attr("data-id"));
    
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#mnombre_paciente").prop("disabled", false);
        $("#mnumero_contacto").prop("disabled", false);
        $("#mid_medico").prop("disabled", false);
        $("#mfecha_cita").prop("disabled", false);
        $("#mhora_cita").prop("disabled", false);
        $("#mmotivo_cita").prop("disabled", false);
        $("#mestado_cita").prop("disabled", false);
        $("#mobservaciones").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#mnombre_paciente").prop("disabled", true);
        $("#mnumero_contacto").prop("disabled", true);
        $("#mid_medico").prop("disabled", true);
        $("#mfecha_cita").prop("disabled", true);
        $("#mhora_cita").prop("disabled", true);
        $("#mmotivo_cita").prop("disabled", true);
        $("#mestado_cita").prop("disabled", true);
        $("#mobservaciones").prop("disabled", true);
    }

    $("#mnombre_paciente").val($(linea).find("td:eq(1)").text());
    $("#mnumero_contacto").val($(linea).find("td:eq(2)").text());
    $("#mid_medico").val($(linea).find("td:eq(3)").attr("data-medico-id")).trigger('change');
    $("#mfecha_cita").val($(linea).find("td:eq(4)").text());
    $("#mhora_cita").val($(linea).find("td:eq(5)").text());
    $("#mmotivo_cita").val($(linea).find("td:eq(6)").text()).trigger('change');
    $("#mestado_cita").val($(linea).find("td:eq(7)").text().toLowerCase()).trigger('change');
    $("#mobservaciones").val($(linea).find("td:eq(8)").text());
    
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
        beforeSend: function() {
            $("#loader").show();
        },
        timeout: 10000,
        success: function(respuesta) {
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
                        // Limpiar formulario de solicitud
                        $("#formCita")[0].reset();
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
                Swal.fire({
                    title: "Error",
                    text: "Error en JSON: " + e.name,
                    icon: "error"
                });
            }
        },
        error: function(request, status, err) {
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
        complete: function() {
            $("#loader").hide();
        }
    });
} 