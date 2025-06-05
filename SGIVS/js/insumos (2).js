function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaInsumos")) {
        $("#tablaInsumos").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaInsumos")) {
        $("#tablaInsumos").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron insumos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay insumos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar insumo...",
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
}

$(document).ready(function () {
    consultar();
    cargarOpciones();

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
        validarkeyup(/^[^"']{1,35}$/, $(this), $("#smarca"), "La marca debe tener un máximo de 35 caracteres");
    });

    // Validaciones para el campo de stock total
    $("#stock_total").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#stock_total").on("keyup", function () {
        validarkeyup(/^[0-9]{1,10}$/, $(this), $("#sstock_total"), "El stock debe ser numeros enteros");
    });

    // Validaciones para el campo de stock mínimo
    $("#stock_minimo").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#stock_minimo").on("keyup", function () {
        validarkeyup(/^[0-9]{1,10}$/, $(this), $("#sstock_minimo"), "El stock mínimo debe ser números enteros");
    });

    // Validaciones para el campo de precio de compra
    $("#precio").on("keypress", function (e) {
        validarkeypress(/^[0-9.\b]*$/, e);
    });

    $("#precio").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,10}$/, $(this), $("#sprecio"), "El precio debe ser mayor o igual a 0");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo insumo
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo insumo?",
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
                        datos.append('stock_total', $("#stock_total").val());
                        datos.append('stock_minimo', $("#stock_minimo").val());                        
                        datos.append('precio', $("#precio").val());
                        datos.append('presentacion_id', $("#presentacion").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                }
            });
            } 
        }

            else if ($(this).text() == "MODIFICAR") {
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
                        text: "¿Deseas modificar este insumo?",
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
                        datos.append('stock_total', $("#stock_total").val());
                        datos.append('stock_minimo', $("#stock_minimo").val());                        
                        datos.append('precio', $("#precio").val());
                        datos.append('presentacion_id', $("#presentacion").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Mensaje de cancelación
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El insumo no ha sido modificado",
                        icon: "error"
                    });
                }
            });
                } }
                else if ($(this).text() == "ELIMINAR") {
            // Validación antes de eliminar 
                    if (validarkeyup(/^[^"']{1,20}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres") == 0) {
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
                        datos.append('codigo', $("#codigo").val()); // Se agrega el código 
                        console.log("Enviando solicitud de eliminación para código: " + $("#codigo").val());
                        enviaAjax(datos); // Envía los datos al servidor
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Mensaje de cancelación
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "Insumo no eliminado",
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

    // Inicializar Select2 en el select de presentacion
$('#presentacion').select2({
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

// Función para validar el envío de datos
function validarenvio() {
    // Validaciones para cada campo del formulario
    if (validarkeyup(/^[^"']{1,20}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El código del insumo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombre"), $("#snombre"), "Solo letras  entre 3 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del insumo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 

    if ($("#marca").val().trim() !== "" &&
        validarkeyup(/^[^"']{1,50}$/, $("#marca"), $("#smarca"), "La marca debe tener entre 1 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La marca debe tener entre 1 y 50 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
    return false;
}

    if (validarkeyup(/^[0-9]{1,10}$/, $("#stock_total"), $("#sstock_total"), "El stock debe ser mayor o igual a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El stock del insumo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[0-9]{1,10}$/, $("#stock_minimo"), $("#sstock_minimo"), "El stock mínimo debe ser mayor o igual a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El stock mínimo del insumo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }
    if (validarkeyup(/^[0-9.]{1,10}$/, $("#precio"), $("#sprecio"), "El precio debe ser mayor o igual a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El precio del insumo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }  

if ($("#presentacion").val() === null) {
    Swal.fire({
        title: "¡ERROR!",
        text: "Debe seleccionar una presentación del producto",
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
// Función para llenar el formulario con datos de un insumo
function pone(pos, accion) {
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#marca").prop("disabled", false);
        $("#stock_total").prop("disabled", false);
        $("#stock_minimo").prop("disabled", false);      
        $("#precio").prop("disabled", false);
        $("#imagen").prop("disabled", false);
        $("#presentacion").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#marca").prop("disabled", true);
        $("#stock_total").prop("disabled", true);
        $("#stock_minimo").prop("disabled", true);
        $("#precio").prop("disabled", true);      
        $("#imagen").prop("disabled", true);
        $("#presentacion").prop("disabled", true);          
    }

    // Obtener valores de cada celda
    $("#codigo").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#marca").val($(linea).find("td:eq(3)").text());
    $("#stock_total").val($(linea).find("td:eq(4)").text().replace(/\D/g, ''));
    $("#stock_minimo").val($(linea).find("td:eq(5)").text());
    
    // Obtener valor de presentación
    var presentacionId = $(linea).find("td:eq(6)").attr("data-presentacion-id");
    $("#presentacion").val(presentacionId).trigger('change');
    
    // Obtener precio (puede estar en índice 7 o 6 dependiendo del rol del usuario)
    var precioText = $(linea).find("td:eq(7)").text().trim();
    if (precioText.includes("$")) {
        var precio = precioText.replace("$", "");
        $("#precio").val(precio);
    } else {
        // Si el precio no está en el índice 7, puede ser porque el usuario no es administrador
        $("#precio").val("");
    }
    
    // Cargar imagen (índice puede variar según el rol del usuario)
    var imgIndex = 8;
    if (!precioText.includes("$")) {
        imgIndex = 7; // Ajustar índice si no hay columna de precio
    }
    
    var imagenSrc = $(linea).find("td:eq(" + imgIndex + ") img").attr("src");
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show();
        // Eliminar duplicación de imagen_url que no existe en el formulario
        try {
            fetch(imagenSrc)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], imagenSrc.split('/').pop(), { type: blob.type });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                $("#imagen")[0].files = dataTransfer.files;
            });
        } catch (error) {
            console.error("Error al cargar la imagen:", error);
        }
    } else {
        $("#imagen_actual").attr("src", "").hide();
    }
    $("#modal1").modal("show");
}

function limpia() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#marca").val("");
    $("#stock_total").val("");
    $("#stock_minimo").val("");        
    $("#precio").val("");
    $("#presentacion").val("").trigger('change'); 
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#codigo").prop("disabled", false);
    $("#nombre").prop("disabled", false);
    $("#marca").prop("disabled", false);
    $("#stock_total").prop("disabled", false);
    $("#stock_minimo").prop("disabled", false);      
    $("#precio").prop("disabled", false);
    $("#presentacion").prop("disabled", false);
    $("#imagen").prop("disabled", false);  
}

function verificarStock(stockTotal, stockMinimo) {
    if (parseInt(stockTotal) <= parseInt(stockMinimo)) {
        return '<span class="badge bg-danger">Stock bajo</span>';
    }
    return '';
}

function mostrarAlertaStockBajo(insumosBajoStock) {
    let mensaje = "Los siguientes insumos tienen stock bajo:\n";
    insumosBajoStock.forEach(insumo => {
        mensaje += `- ${insumo.nombre} (Stock actual: ${insumo.stock_total}, Stock mínimo: ${insumo.stock_minimo})\n`;
    });

    Swal.fire({
        title: "¡Alerta de Stock Bajo!",
        text: mensaje,
        icon: "warning",
        confirmButtonText: "Entendido"
    });
}

function cargarOpciones() {
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'cargarOpciones' },
        success: function(respuesta) {
            var datos = JSON.parse(respuesta);
            
            // Cargar presentaciones
            datos.presentaciones.forEach(function(presentacion) {
                $('#presentacion').append($('<option>', {
                    value: presentacion.id,
                    text: presentacion.nombre
                }));
            }); 
        }
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
    console.log("Enviando petición AJAX...");
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
            console.log("Enviando datos:", datos);
        },
        timeout: 10000,
        success: function (respuesta) {
            console.log("Respuesta recibida:", respuesta);
            try {
                var lee = JSON.parse(respuesta);
                console.log("Datos parseados:", lee);
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
                    console.log("Resultado eliminar:", lee);
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
                    console.error("Error del servidor:", lee.mensaje);
                    Swal.fire({
                        title: "Error",
                        text: lee.mensaje,
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e, "Respuesta:", respuesta);
                Swal.fire({
                    title: "Error",
                    text: "Error en JSON: " + e.name + " - " + e.message,
                    icon: "error"
                });
            }
        },
        error: function (request, status, err) {
            console.error("Error en la petición AJAX:", status, err, request);
            if (status == "timeout") {
                Swal.fire({
                    title: "Error",
                    text: "Servidor ocupado, intente de nuevo",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "ERROR: " + status + " - " + err,
                    icon: "error"
                });
            }
        },
        complete: function () { 
            $("#loader").hide(); // Ocultar loader al completar
            console.log("Petición AJAX completada");
        }
    });
}