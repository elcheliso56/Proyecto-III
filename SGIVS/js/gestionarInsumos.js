$(document).ready(function () {
    consultar();  
    cargarOpciones();

    // Evento para cuando se muestra la pestaña de entradas
    $('#entradas-tab').on('shown.bs.tab', function (e) {
        consultar2();
        carga_insumos();
    });
    // Evento para cuando se muestra la pestaña de entradas
    $('#insumos-tab').on('shown.bs.tab', function (e) {
        consultar();
    });

    // Verificar si se accedió desde la página de presentaciones
    const urlParams = new URLSearchParams(window.location.search);
    const fromPresentaciones = urlParams.get('from') === 'presentaciones';
    
    if (fromPresentaciones) {
        // Abrir el modal de registro automáticamente
        $("#incluir").trigger('click');
    }

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
        validarkeyup(/^[^"']{1,35}$/, $(this), $("#smarca"), "La marca debe tener un máximo de 35 caracteres");
    });

    // Validaciones para el campo de stock total
    $("#stock_total").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#stock_total").on("keyup", function () {
        validarkeyup(/^[0-9]{1,10}$/, $(this), $("#sstock_total"), "El stock debe ser numeros enteros");
    });

    // Validaciones para el campo de stock mínimo
    $("#stock_minimo").on("keypress", function (e) {
        validarkeypress(/^[0-9\b]*$/, e);
    });

    $("#stock_minimo").on("keyup", function () {
        validarkeyup(/^[0-9]{1,10}$/, $(this), $("#sstock_minimo"), "El stock mínimo debe ser números enteros");
    });

    // Validaciones para el campo de precio de compra
    $("#precio").on("keypress", function (e) {
        validarkeypress(/^[0-9.\b]*$/, e);
    });

    $("#precio").on("keyup", function () {
        validarkeyup(/^[0-9.]{1,10}$/, $(this), $("#sprecio"), "El precio debe ser mayor o igual a 0");
    });

    // Manejo de clics en el botón de proceso
    $("#proceso").on("click", function () {
        if ($(this).text() == "INCLUIR") {
            if (validarenvio()) {
            // Confirmación para incluir un nuevo insumo
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas incluir este nuevo insumo?",
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
                        datos.append('stock_total', $("#stock_total").val());
                        datos.append('stock_minimo', $("#stock_minimo").val());                        
                        datos.append('precio', $("#precio").val());
                        datos.append('presentacion_id', $("#presentacion").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                }
            });
            } 
        }

        else if ($(this).text() == "MODIFICAR") {
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
                    text: "¿Deseas modificar este insumo?",
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
                        datos.append('stock_total', $("#stock_total").val());
                        datos.append('stock_minimo', $("#stock_minimo").val());                        
                        datos.append('precio', $("#precio").val());
                        datos.append('presentacion_id', $("#presentacion").val());
                        if ($("#imagen")[0].files[0]) {
                            datos.append('imagen', $("#imagen")[0].files[0]); // Agrega la imagen si existe
                        }
                        enviaAjax(datos); // Envía los datos al servidor
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Mensaje de cancelación
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado",
                        text: "El insumo no ha sido modificado",
                        icon: "error"
                    });
                }
            });
            } }
            else if ($(this).text() == "ELIMINAR") {
            // Validación antes de eliminar 
                if (validarkeyup(/^[^"']{1,20}$/, $("#codigo"), $("#scodigo"), "El Código debe tener entre 1 y 20 caracteres") == 0) {
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
                        datos.append('codigo', $("#codigo").val()); // Se agrega el código 
                        console.log("Enviando solicitud de eliminación para código: " + $("#codigo").val());
                        enviaAjax(datos); // Envía los datos al servidor
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Mensaje de cancelación
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "Insumo no eliminado",
                            icon: "error"
                        });
                    }
                });
                }
            }
        });

    // Manejo del clic en el botón incluir
$("#incluir").on("click", function () {
        limpia(); // Limpia los campos del formulario
        $("#proceso").text("INCLUIR"); // Cambia el texto del botón a 'INCLUIR'
        $("#modal1").modal("show"); // Muestra el modal
    });







    // Inicializar Select2 en el select de presentacion
$('#presentacion').select2({
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


$("#listadodeclientes").on("click",function(){//boton para levantar modal de clientes
    $("#modalclientes").modal("show");
});


$("#listadoDeInsumos").on("click",function(){//boton para levantar modal de insumos
    $("#modalInsumos").modal("show");
});
$("#codigoInsumos").on("keyup",function(){//evento keyup de input codigo 
    var codigo = $(this).val();
    var insumoEncontrado = false;
    
    // Verificar si el código existe
    $("#listadoInsumos tr").each(function(){
        if(codigo == $(this).find("td:eq(1)").text()){
            colocaInsumo($(this));
            insumoEncontrado = true;
        }
    });

    // Si el código no existe y no está vacío, mostrar el botón de insertar
    if (!insumoEncontrado && codigo !== '') {
        // Verificar si el botón ya existe para no duplicarlo
        if ($("#btnInsertarInsumo").length === 0) {
            var btnInsertar = $('<button type="button" class="btn btn-warning" id="btnInsertarInsumo" style="margin-left: 10px;"><i class="bi bi-plus-square"></i> Insertar Insumo</button>');
            $(this).after(btnInsertar);
        }
    } else {
        // Si el código existe o está vacío, eliminar el botón si existe
        $("#btnInsertarInsumo").remove();
    }
}); 

$("#buscarInsumo").on("keyup", function() {
    var valor = $(this).val().toLowerCase();
    $("#listadoInsumos tr").filter(function() {
        var texto = $(this).text().toLowerCase();
        $(this).toggle(texto.indexOf(valor) > -1);
    });
});

$("#entrada").on("click",function(){//evento click de boton entrada
    if(validarEnvioEntradas()){
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
            }
        });
    }
});

$("#incluir2").on("click",function(){
    limpia();
    $("#proceso").text("INCLUIR");
    $("#modal2").modal("show");
}); 

// Manejo del botón de reporte
$("#generar_reporte").click(function() {
    $("#modalReporte").modal("show");
});

// Limpiar formulario de reporte al cerrar el modal
$("#modalReporte").on("hidden.bs.modal", function() {
    $("#formReporte")[0].reset();
});

});

function crearDT() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaInsumos")) {
        $("#tablaInsumos").DataTable({
            language: {
                // Configuración de idioma para la tabla
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron insumos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay insumos registrados",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "<i class='bi bi-search'></i>",
                searchPlaceholder: "Buscar insumo...",
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
         // Ajustar columnas después de la inicialización
        $('#tablaInsumos').DataTable().columns.adjust().draw();
    }
}

function crearDT2() {
    // Crea una nueva tabla si no existe
    if (!$.fn.DataTable.isDataTable("#tablaEntradasInsumos")) {
        $("#tablaEntradasInsumos").DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ por página",
                zeroRecords: "No se encontraron entradas de insumos",
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
            columns: [
                null, // Columna #
                null, // Columna Fecha
                null, // Columna Insumos
                null  // Columna Total
                ],
            columnDefs: [
                { targets: [0, 1, 2, 3], orderable: true }
                ]
        });
    }
    $(window).resize(function() {
        $('#tablaEntradasInsumos').DataTable().columns.adjust().draw();
    });
}

function destruyeDT() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaInsumos")) {
        $("#tablaInsumos").DataTable().destroy();
    }   
}

function destruyeDT2() {
    // Verifica si la tabla existe y la destruye
    if ($.fn.DataTable.isDataTable("#tablaEntradasInsumos")) {
        $("#tablaEntradasInsumos").DataTable().destroy();
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
            text: "El código del insumo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombre"), $("#snombre"), "Solo letras  entre 3 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "El nombre del insumo es obligatorio",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
        return false;
    } 

    if ($("#marca").val().trim() !== "" &&
        validarkeyup(/^[^"']{1,50}$/, $("#marca"), $("#smarca"), "La marca debe tener entre 1 y 50 caracteres") == 0) {
        Swal.fire({
            title: "¡ERROR!",
            text: "La marca debe tener entre 1 y 50 caracteres",
            icon: "error",
            confirmButtonText: "Aceptar"
        });
    return false;
}

if (validarkeyup(/^[0-9]{1,10}$/, $("#stock_total"), $("#sstock_total"), "El stock debe ser mayor o igual a 0") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El stock del insumo es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
} 
if (validarkeyup(/^[0-9]{1,10}$/, $("#stock_minimo"), $("#sstock_minimo"), "El stock mínimo debe ser mayor o igual a 0") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El stock mínimo del insumo es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
}
if (validarkeyup(/^[0-9.]{1,10}$/, $("#precio"), $("#sprecio"), "El precio debe ser mayor o igual a 0") == 0) {
    Swal.fire({
        title: "¡ERROR!",
        text: "El precio del insumo es obligatorio",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
}  

if ($("#presentacion").val() === null) {
    Swal.fire({
        title: "¡ERROR!",
        text: "Debe seleccionar una presentación del producto",
        icon: "error",
        confirmButtonText: "Aceptar"
    });
    return false;
}

return true;
}

function validarEnvioEntradas() {
    if (verificaInsumos()) {
        var datosValidos = true;
        
        $("#detalledeventa tr").each(function() {
            var cantidad = parseFloat($(this).find("td:eq(3) input").val());
            var precio = parseFloat($(this).find("td:eq(4) input").val());
            
            if (cantidad <= 0 || isNaN(cantidad)) {
                datosValidos = false;
                Swal.fire({
                    title: "Error",
                    text: "La cantidad debe ser mayor a 0 para todos los insumos",
                    icon: "error"
                });
                return false;
            }
            
            if (precio <= 0 || isNaN(precio)) {
                datosValidos = false;
                Swal.fire({
                    title: "Error",
                    text: "El precio debe ser mayor a 0 para todos los insumos",
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
            text: "Debe agregar algún insumo a la entrada",
            icon: "error"
        });
        return false;
    }
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
// Función para llenar el formulario con datos de un insumo
function pone(pos, accion) {
    linea = $(pos).closest('tr');
    if (accion == 0) {
        $("#proceso").text("MODIFICAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", false);
        $("#marca").prop("disabled", false);
        $("#stock_total").prop("disabled", false);
        $("#stock_minimo").prop("disabled", false);      
        $("#precio").prop("disabled", false);
        $("#imagen").prop("disabled", false);
        $("#presentacion").prop("disabled", false);
    } else {
        $("#proceso").text("ELIMINAR");
        $("#codigo").prop("disabled", true);
        $("#nombre").prop("disabled", true);
        $("#marca").prop("disabled", true);
        $("#stock_total").prop("disabled", true);
        $("#stock_minimo").prop("disabled", true);
        $("#precio").prop("disabled", true);      
        $("#imagen").prop("disabled", true);
        $("#presentacion").prop("disabled", true);          
    }

    // Obtener valores de cada celda
    $("#codigo").val($(linea).find("td:eq(1)").text());
    $("#nombre").val($(linea).find("td:eq(2)").text());
    $("#marca").val($(linea).find("td:eq(3)").text());
    $("#stock_total").val($(linea).find("td:eq(4)").text().replace(/\D/g, ''));
    $("#stock_minimo").val($(linea).find("td:eq(5)").text());
    



// Obtener valor de presentación
    var presentacionId = $(linea).find("td:eq(6)").attr("data-presentacion-id");
    $("#presentacion").val(presentacionId).trigger('change');
    
    // Obtener precio (puede estar en índice 7 o 6 dependiendo del rol del usuario)
    var precioText = $(linea).find("td:eq(7)").text().trim();
    if (precioText.includes("$")) {
        var precio = precioText.replace("$", "");
        $("#precio").val(precio);
    } else {
        // Si el precio no está en el índice 7, puede ser porque el usuario no es administrador
        $("#precio").val("");
    }
    
    // Cargar imagen (índice puede variar según el rol del usuario)
    var imgIndex = 8;
    if (!precioText.includes("$")) {
        imgIndex = 7; // Ajustar índice si no hay columna de precio
    }
    
    var imagenSrc = $(linea).find("td:eq(" + imgIndex + ") img").attr("src");
    if (imagenSrc) {
        $("#imagen_actual").attr("src", imagenSrc).show();
        // Eliminar duplicación de imagen_url que no existe en el formulario
        try {
            fetch(imagenSrc)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], imagenSrc.split('/').pop(), { type: blob.type });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                $("#imagen")[0].files = dataTransfer.files;
            });
        } catch (error) {
            console.error("Error al cargar la imagen:", error);
        }
    } else {
        $("#imagen_actual").attr("src", "").hide();
    }
    $("#modal1").modal("show");
}

function limpia() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#marca").val("");
    $("#stock_total").val("");
    $("#stock_minimo").val("");        
    $("#precio").val("");
    $("#presentacion").val("").trigger('change'); 
    $("#imagen").val("");
    $("#imagen_actual").attr("src", "").hide();
    $("#codigo").prop("disabled", false);
    $("#nombre").prop("disabled", false);
    $("#marca").prop("disabled", false);
    $("#stock_total").prop("disabled", false);
    $("#stock_minimo").prop("disabled", false);      
    $("#precio").prop("disabled", false);
    $("#presentacion").prop("disabled", false);
    $("#imagen").prop("disabled", false);  
    $("#codigoInsumos").val("");
    $("#detalledeventa").empty();
    $("#datosdelcliente").html("");
}

function verificarStock(stockTotal, stockMinimo) {
    if (parseInt(stockTotal) <= parseInt(stockMinimo)) {
        return '<span class="badge bg-danger">Stock bajo</span>';
    }
    return '';
}

function mostrarAlertaStockBajo(insumosBajoStock) {
    let mensaje = "Los siguientes insumos tienen stock bajo:\n";
    insumosBajoStock.forEach(insumo => {
        mensaje += `- ${insumo.nombre} (Stock actual: ${insumo.stock_total}, Stock mínimo: ${insumo.stock_minimo})\n`;
    });

    Swal.fire({
        title: "¡Alerta de Stock Bajo!",
        text: mensaje,
        icon: "warning",
        confirmButtonText: "Entendido"
    });
}

function carga_insumos(){
    var datos = new FormData();
    datos.append('accion','listadoInsumos'); //le digo que me muestre un listado de insumos
    enviaAjax(datos);
}

function verificaInsumos(){//function para saber si selecciono algun insumos
    var existe = false;
    if($("#detalledeventa tr").length > 0){
        existe = true;
    }
    return existe;
}

function colocaInsumo(linea){//funcion para colocar los insumo
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
        var codigo = $(linea).find("td:eq(1)").text();
        var nombre = $(linea).find("td:eq(2)").text();
        var precio = $(linea).find("td:eq(6)").text();
        
        var l = `
        <tr> 
        <td style="display:none"> <input type="text" name="idp[]" style="display:none" value="${id}"/>${id}</td>
        <td>${codigo}</td>
        <td>${nombre}</td>
        <td> <input type="text" value="1" class="btn" name="cant[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)" /></td>
        <td> <input type="text" name="pcp[]" class="btn" value="${precio}" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>
        <td>${redondearDecimales(precio*1,2)}</td>
        <td> <button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button> </td>
        </tr>`;
        $("#detalledeventa").append(l);
    }
}

function validarCantidad(input) {
    var cantidad = parseInt(input.value);
    var linea = $(input).closest('tr');
    var insumoId = linea.find("td:eq(0) input").val();
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
    if (verificaInsumos()) {
        var datos = new FormData();
        datos.append('accion', 'entrada');
        // Obtener los arrays de insumos
        var idp = $("input[name='idp[]']").map(function(){return $(this).val();}).get();
        var cant = $("input[name='cant[]']").map(function(){return $(this).val();}).get();
        var pcp = $("input[name='pcp[]']").map(function(){return $(this).val();}).get();
        
        // Convertir arrays a strings con formato JSON para asegurar una correcta serialización
        datos.append('idp', idp.join(','));
        datos.append('cant', cant.join(','));
        datos.append('pcp', pcp.join(','));
        
        enviaAjax(datos);
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

function cargarOpciones() {
    $.ajax({
        url: '',
        type: 'POST',
        data: { accion: 'cargarOpciones' },
        success: function(respuesta) {
            var datos = JSON.parse(respuesta);
            
            // Cargar presentaciones
            datos.presentaciones.forEach(function(presentacion) {
                $('#presentacion').append($('<option>', {
                    value: presentacion.id,
                    text: presentacion.nombre
                }));
            }); 
        }
    });
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

function enviaAjax(datos) {
    console.log("Enviando petición AJAX...");
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
                    var tablaHTML = generarTablaInsumos(lee.datos);
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
                else if (lee.resultado == "incluir" || lee.resultado == "modificar") {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Éxito!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje.includes('éxito') ? "success" : "error"
                    }).then((result) => {
                        if (lee.mensaje.includes('éxito')) {
                            $("#modal1").modal("hide");
                            consultar();
                            carga_insumos(); // Recarga la lista de insumos
                            
                            // Verificar si venimos del modal de entrada de insumos
                            if ($("#btnInsertarInsumo").length > 0) {
                                // Volver a abrir el modal de entrada de insumos
                                setTimeout(function() {
                                    $("#modal2").modal("show");
                                    // Restaurar los insumos seleccionados
                                    restaurarInsumosSeleccionados();
                                }, 500);
                            }
                        }
                    });
                } 
                else if (lee.resultado == "eliminar") {
                    console.log("Resultado eliminar:", lee);
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
                    console.error("Error del servidor:", lee.mensaje);
                    Swal.fire({
                        title: "Error",
                        text: lee.mensaje,
                        icon: "error"
                    });
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
                else if(lee.resultado=='listadoInsumos'){
                    var tablaHTML = generarTablaListadoInsumos(lee.datos);
                    $('#listadoInsumos').html(tablaHTML);
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
                    carga_insumos(); // Recarga la lista de insumos
                    limpia(); // Limpia el formulario
                }
            } 
            catch (e) {
                console.error("Error en el procesamiento:", e, "Respuesta original:", respuesta);
                Swal.fire({
                    title: "Error",
                    text: "Error en JSON: " + e.name + ". Revise la consola para más detalles.",
                    icon: "error"
                });
            }
        },
        error: function (request, status, err) {
            console.error("Error en la petición AJAX:", status, err, request);
            if (status == "timeout") {
                Swal.fire({
                    title: "Error",
                    text: "Servidor ocupado, intente de nuevo",
                    icon: "error"
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "ERROR: " + status + " - " + err,
                    icon: "error"
                });
            }
        },
        complete: function () { 
            $("#loader").hide(); // Ocultar loader al completar
            console.log("Petición AJAX completada");
        }

    });
}

function generarTablaInsumos(datos) {
    var html = '';
    datos.forEach(function(insumo) {
        html += '<tr>';
        html += '<td>' + insumo.numero + '</td>';
        html += '<td>' + insumo.codigo + '</td>';
        html += '<td>' + insumo.nombre + '</td>';
        html += '<td>' + insumo.marca + '</td>';
        
        // Columna de stock con badges
        html += '<td>' + insumo.stock_total;
        if (insumo.stock_total == 0) {
            html += '<br><span class="badge bg-danger" title="Este producto esta agotado">No disponible</span>';
        } else if (insumo.stock_total <= insumo.stock_minimo) {
            html += '<br><span class="badge bg-warning" title="Este producto llegó al nivel mínimo de stock">Stock bajo</span>';
        }
        html += '</td>';
        
        html += '<td>' + insumo.stock_minimo + '</td>';
        html += '<td data-presentacion-id="' + insumo.presentacion_id + '">' + insumo.presentacion_nombre + '</td>';
        
        // Columna de precio (solo para administradores)
        if (typeof esAdmin !== 'undefined' && esAdmin) {
            html += '<td>$' + insumo.precio + '</td>';
        }
        
        // Columna de imagen
        html += '<td><a href="' + insumo.imagen + '" target="_blank"><img src="' + insumo.imagen + '" alt="Imagen del insumo" class="img"/></a></td>';
        
        // Columna de acciones
        html += '<td>';
        html += '<button type="button" class="btn-sm btn-primary w-50 small-width mb-1" onclick="pone(this,0)" title="Modificar insumo"><i class="bi bi-arrow-repeat"></i></button><br/>';
        html += '<button type="button" class="btn-sm btn-danger w-50 small-width mt-1" onclick="pone(this,1)" title="Eliminar insumo"><i class="bi bi-trash"></i></button><br/>';
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
        
        // Tabla de detalles
        html += '<td class="align-middle"><table class="table table-sm"><thead><tr>';
        html += '<th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th>';
        html += '</tr></thead><tbody>';
        
        // Detalles de la entrada
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
        
        // Total
        html += '<td class="align-middle">$' + parseFloat(entrada.total).toFixed(2) + '</td>';
        html += '</tr>';
    });
    return html;
}

function generarTablaListadoInsumos(datos) {
    var html = '';
    datos.forEach(function(insumo) {
        html += '<tr style="cursor:pointer" onclick="colocaInsumo(this);">';
        
        // ID (oculto)
        html += '<td style="display:none">' + insumo.id + '</td>';
        
        // Código
        html += '<td>' + insumo.codigo + '</td>';
        
        // Nombre
        html += '<td>' + insumo.nombre + '</td>';
        
        // Marca
        html += '<td>' + insumo.marca + '</td>';
        
        // Cantidad con badges
        html += '<td>' + insumo.cantidad;
        if (insumo.cantidad == 0) {
            html += '<span class="badge bg-danger" title="Este insumo esta agotado">No disponible</span>';
        } else if (insumo.cantidad <= insumo.cantidad_minima) {
            html += '<br><span class="badge bg-warning" title="Este insumo llegó al nivel mínimo de stock">Stock bajo</span>';
        }
        html += '</td>';
        
        // Cantidad mínima
        html += '<td>' + insumo.cantidad_minima + '</td>';
        
        // Precio
        html += '<td>' + insumo.precio + '</td>';
        
        // Presentación
        html += '<td>' + insumo.presentacion_nombre + '</td>';
        
        // Imagen
        html += '<td class="align-middle"><img src="' + insumo.imagen + '" alt="Imagen del insumo" class="img"/></td>';
        
        html += '</tr>';
    });
    return html;
}

// Agregar evento para el botón de insertar insumo
$(document).on('click', '#btnInsertarInsumo', function() {
    // Guardar los insumos seleccionados antes de cerrar el modal
    var insumosSeleccionados = [];
    $("#detalledeventa tr").each(function() {
        var insumo = {
            id: $(this).find("td:eq(0) input").val(),
            codigo: $(this).find("td:eq(1)").text(),
            nombre: $(this).find("td:eq(2)").text(),
            cantidad: $(this).find("td:eq(3) input").val(),
            precio: $(this).find("td:eq(4) input").val(),
            subtotal: $(this).find("td:eq(5)").text()
        };
        insumosSeleccionados.push(insumo);
    });
    
    // Guardar los insumos en una variable global
    window.insumosTemporales = insumosSeleccionados;
    
    // Cerrar el modal actual
    $("#modal2").modal("hide");
    // Limpiar el formulario de insumo
    limpia();
    // Cambiar el texto del botón proceso
    $("#proceso").text("INCLUIR");
    // Mostrar el modal de insumo
    $("#modal1").modal("show");
    // Establecer el código en el campo correspondiente
    $("#codigo").val($("#codigoInsumos").val());
    // Asegurarnos que el campo código esté habilitado para nuevo insumo
    $("#codigo").prop('disabled', false);
});

// Función para restaurar los insumos seleccionados
function restaurarInsumosSeleccionados() {
    if (window.insumosTemporales && window.insumosTemporales.length > 0) {
        $("#detalledeventa").empty(); // Limpiar la tabla actual
        window.insumosTemporales.forEach(function(insumo) {
            var l = `
            <tr> 
                <td style="display:none"> <input type="text" name="idp[]" style="display:none" value="${insumo.id}"/>${insumo.id}</td>
                <td>${insumo.codigo}</td>
                <td>${insumo.nombre}</td>
                <td> <input type="text" value="${insumo.cantidad}" class="btn" name="cant[]" onchange="validarCantidad(this)" onkeypress="validarkeypress(/^[0-9\b]*$/, event)" /></td>
                <td> <input type="text" name="pcp[]" class="btn" value="${insumo.precio}" onchange="validarPrecio(this)" onkeypress="validarkeypress(/^[0-9.\b]*$/, event)"/></td>
                <td>${insumo.subtotal}</td>
                <td> <button type="button" class="btn" id="bc" onclick="eliminalineadetalle(this)">X</button> </td>
            </tr>`;
            $("#detalledeventa").append(l);
        });
        // Limpiar la variable temporal
        window.insumosTemporales = null;
    }
}

$(window).resize(function() {
    $('#tablaEntradasInsumos').DataTable().columns.adjust().draw();
});

$(window).resize(function() {
    $('#tablaInsumos').DataTable().columns.adjust().draw();
});