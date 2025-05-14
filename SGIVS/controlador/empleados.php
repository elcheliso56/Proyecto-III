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
		$o = new empleados(); // Crea una instancia de la clase empleado
		$accion = $_POST['accion']; // Obtiene la acción a realizar
		// Acción para consultar datos
		if($accion=='consultar'){
			echo  json_encode($o->consultar()); // Devuelve los datos en formato JSON
		}
		// Acción para eliminar un empleado
		elseif($accion=='eliminar'){
			$o->set_cedula($_POST['cedula']); // Establece el número de documento
			echo  json_encode($o->eliminar()); // Elimina y devuelve el resultado en JSON
		}
		else{		  
			// Establece los datos del empleado
			$o->set_cedula($_POST['cedula']);
			$o->set_nombre($_POST['nombre']);
			$o->set_apellido($_POST['apellido']);
            $o->set_fecha_nacimiento($_POST['fecha_nacimiento']);
            $o->set_genero($_POST['genero']);
			$o->set_email($_POST['email']);
			$o->set_telefono($_POST['telefono']);
			$o->set_direccion($_POST['direccion']);
            $o->set_fecha_contratacion($_POST['fecha_contratacion']);
            $o->set_cargo($_POST['cargo']);
            $o->set_salario($_POST['salario']);
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
	echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?>
