function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaEquipos")) {
        $("#tablaEquipos").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaEquipos")) {
        $("#tablaEquipos").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron equipos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay equipos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar equipo...",
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
            fixedHeader: false,
            order: [[0, "asc"]], // Ordena por la segunda columna
        });
    }
}

$(document).ready(function () {
    consultar(); // Llama a la función consultar al cargar el documento
    // Validaciones para el campo de código
    $("#codigo").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#codigo").on("keyup", function () {
        validarkeyup(/^[^"']{1,20}$/, $(this), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres");
    });

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombre"), "Solo letras  entre 3 y 50 caracteres");
    });

    // Validaciones para el campo de marca
    $("#marca").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#marca").on("keyup", function () {
        validarkeyup(/^[^"']{1,50}$/, $(this), $("#smarca"), "La marca debe tener un máximo de 50 caracteres");
    });

    // Validaciones para el campo de modelo
    $("#modelo").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#modelo").on("keyup", function () {
        validarkeyup(/^[^"']{1,50}$/, $(this), $("#smodelo"), "El modelo debe tener un máximo de 50 caracteres");
    });

    // Validaciones para el campo de stock total
    $("#cantidad").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#scantidad").on("keyup", function () {
        validarkeyup(/^[0-9]{1,10}$/, $(this), $("#scantidad"), "El stock debe ser numeros enteros");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo equipo
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo equipo?",
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
                        datos.append('accion', 'incluir'); // Acción para incluir
                        // Se agregan los datos del formulario

                        datos.append('codigo', $("#codigo").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('marca', $("#marca").val());
                        datos.append('modelo', $("#modelo").val());                        
                        datos.append('cantidad', $("#cantidad").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                }
            });
            } }

            else if ($(this).text() == "MODIFICAR") {
            // Confirmación para modificar un equipo existente
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
                        text: "¿Deseas modificar este equipo?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Sí, modificar",
                        cancelButtonText: "No, cancelar",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (validarenvio()) {
                                var datos = new FormData();
                        datos.append('accion', 'modificar'); // Acción para modificar
                        // Se agregan los datos del formulario
                        datos.append('codigo', $("#codigo").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('marca', $("#marca").val());
                        datos.append('modelo', $("#modelo").val());
                        datos.append('cantidad', $("#cantidad").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Mensaje de cancelación
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El equipo no ha sido modificado",
                        icon: "error"
                    });
                }
            });
                } }
                else if ($(this).text() == "ELIMINAR") {
            // Validación antes de eliminar un equipo
                    if (validarkeyup(/^[^"']{1,50}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres") == 0) {
                        muestraMensaje("El Código debe tener entre 1 y 20 caracteres");
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
                        datos.append('accion', 'eliminar'); // Acción para eliminar
                        datos.append('codigo', $("#codigo").val()); // Se agrega el código del equipo
                        enviaAjax(datos); // Envía los datos al servidor
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Mensaje de cancelación
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "Equipo no eliminado",
                            icon: "error"
                        });
                    }
                });
                    }
                }
            });

    // Manejo del clic en el botón incluir
$("#incluir").on("click", function () {
        limpia(); // Limpia los campos del formulario
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón a 'INCLUIR'
        $("#modal1").modal("show"); // Muestra el modal
    });
});


// Función para validar el envío de datos
function validarenvio() {
    // Validaciones para cada campo del formulario
    if (validarkeyup(/^[^"']{1,20}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El código del equipo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombre"), $("#snombre"), "Solo letras  entre 3 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del equipo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 

    if ($("#marca").val().trim() !== "" &&
        validarkeyup(/^[^"']{1,35}$/, $("#marca"), $("#smarca"), "La marca debe tener entre 1 y 35 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La marca debe tener entre 1 y 35 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
    return false;
}
if ($("#modelo").val().trim() !== "" &&
    validarkeyup(/^[^"']{1,35}$/, $("#modelo"), $("#smodelo"), "El modelo debe tener entre 1 y 35 caracteres") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El modelo debe tener entre 1 y 35 caracteres",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
return false;
} 

    if (validarkeyup(/^[0-9]{1,10}$/, $("#cantidad"), $("#scantidad"), "El stock debe ser mayor o igual a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El stock del equipo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 

return true;
}

// Función para validar la tecla presionada
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla); // Verifica si la tecla es válida
    if (!a) {
        e.preventDefault(); // Previene la acción si no es válida
    }
}

// Función para validar el campo al soltar la tecla
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val()); // Verifica el valor del campo
    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}
// Función para llenar el formulario con datos de un equipo
function pone(pos, accion) {
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#marca").prop("disabled", false);
        $("#modelo").prop("disabled", false);
        $("#cantidad").prop("disabled", false);
        $("#imagen").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#marca").prop("disabled", true);
        $("#modelo").prop("disabled", true);
        $("#cantidad").prop("disabled", true);
        $("#imagen").prop("disabled", true);
    }
    $("#codigo").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#marca").val($(linea).find("td:eq(3)").text());
    $("#modelo").val($(linea).find("td:eq(4)").text());
    $("#cantidad").val($(linea).find("td:eq(5)").text().replace(/\D/g, ''));

    // Cargar imagen
    var imagenSrc = $(linea).find("td:eq(6) img").attr("src");
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show();// Muestra la imagen actual
        $("#imagen_url").val(imagenSrc);
        $("#imagen_url").val(imagenSrc);
        fetch(imagenSrc)
        .then(res => res.blob())
        .then(blob => {
            const file = new File([blob], imagenSrc.split('/').pop(), { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            $("#imagen")[0].files = dataTransfer.files;
        });
    } else {
        $("#imagen_actual").attr("src", "").hide();
        $("#imagen_url").val("");
    }
    $("#modal1").modal("show");
}

function limpia() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#marca").val("");
    $("#modelo").val("");    
    $("#cantidad").val("");
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#codigo").prop("disabled", false);
    $("#nombre").prop("disabled", false);
    $("#marca").prop("disabled", false);
    $("#modelo").prop("disabled", false);
    $("#cantidad").prop("disabled", false);    
    $("#imagen").prop("disabled", false);
    
}

function verificarStock(cantidad) {
    if (parseInt(cantidad) === parseInt(0)) {
        return '<span class="badge bg-danger">No disponible</span>';
    }
    return '';
}

function mostrarAlertaStockBajo(productosBajoStock) {
    let mensaje = "Los siguientes equipos no están disponibles:\n";
    productosBajoStock.forEach(equipo => {
        mensaje += `- ${equipo.nombre} (Stock actual: ${equipo.cantidad})\n`;
    });

    Swal.fire({
        title: "¡Alerta falta de stock!",
        text: mensaje,
        icon: "warning",
        confirmButtonText: "Entendido"
    });
}

$("#imagen").on("change", function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $("#imagen_actual")
            .attr("src", e.target.result)
            .show();
        };
        reader.readAsDataURL(file);
    } else {
        $("#imagen_actual")
        .attr("src", "")
        .hide();
    }
});

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
                    if (lee.productos_bajo_stock && lee.productos_bajo_stock.length > 0) {
                        mostrarAlertaStockBajo(lee.productos_bajo_stock);
                    }
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
                        title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
                    });
                    if (lee.mensaje == '¡Registro eliminado con exito!') {
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
            $("#loader").hide(); // Ocultar loader al completar
        }
    });
}