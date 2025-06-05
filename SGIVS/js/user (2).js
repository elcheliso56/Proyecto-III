function consultar() {
    // Crea un nuevo objeto FormData y agrega la acción 'consultar'
	var datos = new FormData();
	datos.append('accion', 'consultar');
	enviaAjax(datos);	
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye si es así
	if ($.fn.DataTable.isDataTable("#tablacliente")) {
        $("#tablacliente").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablacliente")) {
        $("#tablacliente").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron pacientes",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay pacientes registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar paciente...",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            pageLength: 5, // Establece el número de registros por página a 5
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], // Opciones de número de registros por página
            autoWidth: false,
            scrollX: true,
            scrollCollapse: true,
            fixedHeader: false,
            order: [[0, "asc"]],
            responsive: true,
        });
    }
    $(window).resize(function() {
        $('#tablacliente').DataTable().columns.adjust().draw();
    });
}

$(document).ready(function() {
    // Llama a la función consultar al cargar el documento
	consultar();
    // Validaciones para el campo de name
    $("#user").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#user").on("keyup", function() {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,10}$/, $(this), $("#suser"), "Solo letras entre 5 y 10 caracteres");
    });
    // Validaciones para el campo de password
    $("#name").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });	
    $("#name").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{2,30}$/, $(this), $("#sname"), "Solo letras entre 2 y 30 caracteres");
    });
    // Validaciones para el campo de contraseña
    $("#password").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]*$/, e);
    });
    $("#password").on("keyup", function() {
        validarkeyup(
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]).{8,15}$/,
            $(this),
            $("#spassword"),
            "Debe tener entre 8 y 15 caracteres, incluir mayúscula, minúscula, número y símbolo"
        );
        // Compara las contraseñas si el campo de confirmación existe
        if ($("#password2").length) {
            compararPasswords();
        }
    });
    $("#password2").on("keyup", function() {
        compararPasswords();
    });
    function compararPasswords() {
        const pass1 = $("#password").val();
        const pass2 = $("#password2").val();
        if (pass1 !== pass2) {
            $("#spassword2").text("Las contraseñas no coinciden");
        } else {
            $("#spassword2").text("");
        }
    }
    // Manejo de clic en el botón de proceso
    $("#proceso").on("click", function() {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo paciente
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo paciente?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, incluir",
                    cancelButtonText: "No, Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (validarenvio()) {
                            var datos = new FormData();
                            datos.append('accion', 'incluir');
                            datos.append('user', $("#user").val());
                            datos.append('name', $("#name").val());
                            datos.append('password', $("#password").val());
                            datos.append('id_rol_user', $("#id_rol_user").val());
                            datos.append('status', $("#status").val());
                            enviaAjax(datos);
                        }
                    }
                });
            }
        }
        else if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
            // Confirmación para modificar un paciente existente
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas modificar la información de este paciente?",
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
                            datos.append('user', $("#user").val());
                            datos.append('name', $("#name").val());
                            datos.append('password', $("#password").val());
                            datos.append('id_rol_user', $("#id_rol_user").val());
                            datos.append('status', $("#status").val());     
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La información de este paciente no ha sido modificada",
                            icon: "error"
                        });
                    }
                });
            }
        }
        // Manejo de eliminación de un paciente
        if ($(this).text() == "ELIMINAR") {
            var validacion;
            validacion = validarkeyup(
                /^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,10}$/,
                $("#user"),
                $("#suser"),
                "Solo letras entre 5 y 10 caracteres"
            );
            if (validacion == 0) {
                muestraMensaje("El documento debe coincidir con el formato solicitado <br/>" + "99999999");
            } else {
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
                        datos.append('user', $("#user").val());
                        enviaAjax(datos);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "paciente no eliminado",
                            icon: "error"
                        });
                    }
                });
            }
        }
    });
    // Manejo del clic en el botón incluir
    $("#incluir").on("click", function() {
        limpia(); // Limpia los campos
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón
        $("#modal1").modal("show"); // Muestra el modal
    });	
});
// Función para validar el envío de datos
function validarenvio() {
    // Validaciones para el campo de cédula
    if ($("#user").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "la user del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    // Validaciones para name
    if ($("#name").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El name del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    // Validaciones para el formato de password
    if ($("#password").val() !== $("#password2").val()) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Las contraseñas no coinciden",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }
    // Validaciones para el fecha de nacimiento
    if ($("#id_rol_user").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El rol del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }   
    //Validaciones para el género
    if ($("#status").val() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El status del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    return true; // Si todas las validaciones pasan, retorna verdadero
}

function validarkeypress(er, e) {
    // Función para validar la tecla presionada
	key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault(); // Previene la acción si no coincide con la expresión regular
    }    
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    // Función para validar el valor al soltar la tecla
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text(""); // Limpia el mensaje de error
        return 1; // Retorna 1 si es válido
    } else {
        etiquetamensaje.text(mensaje); // Muestra el mensaje de error
        return 0; // Retorna 0 si no es válido
    }
}

function pone(pos, accion) {
    // Función para llenar el formulario con los datos del usuario seleccionado
    let linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#user").prop("disabled", true);
        $("#name").prop("disabled", false);
        $("#password").prop("disabled", false);
        $("#id_rol_user").prop("disabled", false);
        $("#status").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#user").prop("disabled", true);
        $("#name").prop("disabled", true);
        $("#password").prop("disabled", true);
        $("#id_rol_user").prop("disabled", true);
        $("#status").prop("disabled", true);
    }
    // Llena los campos del formulario con los datos de la fila seleccionada
    $("#user").val($(linea).find("td:eq(1)").text().trim());
    $("#name").val($(linea).find("td:eq(2)").text());
    $("#password").val($(linea).find("td:eq(3)").text());
    const rolNombre = $(linea).find("td:eq(4)").text().trim();
    let rolValor = "";
    if (rolNombre === "Administrador") rolValor = "1";
    else if (rolNombre === "Recepcionista") rolValor = "2";
    else if (rolNombre === "Doctor") rolValor = "3";
    $("#id_rol_user").val(rolValor);
    const statusNombre = $(linea).find("td:eq(5)").text().trim();
    let statusValor = "";
    if (statusNombre === "Activo") statusValor = "0";
    else if (statusNombre === "Inactivo") statusValor = "1";
    $("#status").val(statusValor);
    $("#modal1").modal("show"); // Muestra el modal
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
            $("#loader").show(); // Mostrar loader
        },
        timeout: 10000, 
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar") {
                    destruyeDT();	
                    $("#resultadoconsulta").html(lee.mensaje);
                    crearDT();
                }
                else if (lee.resultado == "incluir") {
                    if (lee.mensaje == '¡Registro guardado con exito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "El paciente ha sido incluido con éxito.",
                            icon: "success"
                        });
                        $("#modal1").modal("hide");
                        consultar();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: lee.mensaje,
                            icon: "error"
                        });
                    }
                }
                else if (lee.resultado == "modificar") {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Modificado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje.includes('éxito') ? "success" : "error"
                    });
                    if(lee.mensaje.includes('éxito')){
                        $("#modal1").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == "eliminar") {
                    Swal.fire({
                        title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
                    });
                    if(lee.mensaje == '¡Registro eliminado con exito!'){
                        $("#modal1").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == "error") {
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
            $("#loader").hide(); // Ocultar loader al completar
        }
    });
}

function limpia() {
    // Función para limpiar los campos del formulario
    $("#user").val("");
    $("#name").val("");
    $("#password").val("");
    $("#id_rol_user").val("");
    $("#status").val("");
    // Habilita los campos del formulario
    $("#user").prop("disabled", false); 
    $("#name").prop("disabled", false);   
    $("#password").prop("disabled", false); 
    $("#id_rol_user").prop("disabled", false);
    $("#status").prop("disabled", false);
}          