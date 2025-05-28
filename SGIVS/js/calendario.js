let calendar;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el calendario
    const calendarEl = document.getElementById('calendario');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'es',
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        events: function(info, successCallback, failureCallback) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { accion: 'consultar' },
                success: function(respuesta) {
                    try {
                        var datos = JSON.parse(respuesta);
                        if (datos.resultado === 'consultar') {
                            var eventos = [];
                            $(datos.mensaje).each(function() {
                                eventos.push({
                                    id: $(this).attr('data-id'),
                                    title: $(this).find('td:eq(1)').text(),
                                    description: $(this).find('td:eq(2)').text(),
                                    start: $(this).find('td:eq(3)').text(),
                                    end: $(this).find('td:eq(4)').text(),
                                    backgroundColor: $(this).find('td:eq(5)').css('background-color'),
                                    borderColor: $(this).find('td:eq(5)').css('background-color')
                                });
                            });
                            successCallback(eventos);
                        }
                    } catch (e) {
                        console.error('Error al procesar eventos:', e);
                        failureCallback(e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar eventos:', error);
                    failureCallback(error);
                }
            });
        },
        select: function(info) {
            mostrarModalCrear(info.start, info.end);
        },
        eventClick: function(info) {
            mostrarModalEditar(info.event);
        },
        eventDrop: function(info) {
            actualizarEvento(info.event);
        },
        eventResize: function(info) {
            actualizarEvento(info.event);
        }
    });
    calendar.render();

    // Cargar eventos iniciales
    consultar();
});

function mostrarModalCrear(start, end) {
    $('#modalTitle').text('Registrar Evento');
    $('#accion').val('incluir');
    $('#id').val('');
    $('#google_event_id').val('');
    $('#titulo').val('');
    $('#descripcion').val('');
    $('#fecha_inicio').val(start.toISOString().slice(0, 16));
    $('#fecha_fin').val(end.toISOString().slice(0, 16));
    $('#color').val('#3788d8');
    $('#modal1').modal('show');
}

function mostrarModalEditar(evento) {
    $('#modalTitle').text('Modificar Evento');
    $('#accion').val('modificar');
    $('#id').val(evento.id);
    $('#google_event_id').val(evento.extendedProps.google_event_id || '');
    $('#titulo').val(evento.title);
    $('#descripcion').val(evento.extendedProps.description);
    $('#fecha_inicio').val(evento.start.toISOString().slice(0, 16));
    $('#fecha_fin').val(evento.end.toISOString().slice(0, 16));
    $('#color').val(evento.backgroundColor);
    $('#modal1').modal('show');
}

function actualizarEvento(evento) {
    var datos = new FormData();
    datos.append('accion', 'modificar');
    datos.append('id', evento.id);
    datos.append('titulo', evento.title);
    datos.append('descripcion', evento.extendedProps.description);
    datos.append('fecha_inicio', evento.start.toISOString());
    datos.append('fecha_fin', evento.end.toISOString());
    datos.append('color', evento.backgroundColor);
    datos.append('google_event_id', evento.extendedProps.google_event_id || '');
    
    enviaAjax(datos);
}

$("#proceso").on("click", function() {
    if (validarenvio()) {
        var datos = new FormData();
        datos.append('accion', $("#accion").val());
        datos.append('id', $("#id").val());
        datos.append('titulo', $("#titulo").val());
        datos.append('descripcion', $("#descripcion").val());
        datos.append('fecha_inicio', $("#fecha_inicio").val());
        datos.append('fecha_fin', $("#fecha_fin").val());
        datos.append('color', $("#color").val());
        datos.append('google_event_id', $("#google_event_id").val());
        
        enviaAjax(datos);
    }
});

function validarenvio() {
    let valido = true;
    
    if ($("#titulo").val().trim() === '') {
        $("#stitulo").text("El título es obligatorio");
        valido = false;
    } else {
        $("#stitulo").text("");
    }
    
    if ($("#fecha_inicio").val() === '') {
        $("#sfecha_inicio").text("La fecha de inicio es obligatoria");
        valido = false;
    } else {
        $("#sfecha_inicio").text("");
    }
    
    if ($("#fecha_fin").val() === '') {
        $("#sfecha_fin").text("La fecha de fin es obligatoria");
        valido = false;
    } else {
        $("#sfecha_fin").text("");
    }
    
    if (new Date($("#fecha_fin").val()) <= new Date($("#fecha_inicio").val())) {
        $("#sfecha_fin").text("La fecha de fin debe ser posterior a la fecha de inicio");
        valido = false;
    }
    
    return valido;
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
        beforeSend: function() {
            $("#loader").show();
        },
        timeout: 10000,
        success: function(respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado === 'consultar' || lee.resultado === 'incluir' || lee.resultado === 'modificar' || lee.resultado === 'eliminar') {
                    Swal.fire({
                        title: lee.mensaje.includes('éxito') ? "¡Éxito!" : "Error",
                        text: lee.mensaje,
                        icon: lee.mensaje.includes('éxito') ? "success" : "error"
                    });
                    
                    if (lee.mensaje.includes('éxito')) {
                        $("#modal1").modal("hide");
                        calendar.refetchEvents();
                    }
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
            Swal.fire({
                title: "Error",
                text: status === "timeout" ? "Servidor ocupado, intente de nuevo" : "ERROR: " + request + status + err,
                icon: "error"
            });
        },
        complete: function() {
            $("#loader").hide();
        }
    });
}

function consultar() {
    calendar.refetchEvents();
}

$("#incluir").on("click", function() {
    mostrarModalCrear(new Date(), new Date(new Date().getTime() + 60*60*1000));
});

function sincronizarGoogleCalendar() {
    mostrarLoader();
    $.ajax({
        url: 'controlador/calendario.php',
        type: 'POST',
        data: {
            accion: 'sincronizar'
        },
        success: function(response) {
            ocultarLoader();
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sincronización exitosa',
                        text: 'Los eventos se han sincronizado correctamente con Google Calendar'
                    }).then(() => {
                        consultar();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Error al sincronizar con Google Calendar'
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la respuesta del servidor'
                });
            }
        },
        error: function() {
            ocultarLoader();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al comunicarse con el servidor'
            });
        }
    });
} 