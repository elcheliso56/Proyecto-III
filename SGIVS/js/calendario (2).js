// js/calendario.js

$(document).ready(function() {
    var calendarEl = document.getElementById('calendar');
    var calendar;

    // Función para mostrar el loader
    function showLoader(message = 'Cargando calendario...') {
        $('#loader').find('p').text(message);
        $('#loader').fadeIn();
    }

    // Función para ocultar el loader
    function hideLoader() {
        $('#loader').fadeOut();
    }

    // Inicializar el calendario
    function initCalendar() {
        showLoader(); // Mostrar loader al inicio de la inicialización

        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es', // Establecer el idioma a español
            initialView: 'dayGridMonth', // Vista inicial por defecto
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay' // Vistas disponibles
            },
            editable: false, // Las citas no se pueden arrastrar/redimensionar
            selectable: false, // No se pueden seleccionar rangos de fechas
            eventLimit: true, // Muestra "+más" si hay muchos eventos en un día
            
            // Configuración para cargar eventos desde tu controlador PHP
            // Esta es la parte CLAVE que usa "?pagina=calendario" y "accion=obtenerCitasConfirmadas"
            events: {
                url: '?pagina=calendario', // La URL a tu controlador (tu index.php lo redirigirá al controlador de calendario)
                method: 'POST', // Usamos POST para la acción
                extraParams: {
                    accion: 'obtenerCitasConfirmadas' // Este parámetro le dice al controlador qué hacer
                },
                failure: function() {
                    hideLoader();
                    Swal.fire('Error', 'No se pudieron cargar las citas del calendario. Intente de nuevo más tarde.', 'error');
                }
            },
            
            // Función de callback para controlar el loader mientras se cargan los eventos
            loading: function(isLoading) {
                if (isLoading) {
                    showLoader('Actualizando citas...');
                } else {
                    hideLoader();
                }
            },
            
            // Personalizar cómo se montan los eventos (ej. para tooltips)
            eventDidMount: function(info) {
                // Usar jQuery.tooltip para mostrar detalles al pasar el ratón
                // Asegúrate de que Bootstrap o alguna librería que provea $.fn.tooltip esté cargada
                $(info.el).tooltip({
                    title: `
                        <strong>Cliente:</strong> ${info.event.extendedProps.cliente_completo || 'N/A'}<br>
                        <strong>Doctor:</strong> ${info.event.extendedProps.doctor_atendera || 'N/A'}<br>
                        <strong>Motivo:</strong> ${info.event.extendedProps.motivo_cita || 'N/A'}<br>
                        <strong>Fecha:</strong> ${moment(info.event.start).format('DD/MM/YYYY')}<br>
                        <strong>Hora:</strong> ${moment(info.event.start).format('hh:mm A')}
                    `,
                    placement: 'top',
                    html: true,
                    trigger: 'hover',
                    container: 'body' // Para evitar problemas de z-index con modales
                });
            },
            
            // Acción al hacer clic en un evento (mostrar modal de detalles)
            eventClick: function(info) {
                Swal.fire({
                    title: 'Detalles de la Cita',
                    html: `
                        <p><strong>Cliente:</strong> ${info.event.extendedProps.cliente_completo || 'N/A'}</p>
                        <p><strong>Cédula Cliente:</strong> ${info.event.extendedProps.cedula_cliente || 'N/A'}</p>
                        <p><strong>Cédula Representante:</strong> ${info.event.extendedProps.cedula_representante || 'N/A'}</p>
                        <p><strong>Teléfono:</strong> ${info.event.extendedProps.telefono_cliente || 'N/A'}</p>
                        <p><strong>Doctor:</strong> ${info.event.extendedProps.doctor_atendera || 'N/A'}</p>
                        <p><strong>Motivo:</strong> ${info.event.extendedProps.motivo_cita || 'N/A'}</p>
                        <p><strong>Fecha:</strong> ${moment(info.event.start).format('DD/MM/YYYY')}</p>
                        <p><strong>Hora:</strong> ${moment(info.event.start).format('hh:mm A')}</p>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Cerrar'
                });
            }
        });
        calendar.render(); // Renderizar el calendario en la página
    }

    // Asegurarse de que el DOM esté completamente cargado antes de inicializar el calendario
    initCalendar();
});