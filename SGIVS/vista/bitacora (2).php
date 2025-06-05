<?php 
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?> 
<div class="container"> 
    <h1>Bitácora del Sistema</h1>
    <p style="text-align:justify;">"Bienvenido al módulo de Bitácora del Sistema. Aquí podrás visualizar el registro de todas las acciones realizadas en el sistema, incluyendo la fecha, hora, usuario y tipo de acción. Esta información es importante para mantener un control y seguimiento de las actividades realizadas en el sistema."</p>
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
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Hora</th>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Acción</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Módulo</th>
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