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
        limpia(); // Limpia los campos
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
    // Configura jsPDF (si usas la versión umd)
    const { jsPDF } = window.jspdf;

    // Botón para generar PDF
    document.getElementById('generar-pdf').addEventListener('click', function () {
        const iframe = document.querySelector('#miModal iframe');
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

        // 1. Capturar el contenido del iframe con html2canvas
        html2canvas(iframeDoc.body, {
            scale: 4, // Aumenta la calidad
            useCORS: true, // Para contenido externo (si aplica)
            logging: true, // Depuración en consola
            backgroundColor: '#FFFFFF' // Fondo blanco
        }).then(canvas => {
            // 2. Crear el PDF
            const pdf = new jsPDF('p', 'mm', 'a4'); // Orientación vertical, formato A4
            const imgData = canvas.toDataURL('image/png');

            // Calcular dimensiones para que la imagen quepa en el PDF
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            // 3. Agregar la imagen al PDF
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

            // 4. Descargar el PDF
            pdf.save('odontograma.pdf');
        }).catch(error => {
            console.error('Error al generar PDF:', error);
            alert('No se pudo generar el PDF. Ver consola para detalles.');
        });
    });
});

function validarenvio() {
    // Valida el envío de datos
    if (validarkeyup(/^[^"']{3,30}$/, $("#nombre"), $("#snombre"), "Texto entre 3 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre de la historia es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false; // Retorna falso si hay error
    } else if (validarkeyup(/^[^"']{0,100}$/, $("#Apellido"), $("#sApellido"), "El apellido debe tener un máximo de 100 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El apellido debe tener un máximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false; // Retorna falso si hay error
    }
    return true; // Retorna verdadero si todo es correcto
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
        $("#nombre").prop("disabled", true); // Desactiva el campo nombre
        $("#Apellido").prop("disabled", true); // Desactiva el campo Apellido
        $("#telefono").prop("disabled", true); // Desactiva el campo telefono
        $("#correo").prop("disabled", true); // Desactiva el campo correo
        $("#Sexo").prop("disabled", true); // Desactiva el campo Sexo
        $("#Ocupacion").prop("disabled", true); // Desactiva el campo Ocupacion
        $("#PersonaContacto").prop("disabled", true); // Desactiva el campo PersonaContacto
        $("#Edad").prop("disabled", true); // Desactiva el campo Edad
        $("#motivo").prop("disabled", true); // Desactiva el campo motivo
        $("#diagnostico").prop("disabled", true); // Desactiva el campo diagnostico
        $("#tratamiento").prop("disabled", true); // Desactiva el campo tratamiento
        $("#medicamentos").prop("disabled", true); // Desactiva el campo medicamentos
        $("#dientesafectados").prop("disabled", true); // Desactiva el campo dientesafectados
        $("#antecedentes").prop("disabled", true); // Desactiva el campo antecedentes
        $("#fechaconsulta").prop("disabled", true); // Desactiva el campo fechaconsulta
        $("#proximacita").prop("disabled", true); // Desactiva el campo proximacita
        $("#observaciones").prop("disabled", true); // Desactiva el campo observaciones
        // $("#imagen").prop("disabled", true); // Desactiva el campo imagen
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
    $("#nombre").val("").prop("disabled", false); // Limpia el campo nombre y lo habilita
    $("#Apellido").val("").prop("disabled", false);
    $("#Ocupacion").val("");
    $("#Sexo").prop("selectedIndex", 0);
    $("#PersonaContacto").val("");
    $("#telefono").val("").prop("disabled", false);
    $("#Edad").val("");
    $("#correo").val("");
    $("#motivo").val("");
    $("#diagnostico").val("");
    $("#tratamiento").val("");
    $("#medicamentos").val("");
    $("#dientesafectados").val("");
    $("#antecedentes").val("");
    $("#fechaconsulta").val("");
    $("#proximacita").val("");
    $("#observaciones").val("");
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
