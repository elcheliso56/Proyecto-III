<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina; // Mensaje de error si no se encuentra el modelo
    exit;
}  
require_once("modelo/".$pagina.".php");  // Incluye el archivo del modelo

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){ 
    // Comprueba si hay datos enviados por POST
    
    if(!empty($_POST)){
        
 // Escribe los datos de $_POST en un archivo de registro
 file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);

        
        $o = new egresos();   // Crea una nueva instancia de la clase egresos
        $accion = $_POST['accion']; // Obtiene la acción a realizar

        // Acción para consultar egresos
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para eliminar un egreso
        elseif($accion=='eliminar'){
            $o->set_id($_POST['id']); // Establece el id del registro
            echo json_encode($o->eliminar()); // Elimina el egreso y devuelve el resultado
        }
        else{		  
            // Acciones para incluir o modificar un egreso
            if($accion=='incluir' || $accion=='modificar'){
                // Establece los atributos del egreso
                $o->set_descripcion(valor: $_POST['descripcion']);
                $o->set_monto($_POST['monto']);
                $o->set_fecha($_POST['fecha']);
                $o->set_origen($_POST['origen']);

                if($accion == 'modificar'){
                    $o->set_id($_POST['id']);
                }
                // Ejecuta la acción de incluir o modificar
                if($accion == 'incluir'){
                    echo json_encode($o->incluir()); // Incluye el egreso y devuelve el resultado
                } elseif($accion == 'modificar'){
                    echo json_encode($o->modificar()); // Modifica el egreso y devuelve el resultado
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