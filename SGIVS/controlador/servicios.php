<?php  
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina; 
	exit; 
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){ 
	if(!empty($_POST)){ 
		$o = new servicios(); 
		$accion = $_POST['accion']; 
		if($accion=='consultar'){
			echo json_encode($o->consultar()); 
		}
		elseif($accion=='eliminar'){
			$o->set_nombre($_POST['nombre']); 
			echo json_encode($o->eliminar()); 
		}
		else if($accion=='incluir' || $accion=='modificar'){
			$o->set_nombre($_POST['nombre']); 
			$o->set_descripcion($_POST['descripcion']);
			$o->set_precio($_POST['precio']);
			if($accion == 'incluir'){
				try {
					ob_start(); // Inicia el buffer de salida
					$respuesta = $o->incluir();
					ob_end_clean(); // Limpia el buffer y descarta su contenido
					if (!is_array($respuesta)) {
						throw new Exception("La respuesta no es un array válido");
					}
					echo json_encode($respuesta);
				} catch (Exception $e) {
					echo json_encode(array(
						'resultado' => 'error',
						'mensaje' => 'Error al incluir: ' . $e->getMessage()
					));
				}
			} 
			elseif($accion == 'modificar'){
				echo json_encode($o->modificar()); 
			}
		}
		else if($accion=='listadoInsumos'){
			$respuesta = $o->listadoInsumos();
			echo json_encode($respuesta);
		}
		elseif($accion=='listadoEquipos'){
			$respuesta = $o->listadoEquipos();
			echo json_encode($respuesta);
		}
		exit; 
	}	  
	require_once("vista/".$pagina.".php"); 
}
else {
	echo "ERROR 404"; 
}
?>