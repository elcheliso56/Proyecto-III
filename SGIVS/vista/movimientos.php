<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0"><i class="bi bi-cash-stack me-2 text-primary"></i> Movimientos Financieros</h5>
                            <p class="text-sm text-muted mb-0">Registro completo de transacciones del sistema</p>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2" id="btnReporte">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Generar Reporte
                            </button>
                            <div class="btn-group">
                               <button class="btn btn-sm btn-success" id="btnNuevoEgreso">
                                    <a href="?pagina=ingresos" class="text-white text-decoration-none">
                                        <i class="bi bi-plus-circle me-1"></i> Nuevo ingreso</a>
                                </button>
                                <button class="btn btn-sm btn-danger" id="btnNuevoEgreso">
                                    <a href="?pagina=egresos" class="text-white text-decoration-none">
                                        <i class="bi bi-plus-circle me-1"></i> Nuevo Egreso</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="tablaTransacciones">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Descripción</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Monto (Bs)</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Origen/Categoría</th>
                                </tr>
                            </thead>
                            <tbody id="resultadoconsulta">
                                <!-- Aquí se cargan las transacciones dinámicamente -->
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="text-sm text-muted mt-2">Cargando transacciones...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<!-- Scripts -->
<script src="js/select2.min.js"></script>
<script src="js/movimientos.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />

<!-- Loader personalizado -->


<style>
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .table thead th {
        font-size: 0.75rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    
    .badge-ingreso {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .badge-egreso {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>