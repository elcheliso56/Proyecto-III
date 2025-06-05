function consultar(){
    var datos = new FormData();
    datos.append('accion','consultar');
    enviaAjax(datos);   
}

function cargarRoles() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    
    $.ajax({
        async: true,
        url: "?pagina=roles",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if(lee.resultado == "consultar") {
                    var roles = lee.mensaje;
                    var options = '<option value="" selected disabled>Seleccione una opción</option>';
                    roles.forEach(function(rol) {
                        if(rol.estado === 'ACTIVO') {
                            options += `<option value="${rol.id}">${rol.nombre_rol}</option>`;
                        }
                    });
                    $("#id_rol").html(options);
                }
            } catch(e) {
                console.error("Error al cargar roles:", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar roles:", error);
        }
    });
}

function destruyeDT(){
    if ($.fn.DataTable.isDataTable("#tablausuario")) {
        $("#tablausuario").DataTable().destroy();
    }
}

function crearDT(){
    if (!$.fn.DataTable.isDataTable("#tablausuario")) {
        $("#tablausuario").DataTable({
          language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron usuarios",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay usuarios registrados",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "<i class='bi bi-search'></i>",
            searchPlaceholder: "Buscar usuario...",
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
      order: [[0, "asc"]],
  });
    }         
}
$(document).ready(function(){
    consultar();
    cargarRoles();
    $("#contraseña").on("keyup", function() {
        validarkeyup(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/, $(this), $("#scontraseña"), "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número");
    });
    $("#repetir_contraseña").on("keyup", function() {
        if ($(this).val() !== $("#contraseña").val()) {
            $("#srepetir_contraseña").text("Las contraseñas no coinciden");
        } else {
            $("#srepetir_contraseña").text("");
        }
    });

    $("#proceso").on("click",function(){
        if($(this).text()==" INCLUIR"){
            if (validarenvio()) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo usuario?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, incluir",
                    cancelButtonText: "No, Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var datos = new FormData();
                        datos.append('accion','incluir');
                        datos.append('usuario',$("#usuario").val());
                        datos.append('id_rol',$("#id_rol").val());
                        datos.append('contraseña',$("#contraseña").val());
                        datos.append('imagen', $("#imagen")[0].files[0] || new File([], ""));
                        datos.append('estado',$("#estado").val());
                        enviaAjax(datos);
                    }
                });
            }
        }
        else if($(this).text()==" MODIFICAR"){
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
                    text: "¿Deseas modificar la información de este usuario?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Sí, modificar",
                    cancelButtonText: "No, cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        var datos = new FormData();
                        datos.append('accion','modificar');
                        datos.append('usuario',$("#usuario").val());
                        datos.append('id_rol',$("#id_rol").val());
                        datos.append('contraseña',$("#contraseña").val());
                        datos.append('imagen', $("#imagen")[0].files[0] || new File([], ""));
                        datos.append('estado',$("#estado").val());
                        enviaAjax(datos);
                    }
                });
            }
        }
        if($(this).text()==" ELIMINAR"){
            if(validarkeyup(/^[0-9]{7,8}$/,$("#usuario"),
                $("#susuario"),"El formato debe ser el solicitado")==0){
               muestraMensaje("la cedula debe coincidir con el formato solicitado <br/>"+ 
                  "12345678");  
            }
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
                    datos.append('usuario', $("#usuario").val());
                    enviaAjax(datos);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "Tu usuario está a salvo :)",
                        icon: "error"
                    });
                }
            });
        }
    });
});

$("#incluir").on("click",function(){
    limpia();
    $("#proceso").text(" INCLUIR");
    $("#modal1").modal("show");
}); 

function validarenvio(){
    if ($("#usuario").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El usuario es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    if ($("#id_rol").val().trim() === "") {
        Swal.fire({
            title: "¡ERROR!",
            text: "El rol del usuario es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });    
        return false;
    }
    else if(validarkeyup(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/, $("#contraseña"), $("#scontraseña"), "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número") == 0){
        Swal.fire({
            title: "¡ERROR!",
            text: "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número",
            icon: "error",
            confirmButtonText: "Aceptar"
        });  
        return false;
    }
    else if($("#repetir_contraseña").val() !== $("#contraseña").val()){
        Swal.fire({
            title: "¡ERROR!",
            text: "Las contraseñas no coinciden :(",
            icon: "error",
            confirmButtonText: "Aceptar"
        }); 
        return false;
    }
    return true;
}
function validarkeypress(er,e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
      e.preventDefault();
  }    
}
function validarkeyup(er,etiqueta,etiquetamensaje,
    mensaje){
    a = er.test(etiqueta.val());
    if(a){
        etiquetamensaje.text("");
        return 1;
    }
    else{
        etiquetamensaje.text(mensaje);
        return 0;
    }
}
function pone(pos,accion){  
    linea=$(pos).closest('tr');
    if(accion==0){
        $("#proceso").text(" MODIFICAR");
        $("#usuario").prop("disabled",true);
        $("#id_rol").prop("disabled",false);
        $("#contraseña").prop("disabled",false);
        $("#repetir_contraseña").prop("disabled",false);
        $("#imagen").prop("disabled",false);
        $("#estado").prop("disabled",false);
        $("#contraseña").show();
        $("#repetir_contraseña").show();
        $("label[for='contraseña']").show();
        $("label[for='repetir_contraseña']").show();
    }
    else{
        $("#proceso").text(" ELIMINAR");
        $("#usuario").prop("disabled",true);
        $("#id_rol").prop("disabled",true);
        $("#contraseña").prop("disabled",true);
        $("#repetir_contraseña").prop("disabled",true);
        $("#imagen").prop("disabled",true);
        $("#estado").prop("disabled",true);
        $("#contraseña").hide();
        $("#repetir_contraseña").hide();
        $("label[for='contraseña']").hide();
        $("label[for='repetir_contraseña']").hide();
    }
    
    // Obtener los valores de la fila
    var usuario = $(linea).find("td:eq(0)").text();
    var nombreRol = $(linea).find("td:eq(1)").text();
    var estado = $(linea).find("td:eq(2)").text();
    
    // Establecer los valores en los campos
    $("#usuario").val(usuario);
    $("#estado").val(estado);

    // Cargar y seleccionar el rol correcto
    var datos = new FormData();
    datos.append('accion', 'consultar');
    
    $.ajax({
        async: true,
        url: "?pagina=roles",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if(lee.resultado == "consultar") {
                    var roles = lee.mensaje;
                    var options = '<option value="" disabled>Seleccione una opción</option>';
                    roles.forEach(function(rol) {
                        if(rol.estado === 'ACTIVO') {
                            options += `<option value="${rol.id}" ${rol.nombre_rol === nombreRol ? 'selected' : ''}>${rol.nombre_rol}</option>`;
                        }
                    });
                    $("#id_rol").html(options);
                }
            } catch(e) {
                console.error("Error al cargar roles:", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar roles:", error);
        }
    });

    // Limpiar campos de contraseña
    $("#contraseña").val("");
    $("#repetir_contraseña").val("");

    // Mostrar imagen actual si existe
    var imagenSrc = $(linea).find("img").attr("src");
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show();
    } else {
        $("#imagen_actual").attr("src", "otros/img/usuarios/default.png").show();
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
        $("#loader").show(); // Mostrar loader 
    },
    timeout: 10000, 
    success: function (respuesta) {
        try {
            var lee = JSON.parse(respuesta);
            if (lee.resultado == "consultar") {
                destruyeDT();
                let filas = "";
                (lee.mensaje || []).forEach(function(p) {
                    filas += `<tr class='text-center'>
                        <td class='align-middle'>${p.usuario}</td>
                        <td class='align-middle'>${p.nombre_rol}</td>
                        <td class='align-middle'>${p.estado}</td>
                        <td class='align-middle' style='display: flex; justify-content: center;'>
                            <button type='button' class='btn-sm btn-info w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar rol' style='margin:.2rem; width: 40px !important;'><i class='bi bi-arrow-repeat'></i></button><br/>
                            <button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar rol' style='margin:.2rem; width: 40px !important;'><i class='bi bi-trash-fill'></i></button><br/>
                        </td>
                        <td style='display: none;'><img src='${p.imagen || "otros/img/usuarios/default.png"}' /></td>
                    </tr>`;
                });
                $("#resultadoconsulta").html(filas);
                crearDT();
            }
        else if (lee.resultado == "incluir") {
            if (lee.mensaje == '¡Registro guardado con exito!') {
                Swal.fire({
                    title: "¡Incluido!",
                    text: "El usuario ha sido incluido con éxito.",
                    icon: "success"
                });
                $("#modal1").modal("hide");
                consultar();
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
            if(lee.mensaje.includes('éxito')){
                $("#modal1").modal("hide");
                consultar();
            }
        }
        else if (lee.resultado == "eliminar") {
            Swal.fire({
                title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
                text: lee.mensaje,
                icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
            });
            if(lee.mensaje == '¡Registro eliminado con exito!'){
                $("#modal1").modal("hide");
                consultar();
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
function limpia(){
    $("#cedula").val("");
    $("#nombre_apellido").val("");
    $("#usuario").val("");      
    $("#id_rol").prop("selectedIndex",0);
    $("#contraseña").val("");
    $("#repetir_contraseña").val("");       
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#estado").prop("selectedIndex",0);

    $("#cedula").prop("disabled",true);
    $("#nombre_apellido").prop("disabled",true);
    $("#usuario").prop("disabled",true);
    $("#id_rol").prop("disabled",false);
    $("#contraseña").prop("disabled",false);
    $("#repetir_contraseña").prop("disabled",false);
    $("#imagen").prop("disabled",false); 
    $("#estado").prop("disabled",true);
    $("#contraseña").show();
    $("#repetir_contraseña").show();
    $("label[for='contraseña']").show();
    $("label[for='repetir_contraseña']").show();   
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

// Evento para el botón listaEmpleados
$("#listaEmpleados").on("click", function(){
    var datos = new FormData();
    datos.append('accion', 'listaEmpleados');
    enviaAjaxEmpleados(datos);
    $("#modalusuario").modal("show");
});

// Función para colocar los datos del empleado seleccionado
function colocaEmpleado(linea){
    var cedula = $(linea).find("td:eq(0)").text();
    var nombre = $(linea).find("td:eq(1)").text();
    var apellido = $(linea).find("td:eq(2)").text();
    
    $("#cedula").val(cedula);
    $("#nombre_apellido").val(nombre + " " + apellido);
    $("#usuario").val(cedula.toLowerCase());
    $("#modalusuario").modal("hide");
}

// Función para enviar petición Ajax para empleados
function enviaAjaxEmpleados(datos) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function() {
            $("#loader").show();
        },
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if(lee.resultado == "listaEmpleados"){
                    $("#tablapaciente").html(lee.mensaje);
                }
            } catch(e) {
                console.log(e);
                console.log(respuesta);
            }
        },
        error: function(request, status, err) {
            console.log(err);
        },
        complete: function() {
            $("#loader").hide();
        }
    });
}