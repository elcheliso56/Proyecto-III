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
                zeroRecords: "No se encontraron empleados",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay empleados registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar empleado...",
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
    // Llama a la función consultar al cargar el número de cédula
	consultar();

    var hoy = new Date();
    var dia = String(hoy.getDate()).padStart(2, '0');
    var mes = String(hoy.getMonth() + 1).padStart(2, '0');
    var anio = hoy.getFullYear();
    var fechaMax = anio + '-' + mes + '-' + dia;
    $("#fecha_nacimiento").attr("max", fechaMax);
    $("#fecha_contratacion").attr("max", fechaMax);

    //Validaciones para el campo rif
    $("#rif").on("keypress", function(e) {
        validarkeypress(/^[0-9-\b]*$/, e);
    });
    $("#rif").on("keyup", function() {
        validarkeyup(/^[0-9]{7,10}$/, $(this), $("#srif"), "El formato para rif debe ser 123456789");
    });

    // Validaciones para el campo de número de cédula
    $("#cedula").on("keypress", function(e) {
        validarkeypress(/^[0-9-\b]*$/, e);
    });
    $("#cedula").on("keyup", function() {
        validarkeyup(/^[0-9]{7,10}$/, $(this), $("#scedula"), "El formato de CI debe ser 1234567 o 12345678");
    });

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function(e) {
        validarkeypress(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombre").on("keyup", function() {
        validarkeyup(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]{2,30}$/, $(this), $("#snombre"), "Solo letras entre 2 y 30 caracteres");
    });

    // Validaciones para el campo de apellido
    $("#apellido").on("keypress", function(e) {
        validarkeypress(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });	
    $("#apellido").on("keyup", function() {
        validarkeyup(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]{2,30}$/, $(this), $("#sapellido"), "Solo letras entre 2 y 30 caracteres");
    });

    // Validaciones para el campo de fecha de nacimiento y coloca la edad automaticamente en el campo edad
    $("#fecha_nacimiento").on("change", function() {
        var fecha = $(this).val();
        var fechaActual = new Date();
        var fechaNacimiento = new Date(fecha);
        var edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        var mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
        if (mes < 0 || (mes === 0 && fechaActual.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }
        $("#edad").val(edad);
    });

    // Validaciones para el campo de email
    $("#email").on("blur", function() {
        var email = $(this).val().trim();
        var er = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!er.test(email)) {
            $("#mensaje_email").text("Correo inválido").show();
        } else {
            $("#mensaje_email").hide();
        }
    });
    $("#email").on("keypress", function(e) {
        validarkeypress(/^[A-Z0-9\s,.\-@]*$/, e);
    });
    $("#email").on("keyup", function() {
        validarkeyup(/^[\w._%+-]+@[\w.-]+\.[\w]{1,100}$/, $(this), $("#semail"), "El formato de email electrónico debe ser ejemplo@email.com");
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

    //Validaciones para el campo de salario
    $("#salario").on("keypress", function(e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });
    $("#salario").on("keyup", function() {
        validarkeyup(/^[0-9]{1,10}$/, $(this), $("#ssalario"), "El salario debe ser un número entre 1 y 10 dígitos");
    });

    // Manejo de clic en el botón de proceso
    $("#proceso").on("click", function() {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo empleado
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo empleado?",
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
                            datos.append('tipo_rif', $("#tipo_rif").val());
                            datos.append('rif', $("#rif").val());
                            datos.append('tipo_documento', $("#tipo_documento").val());
                            datos.append('cedula', $("#cedula").val());
                            datos.append('nombre', $("#nombre").val());
                            datos.append('apellido', $("#apellido").val());
                            datos.append('fecha_nacimiento', $("#fecha_nacimiento").val());
                            datos.append('genero', $("#genero").val());
                            datos.append('email', $("#email").val());
                            datos.append('telefono', $("#telefono").val());
                            datos.append('direccion', $("#direccion").val());
                            datos.append('fecha_contratacion', $("#fecha_contratacion").val());
                            datos.append('cargo', $("#cargo").val());
                            datos.append('salario', $("#salario").val());
                            enviaAjax(datos);
                        }
                    }
                });
            }
        }
        else if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
            // Confirmación para modificar un empleado existente
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas modificar la información de este empleado?",
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
                            datos.append('tipo_rif', $("#tipo_rif").val());
                            datos.append('rif', $("#rif").val());
                            datos.append('tipo_documento', $("#tipo_documento").val());
                            datos.append('cedula', $("#cedula").val());
                            datos.append('nombre', $("#nombre").val());
                            datos.append('apellido', $("#apellido").val());
                            datos.append('fecha_nacimiento', $("#fecha_nacimiento").val());
                            datos.append('genero', $("#genero").val());
                            datos.append('email', $("#email").val());
                            datos.append('telefono', $("#telefono").val());
                            datos.append('direccion', $("#direccion").val()); 	
                            datos.append('fecha_contratacion', $("#fecha_contratacion").val());
                            datos.append('cargo', $("#cargo").val());
                            datos.append('salario', $("#salario").val());					           
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La información de este empleado no ha sido modificada",
                            icon: "error"
                        });
                    }
                });
            }
        }
        // Manejo de eliminación de un empleado
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
                            text: "Empleado no eliminado",
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
    if ($("#tipo_rif").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    
    if ($("#rif").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    
    if ($("#tipo_documento").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    // Validaciones para el campo de cédula
    if ($("#cedula").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para nombre
    if ($("#nombre").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    
    // Validaciones para el formato de apellido
    if ($("#apellido").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    
    // Validaciones para el fecha de nacimiento y valida que sea mayor a 18 años
    if ($("#fecha_nacimiento").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    
    // Validación de mayoría de edad (18+ años)
    var fecha = $("#fecha_nacimiento").val();
    var fechaActual = new Date();
    var fechaNacimiento = new Date(fecha);
    var edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
    var mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
    if (mes < 0 || (mes === 0 && fechaActual.getDate() < fechaNacimiento.getDate())) {
        edad--;
    }
    if (edad < 18) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El empleado debe ser mayor de edad (18 años o más)",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    //validaciones para el genero
    if ($("#genero").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para el email
    if ($("#email").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });  
        return false;
    }

    // Validaciones para el teléfono
    if ($("#telefono").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para la dirección
    if ($("#direccion").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para la fecha de contratacion
    if ($("#fecha_contratacion").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para el cargo
    if ($("#cargo").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }

    // Validaciones para el salario
    if ($("#salario").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
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
    // Función para llenar el formulario con los datos del empleado seleccionado
    linea = $(pos).closest('tr');
    var fechaNacimiento = new Date($(linea).find("td:eq(4)").text());
    var fechaActual = new Date();
    var edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
    var mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
    if (mes < 0 || (mes === 0 && fechaActual.getDate() < fechaNacimiento.getDate())) {
        edad--;
    }

    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#tipo_rif").prop("disabled", true);
        $("#rif").prop("disabled", true);
        $("#tipo_documento").prop("disabled", true);
        $("#cedula").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#apellido").prop("disabled", false);
        $("#fecha_nacimiento").prop("disabled", true);
        $("#edad").prop("disabled", true);
        $("#edad").val(edad);
        $("#genero").prop("disabled", true);
        $("#email").prop("disabled", false);
        $("#telefono").prop("disabled", false);
        $("#direccion").prop("disabled", false);
        $("#fecha_contratacion").prop("disabled", false);
        $("#cargo").prop("disabled", false);
        $("#salario").prop("disabled", false);
    } 
    
    else {
        $("#proceso").text("ELIMINAR");
        $("#tipo_rif").prop("disabled", true);
        $("#rif").prop("disabled", true);
        $("#tipo_documento").prop("disabled", true);
        $("#cedula").prop("disabled", true);
        $("#cedula").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#apellido").prop("disabled", true);
        $("#fecha_nacimiento").prop("disabled", true);
        $("#edad").prop("disabled", true);
        $("#edad").val(edad);
        $("#genero").prop("disabled", true);
        $("#email").prop("disabled", true);
        $("#telefono").prop("disabled", true);
        $("#direccion").prop("disabled", true);
        $("#fecha_contratacion").prop("disabled", true);
        $("#cargo").prop("disabled", true);
        $("#salario").prop("disabled", true);
    }
    // Llena los campos del formulario con los datos de la fila seleccionada
    var rifCompleto = $(linea).find("td:eq(1)").text().trim();
    var partes = rifCompleto.split("-");
    var tipoRif = partes[0];
    var rif = partes[1];

    var documentoCompleto = $(linea).find("td:eq(2)").text().trim();
    var partes = documentoCompleto.split("-");
    var tipoDocumento = partes[0];
    var cedula = partes[1];

    var nombreCompleto = $(linea).find("td:eq(3)").text().trim();
    var partes = nombreCompleto.split(" ");
    var nombre = partes[0];
    var apellido = partes[1];

    $("#tipo_rif").val(tipoRif);
    $("#rif").val(rif);
    $("#tipo_documento").val(tipoDocumento);
    $("#cedula").val(cedula);
    $("#nombre").val(nombre);
    $("#apellido").val(apellido);
    $("#fecha_nacimiento").val(toISODate($(linea).find("td:eq(4)").text()));
    $("#edad").val(edad);
    $("#genero").val($(linea).find("td:eq(6)").text());
    $("#email").val($(linea).find("td:eq(7)").text());
    $("#telefono").val($(linea).find("td:eq(8)").text());
    $("#direccion").val($(linea).find("td:eq(9)").text());
    $("#fecha_contratacion").val(toISODate($(linea).find("td:eq(10)").text()));
    $("#cargo").val($(linea).find("td:eq(11)").text());
    $("#salario").val($(linea).find("td:eq(12)").text());
    $("#modal1").modal("show"); // Muestra el modal
}

function toISODate(fecha) {
    if (!fecha) return "";
    let partes = fecha.split("-");
    if (partes.length === 3) {
        // Si ya está en formato yyyy-mm-dd, regresa igual
        if (partes[0].length === 4) return fecha;
        // Si está en formato dd-mm-yyyy, conviértelo
        return `${partes[2]}-${partes[1]}-${partes[0]}`;
    }
    return fecha;
}

function formatearFecha(fecha) {
    if (!fecha) return "";
    // Soporta formatos "yyyy-mm-dd" o "yyyy-mm-ddTHH:MM:SS"
    let partes = fecha.split("T")[0].split("-");
    if (partes.length !== 3) return fecha;
    return `${partes[2]}-${partes[1]}-${partes[0]}`;
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
                    let filas = "";
                    (lee.mensaje || []).forEach(function(p, idx) {
                        filas += `<tr class='text-center'>
                            <td class='align-middle'>${idx+1}</td>
                            <td class='align-middle' style='display:none' >${p.tipo_rif}-${p.rif}</td>
                            <td class='align-middle'>${p.tipo_documento}-${p.cedula}</td>
                            <td class='align-middle'>${p.nombre} ${p.apellido}</td>
                            <td class='align-middle' style='display:none'>${formatearFecha(p.fecha_nacimiento)}</td>
                            <td class='align-middle' style='display:none'>${p.edad}</td>
                            <td class='align-middle' style='display:none'>${p.genero}</td>
                            <td class='align-middle' style='display:none'>${p.email}</td>
                            <td class='align-middle'>${p.telefono}</td>
                            <td class='align-middle' style='display:none'>${p.direccion}</td>
                            <td class='align-middle' style='display:none'>${formatearFecha(p.fecha_contratacion)}</td>
                            <td class='align-middle'>${p.cargo}</td>
                            <td class='align-middle' style='display:none'>${p.salario}</td>
                            <td class='align-middle' style='display: flex; justify-content: center;'>
                                <button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='verDetalle(this)' title='Ver detalle' style='margin:.2rem; width: 40px !important;'><i class='bi bi-eye-fill'></i></button><br/>
                                <button type='button' class='btn-sm btn-info w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar rol' style='margin:.2rem; width: 40px !important;'><i class='bi bi-arrow-repeat'></i></button><br/>
                                <button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar rol' style='margin:.2rem; width: 40px !important;'><i class='bi bi-trash-fill'></i></button><br/>
                            </td>
                        </tr>`;
                    });
                    $("#resultadoconsulta").html(filas);
                    crearDT();
                }
                else if (lee.resultado == "incluir") {
                    if (lee.mensaje == '¡Registro guardado con exito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "El empleado ha sido incluido con éxito.",
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
    $("#tipo_rif").prop("selectedIndex", 0);
    $("#rif").val("");
    $("#tipo_documento").prop("selectedIndex", 0);
    $("#cedula").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#fecha_nacimiento").val("");
    $("#edad").val("");
    $("#genero").prop("selectedIndex", 0);
    $("#email").val("");
    $("#telefono").val("");
    $("#direccion").val("");
    $("#fecha_contratacion").val("");
    $("#cargo").prop("selectedIndex", 0);
    $("#salario").val("");

    // Habilita los campos del formulario
    $("#tipo_rif").prop("disabled", false); 
    $("#rif").prop("disabled", false);   
    $("#tipo_documento").prop("disabled", false); 
    $("#cedula").prop("disabled", false); 
    $("#nombre").prop("disabled", false);   
    $("#apellido").prop("disabled", false); 
    $("#fecha_nacimiento").prop("disabled", false);
    $("#edad").prop("disabled", true);
    $("#genero").prop("disabled", false);
    $("#email").prop("disabled", false);   
    $("#telefono").prop("disabled", false); 
    $("#direccion").prop("disabled", false);      
    $("#fecha_contratacion").prop("disabled", false);
    $("#cargo").prop("disabled", false);
    $("#salario").prop("disabled", false);    
}

function verDetalle(pos) {
    var linea = $(pos).closest('tr');
    
    // Obtener los valores de la fila
    var rifCompleto = $(linea).find("td:eq(1)").text().trim();
    var partes = rifCompleto.split("-");
    var tipoRif = partes[0];
    var rif = partes[1];
    
    var documentoCompleto = $(linea).find("td:eq(2)").text().trim();
    var partes = documentoCompleto.split("-");
    var tipoDocumento = partes[0];
    var cedula = partes[1];

    var nombreCompleto = $(linea).find("td:eq(3)").text().trim();
    var partes = nombreCompleto.split(" ");
    var nombre = partes[0];
    var apellido = partes[1];

    var fechaNacimiento = $(linea).find("td:eq(4)").text().trim();
    var edad = $(linea).find("td:eq(5)").text().trim();
    var genero = $(linea).find("td:eq(6)").text().trim();
    var email = $(linea).find("td:eq(7)").text().trim();
    var telefono = $(linea).find("td:eq(8)").text().trim();
    var direccion = $(linea).find("td:eq(9)").text().trim();
    var fechaContratacion = $(linea).find("td:eq(10)").text().trim();
    var cargo = $(linea).find("td:eq(11)").text().trim();
    var salario = $(linea).find("td:eq(12)").text().trim();
    
    // Establecer los valores en el modal de detalles
    $("#detalle_rif").text(tipoRif + "-" + rif);
    $("#detalle_cedula").text(tipoDocumento + "-" + cedula);
    $("#detalle_nombre").text(nombre + " " + apellido);
    $("#detalle_fecha_nacimiento").text(fechaNacimiento);
    $("#detalle_edad").text(edad);
    $("#detalle_genero").text(genero);
    $("#detalle_email").text(email);
    $("#detalle_telefono").text(telefono);
    $("#detalle_direccion").text(direccion);
    $("#detalle_fecha_contratacion").text(fechaContratacion);
    $("#detalle_cargo").text(cargo);
    $("#detalle_salario").text(salario);
    
    // Mostrar el modal
    $("#modalDetalle").modal("show");
}