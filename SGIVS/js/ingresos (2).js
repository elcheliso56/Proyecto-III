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
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron productos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay productos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar producto...",
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
    cargarOpciones(); // Carga las opciones para selectores

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
            $("#sfecha").text("La fecha es obligatoria");
        } else {
            $("#sfecha").text("");
        }
    });

    // Validaciones para el campo de origen
    $("#origen").on("change", function () {
        if ($(this).val() === null) {
            $("#sorigen").text("El origen es obligatorio");
        } else {
            $("#sorigen").text("");
        }
    });

    // Validaciones para el campo de cuenta
    $("#cuenta_id").on("change", function () {
        if ($(this).val() === null) {
            $("#scuenta_id").text("La cuenta es obligatoria");
        } else {
            $("#scuenta_id").text("");
        }
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar este ingreso?",
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
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas modificar este ingreso?",
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
                            text: "El ingreso no ha sido modificado",
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
                        text: "Ingreso no eliminado",
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
        $("#sdescripcion").text("La descripción es obligatoria");
        valido = false;
    }

    // Validar monto
    if ($("#monto").val() === "") {
        $("#smonto").text("El monto es obligatorio");
        valido = false;
    }

    // Validar fecha
    if ($("#fecha").val() === "") {
        $("#sfecha").text("La fecha es obligatoria");
        valido = false;
    }

    // Validar origen
    if ($("#origen").val() === null) {
        $("#sorigen").text("El origen es obligatorio");
        valido = false;
    }

    // Validar cuenta
    if ($("#cuenta_id").val() === null) {
        $("#scuenta_id").text("La cuenta es obligatoria");
        valido = false;
    }

    return valido;
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
    $("#origen").val("").trigger('change');
    $("#cuenta_id").val("").trigger('change');

    $("#descripcion").prop("disabled", false);
    $("#monto").prop("disabled", false);
    $("#fecha").prop("disabled", false);
    $("#origen").prop("disabled", false);
    $("#cuenta_id").prop("disabled", false);
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

// Asegurarse de que cargarOpciones se ejecute cuando el documento esté listo
$(document).ready(function () {
    consultar();
    cargarOpciones();
    
    // Reinicializar Select2 cuando se abra el modal
    $('#modal1').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('#modal1')
        });
    });
});

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