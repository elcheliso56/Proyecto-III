<?php
require_once("comunes/encabezado.php");
require_once("comunes/menu.php");
require_once("config/google_calendar.php");
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Calendario de Eventos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" id="incluir">
                            <i class="bi bi-plus-circle"></i> Nuevo Evento
                        </button>
                        <?php if (isGoogleAuthenticated()): ?>
                            <button type="button" class="btn btn-success" onclick="sincronizarGoogleCalendar()">
                                <i class="bi bi-google"></i> Sincronizar con Google Calendar
                            </button>
                            <span class="badge bg-success ms-2">
                                <i class="bi bi-check-circle"></i> Conectado a Google Calendar
                            </span>
                        <?php else: ?>
                            <a href="<?php echo getAuthUrl(); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-google"></i> Conectar con Google Calendar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendario"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar evento -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEvento">
                    <input type="hidden" id="accion" name="accion">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="google_event_id" name="google_event_id">
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título del evento">
                        <label for="titulo">Título</label>
                        <span id="stitulo" class="text-danger"></span>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" style="height: 100px"></textarea>
                        <label for="descripcion">Descripción</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <span id="sfecha_inicio" class="text-danger"></span>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <span id="sfecha_fin" class="text-danger"></span>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="color" class="form-control" id="color" name="color" value="#3788d8">
                        <label for="color">Color</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="proceso">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Loader -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div>

<!-- FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script del calendario -->
<script src="js/calendario.js"></script>

<style>
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loader {
    border: 5px solid #f3f3f3;
    border-radius: 50%;
    border-top: 5px solid #3498db;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-container p {
    color: white;
    margin-top: 10px;
}
</style> 