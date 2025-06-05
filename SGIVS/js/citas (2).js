$(document).ready(function() {
    let tablaCitasRegistradas = $('#tablaCitasRegistradas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        // Definición de las columnas
        "columns": [
            {"data": "cliente_completo"}, // Cliente (índice 0)
            {"data": "cedula_cliente"}, // Cédula Cliente (índice 1)
            {"data": "cedula_representante"}, // Cédula Representante (índice 2)
            {"data": "telefono_cliente"}, // Teléfono (índice 3)
            {"data": "motivo_cita"}, // Motivo (índice 4)
            {"data": "doctor_atendera"}, // Doctor (índice 5)
            {"data": "fecha_cita"}, // Fecha (índice 6)
            {"data": "hora_cita"}, // Hora (índice 7)
            {"data": "estado_cita_badge"}, // Estado (con badge) (índice 8)
            // ELIMINADO: {"data": "fecha_registro"}, // Fecha Registro
            {"data": "acciones", "orderable": false} // Acciones (no ordenable) (índice 9)
        ],
        // Ahora el orden debe basarse en una columna existente. Por ejemplo, por fecha de cita o estado.
        // Si quieres ordenar por la columna de fecha de registro internamente sin mostrarla:
        // Podrías dejar 'fecha_registro' en los datos de la fila (en tablaCitasRegistradas.row.add)
        // y ocultarla con columnDefs, pero como el usuario no la necesita ver, lo mejor es quitarla.
        // Si no se necesita ordenar por fecha de registro, se puede cambiar el orden predeterminado.
        // Por ejemplo, ordenar por fecha de cita (columna 6) descendente:
        "order": [[6, "desc"]] // Ordenar por la columna de fecha de cita (índice 6)
    });

    let tablaSolicitudesCitas = $('#tablaSolicitudesCitas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "columns": [
            {"data": "nombre"},
            {"data": "apellido"},
            {"data": "telefono"},
            {"data": "motivo"},
            {"data": "fecha_envio"},
            {"data": "acciones", "orderable": false}
        ],
        "order": [[4, "desc"]] // Ordenar por la columna de fecha de envío (índice 4) descendente
    });

    // Función para mostrar el loader
    function showLoader() {
        $('#loader').fadeIn();
    }

    // Función para ocultar el loader
    function hideLoader() {
        $('#loader').fadeOut();
    }

    // Cargar citas registradas al inicio
    cargarCitasRegistradas();

    function cargarCitasRegistradas(criterio = '') {
        showLoader();
        $.ajax({
            url: '?pagina=citas', // Apunta al controlador de citas
            type: 'POST',
            dataType: 'json',
            data: { accion: 'consultarCitasRegistradas', criterio_busqueda: criterio },
            success: function(respuesta) {
                hideLoader();
                if (respuesta.resultado === 'error') {
                    Swal.fire('Error', respuesta.mensaje, 'error');
                } else {
                    tablaCitasRegistradas.clear(); // Limpiar la tabla antes de añadir nuevos datos
                    respuesta.forEach(function(cita) {
                        let estadoBadge = `<span class="badge badge-${obtenerClaseEstado(cita.estado_cita)}">${cita.estado_cita}</span>`;
                        let botonesAccion = `
                            <button class="btn btn-primary btn-sm m-1 btnModificarCita" data-id="${cita.id}"
                                data-cedula-cliente="${cita.cedula_cliente || ''}"
                                data-cedula-representante="${cita.cedula_representante || ''}"
                                data-nombre-cliente="${cita.nombre_cliente}"
                                data-apellido-cliente="${cita.apellido_cliente}"
                                data-telefono-cliente="${cita.telefono_cliente || ''}"
                                data-motivo-cita="${cita.motivo_cita}"
                                data-doctor-atendera="${cita.doctor_atendera}"
                                data-fecha-cita="${cita.fecha_cita}"
                                data-hora-cita="${cita.hora_cita}"
                                title="Modificar Cita">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-warning btn-sm m-1 btnAbrirCambiarEstado" data-id="${cita.id}" data-estado-actual="${cita.estado_cita}" title="Cambiar Estado">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        `;

                        // Añadir los datos como un objeto para DataTables con las columnas definidas
                        // NO se incluye 'fecha_registro' aquí si no la vamos a mostrar en la tabla.
                        tablaCitasRegistradas.row.add({
                            cliente_completo: cita.nombre_cliente + ' ' + cita.apellido_cliente,
                            cedula_cliente: cita.cedula_cliente || 'N/A',
                            cedula_representante: cita.cedula_representante || 'N/A',
                            telefono_cliente: cita.telefono_cliente || 'N/A',
                            motivo_cita: cita.motivo_cita,
                            doctor_atendera: cita.doctor_atendera,
                            fecha_cita: cita.fecha_cita,
                            hora_cita: cita.hora_cita,
                            estado_cita_badge: estadoBadge,
                            acciones: botonesAccion
                        }).draw(false);
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                hideLoader();
                Swal.fire('Error', 'Error al cargar las citas: ' + textStatus + ' ' + errorThrown, 'error');
            }
        });
    }

    function obtenerClaseEstado(estado) {
        switch (estado) {
            case 'Pendiente': return 'warning';
            case 'Confirmada': return 'success';
            case 'Cancelada': return 'danger';
            default: return 'secondary';
        }
    }

    // Botón para registrar nueva cita
    $('#btnRegistrarCita').click(function() {
        $('#formCita')[0].reset(); // Limpiar formulario
        $('#idCita').val(''); // Asegurar que el ID esté vacío para incluir
        $('#citaContactoId').val(''); // Asegurar que el ID de contacto esté vacío
        $('#accionCita').val('incluir'); // Establecer la acción a 'incluir'
        $('#modalGestionCitaLabel').text('Registrar Nueva Cita');
        $('#modalGestionCita').modal('show');
    });

    // Delegación de eventos para el botón de modificar
    $('#tablaCitasRegistradas tbody').on('click', '.btnModificarCita', function() {
        // Obtenemos los datos directamente del botón (ya están serializados)
        const id = $(this).data('id');
        const cedulaCliente = $(this).data('cedula-cliente');
        const cedulaRepresentante = $(this).data('cedula-representante');
        const nombreCliente = $(this).data('nombre-cliente');
        const apellidoCliente = $(this).data('apellido-cliente');
        let telefonoCliente = $(this).data('telefono-cliente');
        const motivoCita = $(this).data('motivo-cita');
        const doctorAtendera = $(this).data('doctor-atendera');
        const fechaCita = $(this).data('fecha-cita');
        const horaCita = $(this).data('hora-cita');

        // Asegurar que el teléfono tenga el formato correcto
        if (telefonoCliente && telefonoCliente !== 'N/A') {
            // Si el teléfono no comienza con +58, agregarlo
            if (!telefonoCliente.startsWith('+58')) {
                // Si ya tiene 10 dígitos, agregar +58 al inicio
                if (telefonoCliente.length === 10) {
                    telefonoCliente = '+58' + telefonoCliente;
                }
                // Si tiene más de 10 dígitos, asumimos que ya incluye el código de país
                else if (telefonoCliente.length > 10) {
                    telefonoCliente = '+' + telefonoCliente;
                }
            }
        }

        // Llenamos el formulario del modal
        $('#idCita').val(id);
        $('#cedulaCliente').val(cedulaCliente);
        $('#cedulaRepresentante').val(cedulaRepresentante);
        $('#nombreCliente').val(nombreCliente);
        $('#apellidoCliente').val(apellidoCliente);
        $('#telefonoCliente').val(telefonoCliente);
        $('#motivoCita').val(motivoCita);
        $('#doctorAtendera').val(doctorAtendera);
        $('#fechaCita').val(fechaCita);
        $('#horaCita').val(horaCita);
        $('#accionCita').val('modificar'); // Establecer la acción a 'modificar'
        $('#modalGestionCitaLabel').text('Modificar Cita');
        $('#modalGestionCita').modal('show');
    });

    // Delegación de eventos para ABRIR el diálogo de cambio de estado (ahora con SweetAlert2)
    $('#tablaCitasRegistradas tbody').on('click', '.btnAbrirCambiarEstado', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const estadoActual = $(this).data('estado-actual');

        Swal.fire({
            title: 'Cambiar Estado de la Cita',
            input: 'select',
            inputOptions: {
                'Pendiente': 'Pendiente',
                'Confirmada': 'Confirmada',
                'Cancelada': 'Cancelada'
            },
            inputValue: estadoActual, // Seleccionar el estado actual por defecto
            inputPlaceholder: 'Selecciona un estado',
            showCancelButton: true,
            confirmButtonText: 'Cambiar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes seleccionar un estado';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const nuevoEstado = result.value;
                if (nuevoEstado === estadoActual) {
                    Swal.fire('Información', 'El estado seleccionado es el mismo que el actual.', 'info');
                    return;
                }
                showLoader();
                $.ajax({
                    url: '?pagina=citas',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        accion: 'cambiarEstado',
                        id: id,
                        estado_cita: nuevoEstado
                    },
                    success: function(respuesta) {
                        hideLoader();
                        if (respuesta.resultado === 'ok') {
                            Swal.fire('¡Éxito!', respuesta.mensaje, 'success');
                            cargarCitasRegistradas(); // Recargar la tabla
                        } else {
                            Swal.fire('Error', respuesta.mensaje, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        hideLoader();
                        Swal.fire('Error', 'Error al cambiar estado: ' + textStatus + ' ' + errorThrown, 'error');
                    }
                });
            }
        });
    });

    // Guardar Cita (Incluir o Modificar)
    $('#btnGuardarCita').click(function() {
        const formData = $('#formCita').serialize(); // Obtener todos los datos del formulario
        // Validaciones básicas de campos obligatorios del lado del cliente
        if (!$('#nombreCliente').val() || !$('#apellidoCliente').val() || !$('#motivoCita').val() ||
            !$('#doctorAtendera').val() || !$('#fechaCita').val() || !$('#horaCita').val()) {
            Swal.fire('Advertencia', 'Por favor, complete todos los campos obligatorios (*).', 'warning');
            return;
        }

        showLoader();
        $.ajax({
            url: '?pagina=citas',
            type: 'POST',
            dataType: 'json',
            data: formData, // Enviar todos los datos del formulario
            success: function(respuesta) {
                hideLoader();
                if (respuesta.resultado === 'ok') {
                    Swal.fire('¡Éxito!', respuesta.mensaje, 'success');
                    $('#modalGestionCita').modal('hide'); // Cerrar el modal
                    cargarCitasRegistradas(); // Recargar la tabla de citas
                    // Si se registró desde una solicitud de contacto, recargar también las solicitudes
                    if ($('#citaContactoId').val() !== '') {
                        cargarSolicitudesCitasContacto();
                    }
                } else {
                    Swal.fire('Error', respuesta.mensaje, 'error');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                hideLoader();
                Swal.fire('Error', 'Error al guardar la cita: ' + textStatus + ' ' + errorThrown, 'error');
            }
        });
    });

    // Validaciones de KeyPress
    $('#nombreCliente, #apellidoCliente').on('keypress', function(e) {
        // Solo permite letras y espacios
        const charCode = e.which ? e.which : e.keyCode;
        // Incluye letras mayúsculas (A-Z), minúsculas (a-z), espacios (32), Ñ (209) y ñ (241)
        if (!((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode === 32 || charCode === 209 || charCode === 241)) {
            e.preventDefault();
        }
    });

    // Validación especial para el teléfono
    $('#telefonoCliente').on('keypress', function(e) {
        const charCode = e.which ? e.which : e.keyCode;
        const value = $(this).val();
        
        // Permitir el + solo al inicio
        if (charCode === 43) { // Código ASCII para '+'
            if (value.length > 0) {
                e.preventDefault();
            }
            return;
        }
        
        // Solo permitir números después del +
        if (charCode < 48 || charCode > 57) { // Valores ASCII para 0-9
            e.preventDefault();
        }
        
        // Limitar la longitud total a 13 caracteres (+58 + 10 dígitos)
        if (value.length >= 13) {
            e.preventDefault();
        }
    });

    // Asegurar que el teléfono comience con +58
    $('#telefonoCliente').on('blur', function() {
        const value = $(this).val();
        if (value && value !== 'N/A') {
            // Si el valor no comienza con +58
            if (!value.startsWith('+58')) {
                // Si ya tiene 10 dígitos, agregar +58 al inicio
                if (value.replace(/[^0-9]/g, '').length === 10) {
                    $(this).val('+58' + value.replace(/[^0-9]/g, ''));
                }
                // Si tiene más de 10 dígitos, asumimos que ya incluye el código de país
                else if (value.replace(/[^0-9]/g, '').length > 10) {
                    $(this).val('+' + value.replace(/[^0-9]/g, ''));
                }
            }
        }
    });

    $('#cedulaCliente, #cedulaRepresentante').on('keypress', function(e) {
        // Solo permite números
        const charCode = e.which ? e.which : e.keyCode;
        if (charCode < 48 || charCode > 57) { // Valores ASCII para 0-9
            e.preventDefault();
        }
    });

    // --- Funciones para Solicitudes de Citas de Contacto ---

    // Botón para ver solicitudes de citas de contacto
    $('#btnVerSolicitudes').click(function() {
        cargarSolicitudesCitasContacto();
        $('#modalSolicitudesCitas').modal('show');
    });

    function cargarSolicitudesCitasContacto() {
        showLoader();
        $.ajax({
            url: '?pagina=citas',
            type: 'POST',
            dataType: 'json',
            data: { accion: 'consultarSolicitudesCitasContacto' },
            success: function(respuesta) {
                hideLoader();
                if (respuesta.resultado === 'error') {
                    Swal.fire('Error', respuesta.mensaje, 'error');
                } else {
                    tablaSolicitudesCitas.clear();
                    respuesta.forEach(function(solicitud) {
                        let botonesAccion = `
                            <button class="btn btn-success btn-sm m-1 btnRegistrarDesdeSolicitud"
                                data-id="${solicitud.id}"
                                data-nombre="${solicitud.nombre}"
                                data-apellido="${solicitud.apellido}"
                                data-telefono="${solicitud.telefono || ''}"
                                data-motivo="${solicitud.motivo}"
                                title="Registrar Cita">
                                <i class="bi bi-journal-plus"></i>
                            </button>
                            <button class="btn btn-danger btn-sm m-1 btnBorrarSolicitud" data-id="${solicitud.id}" title="Eliminar Solicitud">
                                <i class="bi bi-trash"></i>
                            </button>
                        `;
                        
                        tablaSolicitudesCitas.row.add({
                            nombre: solicitud.nombre,
                            apellido: solicitud.apellido,
                            telefono: solicitud.telefono || 'N/A',
                            motivo: solicitud.motivo,
                            fecha_envio: solicitud.fecha_envio,
                            acciones: botonesAccion
                        }).draw(false);
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                hideLoader();
                Swal.fire('Error', 'Error al cargar las solicitudes de citas: ' + textStatus + ' ' + errorThrown, 'error');
            }
        });
    }

    // Delegación de eventos para registrar cita desde solicitud
    $('#tablaSolicitudesCitas tbody').on('click', '.btnRegistrarDesdeSolicitud', function() {
        const idSolicitud = $(this).data('id');
        const nombre = $(this).data('nombre');
        const apellido = $(this).data('apellido');
        const telefono = $(this).data('telefono');
        const motivo = $(this).data('motivo');

        $('#formCita')[0].reset(); // Limpiar el formulario de citas
        $('#citaContactoId').val(idSolicitud); // Establecer el ID de la solicitud
        $('#nombreCliente').val(nombre);
        $('#apellidoCliente').val(apellido);
        $('#telefonoCliente').val(telefono);
        $('#motivoCita').val(motivo);
        $('#accionCita').val('incluir'); // La acción siempre será incluir aquí
        $('#modalGestionCitaLabel').text('Registrar Cita desde Solicitud');
        $('#modalSolicitudesCitas').modal('hide'); // Ocultar el modal de solicitudes
        $('#modalGestionCita').modal('show'); // Mostrar el modal de gestión de cita
    });

    // Delegación de eventos para borrar lógicamente solicitud
    $('#tablaSolicitudesCitas tbody').on('click', '.btnBorrarSolicitud', function() {
        const idSolicitud = $(this).data('id');

        Swal.fire({
            title: '¿Estás seguro de eliminar esta solicitud?',
            text: "Esta acción borrará lógicamente la solicitud de cita de contacto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader();
                $.ajax({
                    url: '?pagina=citas',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        accion: 'eliminarSolicitudCitaContacto',
                        id: idSolicitud
                    },
                    success: function(respuesta) {
                        hideLoader();
                        if (respuesta.resultado === 'ok') {
                            Swal.fire('¡Éxito!', respuesta.mensaje, 'success');
                            cargarSolicitudesCitasContacto(); // Recargar la tabla de solicitudes
                        } else {
                            Swal.fire('Error', respuesta.mensaje, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        hideLoader();
                        Swal.fire('Error', 'Error al eliminar la solicitud: ' + textStatus + ' ' + errorThrown, 'error');
                    }
                });
            }
        });
    });
});