function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function destruyeDT() {
    if ($.fn.DataTable.isDataTable("#tablabancos")) {
        $("#tablabancos").DataTable().destroy();
    }
}

function crearDT() {
    if (!$.fn.DataTable.isDataTable("#tablabancos")) {
        $("#tablabancos").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron bancos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay bancos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar banco...",
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

$(document).ready(function () {
    consultar();

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombre"), "Solo letras y números entre 3 y 100 caracteres");
    });

    // Validaciones para código SWIFT
    $("#codigo_swift").on("keypress", function (e) {
        validarkeypress(/^[A-Z]*$/, e);
    });

    $("#codigo_swift").on("keyup", function () {
        var valor = $(this).val();
        if (valor === "") {
            $("#scodigo_swift").text("");
            return 1;
        }
        validarkeyup(/^[A-Z]{8,11}$/, $(this), $("#scodigo_swift"), "Solo letras mayúsculas entre 8 y 11 caracteres");
    });

    // Validaciones para código local
    $("#codigo_local").on("keypress", function (e) {
        validarkeypress(/^[A-Z0-9]*$/, e);
    });

    $("#codigo_local").on("keyup", function () {
        var valor = $(this).val();
        if (valor === "") {
            $("#scodigo_local").text("");
            return 1;
        }
        validarkeyup(/^[A-Z0-9]{2,20}$/, $(this), $("#scodigo_local"), "Solo letras mayúsculas y números entre 2 y 20 caracteres");
    });

    // Manejo del campo logo
    $("#logo").on("change", function() {
        var file = this.files[0];
        if (file) {
            // Validar tamaño (2MB máximo)
            if (file.size > 2 * 1024 * 1024) {
                $("#slogo").text("El archivo es demasiado grande. Máximo 2MB");
                this.value = "";
                $("#logo_preview").hide();
                return;
            }

            // Validar tipo de archivo
            var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                $("#slogo").text("Solo se permiten archivos JPG, JPEG, PNG y GIF");
                this.value = "";
                $("#logo_preview").hide();
                return;
            }

            // Mostrar vista previa
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#preview_img").attr("src", e.target.result);
                $("#logo_preview").show();
                $("#slogo").text("");
            };
            reader.readAsDataURL(file);
        } else {
            $("#logo_preview").hide();
        }
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas registrar este banco?",
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
                            datos.append('nombre', $("#nombre").val());
                            datos.append('codigo_swift', $("#codigo_swift").val());
                            datos.append('codigo_local', $("#codigo_local").val());
                            datos.append('activo', $("#activo").val());
                            
                            // Agregar el archivo del logo si existe
                            var logoFile = $("#logo")[0].files[0];
                            if (logoFile) {
                                datos.append('logo', logoFile);
                            }
                            
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
                    text: "¿Deseas modificar este banco?",
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
                            datos.append('nombre', $("#nombre").val());
                            datos.append('codigo_swift', $("#codigo_swift").val());
                            datos.append('codigo_local', $("#codigo_local").val());
                            datos.append('activo', $("#activo").val());
                            
                            // Agregar el archivo del logo si existe
                            var logoFile = $("#logo")[0].files[0];
                            if (logoFile) {
                                datos.append('logo', logoFile);
                            }
                            
                            enviaAjax(datos);
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "El banco no ha sido modificado",
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
                        text: "Banco no eliminado",
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

    // Validar nombre
    if ($("#nombre").val() === "") {
        $("#snombre").text("El nombre es obligatorio");
        valido = false;
    }

    // Validar código SWIFT (opcional)
    var codigoSwift = $("#codigo_swift").val();
    if (codigoSwift !== "" && (codigoSwift.length < 8 || codigoSwift.length > 11)) {
        $("#scodigo_swift").text("El código SWIFT debe tener entre 8 y 11 caracteres");
        valido = false;
    }

    // Validar código local (opcional)
    var codigoLocal = $("#codigo_local").val();
    if (codigoLocal !== "" && (codigoLocal.length < 2 || codigoLocal.length > 20)) {
        $("#scodigo_local").text("El código local debe tener entre 2 y 20 caracteres");
        valido = false;
    }

    // Validar logo (opcional)
    var logoFile = $("#logo")[0].files[0];
    if (logoFile) {
        if (logoFile.size > 2 * 1024 * 1024) {
            $("#slogo").text("El archivo es demasiado grande. Máximo 2MB");
            valido = false;
        }
        
        var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(logoFile.type)) {
            $("#slogo").text("Solo se permiten archivos JPG, JPEG, PNG y GIF");
            valido = false;
        }
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
    $("#id").val($(linea).attr("data-id"));
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#id").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#codigo_swift").prop("disabled", true);
        $("#codigo_local").prop("disabled", true);
        $("#logo").prop("disabled", false);
        $("#activo").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#id").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#codigo_swift").prop("disabled", true);
        $("#codigo_local").prop("disabled", true);
        $("#logo").prop("disabled", true);
        $("#activo").prop("disabled", true);
    }

    $("#nombre").val($(linea).find("td:eq(1)").text().trim());
    $("#codigo_swift").val($(linea).find("td:eq(2)").text());
    $("#codigo_local").val($(linea).find("td:eq(3)").text());
    $("#activo").val($(linea).find("td:eq(4)").text() === "Activo" ? "1" : "0").trigger('change');
    
    var logoImg = $(linea).find("td:eq(1) img");
    if (logoImg.length > 0) {
        $("#preview_img").attr("src", logoImg.attr("src"));
        $("#logo_preview").show();
    } else {
        $("#logo_preview").hide();
    }
    
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
            $("#loader").show();
        },
        timeout: 10000,
        success: function (respuesta) {
            try {
                // Verificar si la respuesta está vacía
                if (!respuesta || respuesta.trim() === '') {
                    throw new Error('La respuesta del servidor está vacía');
                }

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
                console.error('Error al procesar la respuesta:', e);
                console.error('Respuesta recibida:', respuesta);
                Swal.fire({
                    title: "Error",
                    text: "Error al procesar la respuesta del servidor: " + e.message,
                    icon: "error"
                });
            }
        },
        error: function (request, status, err) {
            console.error('Error en la petición AJAX:', {request, status, err});
            if (status == "timeout") {
                Swal.fire({
                    title: "Error",
                    text: "Servidor ocupado, intente de nuevo",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Error en la comunicación con el servidor: " + err,
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
    $("#nombre").val("");
    $("#codigo_swift").val("");
    $("#codigo_local").val("");
    $("#logo").val("");
    $("#activo").val("1");
    $("#logo_preview").hide();

    $("#nombre").prop("disabled", false);
    $("#codigo_swift").prop("disabled", false);
    $("#codigo_local").prop("disabled", false);
    $("#logo").prop("disabled", false);
    $("#activo").prop("disabled", false);
} 