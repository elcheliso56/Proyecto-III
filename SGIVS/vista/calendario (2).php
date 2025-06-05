<?php
// vistas/calendario.php
// Vista para mostrar el calendario de citas confirmadas

// Incluir archivos comunes
// Asegúrate de que 'encabezado.php' cargue Bootstrap, jQuery y SweetAlert2
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?>
<div class="container mt-4">
    <h1 class="text-center">Calendario de Citas Confirmadas</h1>
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<link href="assets/fullcalendar/lib/main.min.css" rel="stylesheet" />

<script src="assets/fullcalendar/lib/main.min.js"></script>
<script src="assets/fullcalendar/lib/locales-all.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script src="js/calendario.js"></script>

<div id="loader" class="loader-container" style="display: none;">
    <div class="loader"></div>
    <p>Cargando calendario...</p>
</div>

</body>
</html>