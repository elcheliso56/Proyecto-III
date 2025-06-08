<?php 
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?> 
<div class="container"> 
    <h1>Bitácora del Sistema</h1>
    <div class="container">
        <div class="row mt-1 justify-content-center">
            <div class="col-md-2 text-center">
                <button type="button" class="btn-sm btn-warning w-75 small-width" id="generar_reporte" title="Generar Reporte PDF">
                    <i class="bi bi-file-pdf"></i>
                </button>
            </div>              
        </div>
    </div>
    <div class="container">
        <div class="table-responsive" id="tt">
            <table class="table table-striped table-hover table-center" id="tablaBitacora">
                <thead class="tableh">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">FECHA</th>
                        <th class="text-center">HORA</th>
                        <th class="text-center">USUARIO</th>
                        <th class="text-center">NOMBRE Y APELLIDO</th>
                        <th class="text-center">ACCION</th>
                        <th class="text-center">DESCRIPCION</th>
                        <th class="text-center">MODULO</th>
                    </tr>
                </thead>
                <tbody id="resultadoconsulta">              
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/bitacora.js"></script>
<div id="loader" class="loader-container">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div>
</body>
</html> 