function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaubicacion")) {
        $("#tablaubicacion").DataTable().destroy();
    }
}


    
function crearDT() {
    // Crea la tabla solo si no existe
    if (!$.fn.DataTable.isDataTable("#tablaubicacion")) {
        $("#tablaubicacion").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron historial",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay historial registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar historia...",
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
    $(window).resize(function () {
        $('#tablaubicacion').DataTable().columns.adjust().draw();
    });
}

$(document).ready(function () {
    consultar(); // Llama a la función consultar al cargar el documento

    // Validaciones para el campo nombre
    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });
    $("#nombre").on("keyup", function () {
        validarkeyup(/^[^"']{3,30}$/, $(this), $("#snombre"), "Debe tener entre 3 y 30 caracteres");
    });

    // Validaciones para el campo Apellido
    $("#Apellido").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e); // Letras, números, espacios, comas y puntos
    });
    $("#Apellido").on("keyup", function () {
        validarkeyup(/^[^"']{0,100}$/, $(this), $("#sApellido"), "El Apellido debe tener un máximo de 100 caracteres");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
                // Confirmación para incluir una nueva historia
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir esta nueva Historia?",
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
                            datos.append('nombre', $("#nombre").val());
                            datos.append('Apellido', $("#Apellido").val());
                            datos.append('telefono', $("#telefono").val());
                            datos.append('Sexo', $("#Sexo").val());
                            datos.append('Ocupacion', $("#Ocupacion").val());
                            datos.append('PersonaContacto', $("#PersonaContacto").val());
                            datos.append('Edad', $("#Edad").val());
                            datos.append('correo', $("#correo").val());
                            datos.append('motivo', $("#motivo").val());
                            datos.append('diagnostico', $("#diagnostico").val());
                            datos.append('tratamiento', $("#tratamiento").val());
                            datos.append('medicamentos', $("#medicamentos").val());
                            datos.append('dientesafectados', $("#dientesafectados").val());
                            datos.append('antecedentes', $("#antecedentes").val());
                            datos.append('fechaconsulta', $("#fechaconsulta").val());
                            datos.append('proximacita', $("#proximacita").val());
                            datos.append('observaciones', $("#observaciones").val());

                            /*   if ($("#imagen")[0].files[0]) {
                                   datos.append('imagen', $("#imagen")[0].files[0]); // Agrega imagen si existe
                               }*/
                            enviaAjax(datos); // Envía los datos
                        }
                    }
                });
            }
        }
        else if ($(this).text() == "MODIFICAR") {
            if (validarenvio()) {
                // Confirmación para modificar una historia
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas modificar esta historia?",
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
                            datos.append('nombre', $("#nombre").val());
                          /*  datos.append('descripcion', $("#descripcion").val());
                            if ($("#imagen")[0].files[0]) {
                                datos.append('imagen', $("#imagen")[0].files[0]);
                            }*/
                            enviaAjax(datos); // Envía los datos
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La historia no ha sido modificada",
                            icon: "error"
                        });
                    }
                });
            }
        }
        else if ($(this).text() == "ELIMINAR") {
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
                    datos.append('nombre', $("#nombre").val());
                    enviaAjax(datos); // Envía los datos
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "Ubicación no eliminada",
                        icon: "error"
                    });
                }
            });
        }


    });

   
    $("#incluir").on("click", function () {
        limpia(); // Limpia los campos antes de abrir el modal
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón
        $("#modal1").modal("show"); // Muestra el modal
    });
});
// Manejo del clic en el botón incluir
$("#odontomodal").on("click", function () {
    $("#miModal").modal("show");
    $("#miModal").modal("hide"); // Muestra el modal
});
$("#cierrate").on("click", function () {
    $("#miModal").modal("hide");// Muestra el modal
});
document.addEventListener('DOMContentLoaded', function () {
    // Botón para generar imagen
    document.getElementById('generar-pdf').addEventListener('click', function () {
        const iframe = document.querySelector('#miModal iframe');
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

        html2canvas(iframeDoc.body, {
            scale: 4,
            useCORS: true,
            logging: true,
            backgroundColor: '#FFFFFF'
        }).then(canvas => {
            // Convierte el canvas a imagen PNG y la descarga
            const imgData = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = imgData;
            link.download = 'odontograma.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch(error => {
            console.error('Error al generar imagen:', error);
            alert('No se pudo generar la imagen. Ver consola para detalles.');
        });
    });
});
function validarenvio() {
    // Valida el envío de datos

    // Nombre
    if (validarkeyup(/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/, $("#nombre"), $("#snombre"), "El nombre solo debe contener letras y tener entre 3 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre de la historia es obligatorio y no debe contener números",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Apellido
    if (validarkeyup(/^[^"']{0,100}$/, $("#Apellido"), $("#sApellido"), "El apellido debe tener un máximo de 100 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El apellido debe tener un máximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Ocupacion
    if ($("#Ocupacion").val().trim().length > 50) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La ocupación debe tener un máximo de 50 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Sexo
    if ($("#Sexo").val() === "" || $("#Sexo").val() === null) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Debe seleccionar un sexo",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Persona de Contacto
    if ($("#PersonaContacto").val().trim().length > 50) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La persona de contacto debe tener un máximo de 50 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Teléfono
    // Teléfono (solo números de Venezuela: 0412, 0414, 0416, 0424, 0426, 0212 + 7 dígitos)
    if (
        $("#telefono").val().trim() !== "" &&
        !/^(0412|0414|0416|0424|0426|0212)\d{7}$/.test($("#telefono").val().trim())
    ) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El teléfono debe ser un número venezolano válido (ej: 04141234567, 02121234567)",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Edad
    const edadVal = $("#Edad").val().trim();
    if (
        edadVal !== "" &&
        (!/^\d{1,3}$/.test(edadVal) || parseInt(edadVal, 10) < 0)
    ) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La edad debe ser un número entre 0 y 999",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Correo
    if ($("#correo").val().trim() !== "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($("#correo").val().trim())) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El correo electrónico no es válido",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Motivo
    if ($("#motivo").val().trim().length > 200) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El motivo debe tener un máximo de 200 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Diagnóstico
    if ($("#diagnostico").val().trim().length > 200) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El diagnóstico debe tener un máximo de 200 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Tratamiento
    if ($("#tratamiento").val().trim().length > 200) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El tratamiento debe tener un máximo de 200 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Medicamentos
    if ($("#medicamentos").val().trim().length > 200) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Los medicamentos deben tener un máximo de 200 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Dientes Afectados
    if ($("#dientesafectados").val().trim().length > 100) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Los dientes afectados deben tener un máximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Antecedentes
    if ($("#antecedentes").val().trim().length > 200) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Los antecedentes deben tener un máximo de 200 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Fecha de consulta
    if ($("#fechaconsulta").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "Debe ingresar la fecha de consulta",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Próxima cita (opcional, pero si existe, debe ser fecha válida)
    // No se valida formato aquí, solo si está vacía o no

    // Observaciones
    if ($("#observaciones").val().trim().length > 300) {
        Swal.fire({
            title: "¡ERROR!",
            text: "Las observaciones deben tener un máximo de 300 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    return true;
}

function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault(); // Previene la entrada si no coincide con la expresión regular
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    // Valida el valor del campo al soltar la tecla
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text(""); // Limpia el mensaje si es válido
        return 1; // Retorna 1 si es válido
    } else {
        etiquetamensaje.text(mensaje); // Muestra el mensaje de error
        return 0; // Retorna 0 si no es válido
    }
}

function pone(pos, accion) {
    // Maneja la selección de una fila en la tabla
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR"); // Cambia el texto a MODIFICAR
        $("#nombre").prop("disabled", true); // Desactiva el campo nombre
        $("#Apellido").prop("disabled", true); // Desactiva el campo Apellido
        $("#Ocupacion").prop("disabled", true); // Desactiva el campo Ocupacion
        $("#Sexo").prop("disabled", true); // Desactiva el campo Sexo
        $("#PersonaContacto").prop("disabled", true); // Desactiva el campo PersonaContacto
        $("#telefono").prop("disabled", true); // Desactiva el campo telefono
        $("#Edad").prop("disabled", true); // Desactiva el campo Edad
        $("#correo").prop("disabled", true); // Desactiva el campo correo
        $("#motivo").prop("disabled", true); // Desactiva el campo motivo
        $("#diagnostico").prop("disabled", true); // Desactiva el campo diagnostico
        $("#tratamiento").prop("disabled", true); // Desactiva el campo tratamiento
        $("#medicamentos").prop("disabled", true); // Desactiva el campo medicamentos
        $("#dientesafectados").prop("disabled", true); // Desactiva el campo dientesafectados
        $("#antecedentes").prop("disabled", true); // Desactiva el campo antecedentes
        $("#fechaconsulta").prop("disabled", true); // Desactiva el campo fechaconsulta
        $("#proximacita").prop("disabled", true); // Desactiva el campo proximacita
        $("#observaciones").prop("disabled", true); // Desactiva el campo observaciones




        // $("#imagen").prop("disabled", false); // Activa el campo imagen
    } else {
        $("#proceso").text("ELIMINAR"); // Cambia el texto a ELIMINAR
        // Desactiva todos los campos para que ninguno esté disponible al eliminar
        $("#nombre").prop("disabled", true);
        $("#Apellido").prop("disabled", true);
        $("#telefono").prop("disabled", true);
        $("#correo").prop("disabled", true);
        $("#Sexo").prop("disabled", true);
        $("#Ocupacion").prop("disabled", true);
        $("#PersonaContacto").prop("disabled", true);
        $("#Edad").prop("disabled", true);
        $("#motivo").prop("disabled", true);
        $("#diagnostico").prop("disabled", true);
        $("#tratamiento").prop("disabled", true);
        $("#medicamentos").prop("disabled", true);
        $("#dientesafectados").prop("disabled", true);
        $("#antecedentes").prop("disabled", true);
        $("#fechaconsulta").prop("disabled", true);
        $("#proximacita").prop("disabled", true);
        $("#observaciones").prop("disabled", true);
        // Si tienes campos adicionales, desactívalos aquí también
        // $("#imagen").prop("disabled", true);
    }
    $("#nombre").val($(linea).find("td:eq(1)").text()); // Rellena el campo nombre
    $("#Apellido").val($(linea).find("td:eq(2)").text()); // Rellena el campo Apellido
    $("#telefono").val($(linea).find("td:eq(3)").text()); // Rellena el campo telefono
    $("#correo").val($(linea).find("td:eq(4)").text()); // Rellena el campo correo
    $("#Sexo").val($(linea).find("td:eq(5)").text()); // Rellena el campo Sexo
    $("#Ocupacion").val($(linea).find("td:eq(6)").text()); // Rellena el campo Ocupacion
    $("#PersonaContacto").val($(linea).find("td:eq(7)").text()); // Rellena el campo PersonaContacto
    $("#Edad").val($(linea).find("td:eq(8)").text()); // Rellena el campo Edad
    $("#motivo").val($(linea).find("td:eq(9)").text()); // Rellena el campo motivo
    $("#diagnostico").val($(linea).find("td:eq(10)").text()); // Rellena el campo diagnostico
    $("#tratamiento").val($(linea).find("td:eq(11)").text()); // Rellena el campo tratamiento
    $("#medicamentos").val($(linea).find("td:eq(12)").text()); // Rellena el campo medicamentos
    $("#dientesafectados").val($(linea).find("td:eq(13)").text()); // Rellena el campo dientesafectados
    $("#antecedentes").val($(linea).find("td:eq(14)").text()); // Rellena el campo antecedentes
    $("#fechaconsulta").val($(linea).find("td:eq(15)").text()); // Rellena el campo fechaconsulta
    $("#proximacita").val($(linea).find("td:eq(16)").text()); // Rellena el campo proximacita
    $("#observaciones").val($(linea).find("td:eq(17)").text()); // Rellena el campo observaciones


    /*   var imagenSrc = $(linea).find("td:eq(3) img").attr("src"); // Obtiene la fuente de la imagen
       if (imagenSrc) {
           $("#imagen_actual").attr("src", imagenSrc).show(); // Muestra la imagen actual
           $("#imagen_url").val(imagenSrc);       
           fetch(imagenSrc)
           .then(res => res.blob())
           .then(blob => {
               const file = new File([blob], imagenSrc.split('/').pop(), { type: blob.type });
               const dataTransfer = new DataTransfer();
               dataTransfer.items.add(file);
               $("#imagen")[0].files = dataTransfer.files; // Asigna el archivo a input de imagen
           });
       } else {
           $("#imagen_actual").attr("src", "").hide(); // Oculta la imagen si no existe
           $("#imagen_url").val("");
       }*/
    $("#modal1").modal("show");
     // Muestra el modal
}
function enviaAjax(datos) {
    // Envía datos al servidor mediante AJAX
    $.ajax({
        async: true,
        url: "", // URL del servidor
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () {
            $("#loader").show(); // Mostrar loader 
        },
        timeout: 10000, // Tiempo de espera
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta); // Intenta parsear la respuesta
                if (lee.resultado == "consultar") {
                    destruyeDT();
                    $("#resultadoconsulta").html(lee.mensaje); // Muestra el mensaje de consulta
                    crearDT(); // Crea la tabla
                }
                else if (lee.resultado == "incluir") {
                    if (lee.mensaje == '¡Registro guardado con éxito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "El Historial ha sido incluido con éxito.",
                            icon: "success"
                        });
                        $("#modal1").modal("hide"); // Oculta el modal
                        consultar(); // Vuelve a consultar
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
                    if (lee.mensaje.includes('éxito')) {
                        $("#modal1").modal("hide"); // Oculta el modal
                        consultar(); // Vuelve a consultar
                    }
                }
                else if (lee.resultado == "eliminar") {
                    Swal.fire({
                        title: lee.mensaje == '¡Registro eliminado con éxito!' ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro eliminado con éxito!' ? "success" : "error"
                    });
                    if (lee.mensaje == '¡Registro eliminado con éxito!') {
                        $("#modal1").modal("hide"); // Oculta el modal
                        consultar(); // Vuelve a consultar
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
            // Manejo de errores en la solicitud AJAX
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
    // Limpia los campos del formulario
    // Limpia todos los campos del formulario y habilita los necesarios
    $("#nombre").val("").prop("disabled", false);
    $("#Apellido").val("").prop("disabled", false);
    $("#Ocupacion").val("").prop("disabled", false);
    $("#Sexo").prop("selectedIndex", 0).prop("disabled", false);
    $("#PersonaContacto").val("").prop("disabled", false);
    $("#telefono").val("").prop("disabled", false);
    $("#Edad").val("").prop("disabled", false);
    $("#correo").val("").prop("disabled", false);
    $("#motivo").val("").prop("disabled", false);
    $("#diagnostico").val("").prop("disabled", false);
    $("#tratamiento").val("").prop("disabled", false);
    $("#medicamentos").val("").prop("disabled", false);
    $("#dientesafectados").val("").prop("disabled", false);
    $("#antecedentes").val("").prop("disabled", false);
    $("#fechaconsulta").val("").prop("disabled", false);
    $("#proximacita").val("").prop("disabled", false);
    $("#observaciones").val("").prop("disabled", false);

    // Limpia mensajes de error si existen
    $("#snombre").text("");
    $("#sApellido").text("");
    // Limpia el mensaje de error
    /*$("#descripcion").val("");
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide(); // Oculta la imagen actual
    $("#nombre").prop("disabled", false); // Habilita el campo nombre
    $("#descripcion").prop("disabled", false); // Habilita el campo descripción
    $("#imagen").prop("disabled", false); // Habilita el campo imagen*/
}

/*
$("#imagen").on("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
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
});*/

document.getElementById('buscadorPacientes').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#resultadoconsulta tr');
    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});
