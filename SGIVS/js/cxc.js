function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablacxc")) {
        $("#tablacxc").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablacxc")) {
        $("#tablacxc").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron cuentas por cobrar",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay cuentas por cobrar registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar cuenta...",
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
            order: [[0, "asc"]], // Ordena por la primera columna
        });
    }
}

$(document).ready(function () {
    consultar(); // Llama a la función consultar al cargar el documento
    cargarOpciones(); // Carga las opciones para selectores

    // Validaciones para el campo de paciente
    $("#paciente_id").on("change", function () {
        if ($(this).val() === null) {
            $("#spaciente_id").text("El paciente es obligatorio");
        } else {
            $("#spaciente_id").text("");
        }
    });

    // Validaciones para el campo de monto total
    $("#monto_total").on("keypress", function (e) {
        validarkeypress(/^[0-9.]*$/, e);
    });

    $("#monto_total").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,9}$/, $(this), $("#smonto_total"), "Solo números, máximo 9 dígitos");
    });

    // Validaciones para el campo de fecha de emisión
    $("#fecha_emision").on("change", function () {
        if ($(this).val() === "") {
            $("#sfecha_emision").text("La fecha de emisión es obligatoria");
        } else {
            $("#sfecha_emision").text("");
        }
    });

    // Validaciones para el campo de fecha de vencimiento
    $("#fecha_vencimiento").on("change", function () {
        if ($(this).val() === "") {
            $("#sfecha_vencimiento").text("La fecha de vencimiento es obligatoria");
        } else {
            $("#sfecha_vencimiento").text("");
        }
    });

    // Validaciones para el campo de descripción
    $("#descripcion").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#descripcion").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]{3,90}$/, $(this), $("#sdescripcion"), "Solo letras y números entre 3 y 90 caracteres");
    });

    // Validaciones para el campo de referencia
    $("#referencia").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9-]*$/, e);
    });

    $("#referencia").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9-]{3,20}$/, $(this), $("#sreferencia"), "Solo letras, números y guiones entre 3 y 20 caracteres");
    });

    // Validaciones para el campo de número de cuotas
    $("#numero_cuotas").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    $("#numero_cuotas").on("keyup", function () {
        validarkeyup(/^[1-9][0-9]*$/, $(this), $("#snumero_cuotas"), "Debe ser un número mayor a 0");
    });

    // Validaciones para el campo de frecuencia de pago
    $("#frecuencia_pago").on("change", function () {
        if ($(this).val() === null) {
            $("#sfrecuencia_pago").text("La frecuencia de pago es obligatoria");
        } else {
            $("#sfrecuencia_pago").text("");
        }
    });

    // Validaciones para el campo de monto de pago
    $("#monto_pago").on("keypress", function (e) {
        validarkeypress(/^[0-9.]*$/, e);
    });

    $("#monto_pago").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,9}$/, $(this), $("#smonto_pago"), "Solo números, máximo 9 dígitos");
    });

    // Validaciones para el campo de fecha de pago
    $("#fecha_pago").on("change", function () {
        if ($(this).val() === "") {
            $("#sfecha_pago").text("La fecha de pago es obligatoria");
        } else {
            $("#sfecha_pago").text("");
        }
    });

    // Validaciones para el campo de método de pago
    $("#metodo_pago").on("change", function () {
        if ($(this).val() === null) {
            $("#smetodo_pago").text("El método de pago es obligatorio");
        } else {
            $("#smetodo_pago").text("");
        }
    });

    // Validaciones para el campo de referencia de pago
    $("#referencia_pago").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9-]*$/, e);
    });

    $("#referencia_pago").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9-]{3,20}$/, $(this), $("#sreferencia_pago"), "Solo letras, números y guiones entre 3 y 20 caracteres");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                // Confirmación para incluir un nuevo registro
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar esta cuenta por cobrar?",
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
                            datos.append('accion', 'incluir'); // Acción para incluir
                            // Se agregan los datos del formulario
                            datos.append('paciente_id', $("#paciente_id").val());
                            datos.append('monto_total', $("#monto_total").val());
                            datos.append('fecha_emision', $("#fecha_emision").val());
                            datos.append('fecha_vencimiento', $("#fecha_vencimiento").val());
                            datos.append('descripcion', $("#descripcion").val());
                            datos.append('referencia', $("#referencia").val());
                            datos.append('numero_cuotas', $("#numero_cuotas").val());
                            datos.append('frecuencia_pago', $("#frecuencia_pago").val());
                            datos.append('cuenta_id', $("#cuenta_id").val());
                            
                            console.log("Datos a enviar:", {
                                paciente_id: $("#paciente_id").val(),
                                cuenta_id: $("#cuenta_id").val(),
                                monto_total: $("#monto_total").val(),
                                fecha_emision: $("#fecha_emision").val(),
                                fecha_vencimiento: $("#fecha_vencimiento").val(),
                                descripcion: $("#descripcion").val(),
                                referencia: $("#referencia").val(),
                                numero_cuotas: $("#numero_cuotas").val(),
                                frecuencia_pago: $("#frecuencia_pago").val()
                            });
                            
                            enviaAjax(datos); // Envía los datos al servidor
                        }
                    }
                });
            }
        }
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
                    text: "¿Deseas modificar esta cuenta por cobrar?",
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
                            datos.append('id', $("#id").val());
                            datos.append('paciente_id', $("#paciente_id").val());
                            datos.append('monto_total', $("#monto_total").val());
                            datos.append('monto_pendiente', $("#monto_pendiente").val());
                            datos.append('fecha_emision', $("#fecha_emision").val());
                            datos.append('fecha_vencimiento', $("#fecha_vencimiento").val());
                            datos.append('estado', $("#estado").val());
                            datos.append('descripcion', $("#descripcion").val());
                            datos.append('referencia', $("#referencia").val());
                            datos.append('cuenta_id', $("#cuenta_id").val());
                           
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
            }
        }
        else if ($(this).text() == "ELIMINAR") {
            // Validación antes de eliminar una cuenta por cobrar
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
                    datos.append('id', $("#id").val()); // Se agrega el id de la cuenta
                    datos.append('cuenta_id', $("#cuenta_id").val());
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
    });

    // Manejo del clic en el botón incluir
    $("#incluir").on("click", function () {
        limpia(); // Limpia los campos del formulario
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón a 'INCLUIR'
        $("#campos_modificacion").hide(); // Oculta los campos de modificación
        $("#modal1").modal("show"); // Muestra el modal
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

// Función para validar el envío de datos
function validarenvio() {
    let valido = true;

    // Validar paciente
    if ($("#paciente_id").val() === null || $("#paciente_id").val() === "") {
        $("#spaciente_id").text("El paciente es obligatorio");
        valido = false;
    }

    // Validar monto total
    if ($("#monto_total").val() === "") {
        $("#smonto_total").text("El monto total es obligatorio");
        valido = false;
    }

    // Validar número de cuotas
    if ($("#numero_cuotas").val() === "") {
        $("#snumero_cuotas").text("El número de cuotas es obligatorio");
        valido = false;
    }

    // Validar frecuencia de pago
    if ($("#frecuencia_pago").val() === null || $("#frecuencia_pago").val() === "") {
        $("#sfrecuencia_pago").text("La frecuencia de pago es obligatoria");
        valido = false;
    }

    // Validar fecha de emisión
    if ($("#fecha_emision").val() === "") {
        $("#sfecha_emision").text("La fecha de emisión es obligatoria");
        valido = false;
    }

    // Validar fecha de vencimiento
    if ($("#fecha_vencimiento").val() === "") {
        $("#sfecha_vencimiento").text("La fecha de vencimiento es obligatoria");
        valido = false;
    }

    // Validar descripción
    if ($("#descripcion").val() === "") {
        $("#sdescripcion").text("La descripción es obligatoria");
        valido = false;
    }

    // Validar referencia
    if ($("#referencia").val() === "") {
        $("#sreferencia").text("La referencia es obligatoria");
        valido = false;
    }

    // Validar cuenta
    if ($("#cuenta_id").val() === null || $("#cuenta_id").val() === "") {
        $("#scuenta_id").text("Debe seleccionar una cuenta");
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
    var cuenta_id = $(linea).attr("data-id");
    $("#id").val(cuenta_id);
    
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#campos_modificacion").show();
        cargarCuotas(cuenta_id);
        $("#id").prop("disabled", true);
        $("#paciente_id").prop("disabled", false);
        $("#monto_total").prop("disabled", false);
        $("#monto_pendiente").prop("disabled", false);
        $("#fecha_emision").prop("disabled", false);
        $("#fecha_vencimiento").prop("disabled", false);
        $("#estado").prop("disabled", false);
        $("#descripcion").prop("disabled", false);
        $("#referencia").prop("disabled", false);
    } else if (accion == 2) {
        // Acción para abonar
        $("#id_cuenta_abono").val(cuenta_id);
        
        // Llenar la información de la cuenta
        $("#info_paciente").text($(linea).find("td:eq(1)").text());
        $("#info_monto_total").text($(linea).find("td:eq(2)").text());
        $("#info_monto_pendiente").text($(linea).find("td:eq(3)").text());
        
        // Limpiar y preparar los campos del abono
        $("#monto_abono").val("");
        $("#fecha_abono").val(new Date().toISOString().split('T')[0]);
        $("#referencia_abono").val("");
        
        // Cargar las cuotas pendientes
        cargarCuotasPendientes(cuenta_id);
        
        // Mostrar el modal de abono
        $("#modalAbono").modal("show");
    } else {
        $("#proceso").text("ELIMINAR");
        $("#campos_modificacion").hide();
        $("#id").prop("disabled", true);
        $("#paciente_id").prop("disabled", true);
        $("#monto_total").prop("disabled", true);
        $("#monto_pendiente").prop("disabled", true);
        $("#fecha_emision").prop("disabled", true);
        $("#fecha_vencimiento").prop("disabled", true);
        $("#estado").prop("disabled", true);
        $("#descripcion").prop("disabled", true);
        $("#referencia").prop("disabled", true);
    }

    // Obtener el ID del paciente del data attribute
    var pacienteId = $(linea).find("td:eq(1)").attr("data-paciente-id");
    $("#paciente_id").val(pacienteId).trigger('change');
    
    $("#monto_total").val($(linea).find("td:eq(2)").text().replace(/[^0-9.]/g, ''));
    $("#monto_pendiente").val($(linea).find("td:eq(3)").text().replace(/[^0-9.]/g, ''));
    $("#fecha_emision").val($(linea).find("td:eq(4)").text());
    $("#fecha_vencimiento").val($(linea).find("td:eq(5)").text());
    $("#estado").val($(linea).find("td:eq(6)").text()).trigger('change');
    $("#referencia").val($(linea).find("td:eq(7)").text());
    
    if (accion != 2) {
        $("#modal1").modal("show");
    }
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
                } else if (lee.resultado == "incluir" || lee.resultado == "modificar" || lee.resultado == "ok") {
                    const esExito = lee.mensaje.toLowerCase().includes('exito') || lee.mensaje.toLowerCase().includes('éxito') || lee.resultado === 'ok';
                    Swal.fire({
                        title: esExito ? "¡Éxito!" : "Error",
                        text: lee.mensaje,
                        icon: esExito ? "success" : "error"
                    }).then(() => {
                        if (esExito) {
                            // Cerrar el modal correspondiente
                            if ($("#modalAbono").is(":visible")) {
                                $("#modalAbono").modal("hide");
                            } else {
                                $("#modal1").modal("hide");
                            }
                            // Limpiar el formulario
                            limpia();
                            // Actualizar la tabla
                            consultar();
                        }
                    });
                } else if (lee.resultado == "eliminar") {
                    const esExito = lee.mensaje.toLowerCase().includes('exito') || lee.mensaje.toLowerCase().includes('éxito');
                    Swal.fire({
                        title: esExito ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: esExito ? "success" : "error"
                    }).then(() => {
                        if (esExito) {
                            $("#modal1").modal("hide");
                            consultar();
                        }
                    });
                } else if (lee.resultado == "error") {
                    Swal.fire({
                        title: "Error",
                        text: lee.mensaje,
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
                console.error("Respuesta recibida:", respuesta);
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
    $("#paciente_id").val("").trigger('change');
    $("#monto_total").val("");
    $("#numero_cuotas").val("");
    $("#frecuencia_pago").val("").trigger('change');
    $("#fecha_emision").val("");
    $("#fecha_vencimiento").val("");
    $("#estado").val("pendiente").trigger('change');
    $("#descripcion").val("");
    $("#referencia").val("");
    $("#resultadoCuotas").html("");

    $("#paciente_id").prop("disabled", false);
    $("#monto_total").prop("disabled", false);
    $("#numero_cuotas").prop("disabled", false);
    $("#frecuencia_pago").prop("disabled", false);
    $("#fecha_emision").prop("disabled", false);
    $("#fecha_vencimiento").prop("disabled", false);
    $("#estado").prop("disabled", false);
    $("#descripcion").prop("disabled", false);
    $("#referencia").prop("disabled", false);
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
                
                // Limpiar los selects antes de agregar las opciones
                $('#paciente_id').empty();
                $('#cuenta_id').empty();
                
                // Agregar las opciones por defecto
                $('#paciente_id').append($('<option>', {
                    value: '',
                    text: 'Seleccione un paciente',
                    selected: true,
                    disabled: true
                }));

                $('#cuenta_id').append($('<option>', {
                    value: '',
                    text: 'Seleccione una cuenta',
                    selected: true,
                    disabled: true
                }));
                
                // Verificar si hay pacientes y agregarlos al select
                if (datos.pacientes && datos.pacientes.length > 0) {
                    datos.pacientes.forEach(function(paciente) {
                        $('#paciente_id').append($('<option>', {
                            value: paciente.id,
                            text: paciente.nombre + ' ' + paciente.apellido
                        }));
                    });
                }

                // Verificar si hay cuentas y agregarlas al select
                if (datos.cuentas && datos.cuentas.length > 0) {
                    datos.cuentas.forEach(function(cuenta) {
                        $('#cuenta_id').append($('<option>', {
                            value: cuenta.id,
                            text: cuenta.nombre + ' (' + cuenta.tipo + ' - ' + cuenta.moneda + ')'
                        }));
                    });
                }
                
                // Inicializar Select2 en ambos selects
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

// Función para registrar un pago
function registrarPago(cuota_id) {
    $("#cuota_id").val(cuota_id);
    $("#monto_pago").val("");
    $("#fecha_pago").val(new Date().toISOString().split('T')[0]);
    $("#metodo_pago").val("").trigger('change');
    $("#referencia_pago").val("");
    $("#modalPago").modal("show");
}

// Función para ver el detalle de un pago
function verDetallePago(cuota_id) {
    $.ajax({
        url: '',
        type: 'POST',
        data: { 
            accion: 'consultarDetallePago',
            cuota_id: cuota_id
        },
        success: function(respuesta) {
            try {
                var datos = JSON.parse(respuesta);
                if (datos.resultado == 'consultar') {
                    $("#detalle_monto").text(datos.pago.monto + " USD");
                    $("#detalle_fecha").text(datos.pago.fecha_pago);
                    $("#detalle_metodo").text(datos.pago.metodo_pago);
                    $("#detalle_referencia").text(datos.pago.referencia);
                    $("#modalDetallePago").modal("show");
                } else {
                    Swal.fire({
                        title: "Error",
                        text: datos.mensaje,
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
            }
        }
    });
}

// Manejo del clic en el botón procesar pago
$("#procesarPago").on("click", function () {
    if (validarPago()) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Deseas registrar este pago?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, registrar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append('accion', 'registrarPago');
                datos.append('cuota_id', $("#cuota_id").val());
                datos.append('monto', $("#monto_pago").val());
                datos.append('fecha_pago', $("#fecha_pago").val());
                datos.append('metodo_pago', $("#metodo_pago").val());
                datos.append('referencia', $("#referencia_pago").val());
                
                enviaAjax(datos);
            }
        });
    }
});

// Función para validar el formulario de pago
function validarPago() {
    let valido = true;

    if ($("#monto_pago").val() === "") {
        $("#smonto_pago").text("El monto es obligatorio");
        valido = false;
    }

    if ($("#fecha_pago").val() === "") {
        $("#sfecha_pago").text("La fecha es obligatoria");
        valido = false;
    }

    if ($("#metodo_pago").val() === null) {
        $("#smetodo_pago").text("El método de pago es obligatorio");
        valido = false;
    }

    if ($("#referencia_pago").val() === "") {
        $("#sreferencia_pago").text("La referencia es obligatoria");
        valido = false;
    }

    return valido;
}

// Función para cargar las cuotas al modificar una cuenta
function cargarCuotas(cuenta_id) {
    $.ajax({
        url: '',
        type: 'POST',
        data: { 
            accion: 'consultarCuotas',
            id: cuenta_id
        },
        success: function(respuesta) {
            try {
                var datos = JSON.parse(respuesta);
                if (datos.resultado == 'consultar') {
                    $("#resultadoCuotas").html(datos.mensaje);
                } else {
                    Swal.fire({
                        title: "Error",
                        text: datos.mensaje,
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
            }
        }
    });
}

// Función para cargar las cuotas pendientes
function cargarCuotasPendientes(cuenta_id) {
    $.ajax({
        url: '',
        type: 'POST',
        data: { 
            accion: 'consultarCuotasPendientes',
            id: cuenta_id
        },
        success: function(respuesta) {
            try {
                var datos = JSON.parse(respuesta);
                if (datos.resultado == 'consultar') {
                    $("#resultadoCuotasPendientes").html(datos.mensaje);
                } else {
                    Swal.fire({
                        title: "Error",
                        text: datos.mensaje,
                        icon: "error"
                    });
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

// Función para validar el formulario de abono
function validarAbono() {
    let valido = true;

    if ($("#monto_abono").val() === "") {
        $("#smonto_abono").text("El monto es obligatorio");
        valido = false;
    }

    if ($("#fecha_abono").val() === "") {
        $("#sfecha_abono").text("La fecha es obligatoria");
        valido = false;
    }

    if ($("#referencia_abono").val() === "") {
        $("#sreferencia_abono").text("La referencia es obligatoria");
        valido = false;
    }

    return valido;
}

// Manejo del clic en el botón procesar abono
$("#procesarAbono").on("click", function () {
    if (validarAbono()) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Deseas registrar este abono?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, registrar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append('accion', 'procesarAbono');
                datos.append('id_cuenta', $("#id_cuenta_abono").val());
                datos.append('monto', $("#monto_abono").val());
                datos.append('fecha_pago', $("#fecha_abono").val());
                datos.append('referencia', $("#referencia_abono").val());
                
                enviaAjax(datos);
            }
        });
    }
});