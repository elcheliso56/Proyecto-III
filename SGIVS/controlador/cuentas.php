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
        $o = new cuentas();   // Crea una nueva instancia de la clase cuentas
        $accion = $_POST['accion']; // Obtiene la acción a realizar
        
        // Acción para consultar cuentas
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para eliminar una cuenta
        elseif($accion=='eliminar'){
            $o->set_id($_POST['id']); // Establece el id del registro
            echo json_encode($o->eliminar()); // Elimina la cuenta y devuelve el resultado
        }
        else{		  
            // Acciones para incluir o modificar una cuenta
            if($accion=='incluir' || $accion=='modificar'){
                // Establece los atributos de la cuenta
                $o->set_nombre($_POST['nombre']);
                $o->set_tipo($_POST['tipo']);
                $o->set_moneda($_POST['moneda']);
                $o->set_activa($_POST['activa']);
                $o->set_entidad_bancaria($_POST['entidad_bancaria'] ?? '');
                $o->set_numero_cuenta($_POST['numero_cuenta'] ?? '');

                if($accion == 'modificar'){
                    $o->set_id($_POST['id']);
                }
                // Ejecuta la acción de incluir o modificar
                if($accion == 'incluir'){
                    echo json_encode($o->incluir()); // Incluye la cuenta y devuelve el resultado
                } elseif($accion == 'modificar'){
                    echo json_encode($o->modificar()); // Modifica la cuenta y devuelve el resultado
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