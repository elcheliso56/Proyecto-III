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
                zeroRecords: "NO SE ENCONTRARON RESULTADOS",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "NO HAY REGISTROS",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "BUSCAR...",
                paginate: {
                    first: "PRIMERA",
                    last: "ÚLTIMA",
                    next: "SIGUENTE",
                    previous: "ANTERIOR",
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
    // Llama a la función consultar al cargar el número de cédula
	consultar();

    // Validaciones para el campo de nombre
    $("#nombre_rol").on("keypress", function(e) {
        validarkeypress(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombre_rol").on("keyup", function() {
        validarkeyup(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]{5,30}$/, $(this), $("#snombre_rol"), "Solo letras entre 5 y 30 caracteres");
    });

    // Validaciones para el campo de descripción
    $("#descripcion").on("keypress", function(e) {
        validarkeypress(/^[A-Z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#descripcion").on("keyup", function() {
        validarkeyup(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]{5,100}$/, $(this), $("#sdescripcion"), "Solo letras entre 5 y 100 caracteres");
    });

    // Manejo de clic en el botón de proceso
    $("#proceso").on("click", function() {
        if ($(this).text() == " INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo rol?",
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
                            datos.append('id', $("#id").val());
                            datos.append('nombre_rol', $("#nombre_rol").val());
                            datos.append('descripcion', $("#descripcion").val());
                            datos.append('estado', 'ACTIVO'); // Establecer estado ACTIVO por defecto
                            enviaAjax(datos);
                        }
                    }
                });
            }
        }
        else if ($(this).text() == " MODIFICAR") {
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
                    text: "¿Deseas modificar la información de este rol?",
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
                            datos.append('nombre_rol', $("#nombre_rol").val());
                            datos.append('descripcion', $("#descripcion").val());
                            datos.append('estado', $("#estado").val());

                            // Debug
                            console.log('Enviando datos de modificación:');
                            for (var pair of datos.entries()) {
                                console.log(pair[0] + ': ' + pair[1]);
                            }

                            $.ajax({
                                async: true,
                                url: "?pagina=roles",
                                type: "POST",
                                contentType: false,
                                data: datos,
                                processData: false,
                                cache: false,
                                beforeSend: function() {
                                    $("#loader").show();
                                },
                                success: function(respuesta) {
                                    console.log('Respuesta del servidor:', respuesta);
                                    try {
                                        var lee = JSON.parse(respuesta);
                                        if(lee.resultado == 'modificar') {
                                            Swal.fire({
                                                title: "¡Modificado!",
                                                text: lee.mensaje,
                                                icon: "success"
                                            }).then(() => {
                                                $("#modal1").modal("hide");
                                                consultar();
                                            });
                                        } else {
                                            Swal.fire({
                                                title: "Error",
                                                text: lee.mensaje,
                                                icon: "error"
                                            });
                                        }
                                    } catch(e) {
                                        console.error('Error al procesar respuesta:', e);
                                        console.error('Respuesta recibida:', respuesta);
                                        Swal.fire({
                                            title: "Error",
                                            text: "Error al procesar la respuesta del servidor",
                                            icon: "error"
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error AJAX:', error);
                                    console.error('Estado:', status);
                                    console.error('Respuesta:', xhr.responseText);
                                    Swal.fire({
                                        title: "Error",
                                        text: "Error en la comunicación con el servidor",
                                        icon: "error"
                                    });
                                },
                                complete: function() {
                                    $("#loader").hide();
                                }
                            });
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La información de este rol no ha sido modificada",
                            icon: "error"
                        });
                    }
                });
            }
        }
        // Manejo de eliminación de un empleado
        if ($(this).text() == " ELIMINAR") {
            var validacion;
            validacion = validarkeyup(/^[A-Z\b\s\u00f1\u00d1\u00E0-\u00FC]{5,30}$/, $(this), $("#snombre_rol"), "Solo letras entre 5 y 30 caracteres");
            if (validacion == 'ADMINISTRADOR') {
                muestraMensaje("No se puede eliminar el rol ADMINISTRADOR");
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
                        datos.append('nombre_rol', $("#nombre_rol").val());
                        enviaAjax(datos);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "rol no eliminado",
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
        $("#proceso").text(" INCLUIR"); // Cambia el texto del botón
        $("#modal1").modal("show"); // Muestra el modal
    });	

    $("#guardar").on("click", function() {
        const permisosSeleccionados = [];
        $('.permiso-check:checked').each(function() {
            permisosSeleccionados.push($(this).val());
        });

        console.log("Permisos seleccionados:", permisosSeleccionados);

        if(permisosSeleccionados.length === 0) {
            Swal.fire({
                title: "Advertencia",
                text: "No has seleccionado ningún permiso",
                icon: "warning"
            });
            return;
        }

        var datos = new FormData();
        datos.append('accion', 'guardar_permisos');
        datos.append('id_rol', $("#id_rol").val());
        
        // Enviando cada permiso individualmente en el array
        permisosSeleccionados.forEach(function(permiso) {
            datos.append('permisos[]', permiso);
        });

        // Debug: mostrar todos los datos que se enviarán
        console.log("ID del rol:", $("#id_rol").val());
        for (var pair of datos.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

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
            success: function(respuesta) {
                console.log("Respuesta del servidor:", respuesta);
                try {
                    var lee = JSON.parse(respuesta);
                    if(lee.resultado == "permisos") {
                        Swal.fire({
                            title: "¡Éxito!",
                            text: lee.mensaje,
                            icon: "success"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#modal2").modal("hide");
                                cargarPermisos();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: lee.mensaje,
                            icon: "error"
                        });
                    }
                } catch(e) {
                    console.error("Error al parsear respuesta:", e);
                    console.error("Respuesta del servidor:", respuesta);
                    Swal.fire({
                        title: "Error",
                        text: "Error al procesar la respuesta del servidor: " + e.message,
                        icon: "error"
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX:", error);
                console.error("Estado:", status);
                console.error("Respuesta:", xhr.responseText);
                Swal.fire({
                    title: "Error",
                    text: "Error en la comunicación con el servidor: " + error,
                    icon: "error"
                });
            },
            complete: function() {
                $("#loader").hide();
            }
        });
    });
});
// Función para validar el envío de datos
function validarenvio() {
    if ($("#nombre_rol").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if ($("#descripcion").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El campo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
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
    linea = $(pos).closest('tr');
    var id = $(linea).find("td:eq(0)").text(); // Obtener el ID de la primera columna
    var nombre_rol = $(linea).find("td:eq(1)").text(); // Obtener el nombre del rol

    // Quitar la restricción para ADMINISTRADOR
    // if (nombre_rol === 'ADMINISTRADOR' && (accion == 0 || accion == 1 || accion == 2)) {
    //     Swal.fire({
    //         title: "¡Acción no permitida!",
    //         text: "No se pueden modificar las características del rol ADMINISTRADOR",
    //         icon: "error",
    //         confirmButtonText: "Aceptar"
    //     });
    //     return;
    // }

    if (accion == 0) {
        $("#proceso").text(" MODIFICAR");
        $("#id").prop("disabled", true);
        $("#nombre_rol").prop("disabled", false);
        $("#descripcion").prop("disabled", false);
        $("#estado").prop("disabled", false);
        $("#modal1").modal("show");
    } 
    else if (accion == 1) {
        $("#proceso").text(" ELIMINAR");
        $("#id").prop("disabled", true);
        $("#nombre_rol").prop("disabled", true);
        $("#descripcion").prop("disabled", true);
        $("#estado").prop("disabled", true);
        $("#modal1").modal("show");
    }
    else if (accion == 2) {
        // Gestión de permisos - Usar el ID del rol
        $("#id_rol").val(id);
        cargarPermisos();
        $("#modal2").modal("show");
    }

    $("#id").val(id);
    $("#nombre_rol").val(nombre_rol);
    $("#descripcion").val($(linea).find("td:eq(2)").text());
    $("#estado").val($(linea).find("td:eq(3)").text());
}

function cargarPermisos() {
    var datos = new FormData();
    datos.append('accion', 'consultar_permisos_rol');
    datos.append('id_rol', $("#id_rol").val());
    
    console.log("Cargando permisos para rol:", $("#id_rol").val());

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
        success: function(respuesta) {
            console.log("Respuesta de cargarPermisos:", respuesta);
            try {
                var lee = JSON.parse(respuesta);
                if(lee.resultado == "consultar_permisos_rol") {
                    let permisos = "";
                    lee.mensaje.forEach(function(p) {
                        permisos += `
                            <div class="form-check col-md-6 mb-2">
                                <input class="form-check-input permiso-check" type="checkbox" id="permiso_${p.id_permiso}" value="${p.id_permiso}" ${p.tiene_permiso == 1 ? 'checked' : ''}>
                                <label class="form-check-label" for="permiso_${p.id_permiso}"> ${p.nombre_permiso} </label>
                            </div>`;
                    });
                    $("#rol_permisos").html(permisos);
                } else {
                    console.error("Error en la respuesta:", lee);
                }
            } catch(e) {
                console.error("Error al parsear respuesta de cargarPermisos:", e);
                console.error("Respuesta recibida:", respuesta);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar permisos:", error);
            console.error("Respuesta:", xhr.responseText);
        },
        complete: function() {
            $("#loader").hide();
        }
    });
}

function cambiarEstado(checkbox, id) {
    // Obtener el nombre del rol
    const fila = $(checkbox).closest('tr');
    const nombre_rol = $(fila).find("td:eq(1)").text();

    // Quitar la restricción para ADMINISTRADOR
    // if (nombre_rol === 'ADMINISTRADOR') {
    //     Swal.fire({
    //         title: "¡Acción no permitida!",
    //         text: "No se puede cambiar el estado del rol ADMINISTRADOR",
    //         icon: "error",
    //         confirmButtonText: "Aceptar"
    //     });
    //     // Revertir el cambio en el checkbox
    //     checkbox.checked = !checkbox.checked;
    //     return;
    // }

    const nuevoEstado = checkbox.checked ? 'ACTIVO' : 'INACTIVO';
    
    Swal.fire({
        title: "¿Estás seguro?",
        text: `¿Deseas cambiar el estado del rol a ${nuevoEstado}?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar",
        cancelButtonText: "No, cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('accion', 'cambiar_estado');
            datos.append('id', id);
            datos.append('estado', nuevoEstado);
            
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
                success: function(respuesta) {
                    try {
                        var lee = JSON.parse(respuesta);
                        if(lee.resultado == 'modificar') {
                            Swal.fire({
                                title: "¡Actualizado!",
                                text: "El estado del rol ha sido actualizado.",
                                icon: "success"
                            });
                            consultar(); // Actualizar la tabla
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: lee.mensaje,
                                icon: "error"
                            });
                            consultar(); // Recargar la tabla para mantener el estado anterior
                        }
                    } catch(e) {
                        console.error(e);
                        Swal.fire({
                            title: "Error",
                            text: "Error al procesar la respuesta del servidor",
                            icon: "error"
                        });
                        consultar(); // Recargar la tabla para mantener el estado anterior
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error",
                        text: "Error al comunicarse con el servidor",
                        icon: "error"
                    });
                    consultar(); // Recargar la tabla para mantener el estado anterior
                },
                complete: function() {
                    $("#loader").hide();
                }
            });
        } else {
            consultar(); // Si el usuario cancela, recargar la tabla para mantener el estado anterior
        }
    });
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
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar") {
                    destruyeDT();
                    let filas = "";
                    (lee.mensaje || []).forEach(function(p) {
                        filas += `<tr class='text-center'>
                                        <td class='align-middle' style='display: none;'>${p.id}</td>
                                        <td class='align-middle'>${p.nombre_rol}</td>
                                        <td class='align-middle'>${p.descripcion}</td>
                                        <td class='align-middle'>
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" role="switch" 
                                                    ${p.estado === 'ACTIVO' ? 'checked' : ''} 
                                                    onchange="cambiarEstado(this, '${p.id}')"
                                                    style="width: 40px; height: 20px; cursor: pointer;">
                                            </div>
                                        </td>
                                        <td class='align-middle' style='display: flex; justify-content: center;'>
                                            <button type='button' class='btn-sm btn-warning w-50 small-width mb-1' onclick='pone(this,2)' title='Modificar permisos' style='margin:.2rem; width: 40px !important;'><i class='bi bi-shield-lock-fill'></i></button><br/>
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
                    if (lee.mensaje == '¡Registro eliminado con exito!') {
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: lee.mensaje,
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
                else if (lee.resultado == "error") {
                    Swal.fire({
                        title: "Error",
                        text: lee.mensaje,
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({
                    title: "Error",
                    text: "Error en la respuesta del servidor: " + e.message,
                    icon: "error"
                });
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                Swal.fire({
                    title: "Error",
                    text: "Tiempo de espera agotado, intente de nuevo",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Error en la comunicación con el servidor",
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
    // Función para limpiar los campos del formulario
    $("#id").val("");
    $("#nombre_rol").val("");
    $("#descripcion").val("");
    $("#estado").val("ACTIVO");

    // Habilita los campos del formulario
    $("#id").prop("disabled", true);
    $("#nombre_rol").prop("disabled", false); 
    $("#descripcion").prop("disabled", false);   
    $("#estado").prop("disabled", true);    
}