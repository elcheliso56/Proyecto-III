var datos = new FormData();
datos.append("accion", "modalpaciente");
enviaAjax(datos);

$("#listadopaciente").on("click", function () {
  $("#modalpaciente").modal("show");
});

function colocapa(linea) {
  $("#cedula").val($(linea).find("td:eq(0)").text());
  $("#nombre").val($(linea).find("td:eq(1)").text());
  $("#Apellido").val($(linea).find("td:eq(2)").text());
  $("#telefono").val($(linea).find("td:eq(3)").text());
  $("#modalpaciente").modal("hide");
}

var datos = new FormData();
datos.append("accion", "modaldoc");
enviaAjax(datos);

$("#listadoc").on("click", function () {
  $("#modaldoc").modal("show");
});

function colocadoc(linea) {
  $("#doctor").val($(linea).find("td:eq(0)").text());
  $("#modaldoc").modal("hide");
}

function consultar() {
  // Crea un nuevo objeto FormData y agrega la acción 'consultar'
  var datos = new FormData();
  datos.append("accion", "consultar");
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
        zeroRecords: "No se encontraron clientes",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay clientes registrados",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        search: "<i class='bi bi-search'></i>",
        searchPlaceholder: "Buscar...",
        
        paginate: {
          first: "Primera",
          last: "Última",
          next: "Siguiente",
          previous: "Anterior",
        },
      },
      pageLength: 5, // Establece el número de registros por página a 5
      lengthMenu: [
        [5, 10, 25, 50, 100],
        [5, 10, 25, 50, 100],
      ], // Opciones de número de registros por página
      autoWidth: false,
      scrollX: true,
      scrollCollapse: true,
      fixedHeader: false,
      order: [[0, "asc"]],
      responsive: true,
    });
  }
  $(window).resize(function () {
    $("#tablacliente").DataTable().columns.adjust().draw();
  });
}

$(document).ready(function () {
  // Llama a la función consultar al cargar el documento
  consultar();
  // Validaciones para el campo de nombre
  $("#nombre").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });
  $("#nombre").on("keyup", function () {
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $(this),
      $("#snombre"),
      "Solo letras entre 3 y 30 caracteres"
    );
  });

  // Validaciones para el campo de Apellido
  $("#Apellido").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });
  $("#Apellido").on("keyup", function () {
    validarkeyup(
      /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/,
      $(this),
      $("#sApellido"),
      "Solo letras entre 3 y 30 caracteres"
    );
  });

  // Validaciones para el campo de teléfono
  $("#telefono").on("keypress", function (e) {
    validarkeypress(/^[0-9\b]*$/, e);
  });
  $("#telefono").on("keyup", function () {
    validarkeyup(
      /^0[0-9]{10}$/,
      $(this),
      $("#stelefono"),
      "El formato de teléfono debe ser 04120000000"
    );
  });
  // Validación para el campo de tratamiento: solo letras, números, espacios y algunos signos de puntuación
  $("#tratamiento").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z0-9\s.,;:()\-\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });
  // Validación para el campo de tratamiento: no permitir incluir si está vacío
  $("#tratamiento").on("keyup", function () {
    var tratamiento = $(this).val().trim();
    if (tratamiento.length === 0) {
      $("#stratamiento").text("El tratamiento no puede estar vacío.");
    } else {
      $("#stratamiento").text("");
    }
  });
  // Validación para la fecha de consulta: solo permitir la fecha de hoy
  $("#fechaconsulta").on("change", function () {
    var fechaIngresada = $(this).val();
    var hoy = new Date();
    var yyyy = hoy.getFullYear();
    var mm = String(hoy.getMonth() + 1).padStart(2, '0');
    var dd = String(hoy.getDate()).padStart(2, '0');
    var fechaHoy = yyyy + '-' + mm + '-' + dd;

    if (fechaIngresada && fechaIngresada !== fechaHoy) {
      $("#sfechaconsulta").text("Solo se permite la fecha de hoy: " + fechaHoy);
      $(this).val(""); // Limpiar el campo si no es la fecha de hoy
    } else {
      $("#sfechaconsulta").text("");
    }
  });

  // Manejo de clic en el botón de proceso
  $("#proceso").on("click", function () {
    if ($(this).text() == "INCLUIR") {
      if (validarenvio()) {
        // Confirmación para incluir un nuevo cliente
        Swal.fire({
          title: "¿Estás seguro?",
          text: "¿Deseas incluir este nuevo cliente?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sí, incluir",
          cancelButtonText: "No, Cancelar",
        }).then((result) => {
          if (result.isConfirmed) {
            if (validarenvio()) {
              var datos = new FormData();
              datos.append("accion", "incluir");
              datos.append("cedula", $("#cedula").val());
              datos.append("nombre", $("#nombre").val());
              datos.append("Apellido", $("#Apellido").val());
              datos.append("telefono", $("#telefono").val());
              datos.append("tratamiento", $("#tratamiento").val());
              datos.append("fechaconsulta", $("#fechaconsulta").val());
              datos.append("doctor", $("#doctor").val());

              enviaAjax(datos);
            }
          }
        });
      }
    } else if ($(this).text() == "MODIFICAR") {
      if (validarenvio()) {
        // Confirmación para modificar un cliente existente
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
          },
          buttonsStyling: false,
        });
        swalWithBootstrapButtons
          .fire({
            title: "¿Estás seguro?",
            text: "¿Deseas modificar la información de este cliente?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, modificar",
            cancelButtonText: "No, cancelar",
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {
              if (validarenvio()) {
                var datos = new FormData();
                datos.append("accion", "modificar");
                datos.append("cedula", $("#cedula").val());
                datos.append("nombre", $("#nombre").val());
                datos.append("Apellido", $("#Apellido").val());
                datos.append("telefono", $("#telefono").val());
                datos.append("tratamiento", $("#tratamiento").val());
                datos.append("fechaconsulta", $("#fechaconsulta").val());
                datos.append("doctor", $("#doctor").val());
                enviaAjax(datos);
              }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "La información de este cliente no ha sido modificada",
                icon: "error",
              });
            }
          });
      }
    }

    // Manejo de eliminación de un cliente
    if ($(this).text() == "ELIMINAR") {
      var tipoDocumento = $("#tipo_documento").val();
      var numeroDocumento = $("#cedula").val();
      var validacion;
      if (tipoDocumento === "Cédula") {
        validacion = validarkeyup(
          /^[0-9]{7,8}$/,
          $("#cedula"),
          $("#scedula"),
          "El formato de CI debe ser 1234567 o 12345678"
        );
      } else if (tipoDocumento === "RIF") {
        validacion = validarkeyup(
          /^[VJE]{1}-[0-9]{9}$/,
          $("#cedula"),
          $("#scedula"),
          "El formato de RIF debe ser V/J/E-123456789"
        );
      }

      if (validacion == 0) {
        muestraMensaje(
          "El documento debe coincidir con el formato solicitado <br/>" +
            "99999999"
        );
      } else {
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
          },
          buttonsStyling: false,
        });
        swalWithBootstrapButtons
          .fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar!",
            cancelButtonText: "No, cancelar!",
            reverseButtons: true,
          })
          .then((result) => {
            if (result.isConfirmed) {
              var datos = new FormData();
              datos.append("accion", "eliminar");
              datos.append("cedula", numeroDocumento);
              enviaAjax(datos);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Cliente no eliminado",
                icon: "error",
              });
            }
          });
      }
    }
  });

  // Manejo del clic en el botón incluir
  $("#incluir").on("click", function () {
    limpia(); // Limpia los campos
    $("#proceso").text("INCLUIR"); // Cambia el texto del botón
    $("#modal1").modal("show"); // Muestra el modal
  });
});

// Función para validar el envío de datos
function validarenvio() {
  // Validación de cédula
  let cedula = $("#cedula").val().trim();
  if (
    cedula === "" ||
    !/^\d{7,8}$/.test(cedula) ||
    parseInt(cedula, 10) > 32000000
  ) {
    Swal.fire({
      title: "¡ERROR!",
      text: "La cédula es obligatoria, debe tener 7 u 8 dígitos numéricos.",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
    return false;
  }

  // Validación de nombre
  let nombre = $("#nombre").val().trim();
  if (nombre === "" || !/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(nombre)) {
    Swal.fire({
      title: "¡ERROR!",
      text: "El nombre es obligatorio y debe tener solo letras (3-30 caracteres).",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
    return false;
  }

  // Validación de apellido
  let apellido = $("#Apellido").val().trim();
  if (apellido === "" || !/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,30}$/.test(apellido)) {
    Swal.fire({
      title: "¡ERROR!",
      text: "El apellido es obligatorio y debe tener solo letras (3-30 caracteres).",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
    return false;
  }

  // Validación de teléfono
  let telefono = $("#telefono").val().trim();
  if (telefono === "" || !/^0\d{10}$/.test(telefono)) {
    Swal.fire({
      title: "¡ERROR!",
      text: "El teléfono es obligatorio y debe tener el formato 0412xxxxxxx.",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
    return false;
  }

  // Validación de tratamiento
  let tratamiento = $("#tratamiento").val().trim();
  if (tratamiento.length < 5 || tratamiento.length > 200) {
    Swal.fire({
      title: "¡ERROR!",
      text: "El tratamiento es obligatorio (mínimo 5 y máximo 200 caracteres).",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
    return false;
  }

  // Validación de fecha de consulta
  let fecha = $("#fechaconsulta").val().trim();
  if (fecha === "") {
    Swal.fire({
      title: "¡ERROR!",
      text: "La fecha de consulta es obligatoria.",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
    return false;
  }

  // Validación de doctor
  let doctor = $("#doctor").val().trim();
  if (doctor.length < 3) {
    Swal.fire({
      title: "¡ERROR!",
      text: "El nombre del doctor es obligatorio (mínimo 3 caracteres).",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
    return false;
  }

  return true;
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

function limpia() {
  // Función para limpiar los campos del formulario
  $("#cedula").val("");
  $("#nombre").val("");
  $("#Apellido").val("");
  $("#telefono").val("");
  $("#tratamiento").prop("selectedIndex", 0);
  $("#fechaconsulta").val("");
   $("#fechaconsulta").prop("disabled", false); // Limpia el textarea de tratamientos
  $("#doctor").val(""); // Limpia el campo del doctor
  // Habilita los campos del formulario
}

function pone(pos, accion) {
  // Función para llenar el formulario con los datos del cliente seleccionado
  linea = $(pos).closest("tr");
  if (accion == 0) {
    $("#proceso").text("MODIFICAR");
    $("#cedula").prop("disabled", true);
    $("#nombre").prop("disabled", true);
    $("#Apellido").prop("disabled", true);
    $("#tratamieto").prop("disabled", false);
    $("#telefono").prop("disabled", true);
    $("#fechaconsulta").prop("disabled", false);
    $("#doctor").prop("disabled", true);
  } else {
    // Si la acción es eliminar, deshabilita todos los campos
    $("#proceso").text("ELIMINAR");
    $("#cedula").prop("disabled", true);
    $("#nombre").prop("disabled", true);
    $("#Apellido").prop("disabled", true);
    $("#tratamieto").prop("disabled", true);
    $("#telefono").prop("disabled", true);
    $("#fechaconsulta").prop("disabled", true);
    $("#doctor").prop("disabled", true);
  }

  $("#cedula").val($(linea).find("td:eq(1)").text());
  $("#nombre").val($(linea).find("td:eq(2)").text());
  $("#Apellido").val($(linea).find("td:eq(3)").text());
  $("#telefono").val($(linea).find("td:eq(4)").text());
  $("#tratamiento").val($(linea).find("td:eq(5)").text());
  $("#fechaconsulta").val($(linea).find("td:eq(6)").text());
  $("#doctor").val($(linea).find("td:eq(7)").text());
  $("#modal1").modal("show"); // Muestra el modal
}

function enviaAjax(datos) {
  // Función para enviar datos a través de AJAX
  $.ajax({
    async: true,
    url: "", // URL del servidor
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () {},
    timeout: 10000, // Tiempo de espera
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta); // Intenta parsear la respuesta JSON
        if (lee.resultado == "consultar") {
          destruyeDT();
          $("#resultadoconsulta").html(lee.mensaje);
          crearDT(); // Crea la tabla con los nuevos datos
        } else if (lee.resultado == "incluir") {
          if (lee.mensaje == "¡Registro guardado con exito!") {
            Swal.fire({
              title: "¡Incluido!",
              text: "El cliente ha sido incluido con éxito.",
              icon: "success",
            });
            $("#modal1").modal("hide");
            consultar(); // Actualiza la lista de clientes
          } else {
            Swal.fire({
              title: "Error",
              text: lee.mensaje,
              icon: "error",
            });
          }
        } else if (lee.resultado == "modificar") {
          Swal.fire({
            title: lee.mensaje.includes("éxito") ? "¡Modificado!" : "Error",
            text: lee.mensaje,
            icon: lee.mensaje.includes("éxito") ? "success" : "error",
          });
          if (lee.mensaje.includes("éxito")) {
            $("#modal1").modal("hide");
            consultar(); // Actualiza la lista de clientes
          }
        } else if (lee.resultado == "eliminar") {
          Swal.fire({
            title:
              lee.mensaje == "¡Registro eliminado con exito!"
                ? "¡Eliminado!"
                : "Error",
            text: lee.mensaje,
            icon:
              lee.mensaje == "¡Registro eliminado con exito!"
                ? "success"
                : "error",
          });
          if (lee.mensaje == "¡Registro eliminado con exito!") {
            $("#modal1").modal("hide");
            consultar(); // Actualiza la lista de clientes
          }
        } else if (lee.resultado == "error") {
          Swal.fire({
            title: "Error",
            text: lee.mensaje,
            icon: "error",
          });
        }
        if (lee.resultado == "modalpaciente") {
          $("#tablapaciente").html(lee.mensaje);
        }
         if (lee.resultado == "modaldoc") {
          $("#tabladoc").html(lee.mensaje);
        }
       
      } catch (e) {
        Swal.fire({
          title: "Error",
          text: "Error en JSON o: " + e.name,
          icon: "error",
        });
      }
      
    },
    error: function (request, status, err) {
      if (status == "timeout") {
        Swal.fire({
          title: "Error",
          text: "Servidor ocupado, intente de nuevo",
          icon: "error",
        });
      } else {
        Swal.fire({
          title: "Error",
          text: "ERROR: " + request + status + err,
          icon: "error",
        });
      }
    },
    complete: function () {},
  });
}


// Función para añadir el tratamiento seleccionado al textarea
$("#btn_add_tratamiento").on("click", function () {
  var selectedTreatment = $("#select_tratamiento_add").val(); // Obtiene el valor del select
  var currentTreatmentText = $("#tratamiento").val(); // Obtiene el texto actual del textarea

  if (selectedTreatment) {
    // Añadir el tratamiento, con una nueva línea si ya hay texto
    if (currentTreatmentText.trim() !== "") {
      $("#tratamiento").val(currentTreatmentText + "\n- " + selectedTreatment);
    } else {
      $("#tratamiento").val("- " + selectedTreatment);
    }
    // Opcional: Limpiar la selección en el Select2 después de añadir
    $("#select_tratamiento_add").val(null).trigger("change");
  } else {
    alert("Por favor, seleccione un tratamiento de la lista.");
  }
});

// Evento para limpiar el Select2 y el textarea de tratamientos cuando se cierra el modal
$("#modal1").on("hidden.bs.modal", function () {
  // Limpiar el select de paciente si lo tienes y sus campos
  // $('#select_paciente').val(null).trigger('change');
  // $('#nombre_paciente').val(''); etc.

  // Limpiar el textarea de tratamiento y el select de añadir tratamientos
  $("#tratamiento").val("");
  $("#select_tratamiento_add").val(null).trigger("change");
});
