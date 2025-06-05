<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-wallet2 me-2"></i>Cuentas</h1>
        <div>
            <button type="button" class="btn btn-info" id="incluir" title="Registrar Cuenta">
                <i class="bi bi-plus-circle me-1"></i> Nueva Cuenta
            </button>
        </div>
    </div>

    <!-- Tabla de cuentas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">Listado de Cuentas</h6>
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
                <!-- Agrega esto antes de la tabla -->
               
                <table class="table table-bordered table-hover" id="tablacuentas" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Moneda</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Entidad Bancaria</th>
                            <th class="text-center">Número de Cuenta</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan las cuentas dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar cuenta -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-wallet2 me-2"></i>Registrar Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre de la cuenta">
                    <label for="nombre">Nombre de la cuenta</label>
                    <span id="snombre" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="tipo">
                        <option value="" selected disabled>Seleccione un tipo</option>
                        <option value="bancaria">Bancaria</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="otro">Otro</option>
                    </select>
                    <label for="tipo">Tipo de cuenta</label>
                    <span id="stipo" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3" id="entidad_bancaria_group" style="display: none;">
                    <input type="text" class="form-control" id="entidad_bancaria" placeholder="Nombre del banco">
                    <label for="entidad_bancaria">Entidad Bancaria</label>
                    <span id="sentidad_bancaria" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3" id="numero_cuenta_group" style="display: none;">
                    <input type="text" class="form-control" id="numero_cuenta" placeholder="Número de cuenta">
                    <label for="numero_cuenta">Número de Cuenta</label>
                    <span id="snumero_cuenta" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="moneda">
                        <option value="" selected disabled>Seleccione una moneda</option>
                        <option value="Bs">Bolívares (Bs)</option>
                        <option value="USD">Dólares (USD)</option>
                        <option value="EUR">Euros (EUR)</option>
                    </select>
                    <label for="moneda">Moneda</label>
                    <span id="smoneda" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="activa">
                        <option value="1" selected>Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                    <label for="activa">Estado</label>
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
<script type="text/javascript" src="js/cuentas.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-info" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div> 