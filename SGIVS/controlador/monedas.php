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
        $o = new monedas();   // Crea una nueva instancia de la clase monedas
        $accion = $_POST['accion']; // Obtiene la acción a realizar
        
        // Acción para consultar monedas
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para consultar moneda por ID
        elseif($accion=='consultarPorId'){
            $o->set_id($_POST['id']);
            $resultado = $o->consultarPorId($_POST['id']);
            echo json_encode(['resultado' => 'consultar', 'data' => $resultado]);
        }
        // Acción para obtener monedas activas
        elseif($accion=='obtenerMonedasActivas'){
            echo json_encode($o->obtenerMonedasActivas());
        }
        // Acción para obtener moneda principal
        elseif($accion=='obtenerMonedaPrincipal'){
            echo json_encode($o->obtenerMonedaPrincipal());
        }
        // Acción para eliminar una moneda
        elseif($accion=='eliminar'){
            $o->set_id($_POST['id']); // Establece el id del registro
            echo json_encode($o->eliminar()); // Elimina la moneda y devuelve el resultado
        }
        else{		  
            // Acciones para incluir o modificar una moneda
            if($accion=='incluir' || $accion=='modificar'){
                // Establece los atributos de la moneda
                $o->set_codigo($_POST['codigo']);
                $o->set_nombre($_POST['nombre']);
                $o->set_simbolo($_POST['simbolo']);
                $o->set_activa($_POST['activa']);
                $o->set_es_principal($_POST['es_principal']);

                if($accion == 'modificar'){
                    $o->set_id($_POST['id']);
                }
                // Ejecuta la acción de incluir o modificar
                if($accion == 'incluir'){
                    echo json_encode($o->incluir()); // Incluye la moneda y devuelve el resultado
                } elseif($accion == 'modificar'){
                    echo json_encode($o->modificar()); // Modifica la moneda y devuelve el resultado
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