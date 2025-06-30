<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        $o = new tipos_cambio();   // Crea una nueva instancia de la clase tipos_cambio
        $accion = $_POST['accion']; // Obtiene la acción a realizar
        
        // Acción para consultar tipos de cambio
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para consultar tipo de cambio por ID
        elseif($accion=='consultarPorId'){
            $o->set_id($_POST['id']);
            $resultado = $o->consultarPorId($_POST['id']);
            echo json_encode(['resultado' => 'consultar', 'data' => $resultado]);
        }
        // Acción para obtener tipo de cambio actual
        elseif($accion=='obtenerTipoCambioActual'){
            $moneda_origen = $_POST['moneda_origen'];
            $moneda_destino = $_POST['moneda_destino'];
            $resultado = $o->obtenerTipoCambioActual($moneda_origen, $moneda_destino);
            echo json_encode($resultado);
        }
        // Acción para convertir monto
        elseif($accion=='convertirMonto'){
            $monto = $_POST['monto'];
            $moneda_origen = $_POST['moneda_origen'];
            $moneda_destino = $_POST['moneda_destino'];
            $fecha = $_POST['fecha'] ?? null;
            $resultado = $o->convertirMonto($monto, $moneda_origen, $moneda_destino, $fecha);
            echo json_encode($resultado);
        }
        // Acción para obtener tipos de cambio por fecha
        elseif($accion=='obtenerTiposCambioPorFecha'){
            $fecha = $_POST['fecha'];
            $resultado = $o->obtenerTiposCambioPorFecha($fecha);
            echo json_encode($resultado);
        }
        // Acción para eliminar un tipo de cambio
        elseif($accion=='eliminar'){
            $o->set_id($_POST['id']); // Establece el id del registro
            echo json_encode($o->eliminar()); // Elimina el tipo de cambio y devuelve el resultado
        }
        else{		  
            // Acciones para incluir o modificar un tipo de cambio
            if($accion=='incluir' || $accion=='modificar'){
                // Establece los atributos del tipo de cambio
                $o->set_moneda_origen($_POST['moneda_origen']);
                $o->set_moneda_destino($_POST['moneda_destino']);
                $o->set_tipo_cambio($_POST['tipo_cambio']);
                $o->set_fecha($_POST['fecha']);
                $o->set_usuario_id($_SESSION['id'] ?? null);

                if($accion == 'modificar'){
                    $o->set_id($_POST['id']);
                }
                // Ejecuta la acción de incluir o modificar
                if($accion == 'incluir'){
                    echo json_encode($o->incluir()); // Incluye el tipo de cambio y devuelve el resultado
                } elseif($accion == 'modificar'){
                    echo json_encode($o->modificar()); // Modifica el tipo de cambio y devuelve el resultado
                }
            }
        }
        exit; // Termina la ejecución del script 
    }	
    require_once("vista/".$pagina.".php"); // Incluye el archivo de vista
}
else{
    echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?> 