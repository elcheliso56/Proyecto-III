<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-cash-coin me-2"></i>Ingresos</h1>
        <div>
            <button type="button" class="btn btn-info" id="incluir" title="Registrar Ingreso">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Ingreso
            </button>
        </div>
    </div>

    <!-- Tabla de ingresos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">Listado de Ingresos</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <a class="dropdown-item" href="#" id="exportarExcel"><i class="bi bi-file-excel me-2"></i>Exportar a Excel</a>
                    <a class="dropdown-item" href="#" id="imprimirListado"><i class="bi bi-printer me-2"></i>Imprimir Listado</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                
                <table class="table table-striped table-hover" id="tablaingresos" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Monto</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Origen</th>
                            <th class="text-center">Cuenta</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan los ingresos dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar ingreso -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-cash-coin me-2"></i>Registrar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="descripcion" placeholder="Descripción del ingreso">
                    <label for="descripcion">Descripción del ingreso</label>
                    <span id="sdescripcion" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="monto" placeholder="Monto en bolívares">
                    <label for="monto">Monto (Bs)</label>
                    <span id="smonto" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fecha" placeholder="Fecha del ingreso">
                    <label for="fecha">Fecha</label>
                    <span id="sfecha" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="origen">
                        <option value="" selected disabled>Seleccione un origen</option>
                        <option value="manual">Manual</option>
                        <option value="consulta">Consulta</option>
                        <option value="servicio">Servicio</option>
                    </select>
                    <label for="origen">Origen del ingreso</label>
                    <span id="sorigen" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="cuenta_id">
                        <option value="" selected disabled>Seleccione una cuenta</option>
                    </select>
                    <label for="cuenta_id">Cuenta</label>
                    <span id="scuenta_id" class="text-danger small"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="bc">
                    <i class="bi bi-x-square me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-info" id="proceso">
                    <i class="bi bi-check-circle me-1"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="js/ingresos.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader  -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-info" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div>
