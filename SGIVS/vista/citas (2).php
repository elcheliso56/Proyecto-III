<?php
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?>
<div class="container">
    <h1>Gestionar Citas</h1>
    <div class="container">
        <div class="row mt-1 justify-content-center">
            <div class="col-md-4 text-center">
                <button type="button" class="btn-sm btn-success w-75 small-width" id="btnRegistrarCita" title="Registrar Nueva Cita">
                    <i class="bi bi-plus-square"></i> Nueva Cita
                </button>
            </div>
            <div class="col-md-4 text-center">
                <button type="button" class="btn-sm btn-info w-75 small-width" id="btnVerSolicitudes" title="Ver Solicitudes de Contacto">
                    <i class="bi bi-person-lines-fill"></i> Solicitudes
                </button>
            </div>
            </div>
    </div>

    <div class="container mt-4">
        <div class="table-responsive" id="tt">
            <table class="table table-striped table-hover table-center" id="tablaCitasRegistradas">
                <thead class="tableh">
                    <tr>
                        <th class="text-center">Cliente</th>
                        <th class="text-center">Cédula Cliente</th>
                        <th class="text-center">Cédula Rep.</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Motivo</th>
                        <th class="text-center">Doctor</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Hora</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="resultadoCitasRegistradas">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGestionCita" tabindex="-1" role="dialog" aria-labelledby="modalGestionCitaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGestionCitaLabel">Gestionar Cita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCita">
                    <input type="hidden" id="idCita" name="id">
                    <input type="hidden" id="accionCita" name="accion">
                    <input type="hidden" id="citaContactoId" name="cita_contacto_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombreCliente">Nombre del Cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombreCliente" name="nombre_cliente" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellidoCliente">Apellido del Cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidoCliente" name="apellido_cliente" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cedulaCliente">Cédula Cliente</label>
                                <input type="text" class="form-control" id="cedulaCliente" name="cedula_cliente">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cedulaRepresentante">Cédula Representante</label>
                                <input type="text" class="form-control" id="cedulaRepresentante" name="cedula_representante">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefonoCliente">Teléfono Cliente</label>
                                <input type="text" class="form-control" id="telefonoCliente" name="telefono_cliente" 
                                    placeholder="+584121234567" 
                                    pattern="\+58\d{10}"
                                    title="Ingrese el número con el formato internacional: +58 seguido de 10 dígitos">
                                <small class="form-text text-muted">Formato requerido: +58 seguido de 10 dígitos (ejemplo: +584121234567)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="doctorAtendera">Doctor que Atenderá <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="doctorAtendera" name="doctor_atendera" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="motivoCita">Motivo de la Cita <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivoCita" name="motivo_cita" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechaCita">Fecha de la Cita <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fechaCita" name="fecha_cita" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horaCita">Hora de la Cita <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="horaCita" name="hora_cita" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarCita"><i class="bi bi-save"></i> Guardar Cita</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSolicitudesCitas" tabindex="-1" role="dialog" aria-labelledby="modalSolicitudesCitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSolicitudesCitasLabel">Solicitudes de Citas de Contacto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaSolicitudesCitas">
                        <thead>
                            <tr>                                
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Apellido</th>
                                <th class="text-center">Teléfono</th>
                                <th class="text-center">Motivo</th>
                                <th class="text-center">F. Envío</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="resultadoSolicitudesCitas">
                            </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="loader" class="loader-container">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div>

<script type="text/javascript" src="js/citas.js"></script>
<link href="css/citas.css" rel="stylesheet" />
</body>
</html>