<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid mt-4">
    <h2 class="mb-4">📊 Dashboard</h2>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cuenta">Cuenta</label>
                        <select class="form-select" id="cuenta">
                            <option value="">Todas las cuentas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fechaInicio">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fechaInicio">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fechaFin">Fecha Fin</label>
                        <input type="date" class="form-control" id="fechaFin">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary w-100" onclick="aplicarFiltros()">
                            <i class="bi bi-search"></i> Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Ingresos</h5>
                    <h3 class="card-text" id="totalIngresos">Bs. 0.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Egresos</h5>
                    <h3 class="card-text" id="totalEgresos">Bs. 0.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Ganancias</h5>
                    <h3 class="card-text" id="totalGanancias">Bs. 0.00</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Ingresos y Egresos por Mes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ingresos vs Egresos por Mes</h5>
                    <canvas id="graficoComparativo"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Distribución -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ingresos por Origen</h5>
                    <canvas id="graficoIngresosOrigen"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Egresos por Origen</h5>
                    <canvas id="graficoEgresosCategoria"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Movimientos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Últimos Ingresos</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th>Origen</th>
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
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Últimos Egresos</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th>Categoría</th>
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

<!-- Loader -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div>

<style>
    .card-body canvas {
        max-height: 300px; /* Ajusta este valor según lo necesites */
    }

    /* Opcional: Ajustar el tamaño del loader si fuera necesario */
    .loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1050; /* Asegurarse de que esté por encima de otros elementos */
    }

    .loader {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style> 