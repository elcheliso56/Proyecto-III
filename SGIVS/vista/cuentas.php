<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container mt-4">
    <h2 class="mb-1">💰 Cuentas</h2> 
    <p class="text-muted">Listado de cuentas registradas en el sistema.</p>

    <div class="text-end mb-3">
        <button type="button" class="btn btn-success" id="incluir" title="Registrar Cuenta">
            <i class="bi bi-plus-circle"></i> Nueva Cuenta
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover text-center align-middle" id="tablacuentas">
            <thead class="tableh">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Moneda</th>
                    <th>Estado</th>
                    <th>Entidad Bancaria</th>
                    <th>Número de Cuenta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="resultadoconsulta">
                <!-- Aquí se cargan las cuentas dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Registrar cuenta -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-wallet2"></i> Registrar Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre de la cuenta">
                    <label for="nombre">Nombre de la cuenta</label>
                    <span id="snombre"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="tipo">
                        <option value="" selected disabled>Seleccione un tipo</option>
                        <option value="bancaria">Bancaria</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="otro">Otro</option>
                    </select>
                    <label for="tipo">Tipo de cuenta</label>
                    <span id="stipo"></span>
                </div>

                <div class="form-floating mb-3" id="entidad_bancaria_group" style="display: none;">
                    <input type="text" class="form-control" id="entidad_bancaria" placeholder="Nombre del banco">
                    <label for="entidad_bancaria">Entidad Bancaria</label>
                    <span id="sentidad_bancaria"></span>
                </div>

                <div class="form-floating mb-3" id="numero_cuenta_group" style="display: none;">
                    <input type="text" class="form-control" id="numero_cuenta" placeholder="Número de cuenta">
                    <label for="numero_cuenta">Número de Cuenta</label>
                    <span id="snumero_cuenta"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="moneda">
                        <option value="" selected disabled>Seleccione una moneda</option>
                        <option value="Bs">Bolívares (Bs)</option>
                        <option value="USD">Dólares (USD)</option>
                        <option value="EUR">Euros (EUR)</option>
                    </select>
                    <label for="moneda">Moneda</label>
                    <span id="smoneda"></span>
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
<script type="text/javascript" src="js/cuentas.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div> 