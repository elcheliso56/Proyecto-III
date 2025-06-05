<?php  
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina; // Mensaje de error si no se encuentra el modelo
	exit; // Termina la ejecución
}  
require_once("modelo/".$pagina.".php"); // Incluye el archivo del modelo
// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){	    
	if(!empty($_POST)){ // Comprueba si hay datos enviados por POST
		$o = new roles(); // Crea una instancia de la clase clientes
		$accion = $_POST['accion']; // Obtiene la acción a realizar
		// Acción para consultar datos
		if($accion=='consultar'){
			echo  json_encode($o->consultar()); // Devuelve los datos en formato JSON
		}
		// Acción para eliminar un empleado
		elseif($accion=='eliminar'){
			$o->set_nombre_rol($_POST['nombre_rol']); // Establece el número de documento
			echo  json_encode($o->eliminar()); // Elimina y devuelve el resultado en JSON
		}
        // Acción para consultar permisos de un rol
        elseif($accion=='consultar_permisos_rol'){
            $o->set_id($_POST['id_rol']);
            echo json_encode($o->consultar_permisos_rol());
        }
        // Acción para guardar permisos
        elseif($accion=='guardar_permisos'){
            $o->set_id($_POST['id_rol']);
            $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];
            if (!is_array($permisos)) {
                $permisos = [];
            }

            echo json_encode($o->guardar_permisos($permisos));
        }
		else{		  
			
			$o->set_id($_POST['id']);
			$o->set_nombre_rol($_POST['nombre_rol']);
			$o->set_descripcion($_POST['descripcion']);
            $o->set_estado($_POST['estado']);
			
			// Acción para incluir un nuevo empleado
			if($accion=='incluir'){
				echo  json_encode($o->incluir()); // Incluye y devuelve el resultado en JSON
			}
			// Acción para modificar un empleado existente
			elseif($accion=='modificar'){
				echo  json_encode($o->modificar()); // Modifica y devuelve el resultado en JSON
			}
		}
		exit; // Termina la ejecución después de procesar la acción
	}	  
	require_once("vista/".$pagina.".php"); // Incluye el archivo de vista
}
else{
	require_once("vista/404.php"); // Mensaje de error si no se encuentra la vista
}
?>
