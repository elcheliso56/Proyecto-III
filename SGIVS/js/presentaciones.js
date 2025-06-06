function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaPresentaciones")) {
        $("#tablaPresentaciones").DataTable().destroy();
    }
}

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaPresentaciones")) {
        $("#tablaPresentaciones").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron presentaciones",
                info: "Página _PAGE_ de _PAGES_",
                infoEmpty: "No hay presentaciones registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar presentaciones...",
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
        $('#tablaPresentaciones').DataTable().columns.adjust().draw();
    });
}

$(document).ready(function() {
    consultar(); // Llama a la función consultar al cargar el documento
     $("#proceso").text("INCLUIR");
     $("#modal1").modal("show");


    // Validaciones para el campo nombre
    $("#nombre").on("keypress", function(e) {
        validarkeypress(/^[^"']*$/, e); 
    });
    $("#nombre").on("keyup", function() {
        validarkeyup(/^[^"']{3,30}$/, $(this), $("#snombre"), "Debe tener entre 3 y 30 caracteres");
    });

    // Validaciones para el campo descripción
    $("#descripcion").on("keypress", function(e) {
        validarkeypress(/^[^"']*$/, e); // Letras, números, espacios, comas y puntos
    });
    $("#descripcion").on("keyup", function() {
        validarkeyup(/^[^"']{1,100}$/, $(this), $("#sdescripcion"), "La descripcion debe tener un máximo de 100 caracteres");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function() {
        if ($(this).text() == "INCLUIR") {
            // Confirmación para incluir una nueva presentacion

           if (validarenvio()) {

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas incluir esta nueva presentacion?",
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
                        datos.append('descripcion', $("#descripcion").val());
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                }
            });
        }
}
         else if ($(this).text() == "MODIFICAR") {
            // Confirmación para modificar una presentacion
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
              text: "¿Deseas modificar esta presentacion?",
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
                  datos.append('nombre', $("#nombre").val());
                  datos.append('descripcion', $("#descripcion").val());
                enviaAjax(datos);
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
              title: "Cancelado",
              text: "La presentacion no ha sido modificada",
              icon: "error"
          });
        }
    });
      }}
      else if($(this).text()=="ELIMINAR"){
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
               datos.append('nombre', $("#nombre").val());
               enviaAjax(datos);
           } else if (result.dismiss === Swal.DismissReason.cancel) {
               swalWithBootstrapButtons.fire({
                  title: "Cancelado",
                  text: "presentacion no eliminada",
                  icon: "error"
              });
           }
       });
    }
});

    $("#incluir").on("click",function(){
     limpia();
     $("#proceso").text("INCLUIR");
     $("#modal1").modal("show");
 });	

    // Manejador para el botón de volver a registrar insumo
    $("#volverRegistrarInsumo").on("click", function() {
        window.location.href = "?pagina=gestionarInsumos&from=presentaciones";
    });
});

// Función para validar el envío de datos
function validarenvio() {
    if (validarkeyup(/^[^"']{3,30}$/, $("#nombre"), $("#snombre"), "Texto entre 3 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre de la presentacion es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false; // Retorna falso si hay error
    } else if (validarkeyup(/^[^"']{1,100}$/, $("#descripcion"), $("#sdescripcion"), "La descripcion debe tener un máximo de 100 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La descripcion debe tener un máximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false; // Retorna falso si hay error
    }
    return true; // Retorna verdadero si todo es correcto
}

// Función para validar la tecla presionada
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault(); // Previene la acción si no coincide con la expresión regular
    }
}

// Función para validar el valor de un campo
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text(""); // Limpia el mensaje si es válido
        return 1; // Retorna 1 si es válido
    } else {
        etiquetamensaje.text(mensaje); // Muestra el mensaje de error
        return 0; // Retorna 0 si no es válido
    }
}

// Función para manejar la selección de una fila
function pone(pos, accion) {
    linea = $(pos).closest('tr'); // Obtiene la fila más cercana
    if (accion == 0) {
        $("#proceso").text("MODIFICAR"); // Cambia el texto a modificar
        $("#nombre").prop("disabled", true); 
        $("#descripcion").prop("disabled", false); // Activa el campo descripción
    } else {
        $("#proceso").text("ELIMINAR"); // Cambia el texto a eliminar
        $("#nombre").prop("disabled", true); // Desactiva el campo nombre
        $("#descripcion").prop("disabled", true); // Desactiva el campo descripción
    }
    // Rellena los campos con los datos de la fila seleccionada
    $("#nombre").val($(linea).find("td:eq(1)").text());
    $("#descripcion").val($(linea).find("td:eq(2)").text());
    $("#modal1").modal("show"); // Muestra el modal
}

// Función para enviar datos mediante AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "", // URL del servidor
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function() {
            $("#loader").show(); // Mostrar loader
        },
        timeout: 10000,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta); // Intenta parsear la respuesta
                if (lee.resultado == "consultar") {
                    destruyeDT(); // Destruye la tabla
                    $("#resultadoconsulta").html(lee.mensaje); // Muestra el mensaje
                    crearDT(); // Crea la tabla
                } else if (lee.resultado == "incluir") {
                    // Manejo de respuesta al incluir
                    if (lee.mensaje == '¡Registro guardado con exito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "La presentacion ha sido incluida con éxito.",
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
                } else if (lee.resultado == "modificar") {
                    // Manejo de respuesta al modificar
                    Swal.fire({
                        title: lee.mensaje == '¡Registro actualizado con exito!' ? "¡Modificado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro actualizado con exito!' ? "success" : "error"
                    });
                    if (lee.mensaje == '¡Registro actualizado con exito!') {
                        $("#modal1").modal("hide"); // Oculta el modal
                        consultar(); // Vuelve a consultar
                    }
                } else if (lee.resultado == "eliminar") {
                    // Manejo de respuesta al eliminar
                    Swal.fire({
                        title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
                    });
                    if (lee.mensaje == '¡Registro eliminado con exito!') {
                        $("#modal1").modal("hide"); // Oculta el modal
                        consultar(); // Vuelve a consultar
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
        error: function(request, status, err) {
            // Manejo de errores
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
        complete: function() {
            $("#loader").hide(); // Ocultar loader al completar
        }
    });
}

// Función para limpiar los campos del formulario
function limpia() {
    $("#nombre").val(""); // Limpia el campo nombre
    $("#descripcion").val(""); // Limpia el campo descripción
    $("#nombre").prop("disabled", false); // Habilita el campo nombre
    $("#descripcion").prop("disabled", false); // Habilita el campo descripción
}