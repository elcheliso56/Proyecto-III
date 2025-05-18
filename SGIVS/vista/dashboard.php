<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid mt-4">
    <h2 class="mb-4">📊 Dashboard</h2>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Ingresos</h5>
                    <h3 class="card-text" id="totalIngresos">Bs. 0.00</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Egresos</h5>
                    <h3 class="card-text" id="totalEgresos">Bs. 0.00</h3>
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
                    <h5 class="card-title">Egresos por Categoría</h5>
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