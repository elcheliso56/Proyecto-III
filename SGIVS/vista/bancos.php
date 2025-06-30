<?php  
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-bank me-2"></i>Bancos</h1>
        <div>
            <button type="button" class="btn btn-info" id="incluir" title="Registrar Banco">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Banco
            </button>
        </div>
    </div>

    <!-- Tabla de bancos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">Listado de Bancos</h6>
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
                <table class="table table-bordered table-hover" id="tablabancos" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-center">Banco</th>
                            <th class="text-center">Código SWIFT</th>
                            <th class="text-center">Código Local</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Fecha de Registro</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoconsulta">
                        <!-- Aquí se cargan los bancos dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar banco -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="f" autocomplete="off" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-bank me-2"></i>Registrar Banco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" id="accion" />
                <input type="hidden" name="id" id="id">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre del banco">
                    <label for="nombre">Nombre del Banco</label>
                    <span id="snombre" class="text-danger small"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="codigo_swift" placeholder="Código SWIFT (opcional)">
                    <label for="codigo_swift">Código SWIFT (opcional)</label>
                    <span id="scodigo_swift" class="text-danger small"></span>
                    <small class="form-text text-muted">Ej: BANCPEPL (8-11 caracteres, solo letras mayúsculas)</small>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="codigo_local" placeholder="Código Local (opcional)">
                    <label for="codigo_local">Código Local (opcional)</label>
                    <span id="scodigo_local" class="text-danger small"></span>
                    <small class="form-text text-muted">Ej: BCP (2-20 caracteres, letras y números)</small>
                </div>

                <div class="form-floating mb-3">
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    <label for="logo">Logo del Banco (opcional)</label>
                    <span id="slogo" class="text-danger small"></span>
                    <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB</small>
                    <div id="logo_preview" class="mt-2" style="display: none;">
                        <img id="preview_img" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select select2" id="activo">
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                    <label for="activo">Estado</label>
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
<script type="text/javascript" src="js/bancos.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="spinner-border text-info" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-2">Procesando solicitud...</p>
</div> 