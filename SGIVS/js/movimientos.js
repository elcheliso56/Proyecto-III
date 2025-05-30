function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaingresos")) {
        $("#tablaingresos").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaingresos")) {
        $("#tablaingresos").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron registros",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                searchPlaceholder: "Buscar...",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            autoWidth: false,
            responsive: true,
            order: [[0, "desc"]],
            columnDefs: [
                { orderable: false, targets: -1 }, // Deshabilitar ordenamiento en la columna de acciones
                { className: "text-center", targets: "_all" } // Centrar todo el contenido
            ]
        });
    }
}

$(document).ready(function () {
    consultar(); // Llama a la función consultar al cargar el documento
    cargarOpciones(); // Carga las opciones para selectores

    // Inicializar el buscador
    $("#buscadorMovimientos").on("keyup", function() {
        $("#tablaingresos").DataTable().search(this.value).draw();
    });

    // Validaciones para el campo de código


    // Validaciones para el campo de nombre
// Solo permite letras, espacios y algunas letras acentuadas al presionar teclas
$("#descripcion").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});

// Valida que el texto tenga entre 3 y 90 letras al soltar teclas
$("#descripcion").on("keyup", function () {
    validarkeyup(/^[A-Za-z\s\u00f1\u00d1\u00E0-\u00FC]{3,90}$/, $(this), $("#snombre"), "Solo letras entre 3 y 90 caracteres");
});

// Solo permite números al presionar teclas
$("#monto").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
});

// Valida que tenga entre 1 y 9 dígitos al soltar teclas
$("#monto").on("keyup", function () {
    validarkeyup(/^[0-9]{1,9}$/, $(this), $("#sprecio_venta"), "Solo números, máximo 9 dígitos");
});



    

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo registro
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo producto?",
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
                        datos.append('descripcion', $("#descripcion").val());
                        datos.append('monto', $("#monto").val());
                        datos.append('fecha', $("#fecha").val());
                        datos.append('origen', $("#origen").val());

                        
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                }
            });
            } }

            else if ($(this).text() == "MODIFICAR") {
            // Confirmación para modificar un registro existente
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
                        text: "¿Deseas modificar este producto?",
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
                        datos.append('id', $("#id").val()); // Se agrega el código del producto
                        datos.append('descripcion', $("#descripcion").val());
                        datos.append('monto', $("#monto").val());
                        datos.append('origen', $("#origen").val());
                        datos.append('fecha', $("#fecha").val());
                       
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Mensaje de cancelación
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El registro no ha sido modificado",
                        icon: "error"
                    });
                }
            });
                } }
                else if ($(this).text() == "ELIMINAR") {
            // Validación antes de eliminar un producto
                    
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
                        datos.append('id', $("#id").val()); // Se agrega el código del producto
                        enviaAjax(datos); // Envía los datos al servidor
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Mensaje de cancelación
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "registro no eliminado",
                            icon: "error"
                        });
                    }
                });
                    }
                }
            );

    // Manejo del clic en el botón incluir
$("#incluir").on("click", function () {
        limpia(); // Limpia los campos del formulario
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón a 'INCLUIR'
        $("#modal1").modal("show"); // Muestra el modal
    });

    // Inicializar Select2 en el select de tipo_unidad
$('#tipo_unidad').select2({
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

$('#categoria').select2({
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
// Función para llenar el formulario con datos de un registro existente
// y habilitar o deshabilitar campos según la acción (modificar o eliminar)
function pone(pos, accion) {
    linea = $(pos).closest('tr');
    $("#id").val($(linea).attr("data-id")); // Leer el ID del atributo data-id de la fila
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

    var pc = $(linea).find("td:eq(3)").text().trim();
    var pcp = pc.split("$");
    var precio_compra = pcp[1];
    var pv = $(linea).find("td:eq(4)").text().trim();
    var pvp = pc.split("$");
    var precio_venta = pcp[1];


    $("#descripcion").val($(linea).find("td:eq(1)").text());
    $("#monto").val($(linea).find("td:eq(2)").text().replace(/\D/g, ''));
    $("#origen").val($(linea).find("td:eq(4)").text()).trigger('change');
    $("#fecha").val($(linea).find("td:eq(3)").text());


 
    
    // Seleccionar la categoría correcta
    var categoriaId = $(linea).find("td:eq(10)").attr("data-categoria-id");
    $("#categoria").val(categoriaId).trigger('change');
    
    // Seleccionar la ubicación correcta
    var ubicacionId = $(linea).find("td:eq(11)").attr("data-ubicacion-id");
    $("#ubicacion").val(ubicacionId).trigger('change');
    

    // Cargar imagen
    
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
            $("#loader").show(); // Mostrar loader 
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

function verificarStock(stockTotal, stockMinimo) {
    if (parseInt(stockTotal) <= parseInt(stockMinimo)) {
        return '<span class="badge bg-danger">Stock bajo</span>';
    }
    return '';
}

function mostrarAlertaStockBajo(productosBajoStock) {
    let mensaje = "Los siguientes productos tienen stock bajo:\n";
    productosBajoStock.forEach(producto => {
        mensaje += `- ${producto.nombre} (Stock actual: ${producto.stock_total}, Stock mínimo: ${producto.stock_minimo})\n`;
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
            
            // Cargar categorías
            datos.categorias.forEach(function(categoria) {
                $('#categoria').append($('<option>', {
                    value: categoria.id,
                    text: categoria.nombre
                }));
            });
            
            datos.ubicaciones.forEach(function(ubicacion) {
                $('#ubicacion').append($('<option>', {
                    value: ubicacion.id,
                    text: ubicacion.nombre
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

function validarPrecios() {
    const precioCompra = parseFloat($("#precio_compra").val());
    const precioVenta = parseFloat($("#precio_venta").val());
    if (precioVenta < precioCompra ) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El precio de venta debe ser mayor al precio de compra",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }    
    return true;
}