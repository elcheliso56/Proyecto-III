<?php
if (!is_file("modelo/".$pagina.".php")){
    header('Content-Type: application/json');
    echo json_encode(['error' => true, 'mensaje' => "Falta definir la clase ".$pagina]);
    exit;
}  
require_once("modelo/".$pagina.".php");

if(is_file("vista/".$pagina.".php")){ 
    if(!empty($_POST)){
        header('Content-Type: application/json');
        $o = new dashboard();
        $accion = $_POST['accion'];

        if($accion=='obtenerDatos'){
            try {
                $datos = array(
                    'ingresosPorMes' => $o->obtenerIngresosPorMes(),
                    'egresosPorMes' => $o->obtenerEgresosPorMes(),
                    'totalIngresos' => $o->obtenerTotalIngresos(),
                    'totalEgresos' => $o->obtenerTotalEgresos(),
                    'ingresosPorOrigen' => $o->obtenerIngresosPorOrigen(),
                    'egresosPorOrigen' => $o->obtenerEgresosPorOrigen(),
                    'ultimosIngresos' => $o->obtenerUltimosIngresos(),
                    'ultimosEgresos' => $o->obtenerUltimosEgresos()
                );

                // Verificar si hay datos de egresos
                if (empty($datos['egresosPorMes']) && empty($datos['egresosPorOrigen'])) {
                    error_log("No se encontraron datos de egresos en la base de datos");
                }

                echo json_encode($datos);
            } catch (Exception $e) {
                error_log("Error en el controlador dashboard: " . $e->getMessage());
                echo json_encode(array(
                    'error' => true,
                    'mensaje' => 'Error al obtener los datos: ' . $e->getMessage()
                ));
            }
        }
        exit;
    }
    require_once("vista/".$pagina.".php");
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => true, 'mensaje' => 'ERROR 404']);
}
?> 