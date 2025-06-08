<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina; // Mensaje de error si falta la clase
	exit;
}  
require_once("modelo/".$pagina.".php"); // Incluye el archivo del modelo
// Verifica si la vista existe
if(is_file("vista/".$pagina.".php")){  
	if(!empty($_POST)){ // Comprueba si se han enviado datos por POST
		$o = new usuarios(); // Crea una instancia de la clase usuarios
		$accion = $_POST['accion']; // Obtiene la acción a realizar

		// Acción para consultar usuarios
		if($accion=='consultar'){
			echo  json_encode($o->consultar());  
		}
		// Acción para eliminar un usuario
		elseif($accion=='eliminar'){
			$o->set_usuario($_POST['usuario']); // Establece la cédula del usuario a eliminar
			echo  json_encode($o->eliminar());
		}
		// Acción para listar empleados
		elseif($accion=='listaEmpleados'){
			echo json_encode($o->listaEmpleados());
		}
		else{		  
			// Establece los datos del usuario
			$o->set_usuario($_POST['usuario']);
			
			// Si solo se está actualizando el estado
			if (isset($_POST['estado']) && !isset($_POST['id_rol']) && !isset($_POST['nombre_apellido'])) {
				$o->set_estado($_POST['estado']);
				echo json_encode($o->modificarEstado());
				exit;
			}
			
			// Para otras modificaciones, establecer todos los campos
			$o->set_nombre_apellido($_POST['nombre_apellido']);
			$o->set_id_rol($_POST['id_rol']);
			if (!empty($_POST['contraseña'])) {
				$o->set_contraseña($_POST['contraseña']);
			}
			$o->set_estado($_POST['estado']);

			// Manejo de la imagen del usuario
			if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
				$imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; // Genera un nombre único para la imagen
				$imagen_ruta = 'otros/img/usuarios/' . $imagen_nombre; // Define la ruta de la imagen
				move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); // Mueve la imagen a la ruta definida
				$o->set_imagen($imagen_ruta); // Establece la ruta de la imagen en el objeto
			} else {
				$o->set_imagen('otros/img/usuarios/default.png'); // Establece una imagen por defecto si no se sube ninguna
			}
			
			// Acción para incluir un nuevo usuario
			if($accion=='incluir'){
				echo  json_encode($o->incluir());
			}
			// Acción para modificar un usuario existente
			elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
			}
		}
		exit; // Finaliza la ejecución después de procesar la acción
	}	  
	require_once("vista/".$pagina.".php"); // Incluye la vista correspondiente
}
else{
	echo "ERROR 404"; // Mensaje de error si la vista no existe
}
?>