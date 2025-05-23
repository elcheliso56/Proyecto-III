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

function establecerFechaActual() {
    var fecha = new Date();
    var dia = fecha.getDate();
    var mes = fecha.getMonth() + 1;
    var anio = fecha.getFullYear();
    var fechaActual = anio + "-" + (mes < 10 ? "0" + mes : mes) + "-" + (dia < 10 ? "0" + dia : dia);
    $("#fecha_registro").val(fechaActual);
}

$(document).ready(function() {
    // Llama a la función consultar al cargar el documento
	consultar();
    // Validaciones para el campo de número de documento
    $("#cedula").on("keypress", function(e) {
        validarkeypress(/^[VEJ0-9-\b]*$/, e);
    });
    $("#cedula").on("keyup", function() {
        validarkeyup(/^[0-9]{7,8}$/, $(this), $("#scedula"), "El formato de CI debe ser 1234567 o 12345678");
    });
    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombre").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{2,30}$/, $(this), $("#snombre"), "Solo letras entre 2 y 30 caracteres");
    });
    // Validaciones para el campo de apellido
    $("#apellido").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });	
    $("#apellido").on("keyup", function() {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{2,30}$/, $(this), $("#sapellido"), "Solo letras entre 2 y 30 caracteres");
    });
    //Validaciones para el campo de fecha de nacimiento y coloca la edad y clasificación automática
    $("#fecha_nacimiento").on("change", function() {
        var fecha = $(this).val();
        var fechaActual = new Date();
        var fechaNacimiento = new Date(fecha);
        var anios = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        var meses = fechaActual.getMonth() - fechaNacimiento.getMonth();
        var dias = fechaActual.getDate() - fechaNacimiento.getDate();
        if (dias < 0) {
            meses--;
            dias += new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 0).getDate();
        }
        if (meses < 0) {
            anios--;
            meses += 12;
        }
        $("#edad").val(anios);
        // Clasificación automática
        var clasificacion = "";
        if (anios <= 12) {
            clasificacion = "Niño";
        } else if (anios <= 17) {
            clasificacion = "Adolescente";
        } else {
            clasificacion = "Adulto";
        }
        $("#clasificacion").val(clasificacion);
    });
    // Validaciones para el campo de alergias
    $("#alergias").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\s,.\-]*$/, e);
    });
    $("#alergias").on("keyup", function() {
        validarkeyup(/^[A-Za-z\s,.\-]{2,20}$/, $(this), $("#salergias"), "Las alergias deben tener entre 2 y 20 caracteres");
    });
    // Validaciones para el campo de antecedentes médicos
    $("#antecedentes").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z\s,.\-]*$/, e);
    });
    $("#antecedentes").on("keyup", function() {
        validarkeyup(/^[A-Za-z\s,.\-]{2,20}$/, $(this), $("#santecedentes"), "Los antecedentes médicos deben tener entre 2 y 20 caracteres");
    });
    // Validaciones para el campo de email
    $("#email").on("keypress", function(e) {
        validarkeypress(/^[A-Za-z0-9\s,.\-@]*$/, e);
    });
    $("#email").on("keyup", function() {
        validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $(this), $("#semail"), "El formato de email electrónico debe ser ejemplo@email.com");
    });
    // Validaciones para el campo de teléfono
    $("#telefono").on("keypress", function(e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });
    $("#telefono").on("keyup", function() {
        validarkeyup(/^0[0-9]{10}$/, $(this), $("#stelefono"), "El formato de teléfono debe ser 04120000000");
    });
    // Validaciones para el campo de dirección
    $("#direccion").on("keypress", function(e) {
        validarkeypress(/^[^"']*$/, e);
    });
    $("#direccion").on("keyup", function() {
        validarkeyup(/^[^"']{1,100}$/, $(this), $("#sdireccion"), "La dirección debe tener entre 1 y 100 caracteres");
    });	
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
                            datos.append('cedula', $("#cedula").val());
                            datos.append('nombre', $("#nombre").val());
                            datos.append('apellido', $("#apellido").val());
                            datos.append('fecha_nacimiento', $("#fecha_nacimiento").val());
                            datos.append('genero', $("#genero").val());
                            datos.append('alergias', $("#alergias").val());
                            datos.append('antecedentes', $("#antecedentes").val());
                            datos.append('email', $("#email").val());
                            datos.append('telefono', $("#telefono").val());
                            datos.append('direccion', $("#direccion").val());
                            datos.append('fecha_registro', $("#fecha_registro").val());
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
                            datos.append('cedula', $("#cedula").val());
                            datos.append('nombre', $("#nombre").val());
                            datos.append('apellido', $("#apellido").val());
                            datos.append('fecha_nacimiento', $("#fecha_nacimiento").val());
                            datos.append('genero', $("#genero").val());
                            datos.append('alergias', $("#alergias").val());
                            datos.append('antecedentes', $("#antecedentes").val());
                            datos.append('email', $("#email").val());
                            datos.append('telefono', $("#telefono").val());
                            datos.append('direccion', $("#direccion").val()); 		
                            datos.append('fecha_registro', $("#fecha_registro").val());						           
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
            validacion = validarkeyup(/^[0-9]{7,8}$/, $("#cedula"), $("#scedula"), "El formato de CI debe ser 1234567 o 12345678");
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
                        datos.append('cedula', $("#cedula").val());
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
    if ($("#cedula").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "la cedula del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if (validarkeyup(/^[0-9]{7,8}$/, $("#cedula"), $("#scedula"), "El formato de CI debe ser 1234567 o 12345678") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El formato de CI debe ser 1234567 o 12345678",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    // Validaciones para nombre
    if ($("#nombre").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{2,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 2 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre debe contener solo letras y tener entre 2 y 30 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    // Validaciones para el formato de apellido
    if ($("#apellido").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El apellido del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{2,30}$/, $("#apellido"), $("#sapellido"), "Solo letras entre 2 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El apellido debe contener solo letras y tener entre 3 y 30 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });     
        return false;
    }
    // Validaciones para el fecha de nacimiento
    if ($("#fecha_nacimiento").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "La fecha de nacimiento del paciente es obligatoria",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    //Validaciones para el género
    if ($("#genero").val() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El genero del paciente es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    // Validaciones para alergias
    if ($("#alergias").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "Las alergias del paciente son obligatorias",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if (validarkeyup(/^[A-Za-z\s,.\-]{2,20}$/, $("#alergias"), $("#salergias"), "Las alergias deben tener entre 2 y 20 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Las alergias deben tener entre 2 y 20 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    // Validaciones para antecedentes médicos
    if ($("#antecedentes").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "Los antecedentes médicos del paciente son obligatorios",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if (validarkeyup(/^[A-Za-z\s,.\-]{2,20}$/, $("#antecedentes"), $("#santecedentes"), "Los antecedentes médicos deben tener entre 2 y 20 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Los antecedentes médicos deben tener entre 2 y 20 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }    
    // Validaciones para el email
    if ($("#email").val().trim() !== "" && validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{2,}$/, $("#email"), $("#semail"), "El formato de email electrónico debe ser ejemplo@email.com") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El formato de email electrónico debe ser ejemplo@email.com",
            icon: "error",
            confirmButtonText: "Aceptar"
        });  
        return false;
    }
    // Validaciones para el teléfono
    if (validarkeyup(/^0[0-9]{10}$/, $("#telefono"), $("#stelefono"), "El formato de teléfono debe ser 04120000000") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El formato de teléfono debe ser 04120000000",
            icon: "error",
            confirmButtonText: "Aceptar"
        });   
        return false;
    }
    // Validaciones para la dirección
    if (validarkeyup(/^[^"']{1,100}$/, $("#direccion"), $("#sdireccion"), "La dirección debe tener entre 1 y 100 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La dirección debe tener un máximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });   
        return false;
    }
    // Validaciones para la fecha de registro
    if (validarkeyup(/^[0-9]{4}[-/][0-9]{2}[-/][0-9]{2}$/, $("#fecha_registro"), $("#sfecha_registro"), "El formato de fecha de registro debe ser AAAA-MM-DD") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El formato de fecha de registro debe ser DD-MM-YYYY",
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
    // Función para llenar el formulario con los datos del paciente seleccionado
    linea = $(pos).closest('tr');
    var fechaNacimiento = new Date($(linea).find("td:eq(4)").text());
    var fechaActual = new Date();
    var anios = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
    var meses = fechaActual.getMonth() - fechaNacimiento.getMonth();
    var dias = fechaActual.getDate() - fechaNacimiento.getDate();
    if (dias < 0) {
        meses--;
        dias += new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 0).getDate();
    }
    if (meses < 0) {
        anios--;
        meses += 12;
    }
    // Clasificación automática
    var clasificacion = $(linea).find("td:eq(6)").text();
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#cedula").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#apellido").prop("disabled", false);
        $("#fecha_nacimiento").prop("disabled", true);
        $("#edad").prop("disabled", true);
        $("#edad").val(anios);
        $("#clasificacion").prop("disabled", true);
        $("#clasificacion").val(clasificacion);
        $("#genero").prop("disabled", true);
        $("#alergias").prop("disabled", false);
        $("#antecedentes").prop("disabled", false);
        $("#email").prop("disabled", false);
        $("#telefono").prop("disabled", false);
        $("#direccion").prop("disabled", false);
        $("#fecha_registro").prop("disabled", true);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#cedula").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#apellido").prop("disabled", true);
        $("#fecha_nacimiento").prop("disabled", true);
        $("#edad").prop("disabled", true);
        $("#edad").val(anios);
        $("#clasificacion").prop("disabled", true);
        $("#clasificacion").val(clasificacion);
        $("#genero").prop("disabled", true);
        $("#alergias").prop("disabled", true);
        $("#antecedentes").prop("disabled", true);
        $("#email").prop("disabled", true);
        $("#telefono").prop("disabled", true);
        $("#direccion").prop("disabled", true);
        $("#fecha_registro").prop("disabled", true);
    }
    // Llena los campos del formulario con los datos de la fila seleccionada
    $("#cedula").val($(linea).find("td:eq(1)").text().trim());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#apellido").val($(linea).find("td:eq(3)").text());
    $("#fecha_nacimiento").val($(linea).find("td:eq(4)").text());
    $("#edad").val(anios);
    $("#clasificacion").val(clasificacion);
    $("#genero").val($(linea).find("td:eq(7)").text());
    $("#alergias").val($(linea).find("td:eq(8)").text());
    $("#antecedentes").val($(linea).find("td:eq(9)").text());
    $("#email").val($(linea).find("td:eq(10)").text());
    $("#telefono").val($(linea).find("td:eq(11)").text());
    $("#direccion").val($(linea).find("td:eq(12)").text());
    $("#fecha_registro").val($(linea).find("td:eq(13)").text());
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
    $("#cedula").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#fecha_nacimiento").val("");
    $("#edad").val("");
    $("#clasificacion").val("");
    $("#genero").prop("selectedIndex", 0);
    $("#alergias").val("");
    $("#antecedentes").val("");
    $("#email").val("");
    $("#telefono").val("");
    $("#direccion").val("");
    establecerFechaActual();
    // Habilita los campos del formulario
    $("#cedula").prop("disabled", false); 
    $("#nombre").prop("disabled", false);   
    $("#apellido").prop("disabled", false); 
    $("#fecha_nacimiento").prop("disabled", false);
    $("#edad").prop("disabled", true);
    $("#clasificacion").prop("disabled", true);
    $("#genero").prop("disabled", false);
    $("#alergias").prop("disabled", false);
    $("#antecedentes").prop("disabled", false);
    $("#email").prop("disabled", false);   
    $("#telefono").prop("disabled", false); 
    $("#direccion").prop("disabled", false);   
    $("#fecha_registro").prop("disabled", true); // Habilita el campo de fecha de registro               
}