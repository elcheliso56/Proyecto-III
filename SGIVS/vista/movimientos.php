<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-cash-stack me-2"></i>Movimientos Financieros</h1>
        <div>
            <div class="btn-group">
                <button class="btn btn-info" id="btnNuevoEgreso">
                    <a href="?pagina=ingresos" class="text-white text-decoration-none">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo ingreso
                    </a>
                </button>
                <button class="btn btn-danger" id="btnNuevoEgreso">
                    <a href="?pagina=egresos" class="text-white text-decoration-none">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo Egreso
                    </a>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de movimientos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registro de Transacciones</h6>
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
                
                <table class="table table-striped table-hover" id="tablaingresos">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Monto (Bs)</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Origen/Categoría</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan las transacciones dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="js/select2.min.js"></script>
<script src="js/movimientos.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />

<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div>

<style>
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