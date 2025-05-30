<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-cash-coin me-2"></i>Cuentas por Cobrar</h1>
        <div>
            <button type="button" class="btn btn-success" id="incluir" title="Registrar Cuenta por Cobrar">
                <i class="bi bi-plus-circle me-1"></i> Nueva Cuenta por Cobrar
            </button>
        </div>
    </div>

    <!-- Tabla de cuentas por cobrar -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">Listado de Cuentas por Cobrar</h6>
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
                
                <table class="table table-striped table-hover" id="tablacxc" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-center">Paciente</th>
                            <th class="text-center">Monto Total</th>
                            <th class="text-center">Monto Pendiente</th>
                            <th class="text-center">Fecha Emisión</th>
                            <th class="text-center">Fecha Vencimiento</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Referencia</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan las cuentas por cobrar dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar cuenta por cobrar -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cash-coin me-2"></i>Registrar Cuenta por Cobrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="paciente_id">
                        <option value="" selected disabled>Seleccione un paciente</option>
                    </select>
                    <label for="paciente_id">Paciente</label>
                    <span id="spaciente_id" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="monto_total" placeholder="Monto total">
                    <label for="monto_total">Monto Total (USD)</label>
                    <span id="smonto_total" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="numero_cuotas" placeholder="Número de cuotas">
                    <label for="numero_cuotas">Número de Cuotas</label>
                    <span id="snumero_cuotas" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="frecuencia_pago">
                        <option value="" selected disabled>Seleccione frecuencia</option>
                        <option value="semanal">Semanal</option>
                        <option value="quincenal">Quincenal</option>
                        <option value="mensual">Mensual</option>
                    </select>
                    <label for="frecuencia_pago">Frecuencia de Pago</label>
                    <span id="sfrecuencia_pago" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fecha_emision" placeholder="Fecha de emisión">
                    <label for="fecha_emision">Fecha de Emisión</label>
                    <span id="sfecha_emision" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fecha_vencimiento" placeholder="Fecha de vencimiento">
                    <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                    <span id="sfecha_vencimiento" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="descripcion" placeholder="Descripción">
                    <label for="descripcion">Descripción</label>
                    <span id="sdescripcion" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="referencia" placeholder="Referencia">
                    <label for="referencia">Referencia</label>
                    <span id="sreferencia" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="cuenta_id">
                        <option value="" selected disabled>Seleccione una cuenta</option>
                    </select>
                    <label for="cuenta_id">Cuenta</label>
                    <span id="scuenta_id" class="text-danger"></span>
                </div>

                <!-- Campos adicionales para modificación -->
                <div id="campos_modificacion" style="display: none;">
                    <div class="form-floating mb-3">
                        <input type="number" step="0.01" class="form-control" id="monto_pendiente" placeholder="Monto pendiente">
                        <label for="monto_pendiente">Monto Pendiente (USD)</label>
                        <span id="smonto_pendiente" class="text-danger"></span>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select select2" id="estado">
                            <option value="pendiente">Pendiente</option>
                            <option value="parcial">Parcial</option>
                            <option value="pagado">Pagado</option>
                            <option value="vencido">Vencido</option>
                        </select>
                        <label for="estado">Estado</label>
                        <span id="sestado" class="text-danger"></span>
                    </div>

                    <!-- Tabla de cuotas -->
                    <div class="table-responsive mt-3">
                        <h5>Cuotas de Pago</h5>
                        <table class="table table-sm table-striped" id="tablaCuotas">
                            <thead>
                                <tr>
                                    <th># Cuota</th>
                                    <th>Monto</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="resultadoCuotas">
                                <!-- Aquí se cargan las cuotas dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="bc">
                    <i class="bi bi-x-square me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="proceso">
                    <i class="bi bi-check-circle me-1"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para registrar pago -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="fPago" autocomplete="off" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cash me-2"></i>Registrar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="cuota_id" id="cuota_id">

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="monto_pago" placeholder="Monto del pago">
                    <label for="monto_pago">Monto del Pago (USD)</label>
                    <span id="smonto_pago" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fecha_pago" placeholder="Fecha del pago">
                    <label for="fecha_pago">Fecha del Pago</label>
                    <span id="sfecha_pago" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="metodo_pago">
                        <option value="" selected disabled>Seleccione método</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="otro">Otro</option>
                    </select>
                    <label for="metodo_pago">Método de Pago</label>
                    <span id="smetodo_pago" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="referencia_pago" placeholder="Referencia del pago">
                    <label for="referencia_pago">Referencia del Pago</label>
                    <span id="sreferencia_pago" class="text-danger"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-square me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="procesarPago">
                    <i class="bi bi-check-circle me-1"></i> Registrar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para ver detalle de pago -->
<div class="modal fade" id="modalDetallePago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Detalle del Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <th>Monto Pagado:</th>
                            <td id="detalle_monto"></td>
                        </tr>
                        <tr>
                            <th>Fecha de Pago:</th>
                            <td id="detalle_fecha"></td>
                        </tr>
                        <tr>
                            <th>Método de Pago:</th>
                            <td id="detalle_metodo"></td>
                        </tr>
                        <tr>
                            <th>Referencia:</th>
                            <td id="detalle_referencia"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-square me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para abonar a cuenta -->
<div class="modal fade" id="modalAbono" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="fAbono" autocomplete="off" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cash me-2"></i>Abonar a Cuenta por Cobrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id_cuenta" id="id_cuenta_abono">
                
                <!-- Información de la cuenta -->
                <div class="alert alert-info">
                    <h6>Información de la Cuenta</h6>
                    <p class="mb-1"><strong>Paciente:</strong> <span id="info_paciente"></span></p>
                    <p class="mb-1"><strong>Monto Total:</strong> <span id="info_monto_total"></span></p>
                    <p class="mb-1"><strong>Monto Pendiente:</strong> <span id="info_monto_pendiente"></span></p>
                </div>

                <!-- Campos para el abono -->
                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="monto_abono" placeholder="Monto del abono">
                    <label for="monto_abono">Monto del Abono (USD)</label>
                    <span id="smonto_abono" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fecha_abono" placeholder="Fecha del abono">
                    <label for="fecha_abono">Fecha del Abono</label>
                    <span id="sfecha_abono" class="text-danger"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="referencia_abono" placeholder="Referencia del abono">
                    <label for="referencia_abono">Referencia del Abono</label>
                    <span id="sreferencia_abono" class="text-danger"></span>
                </div>

                <!-- Tabla de cuotas pendientes -->
                <div class="table-responsive mt-3">
                    <h5>Cuotas Pendientes</h5>
                    <table class="table table-sm table-striped" id="tablaCuotasPendientes">
                        <thead>
                            <tr>
                                <th># Cuota</th>
                                <th>Monto</th>
                                <th>Fecha Vencimiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="resultadoCuotasPendientes">
                            <!-- Aquí se cargan las cuotas pendientes dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-square me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="procesarAbono">
                    <i class="bi bi-check-circle me-1"></i> Registrar Abono
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="js/cxc.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-success" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div>
