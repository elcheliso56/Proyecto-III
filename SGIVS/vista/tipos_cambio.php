<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-graph-up-arrow me-2"></i>Tipos de Cambio</h1>
        <div>
            <button type="button" class="btn btn-info" id="incluir" title="Registrar Tipo de Cambio">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Cambio
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Filtros</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="filtro_fecha" value="<?php echo date('Y-m-d'); ?>">
                        <label for="filtro_fecha">Fecha</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <select class="form-select select2" id="filtro_moneda_origen">
                            <option value="">Todas las monedas origen</option>
                        </select>
                        <label for="filtro_moneda_origen">Moneda Origen</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating mb-3">
                        <select class="form-select select2" id="filtro_moneda_destino">
                            <option value="">Todas las monedas destino</option>
                        </select>
                        <label for="filtro_moneda_destino">Moneda Destino</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-end h-100">
                        <button type="button" class="btn btn-primary me-2" id="aplicar_filtros">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-secondary" id="limpiar_filtros">
                            <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de tipos de cambio -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">Listado de Tipos de Cambio</h6>
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
                <table class="table table-bordered table-hover" id="tablatiposcambio" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-center">Moneda Origen</th>
                            <th class="text-center">Moneda Destino</th>
                            <th class="text-center">Tipo de Cambio</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan los tipos de cambio dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar tipo de cambio -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-graph-up-arrow me-2"></i>Registrar Tipo de Cambio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="moneda_origen_display" readonly>
                    <label for="moneda_origen_display">Moneda Origen (Principal)</label>
                    <input type="hidden" id="moneda_origen" name="moneda_origen">
                    <small class="form-text text-muted">Moneda principal del sistema (USD)</small>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="moneda_destino">
                        <option value="" selected disabled>Seleccione moneda destino</option>
                    </select>
                    <label for="moneda_destino">Moneda Destino</label>
                    <span id="smoneda_destino" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="tipo_cambio" placeholder="0.0000" step="0.0001" min="0.0001">
                    <label for="tipo_cambio">Tipo de Cambio</label>
                    <span id="stipo_cambio" class="text-danger small"></span>
                    <small class="form-text text-muted">Valor del tipo de cambio (ej: 35.5000 para USD a VES)</small>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fecha" value="<?php echo date('Y-m-d'); ?>" readonly>
                    <label for="fecha">Fecha del Tipo de Cambio</label>
                    <span id="sfecha" class="text-danger small"></span>
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
<script type="text/javascript" src="js/tipos_cambio.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-info" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div> 