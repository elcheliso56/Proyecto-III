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
                $cuenta = $_POST['cuenta'] ?? '';
                $fechaInicio = $_POST['fechaInicio'] ?? '';
                $fechaFin = $_POST['fechaFin'] ?? '';

                $datos = array(
                    'error' => false,
                    'ingresosPorMes' => $o->obtenerIngresosPorMes($cuenta, $fechaInicio, $fechaFin),
                    'egresosPorMes' => $o->obtenerEgresosPorMes($cuenta, $fechaInicio, $fechaFin),
                    'totalIngresos' => $o->obtenerTotalIngresos($cuenta, $fechaInicio, $fechaFin),
                    'totalEgresos' => $o->obtenerTotalEgresos($cuenta, $fechaInicio, $fechaFin),
                    'ingresosPorOrigen' => $o->obtenerIngresosPorOrigen($cuenta, $fechaInicio, $fechaFin),
                    'egresosPorOrigen' => $o->obtenerEgresosPorOrigen($cuenta, $fechaInicio, $fechaFin),
                    'ultimosIngresos' => $o->obtenerUltimosIngresos($cuenta, $fechaInicio, $fechaFin),
                    'ultimosEgresos' => $o->obtenerUltimosEgresos($cuenta, $fechaInicio, $fechaFin)
                );

                echo json_encode($datos);
            } catch (Exception $e) {
                error_log("Error en el controlador dashboard: " . $e->getMessage());
                echo json_encode(array(
                    'error' => true,
                    'mensaje' => 'Error al obtener los datos: ' . $e->getMessage()
                ));
            }
        } elseif($accion=='obtenerCuentas') {
            try {
                $cuentas = $o->obtenerCuentas();
                echo json_encode(['error' => false, 'cuentas' => $cuentas]);
            } catch (Exception $e) {
                error_log("Error al obtener cuentas: " . $e->getMessage());
                echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
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