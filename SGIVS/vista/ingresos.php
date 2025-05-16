<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container mt-4">
    <h2 class="mb-1">📊 Ingresos</h2> 
    <p class="text-muted">Listado de ingresos registrados en el sistema.</p>

    <div class="text-end mb-3">
        <button type="button" class="btn btn-success" id="incluir" title="Registrar Ingreso">
            <i class="bi bi-plus-circle"></i> Nuevo Ingreso
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover text-center align-middle" id="tablaingresos">
            <thead class="tableh">
                <tr>
                    <th>#</th>
                    <th>Descripción</th>
                    <th>Monto (Bs)</th>
                    <th>Fecha</th>
                    <th>Origen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="resultadoconsulta">
                <!-- Aquí se cargan los ingresos dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Registrar ingreso -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cash-coin"></i> Registrar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="descripcion" placeholder="Descripción del ingreso">
                    <label for="descripcion">Descripción del ingreso</label>
                    <span id="sdescripcion"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="monto" placeholder="Monto en bolívares">
                    <label for="monto">Monto (Bs)</label>
                    <span id="smonto"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fecha" placeholder="Fecha del ingreso">
                    <label for="fecha">Fecha</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="origen">
                        <option value="" selected disabled>Seleccione un origen</option>
                        <option value="manual">Manual</option>
                        <option value="consulta">Consulta</option>
                        <option value="servicio">Servicio</option>
                    </select>
                    <label for="origen">Origen del ingreso</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="bc">
                    <i class="bi bi-x-square"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="proceso">
                    <i class="bi bi-check-circle"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="js/ingresos.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div>
