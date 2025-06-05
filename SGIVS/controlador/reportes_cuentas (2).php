<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  
require_once("modelo/".$pagina.".php");  // Incluye el archivo del modelo

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){ 
    // Comprueba si hay datos enviados por POST
    if(!empty($_POST)){
        $o = new reportes_cuentas();   // Crea una nueva instancia de la clase reportes_cuentas
        $accion = $_POST['accion']; // Obtiene la acción a realizar
        
        // Acción para consultar cuentas
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para generar reporte
        elseif($accion=='generar_reporte'){
            $o->set_tipo_reporte($_POST['tipo_reporte']);
            $o->set_fecha_inicio($_POST['fecha_inicio'] ?? '');
            $o->set_fecha_fin($_POST['fecha_fin'] ?? '');
            $o->set_cuenta_id($_POST['cuenta_id'] ?? '');
            $o->set_moneda($_POST['moneda'] ?? '');
            $o->set_estado($_POST['estado'] ?? '');
            echo json_encode($o->generarReporte());
        }
        exit; // Termina la ejecución del script 
    }	
    require_once("vista/".$pagina.".php"); // Incluye el archivo de vista
}
else{
    echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?> 