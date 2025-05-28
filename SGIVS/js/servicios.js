$(document).ready(function() {
    consultar(); // Llama a la función consultar al cargar el documento
    carga_insumos();// Cargar la lista de insumos
    carga_equipos();//carga la lista de equipos

    $("#listadoDeInsumos").on("click",function(){//boton para levantar modal de insumos
        $("#modalInsumos").modal("show");
    });

    $("#listadoDeEquipos").on("click",function(){//boton para levantar modal de equipos
        $("#modalEquipos").modal("show");
    });

    // Evento para buscar insumo por código mientras se escribe
    $("#codigoInsumo").on("keyup", function() {
        var codigo = $(this).val();
        if(codigo.length > 0) {
            var encontrado = false;
            $("#listadoInsumos tr").each(function() {
                if($(this).find("td:eq(1)").text() === codigo) {
                    colocaInsumo(this);
                    encontrado = true;
                    $("#codigoInsumo").val(""); // Limpiar el campo
                    return false; // Salir del bucle
                }
            });
        }
    });

    // Evento para buscar equipo por código mientras se escribe
    $("#codigoEquipo").on("keyup", function() {
        var codigo = $(this).val();
        if(codigo.length > 0) {
            var encontrado = false;
            $("#listadoEquipos tr").each(function() {
                if($(this).find("td:eq(1)").text() === codigo) {
                    colocaEquipo(this);
                    encontrado = true;
                    $("#codigoEquipo").val(""); // Limpiar el campo
                    return false; // Salir del bucle
                }
            });
        }
    });

    $("#buscarInsumo").on("keyup", function() {
        var valor = $(this).val().toLowerCase();
        $("#listadoInsumos tr").filter(function() {
            var texto = $(this).text().toLowerCase();
            $(this).toggle(texto.indexOf(valor) > -1);
        });
    });

    $("#buscarEquipo").on("keyup", function() {
        var valor = $(this).val().toLowerCase();
        $("#listadoEquipos tr").filter(function() {
            var texto = $(this).text().toLowerCase();
            $(this).toggle(texto.indexOf(valor) > -1);
        });
    });

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
        validarkeyup(/^[^"']{0,100}$/, $(this), $("#sdescripcion"), "La descripcion debe tener un máximo de 100 caracteres");
    });

    // Validaciones para el campo de precio de compra
    $("#precio").on("keypress", function (e) {
        validarkeypress(/^[0-9.\b]*$/, e);
    });

    $("#precio").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,10}$/, $(this), $("#sprecio"), "El precio debe ser mayor o igual a 0");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function() {
        if ($(this).text() == "INCLUIR") {
            // Confirmación para incluir un nuevo servicio
         if (validarenvio()) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas incluir esta nuevo servicio?",
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
                        datos.append('accion', 'incluir');
                        datos.append('nombre', $("#nombre").val());
                        datos.append('descripcion', $("#descripcion").val());
                        datos.append('precio', $("#precio").val());

                        // Agregar insumos si existen
                        $("#InsumosSeleccionados tr").each(function(index) {
                            datos.append('id_insumo[]', $(this).find("td:eq(0) input").val());
                            datos.append('cantidad[]', $(this).find("td:eq(3) input").val());
                            datos.append('precio[]', $(this).find("td:eq(4) input").val());
                        });

                        // Agregar equipos si existen
                        $("#EquiposSeleccionados tr").each(function(index) {
                            datos.append('id_equipo[]', $(this).find("td:eq(0) input").val());
                            datos.append('cantidad[]', $(this).find("td:eq(3) input").val());
                            datos.append('precio[]', $(this).find("td:eq(4) input").val());
                        });

                        enviaAjax(datos);
                        $("#modal1").modal("hide");
                        consultar();
                        carga_insumos();
                        carga_equipos();
                    }
                }
            });
        }
    }
    else if ($(this).text() == "MODIFICAR") {
            // Confirmación para modificar un servicio
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
              text: "¿Deseas modificar este servicio?",
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
                  datos.append('precio', $("#precio").val());
                  enviaAjax(datos);
              }
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
              title: "Cancelado",
              text: "El servicio no ha sido modificado",
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
          text: "Servicio no eliminado",
          icon: "error"
      });
     }
 });
}
});



$("#salida").on("click",function(){//evento click de boton salida
    if(validarenvio()){
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Deseas procesar esta salida?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, aceptar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#accion').val('salida');
                var datos = new FormData($('#f')[0]);       
                enviaAjax(datos);

            }
        });
    }
});

    $("#incluir").on("click",function(){
     limpia();
     $("#proceso").text("INCLUIR");
     $("#modal1").modal("show");
 });
});

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaServicios")) {
        $("#tablaServicios").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron servicios",
                info: "Página _PAGE_ de _PAGES_",
                infoEmpty: "No hay servicios registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar servicio...",
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
        $('#tablaServicios').DataTable().columns.adjust().draw();
    });
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaServicios")) {
        $("#tablaServicios").DataTable().destroy();
    }
}

// Variable global para los datos de servicios
var serviciosDatosGlobal = [];




// Función para validar el envío de datos
function validarenvio() {
    if (validarkeyup(/^[^"']{3,30}$/, $("#nombre"), $("#snombre"), "Texto entre 3 y 30 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del servicio es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[^"']{0,100}$/, $("#descripcion"), $("#sdescripcion"), "La descripcion debe tener un máximo de 100 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La descripcion debe tener un máximo de 100 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }
    if (validarkeyup(/^[0-9.]{1,10}$/, $("#precio"), $("#sprecio"), "El precio debe ser mayor o igual a 0") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El precio del servicio es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    }

    // Validar insumos si existen
    if (verificaInsumos()) {
        var cantidadesValidas = true;
        $("#InsumosSeleccionados tr").each(function() {
            var cantidad = $(this).find("td:eq(3) input").val();
            if (cantidad <= 0) {
                cantidadesValidas = false;
                return false;
            }
        });

        if (!cantidadesValidas) {
            Swal.fire({
                title: "Error",
                text: "La cantidad debe ser mayor a 0 para todos los insumos",
                icon: "error"
            });
            return false;
        }
    }

    // Validar equipos si existen
    if (verificaEquipos()) {
        var cantidadesValidas = true;
        $("#EquiposSeleccionados tr").each(function() {
            var cantidad = $(this).find("td:eq(3) input").val();
            if (cantidad <= 0) {
                cantidadesValidas = false;
                return false;
            }
        });

        if (!cantidadesValidas) {
            Swal.fire({
                title: "Error",
                text: "La cantidad debe ser mayor a 0 para todos los equipos",
                icon: "error"
            });
            return false;
        }
    }

    // Verificar que al menos haya un insumo o un equipo
    if (!verificaInsumos() && !verificaEquipos()) {
        Swal.fire({
            title: "Error",
            text: "Debe agregar al menos un insumo o un equipo",
            icon: "error"
        });
        return false;
    }

    return true;
}



function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}


function carga_insumos(){
    var datos = new FormData();
    datos.append('accion','listadoInsumos'); //le digo que me muestre un listado de productos
    enviaAjax(datos);
}
        
function carga_equipos(){ 
    var datos = new FormData();//a ese datos le añadimos la informacion a enviar
    datos.append('accion','listadoEquipos'); //le digo que me muestre un listado de aulas
    enviaAjax(datos);   //ahora se envia el formdata por ajax
}

function verificaInsumos(){
    var existe = false;
    if($("#InsumosSeleccionados tr").length > 0){
        existe = true;
    }
    return existe;
}

function verificaEquipos(){
    var existe = false;
    if($("#EquiposSeleccionados tr").length > 0){
        existe = true;
    }
    return existe;
}


function colocaInsumo(linea){
    var id = $(linea).find("td:eq(0)").text();
    var encontro = false;
    $("#InsumosSeleccionados tr").each(function(){
        if(id*1 == $(this).find("td:eq(0)").text()*1){
            encontro = true;
            var t = $(this).find("td:eq(3)").children();
            t.val(t.val()*1+1);
            modificasubtotal(t);
        } 
    });
    if(!encontro){
        var cd=1;
        var l = `
        <tr> 
            <td style="display:none"> <input type="text" name="id_insumo[]" style="display:none" value="`+ $(linea).find("td:eq(0)").text()+`"/>`+$(linea).find("td:eq(0)").text()+`</td>
            <td>`+ $(linea).find("td:eq(1)").text()+ `</td>
            <td>`+ $(linea).find("td:eq(2)").text()+ `</td>
            <td> <input type="text" value="1" class="btn" name="cantidad[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)"/></td>
            <td> <input type="text" name="precio[]" class="btn" value="${$(linea).find("td:eq(6)").text()}" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>
            <td>`+ redondearDecimales($(linea).find("td:eq(6)").text()*1,2)+ `</td>
            <td> <button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button> </td>
        </tr>`;
        $("#InsumosSeleccionados").append(l);
    }
}

function colocaEquipo(linea){
    var id = $(linea).find("td:eq(0)").text();
    var encontro = false;
    $("#EquiposSeleccionados tr").each(function(){
        if(id*1 == $(this).find("td:eq(0)").text()*1){
            encontro = true;
            var t = $(this).find("td:eq(3)").children();
            t.val(t.val()*1+1);
            modificasubtotal(t);
        } 
    });
    if(!encontro){
        var cd=1;
        var l = `
        <tr> 
            <td style="display:none"> <input type="text" name="id_equipo[]" style="display:none" value="`+ $(linea).find("td:eq(0)").text()+`"/>`+$(linea).find("td:eq(0)").text()+`</td>
            <td>`+ $(linea).find("td:eq(1)").text()+ `</td>
            <td>`+ $(linea).find("td:eq(2)").text()+ `</td>
            <td> <input type="text" value="1" class="btn" name="cantidad[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)"/></td>
            <td> <input type="text" name="precio[]" class="btn" value="${$(linea).find("td:eq(6)").text()}" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>
            <td>`+ redondearDecimales($(linea).find("td:eq(6)").text()*1,2)+ `</td>
            <td> <button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button> </td>
        </tr>`;
        $("#EquiposSeleccionados").append(l);
    }
}

function validarCantidad(input) {
    var cantidad = parseInt(input.value);
    var linea = $(input).closest('tr');
    var productoId = linea.find("td:eq(0) input").val();
    var stockDisponible = 0;
    
    // Validar que sea un número entero positivo
    if (!/^[1-9][0-9]*$/.test(input.value)) {
        Swal.fire({
            title: "Error",
            text: "La cantidad debe ser un número mayor a cero",
            icon: "error"
        });
        input.value = 1;
        return;
    }
    
    // Buscar el stock disponible del producto en la tabla de insumos
    $("#listadoInsumos tr").each(function() {
        if($(this).find("td:eq(0)").text() == productoId) {
            stockDisponible = parseInt($(this).find("td:eq(4)").text());
            return false;
        }
    });

    // Si no se encontró el stock, buscar en la tabla de equipos
    if (stockDisponible === 0) {
        $("#listadoEquipos tr").each(function() {
            if($(this).find("td:eq(0)").text() == productoId) {
                stockDisponible = parseInt($(this).find("td:eq(5)").text());
                return false;
            }
        });
    }


    
    modificasubtotal(input);
}

// Función para manejar la selección de una fila
function pone(pos, accion) {
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#nombre").prop("disabled", true);
        $("#descripcion").prop("disabled", false);
        $("#precio").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#nombre").prop("disabled", true);
        $("#descripcion").prop("disabled", true);
        $("#precio").prop("disabled", true);
    }
    $("#nombre").val($(linea).find("td:eq(1)").text());
    $("#descripcion").val($(linea).find("td:eq(2)").text());
    $("#precio").val($(linea).find("td:eq(3)").text());

    // Limpiar las tablas de insumos y equipos seleccionados
    $("#InsumosSeleccionados").empty();
    $("#EquiposSeleccionados").empty();

    // Obtener el índice del servicio desde el número de fila
    var idx = parseInt($(linea).find("td:eq(0)").text()) - 1;
    
    if (idx !== undefined && idx >= 0) {
        var servicio = serviciosDatosGlobal[idx];
        
        // Cargar insumos asociados
        if (servicio.insumos && servicio.insumos.length > 0) {
            servicio.insumos.forEach(function(insumo) {
                var l = '<tr>';
                l += '<td style="display:none"><input type="text" name="id_insumo[]" style="display:none" value="' + insumo.id + '"/>' + insumo.id + '</td>';
                l += '<td>' + insumo.codigo + '</td>';
                l += '<td>' + insumo.nombre + '</td>';
                l += '<td><input type="text" value="' + insumo.cantidad + '" class="btn" name="cantidad[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)"/></td>';
                l += '<td><input type="text" name="precio[]" class="btn" value="' + parseFloat(insumo.precio).toFixed(2) + '" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>';
                l += '<td>' + parseFloat(insumo.subtotal).toFixed(2) + '</td>';
                l += '<td><button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button></td>';
                l += '</tr>';
                $("#InsumosSeleccionados").append(l);
            });
        }

        // Cargar equipos asociados
        if (servicio.equipos && servicio.equipos.length > 0) {
            servicio.equipos.forEach(function(equipo) {
                var l = '<tr>';
                l += '<td style="display:none"><input type="text" name="id_equipo[]" style="display:none" value="' + equipo.id + '"/>' + equipo.id + '</td>';
                l += '<td>' + equipo.codigo + '</td>';
                l += '<td>' + equipo.nombre + '</td>';
                l += '<td><input type="text" value="' + equipo.cantidad + '" class="btn" name="cantidad[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)"/></td>';
                l += '<td><input type="text" name="precio[]" class="btn" value="' + parseFloat(equipo.precio).toFixed(2) + '" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>';
                l += '<td>' + parseFloat(equipo.subtotal).toFixed(2) + '</td>';
                l += '<td><button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button></td>';
                l += '</tr>';
                $("#EquiposSeleccionados").append(l);
            });
        }
    }

    $("#modal1").modal("show");
}

// Función para limpiar los campos del formulario
function limpia() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#precio").val("");
    $("#codigoInsumo").val("");
    $("#codigoEquipo").val("");
    $("#nombre").prop("disabled", false);
    $("#descripcion").prop("disabled", false);
    $("#precio").prop("disabled", false);
    $("#InsumosSeleccionados").empty();
    $("#EquiposSeleccionados").empty();
    $("#datosdelcliente").html("");
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

function muestraMensaje(mensaje){//Funcion que muestra el modal con un mensaje
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
     $("#mostrarmodal").modal("hide");
 },5000);
}

function validarPrecio(input) {
    // Validar formato de número con 2 decimales
    if (!/^\d+(\.\d{0,2})?$/.test(input.value)) {
        Swal.fire({
            title: "Error",
            text: "El precio debe ser un número con máximo 2 decimales",
            icon: "error"
        });
        if(input.value == ""){
            input.value = "1.00";
        }
        input.value = parseFloat(input.value).toFixed(2);
    }
    // Validar que sea mayor a 0
    if (parseFloat(input.value) <= 0) {
        Swal.fire({
            title: "Error",
            text: "El precio debe ser mayor a 0",
            icon: "error"
        });
        input.value = "1.00";
    }
    modificasubtotal(input);
}

function modificasubtotal(input){
    var linea = $(input).closest('tr');
    var cantidad = linea.find("td:eq(3) input").val()*1;
    var precio = linea.find("td:eq(4) input").val()*1;
    linea.find("td:eq(5)").text(redondearDecimales((cantidad*precio),2));
}


function eliminalineadetalle(boton){//funcion para eliminar linea de detalle de salidas X
    $(boton).closest('tr').remove();
}

function redondearDecimales(numero, decimales) {
    return Number(Math.round(numero +'e'+ decimales) +'e-'+ decimales).toFixed(decimales);
    
}

// Función para enviar datos mediante AJAX
function enviaAjax(datos) {
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
        timeout: 10000,
        success: function(respuesta) {
            console.log("Respuesta recibida:", respuesta);
            try {
                var lee = JSON.parse(respuesta);
                console.log("JSON parseado:", lee);
                console.log("Resultado:", lee.resultado);
                
                if (lee.resultado == "consultar") {
                    destruyeDT();
                    serviciosDatosGlobal = lee.datos;
                    $("#resultadoconsulta").html(generarTablaServicios(lee.datos));
                    crearDT();
                } else if (lee.resultado == "incluir") {
                    // Manejo de respuesta al incluir
                    
                    if (lee.mensaje == '¡Servicio registrado con éxito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "El servicio ha sido incluido con éxito.",
                            icon: "success"
                        });
                        $("#modal1").modal("hide"); // Oculta el modal
                        consultar(); // Vuelve a consultar            
                        carga_insumos();
                        carga_equipos();
                        limpia(); // Limpia el formulario       


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
                        carga_insumos();
                        carga_equipos();
                        limpia(); // Limpia el formulario   
                    }
                } 
                else if (lee.resultado == "eliminar") {
                    // Manejo de respuesta al eliminar
                    Swal.fire({
                        title: lee.mensaje == '¡Registro eliminado con exito!' ? "¡Eliminado!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje == '¡Registro eliminado con exito!' ? "success" : "error"
                    });
                    if (lee.mensaje == '¡Registro eliminado con exito!') {
                        $("#modal1").modal("hide"); // Oculta el modal
                        consultar(); // Vuelve a consultar
                        carga_insumos();
                        carga_equipos();
                        limpia(); // Limpia el formulario                          
                    }
                } 

                else if(lee.resultado=='listadoInsumos'){
                    $('#listadoInsumos').html(generarTablaInsumos(lee.datos));
                }
                else if(lee.resultado=='listadoEquipos'){
                    $('#listadoEquipos').html(generarTablaEquipos(lee.datos));
                }

                else if (lee.resultado == "error") {
                    Swal.fire({
                        title: "Error",
                        text: lee.mensaje,
                        icon: "error"
                    });
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e);
                console.error("Respuesta que causó el error:", respuesta);
                Swal.fire({
                    title: "Error",
                    text: "Error al procesar la respuesta del servidor: " + e.message,
                    icon: "error"
                });
            }
        },
        error: function(request, status, err) {
            console.error("Error en la petición AJAX:", {request, status, err});
            if (status == "timeout") {
                Swal.fire({
                    title: "Error",
                    text: "Servidor ocupado, intente de nuevo",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Error en la petición: " + err,
                    icon: "error"
                });
            }
        },
        complete: function() {
            $("#loader").hide(); // Ocultar loader al completar
        }
    });
}



function toggleDetalles(boton) {
    try {
        var detalles = $(boton).next('div');
        if (!detalles.length) {
            console.error('No se encontró el elemento de detalles');
            return;
        }
        
        if (detalles.is(':visible')) {
            detalles.slideUp(200, function() {
                $(boton).text($(boton).text().replace('Ocultar', 'Ver'));
            });
        } else {
            detalles.slideDown(200, function() {
                $(boton).text($(boton).text().replace('Ver', 'Ocultar'));
            });
        }
    } catch (error) {
        console.error('Error en toggleDetalles:', error);
    }
}

function generarTablaServicios(datos) {
    var html = '';
    if (datos.length === 0) {
        html += '<tr><td colspan="7" class="text-center">No hay servicios registrados</td></tr>';
        return html;
    }
    datos.forEach(function(servicio, idx) {
        html += '<tr class="text-center">';
        html += '<td class="align-middle">' + servicio.numero + '</td>';
        html += '<td class="align-middle">' + servicio.nombre + '</td>';
        html += '<td class="align-middle">' + servicio.descripcion + '</td>';
        html += '<td class="align-middle">' + parseFloat(servicio.precio).toFixed(2) + '</td>';
        // Insumos
        if (servicio.insumos && servicio.insumos.length > 0) {
            html += '<td class="align-middle"><button type="button" class="btn btn-sm btn-info detalles-insumos-btn" data-index="' + idx + '">Ver Insumos</button></td>';
        } else {
            html += '<td class="align-middle text-muted">Sin insumos</td>';
        }
        // Equipos
        if (servicio.equipos && servicio.equipos.length > 0) {
            html += '<td class="align-middle"><button type="button" class="btn btn-sm btn-info detalles-equipos-btn" data-index="' + idx + '">Ver Equipos</button></td>';
        } else {
            html += '<td class="align-middle text-muted">Sin equipos</td>';
        }
        html += '<td class="align-middle">';
        html += '<button type="button" class="btn-sm btn-primary w-50 small-width mb-1" onclick="pone(this,0)" title="Modificar servicio"><i class="bi bi-arrow-repeat"></i></button><br/>';
        html += '<button type="button" class="btn-sm btn-danger w-50 small-width mt-1" onclick="pone(this,1)" title="Eliminar servicio"><i class="bi bi-trash"></i></button><br/>';
        html += '</td>';
        html += '</tr>';
    });
    return html;
}

// Evento para mostrar insumos en el modal
$(document).on('click', '.detalles-insumos-btn', function() {
    var idx = $(this).data('index');
    var servicio = serviciosDatosGlobal[idx];
    var html = '<div class="table-responsive"><table class="table table-sm table-bordered">';
    html += '<thead><tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>';
    if (servicio.insumos && servicio.insumos.length > 0) {
        servicio.insumos.forEach(function(insumo) {
            html += '<tr>';
            html += '<td>' + insumo.codigo + '</td>';
            html += '<td>' + insumo.nombre + '</td>';
            html += '<td>' + insumo.cantidad + '</td>';
            html += '<td>' + parseFloat(insumo.precio).toFixed(2) + '</td>';
            html += '<td>' + parseFloat(insumo.subtotal).toFixed(2) + '</td>';
            html += '</tr>';
        });
    } else {
        html += '<tr><td colspan="5" class="text-center">Sin insumos</td></tr>';
    }
    html += '</tbody></table></div>';
    $("#modalDetallesLabel").text("Insumos del Servicio: "+servicio.nombre);
    $("#contenidoDetalles").html(html);
    $("#modalDetalles").modal("show");
});

// Evento para mostrar equipos en el modal
$(document).on('click', '.detalles-equipos-btn', function() {
    var idx = $(this).data('index');
    var servicio = serviciosDatosGlobal[idx];
    var html = '<div class="table-responsive"><table class="table table-sm table-bordered">';
    html += '<thead><tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>';
    if (servicio.equipos && servicio.equipos.length > 0) {
        servicio.equipos.forEach(function(equipo) {
            html += '<tr>';
            html += '<td>' + equipo.codigo + '</td>';
            html += '<td>' + equipo.nombre + '</td>';
            html += '<td>' + equipo.cantidad + '</td>';
            html += '<td>' + parseFloat(equipo.precio).toFixed(2) + '</td>';
            html += '<td>' + parseFloat(equipo.subtotal).toFixed(2) + '</td>';
            html += '</tr>';
        });
    } else {
        html += '<tr><td colspan="5" class="text-center">Sin equipos</td></tr>';
    }
    html += '</tbody></table></div>';
    $("#modalDetallesLabel").text("Equipos del Servicio: "+servicio.nombre);
    $("#contenidoDetalles").html(html);
    $("#modalDetalles").modal("show");
});

function generarTablaInsumos(datos) {
    var html = '';
    datos.forEach(function(insumo) {
        html += '<tr style="cursor:pointer" onclick="colocaInsumo(this);">';
        html += '<td style="display:none">' + insumo.id + '</td>';
        html += '<td>' + insumo.codigo + '</td>';
        html += '<td>' + insumo.nombre + '</td>';
        html += '<td>' + insumo.marca + '</td>';
        
        // Columna de cantidad con badges
        html += '<td>' + insumo.cantidad;
        if (insumo.sin_stock) {
            html += '<span class="badge bg-danger" title="Este insumo esta agotado">No disponible</span>';
        } else if (insumo.stock_bajo) {
            html += '<br><span class="badge bg-warning" title="Este insumo llegó al nivel mínimo">Stock bajo</span>';
        }
        html += '</td>';
        
        html += '<td>' + insumo.cantidad_minima + '</td>';
        html += '<td>' + insumo.precio + '</td>';
        html += '<td>' + insumo.id_presentacion + '</td>';
        html += '<td class="align-middle"><img src="' + insumo.imagen + '" alt="Imagen del insumo" class="img"/></td>';
        html += '</tr>';
    });
    return html;
}

function generarTablaEquipos(datos) {
    var html = '';
    datos.forEach(function(equipo) {
        html += '<tr style="cursor:pointer" onclick="colocaEquipo(this);">';
        html += '<td style="display:none">' + equipo.id + '</td>';
        html += '<td>' + equipo.codigo + '</td>';
        html += '<td>' + equipo.nombre + '</td>';
        html += '<td>' + equipo.marca + '</td>';
        html += '<td>' + equipo.modelo + '</td>';
        html += '<td>' + equipo.cantidad + '</td>';
        html += '<td>' + equipo.precio + '</td>';
        html += '<td class="align-middle"><img src="' + equipo.imagen + '" alt="Imagen del equipo" class="img"/></td>';
        html += '</tr>';
    });
    return html;
}


