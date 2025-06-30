<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-currency-exchange me-2"></i>Monedas</h1>
        <div>
            <button type="button" class="btn btn-info" id="incluir" title="Registrar Moneda">
                <i class="bi bi-plus-circle me-1"></i> Nueva Moneda
            </button>
        </div>
    </div>

    <!-- Tabla de monedas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">Listado de Monedas</h6>
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
                <table class="table table-bordered table-hover" id="tablamonedas" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Símbolo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan las monedas dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar moneda -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-currency-exchange me-2"></i>Registrar Moneda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="codigo" placeholder="Código de la moneda (ej: USD)">
                    <label for="codigo">Código de la moneda</label>
                    <span id="scodigo" class="text-danger small"></span>
                    <small class="form-text text-muted">Código ISO de 3 letras (ej: USD, VES, EUR)</small>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre completo de la moneda">
                    <label for="nombre">Nombre de la moneda</label>
                    <span id="snombre" class="text-danger small"></span>
                    <small class="form-text text-muted">Nombre completo (ej: Dólar Estadounidense)</small>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="simbolo" placeholder="Símbolo de la moneda (ej: $)">
                    <label for="simbolo">Símbolo de la moneda</label>
                    <span id="ssimbolo" class="text-danger small"></span>
                    <small class="form-text text-muted">Símbolo (ej: $, Bs, €)</small>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="activa">
                        <option value="1" selected>Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                    <label for="activa">Estado</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="es_principal">
                        <option value="0" selected>Secundaria</option>
                        <option value="1">Principal</option>
                    </select>
                    <label for="es_principal">Tipo de moneda</label>
                    <small class="form-text text-muted">Solo puede haber una moneda principal (USD)</small>
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
<script type="text/javascript" src="js/monedas.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-info" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div> 