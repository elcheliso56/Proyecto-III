$(document).ready(function () {
    consultar(); // Llama a la función consultar al cargar el documento

    // Evento para cuando se muestra la pestaña de entradas
    $('#entradas-tab').on('shown.bs.tab', function (e) {
        consultar2();
        carga_equipos();//carga la lista de equipos
    });

    // Evento para cuando se muestra la pestaña de entradas
    $('#equipos-tab').on('shown.bs.tab', function (e) {
        consultar();
    });



    // Validaciones para el campo de código
    $("#codigo").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#codigo").on("keyup", function () {
        validarkeyup(/^[^"']{1,20}$/, $(this), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres");
    });

    // Validaciones para el campo de nombre
    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombre"), "Solo letras  entre 3 y 50 caracteres");
    });

    // Validaciones para el campo de marca
    $("#marca").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#marca").on("keyup", function () {
        validarkeyup(/^[^"']{1,50}$/, $(this), $("#smarca"), "La marca debe tener un máximo de 50 caracteres");
    });

    // Validaciones para el campo de modelo
    $("#modelo").on("keypress", function (e) {
        validarkeypress(/^[^"']*$/, e);
    });

    $("#modelo").on("keyup", function () {
        validarkeyup(/^[^"']{1,50}$/, $(this), $("#smodelo"), "El modelo debe tener un máximo de 50 caracteres");
    });

    // Validaciones para el campo de stock total
    $("#cantidad").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#scantidad").on("keyup", function () {
        validarkeyup(/^[0-9]{1,10}$/, $(this), $("#scantidad"), "El stock debe ser numeros enteros");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo equipo
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo equipo?",
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

                        datos.append('codigo', $("#codigo").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('marca', $("#marca").val());
                        datos.append('modelo', $("#modelo").val());                        
                        datos.append('cantidad', $("#cantidad").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                }
            });
            } }

            else if ($(this).text() == "MODIFICAR") {
            // Confirmación para modificar un equipo existente
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
                        text: "¿Deseas modificar este equipo?",
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
                        datos.append('codigo', $("#codigo").val());
                        datos.append('nombre', $("#nombre").val());
                        datos.append('marca', $("#marca").val());
                        datos.append('modelo', $("#modelo").val());
                        datos.append('cantidad', $("#cantidad").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Mensaje de cancelación
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El equipo no ha sido modificado",
                        icon: "error"
                    });
                }
            });
                } }
                else if ($(this).text() == "ELIMINAR") {
            // Validación antes de eliminar un equipo
                    if (validarkeyup(/^[^"']{1,50}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres") == 0) {
                        muestraMensaje("El Código debe tener entre 1 y 20 caracteres");
                    } else {
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
                        datos.append('codigo', $("#codigo").val()); // Se agrega el código del equipo
                        enviaAjax(datos); // Envía los datos al servidor
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Mensaje de cancelación
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "Equipo no eliminado",
                            icon: "error"
                        });
                    }
                });
                    }
                }
            });




$("#listadoDeEquipos").on("click",function(){//boton para levantar modal de equipos
    $("#modalEquipos").modal("show");
});


$("#codigoEquipo").on("keyup",function(){//evento keyup de input codigoEquipo
    var codigo = $(this).val();
    $("#listadoEquipos tr").each(function(){
        if(codigo == $(this).find("td:eq(1)").text()){
            colocaEquipo($(this));
        }
    });
}); 

$("#buscarEquipos").on("keyup", function() {
    var valor = $(this).val().toLowerCase();
    $("#listadoEquipos tr").filter(function() {
        var texto = $(this).text().toLowerCase();
        $(this).toggle(texto.indexOf(valor) > -1);
    });
});

$("#entrada").on("click",function(){//evento click de boton entrada
    if(validarenvio2()){
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Deseas procesar esta entrada?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, aceptar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                enviarFormulario();
                $("#modal2").modal("hide");
                consultar2();
                carga_equipos();
            }
        });
    }
});


    // Manejo del clic en el botón incluir
$("#incluir").on("click", function () {
        limpia(); // Limpia los campos del formulario
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón a 'INCLUIR'
        $("#modal1").modal("show"); // Muestra el modal
    });


$("#incluir2").on("click",function(){
    limpia();
    $("#entrada").text("INCLUIR");
    $("#modal2").modal("show");
}); 
});

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaEquipos")) {
        $("#tablaEquipos").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron equipos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay equipos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar equipo...",
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

function crearDT2() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaSalidaEquipos")) {
        $("#tablaSalidaEquipos").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron entradas de equipos",
                info: "Página _PAGE_ de _PAGES_",
                infoEmpty: "No hay entradas registradas",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar entrada...",
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
            order: [[1, "desc"]],
            responsive: true,
            columnDefs: [
                { targets: 3, orderable: false },
                { targets: 4, orderable: true }
                ]
        });
    }
    $(window).resize(function() {
        $('#tablaSalidaEquipos').DataTable().columns.adjust().draw();
    });
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaEquipos")) {
        $("#tablaEquipos").DataTable().destroy();
    }
}

function destruyeDT2() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaSalidaEquipos")) {
        $("#tablaSalidaEquipos").DataTable().destroy();
    }
}

function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

function consultar2() {
    var datos = new FormData();
    datos.append('accion', 'consultar2'); // Se agrega la acción 'consultar' a los datos
    enviaAjax(datos); // Se envían los datos al servidor
}

// Función para validar el envío de datos
function validarenvio() {
    // Validaciones para cada campo del formulario
    if (validarkeyup(/^[^"']{1,20}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El código del equipo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombre"), $("#snombre"), "Solo letras  entre 3 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del equipo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 

    if ($("#marca").val().trim() !== "" &&
        validarkeyup(/^[^"']{1,35}$/, $("#marca"), $("#smarca"), "La marca debe tener entre 1 y 35 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La marca debe tener entre 1 y 35 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
    return false;
}
if ($("#modelo").val().trim() !== "" &&
    validarkeyup(/^[^"']{1,35}$/, $("#modelo"), $("#smodelo"), "El modelo debe tener entre 1 y 35 caracteres") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El modelo debe tener entre 1 y 35 caracteres",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
return false;
} 

if (validarkeyup(/^[0-9]{1,10}$/, $("#cantidad"), $("#scantidad"), "El stock debe ser mayor o igual a 0") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El stock del equipo es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
} 

return true;
}

function validarenvio2() {
    if (verificaEquipos()) {
        var datosValidos = true;
        
        $("#detalledeventa tr").each(function() {
            var cantidad = parseFloat($(this).find("td:eq(3) input").val());
            var precio = parseFloat($(this).find("td:eq(4) input").val());
            
            if (cantidad <= 0 || isNaN(cantidad)) {
                datosValidos = false;
                Swal.fire({
                    title: "Error",
                    text: "La cantidad debe ser mayor a 0 para todos los equipos",
                    icon: "error"
                });
                return false;
            }
            
            if (precio <= 0 || isNaN(precio)) {
                datosValidos = false;
                Swal.fire({
                    title: "Error",
                    text: "El precio debe ser mayor a 0 para todos los equipos",
                    icon: "error"
                });
                return false;
            }
        });

        if (!datosValidos) {
            return false;
        }

        return true;
    } else {
        Swal.fire({
            title: "Error",
            text: "Debe agregar algún equipo a la entrada",
            icon: "error"
        });
        return false;
    }
}
// Función para llenar el formulario con datos de un equipo
function pone(pos, accion) {
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#marca").prop("disabled", false);
        $("#modelo").prop("disabled", false);
        $("#cantidad").prop("disabled", false);
        $("#imagen").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#marca").prop("disabled", true);
        $("#modelo").prop("disabled", true);
        $("#cantidad").prop("disabled", true);
        $("#imagen").prop("disabled", true);
    }
    $("#codigo").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#marca").val($(linea).find("td:eq(3)").text());
    $("#modelo").val($(linea).find("td:eq(4)").text());
    $("#cantidad").val($(linea).find("td:eq(5)").text().replace(/\D/g, ''));

    // Cargar imagen
    var imagenSrc = $(linea).find("td:eq(6) img").attr("src");
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show();// Muestra la imagen actual
        $("#imagen_url").val(imagenSrc);
        $("#imagen_url").val(imagenSrc);
        fetch(imagenSrc)
        .then(res => res.blob())
        .then(blob => {
            const file = new File([blob], imagenSrc.split('/').pop(), { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            $("#imagen")[0].files = dataTransfer.files;
        });
    } else {
        $("#imagen_actual").attr("src", "").hide();
        $("#imagen_url").val("");
    }
    $("#modal1").modal("show");
}

function limpia() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#marca").val("");
    $("#modelo").val("");    
    $("#cantidad").val("");
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#codigo").prop("disabled", false);
    $("#nombre").prop("disabled", false);
    $("#marca").prop("disabled", false);
    $("#modelo").prop("disabled", false);
    $("#cantidad").prop("disabled", false);    
    $("#imagen").prop("disabled", false);  
    $("#codigoEquipo").val("");
    $("#detalledeventa").empty();
    $("#nota_entrega").val("");
    $("#check_nota_entrega").hide();    
}


function verificarStock(cantidad) {
    if (parseInt(cantidad) === parseInt(0)) {
        return '<span class="badge bg-danger">No disponible</span>';
    }
    return '';
}

function mostrarAlertaStockBajo(productosBajoStock) {
    let mensaje = "Los siguientes equipos no están disponibles:\n";
    productosBajoStock.forEach(equipo => {
        mensaje += `- ${equipo.nombre} (Stock actual: ${equipo.cantidad})\n`;
    });

    Swal.fire({
        title: "¡Alerta falta de stock!",
        text: mensaje,
        icon: "warning",
        confirmButtonText: "Entendido"
    });
}

function carga_equipos(){
    var datos = new FormData();
    datos.append('accion','listadoEquipos'); //le digo que me muestre un listado de equipos
    enviaAjax(datos);
}

function verificaEquipos(){//function para saber si selecciono algun equipo
    var existe = false;
    // Comprobamos si hay filas en la tabla de detalle de venta
    var filas = $("#detalledeventa tr").length;
    console.log("Filas en detalledeventa: " + filas);
    if(filas > 0){
        existe = true;
    }
    return existe;
}

function colocaEquipo(linea){//funcion para colocar los equipos
    var id = $(linea).find("td:eq(0)").text();
    var encontro = false;
    
    $("#detalledeventa tr").each(function(){
        if(id*1 == $(this).find("td:eq(0)").text()*1){
            encontro = true;
            var t = $(this).find("td:eq(3)").children();
            t.val(t.val()*1+1);
            modificasubtotal(t);
        } 
    });
    
    if(!encontro){
        var l = `
        <tr> 
        <td style="display:none"> <input type="text" name="idp[]" style="display:none" value="${$(linea).find("td:eq(0)").text()}"/>${$(linea).find("td:eq(0)").text()}</td>
        <td>${$(linea).find("td:eq(1)").text()}</td>
        <td>${$(linea).find("td:eq(2)").text()}</td>
        <td> <input type="text" value="1" class="btn" name="cant[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)" /></td>
        <td> <input type="text" name="pcp[]" class="btn" value="${$(linea).find("td:eq(6)").text()}" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>
        <td>${redondearDecimales($(linea).find("td:eq(6)").text()*1,2)}</td>
        <td> <button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button> </td>
        </tr>`;
        $("#detalledeventa").append(l);
    }
}

function validarCantidad(input) {
    var cantidad = parseInt(input.value);
    var linea = $(input).closest('tr');
    var equipoId = linea.find("td:eq(0) input").val();
    var stockDisponible = 0;
    
    // Validar que sea un número entero positivo
    if (!/^[1-9][0-9]*$/.test(input.value)) {
        Swal.fire({
            title: "Error",
            text: "La cantidad debe ser un número mayor a cero",
            icon: "error"
        });
        input.value = 1;
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



function muestraMensaje(mensaje){//Funcion que muestra el modal con un mensaje
    $("#contenidodemodal").html(mensaje);
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
       $("#mostrarmodal").modal("hide");
   },5000);
}



function enviarFormulario() {
    if (verificaEquipos()) {
        var datos = new FormData();
        datos.append('accion', 'entrada');        
        // Obtener los arrays de equipos
        var idp = $("input[name='idp[]']").map(function(){return $(this).val();}).get();
        var cant = $("input[name='cant[]']").map(function(){return $(this).val();}).get();
        var pcp = $("input[name='pcp[]']").map(function(){return $(this).val();}).get();
        
        console.log("IDs de equipos:", idp);
        console.log("Cantidades:", cant);
        console.log("Precios:", pcp);
        
        // Corregir la forma de enviar arrays en FormData
        for(let i = 0; i < idp.length; i++) {
            datos.append('idp[]', idp[i]);
            datos.append('cant[]', cant[i]);
            datos.append('pcp[]', pcp[i]);
        }
        
        if ($("#nota_entrega")[0].files[0]) {
            datos.append('nota_entrega', $("#nota_entrega")[0].files[0]);
        }
        
        enviaAjax(datos);
    } else {
        Swal.fire({
            title: "Error",
            text: "Debe seleccionar al menos un equipo para la entrada",
            icon: "error"
        });
    }
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

function validarkeypress(er, e) {// Función para validar la tecla presionada
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault(); // Previene la acción si no coincide con la expresión regular
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {// Función para validar el valor de un campo
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text(""); // Limpia el mensaje si es válido
        return 1; // Retorna 1 si es válido
    } else {
        etiquetamensaje.text(mensaje); // Muestra el mensaje de error
        return 0; // Retorna 0 si no es válido
    }
}

$("#nota_entrega").on("change", function() {
    const file = this.files[0];
    if (file) {
        // Mostrar el check
        $("#check_nota_entrega").show();
    } else {
        // Ocultar el check si no hay archivo seleccionado
        $("#check_nota_entrega").hide();
    }
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

function enviaAjax(datos) {
    console.log("Enviando petición AJAX...");
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
            console.log("Enviando datos:", datos);
        },
        timeout: 10000,
        success: function (respuesta) {
            console.log("Respuesta recibida:", respuesta);
            try {
                console.log("Respuesta del servidor:", respuesta); // Depuración
                var lee = JSON.parse(respuesta); // Intenta parsear la respuesta
                console.log("Respuesta parseada:", lee); // Depuración
                if (lee.resultado == "consultar") {
                    destruyeDT();
                    var tablaHTML = generarTablaEquipos(lee.datos);
                    $("#resultadoconsulta").html(tablaHTML);
                    crearDT();
                    if (lee.productos_bajo_stock && lee.productos_bajo_stock.length > 0) {
                        mostrarAlertaStockBajo(lee.productos_bajo_stock);
                    }
                } 
                else if (lee.resultado == "consultar2") {
                    destruyeDT2();
                    var tablaHTML = generarTablaEntradas(lee.datos);
                    $("#resultadoconsulta2").html(tablaHTML);
                    crearDT2();
                } 
                else if (lee.resultado == "incluir2") {
                    // Manejo de respuesta al incluir
                    if (lee.mensaje == '¡Registro guardado con exito!') {
                        Swal.fire({
                            title: "¡Incluido!",
                            text: "La entrada ha sido incluida con éxito.",
                            icon: "success"
                        });
                        $("#modal2").modal("hide"); // Oculta el modal
                        consultar2(); // Vuelve a consultar
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: lee.mensaje,
                            icon: "error"
                        });
                    }
                }  
                if(lee.resultado=='listadoproveedores'){
                    $('#listadoproveedores').html(lee.mensaje);
                }
                else if(lee.resultado=='listadoEquipos'){
                    $('#listadoEquipos').html(generarTablaListadoEquipos(lee.datos));
                }
                else if(lee.resultado=='entrada'){   

                 console.log(lee.mensaje);             
                 Swal.fire({
                    title: "¡Incluido!",
                    text: "La entrada ha sido incluida con éxito.",
                    icon: "success"
                });
                    $("#modal2").modal("hide"); // Oculta el modal
                    consultar2(); // Actualiza la tabla de salidas
                    carga_equipos(); // Recarga la lista de equipos
                    limpia(); // Limpia el formulario
                }
                else if (lee.resultado == "consultar") {
                    destruyeDT();
                    $("#resultadoconsulta").html(lee.mensaje);
                    crearDT();
                    if (lee.productos_bajo_stock && lee.productos_bajo_stock.length > 0) {
                        mostrarAlertaStockBajo(lee.productos_bajo_stock);
                    }
                } 
                else if (lee.resultado == "incluir" || lee.resultado == "modificar") {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Éxito!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje.includes('éxito') ? "success" : "error"
                    });
                    if (lee.mensaje.includes('éxito')) {
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
                    if (lee.mensaje == '¡Registro eliminado con exito!') {
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
            } 
            catch (e) {
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

function generarTablaEquipos(datos) {
    var html = '';
    datos.forEach(function(equipo) {
        html += '<tr>';
        html += '<td>' + equipo.numero + '</td>';
        html += '<td>' + equipo.codigo + '</td>';
        html += '<td>' + equipo.nombre + '</td>';
        html += '<td>' + equipo.marca + '</td>';
        html += '<td>' + equipo.modelo + '</td>';
        
        // Columna de cantidad con badges
        html += '<td>' + equipo.cantidad;
        if (equipo.cantidad == 0) {
            html += '<br><span class="badge bg-danger" title="Este equipo esta agotado">No disponible</span>';
        }
        html += '</td>';
        
        // Columna de imagen
        html += '<td><a href="' + equipo.imagen + '" target="_blank"><img src="' + equipo.imagen + '" alt="Imagen del equipo" class="img"/></a></td>';
        
        // Columna de acciones
        html += '<td>';
        html += '<button type="button" class="btn-sm btn-primary w-50 small-width mb-1" onclick="pone(this,0)" title="Modificar equipo"><i class="bi bi-arrow-repeat"></i></button><br/>';
        html += '<button type="button" class="btn-sm btn-danger w-50 small-width mt-1" onclick="pone(this,1)" title="Eliminar equipo"><i class="bi bi-trash"></i></button><br/>';
        html += '</td>';
        
        html += '</tr>';
    });
    return html;
}

function generarTablaEntradas(datos) {
    var html = '';
    datos.forEach(function(entrada) {
        html += '<tr class="text-center">';
        html += '<td class="align-middle">' + entrada.numero + '</td>';
        html += '<td class="align-middle">' + entrada.fecha_entrada + '</td>';
        
        // Columna de nota de entrega
        html += '<td class="align-middle">';
        if (entrada.nota_entrega) {
            html += '<a href="' + entrada.nota_entrega + '" target="_blank">Ver nota</a>';
        } else {
            html += 'No disponible';
        }
        html += '</td>';
        
        // Columna de equipos con tabla anidada
        html += '<td class="align-middle"><table class="table table-sm"><thead><tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>';
        
        // Agregar detalles de cada equipo
        entrada.detalles.forEach(function(detalle) {
            html += '<tr>';
            html += '<td>' + detalle.codigo + '</td>';
            html += '<td>' + detalle.nombre + '</td>';
            html += '<td>' + detalle.cantidad + '</td>';
            html += '<td>$' + parseFloat(detalle.precio).toFixed(2) + '</td>';
            html += '<td>$' + parseFloat(detalle.subtotal).toFixed(2) + '</td>';
            html += '</tr>';
        });
        
        html += '</tbody></table></td>';
        
        // Columna de total
        html += '<td class="align-middle">$' + parseFloat(entrada.total).toFixed(2) + '</td>';
        html += '</tr>';
    });
    return html;
}

function generarTablaListadoEquipos(datos) {
    var html = '';
    datos.forEach(function(equipo) {
        html += '<tr style="cursor:pointer" onclick="colocaEquipo(this);">';
        html += '<td style="display:none">' + equipo.id + '</td>';
        html += '<td>' + equipo.codigo + '</td>';
        html += '<td>' + equipo.nombre + '</td>';
        html += '<td>' + equipo.marca + '</td>';
        html += '<td>' + equipo.modelo + '</td>';
        
        // Columna de cantidad con badge para stock bajo
        html += '<td>' + equipo.cantidad;
        if (equipo.cantidad == 0) {
            html += '<br><span class="badge bg-danger" title="Este equipo esta agotado">No disponible</span>';
        }
        html += '</td>';
        
        html += '<td>' + equipo.precio + '</td>';
        html += '<td class="align-middle"><img src="' + equipo.imagen + '" alt="Imagen del equipo" class="img"/></td>';
        html += '</tr>';
    });
    return html;
}