<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-graph-up me-2"></i>Dashboard</h1>
        <div>
            <button class="btn btn-primary" onclick="aplicarFiltros()">
                <i class="bi bi-search me-1"></i> Aplicar Filtros
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="cuenta">
                            <option value="">Todas las cuentas</option>
                        </select>
                        <label for="cuenta">Cuenta</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="fechaInicio">
                        <label for="fechaInicio">Fecha Inicio</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="fechaFin">
                        <label for="fechaFin">Fecha Fin</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body bg-primary text-white rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Ingresos</h6>
                            <h3 class="card-text mt-2" id="totalIngresos">Bs. 0.00</h3>
                        </div>
                        <i class="bi bi-cash-coin fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body bg-danger text-white rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Egresos</h6>
                            <h3 class="card-text mt-2" id="totalEgresos">Bs. 0.00</h3>
                        </div>
                        <i class="bi bi-cash-stack fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body bg-success text-white rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Ganancias</h6>
                            <h3 class="card-text mt-2" id="totalGanancias">Bs. 0.00</h3>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Ingresos y Egresos por Mes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Ingresos vs Egresos por Mes</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficoComparativo"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Distribución -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Ingresos por Origen</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficoIngresosOrigen"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Egresos por Origen</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficoEgresosCategoria"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Movimientos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Últimos Ingresos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Origen</th>
                                </tr>
                            </thead>
                            <tbody id="ultimosIngresos">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Últimos Egresos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Categoría</th>
                                </tr>
                            </thead>
                            <tbody id="ultimosEgresos">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts necesarios -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="js/dashboard.js"></script>

<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div>

<style>
    .card-body canvas {
        max-height: 300px;
    }

    .loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }
</style> 