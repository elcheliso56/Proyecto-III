<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container mt-4">
    <h2 class="mb-1">📅 Citas Dentales</h2> 
    <p class="text-muted">Solicitud y gestión de citas dentales.</p>

    <!-- Form for non-registered clients -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Solicitar Cita</h5>
        </div>
        <div class="card-body">
            <form id="formCita" class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="nombre_paciente" placeholder="Nombre completo">
                        <label for="nombre_paciente">Nombre completo</label>
                        <span id="snombre_paciente" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="tel" class="form-control" id="numero_contacto" placeholder="Teléfono">
                        <label for="numero_contacto">Teléfono</label>
                        <span id="snumero_contacto" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="id_medico">
                            <option value="" selected disabled>Seleccione un médico</option>
                            <!-- Aquí se cargarán los médicos dinámicamente -->
                        </select>
                        <label for="id_medico">Médico</label>
                        <span id="sid_medico" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="fecha_cita" placeholder="Fecha">
                        <label for="fecha_cita">Fecha</label>
                        <span id="sfecha_cita" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="time" class="form-control" id="hora_cita" placeholder="Hora">
                        <label for="hora_cita">Hora</label>
                        <span id="shora_cita" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="motivo_cita">
                            <option value="" selected disabled>Seleccione un motivo</option>
                            <option value="Limpieza">Limpieza dental</option>
                            <option value="Extracción">Extracción</option>
                            <option value="Ortodoncia">Ortodoncia</option>
                            <option value="Blanqueamiento">Blanqueamiento</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <label for="motivo_cita">Motivo de la cita</label>
                        <span id="smotivo_cita" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea class="form-control" id="observaciones" placeholder="Observaciones" style="height: 100px"></textarea>
                        <label for="observaciones">Observaciones</label>
                    </div>
                </div>
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-primary" id="solicitarCita">
                        <i class="bi bi-send"></i> Solicitar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table for managing appointments -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Gestión de Citas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center align-middle" id="tablacitas">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Teléfono</th>
                            <th>Médico</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan las citas dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Modificar cita -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-calendar-check"></i> Modificar Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id_cita" id="id_cita">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="mnombre_paciente" placeholder="Nombre completo">
                    <label for="mnombre_paciente">Nombre completo</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="tel" class="form-control" id="mnumero_contacto" placeholder="Teléfono">
                    <label for="mnumero_contacto">Teléfono</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="mid_medico">
                        <!-- Aquí se cargarán los médicos dinámicamente -->
                    </select>
                    <label for="mid_medico">Médico</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="mfecha_cita" placeholder="Fecha">
                    <label for="mfecha_cita">Fecha</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="time" class="form-control" id="mhora_cita" placeholder="Hora">
                    <label for="mhora_cita">Hora</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="mmotivo_cita">
                        <option value="Limpieza">Limpieza dental</option>
                        <option value="Extracción">Extracción</option>
                        <option value="Ortodoncia">Ortodoncia</option>
                        <option value="Blanqueamiento">Blanqueamiento</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <label for="mmotivo_cita">Motivo de la cita</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="mestado_cita">
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                    <label for="mestado_cita">Estado</label>
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" id="mobservaciones" placeholder="Observaciones" style="height: 100px"></textarea>
                    <label for="mobservaciones">Observaciones</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-square"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="proceso">
                    <i class="bi bi-check-circle"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="js/citas.js"></script>

<!-- Loader -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div> 