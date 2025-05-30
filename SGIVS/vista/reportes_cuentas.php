<?php
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-file-earmark-text me-2"></i>Reportes de Cuentas</h1>
    </div>

    <!-- Formulario de reportes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Generar Reporte</h6>
        </div>
        <div class="card-body">
            <form id="formReporte" method="post" target="_blank">
                <input type="hidden" name="accion" value="generar_reporte">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="tipo_reporte" name="tipo_reporte" required>
                                <option value="" selected disabled>Seleccione un tipo de reporte</option>
                                <option value="estado_cuentas">Estado de Cuentas</option>
                                <option value="movimientos">Movimientos</option>
                                <option value="cuentas_cobrar">Cuentas por Cobrar</option>
                                <option value="cuentas_pagar">Cuentas por Pagar</option>
                            </select>
                            <label for="tipo_reporte">Tipo de Reporte</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="cuenta_id" name="cuenta_id">
                                <option value="" selected>Todas las cuentas</option>
                            </select>
                            <label for="cuenta_id">Cuenta</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                            <label for="fecha_inicio">Fecha Inicio</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            <label for="fecha_fin">Fecha Fin</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="moneda" name="moneda">
                                <option value="" selected>Todas las monedas</option>
                                <option value="Bs">Bolívares (Bs)</option>
                                <option value="USD">Dólares (USD)</option>
                                <option value="EUR">Euros (EUR)</option>
                            </select>
                            <label for="moneda">Moneda</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="estado" name="estado">
                                <option value="" selected>Todos los estados</option>
                                <option value="activa">Activa</option>
                                <option value="inactiva">Inactiva</option>
                            </select>
                            <label for="estado">Estado</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Generar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="js/reportes_cuentas.js"></script>

<!-- Loader -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-info" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div> 