<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;	
	exit;
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){
	if(!empty($_POST)){
		$o = new gestionarEquipos();
		$accion = $_POST['accion'];
		if($accion=='consultar'){
			echo  json_encode($o->consultar()); 
		}
		else if($accion=='incluir2'){
			$o->set_descripcion($_POST['descripcion']); 
			if($accion == 'incluir2'){
				$o->set_nombre($_POST['nombre']); 
				echo json_encode($o->incluir2()); 
			} 	
		}
		elseif($accion=='listadoEquipos'){
			$respuesta = $o->listadoEquipos();
			echo json_encode($respuesta);
		}
		elseif($accion=='entrada'){
			$nota_ruta = null;
			
			if (isset($_FILES['nota_entrega']) && $_FILES['nota_entrega']['error'] == 0) {
				$nota_nombre = uniqid() . '_' . $_FILES['nota_entrega']['name'];
				$nota_ruta = 'otros/img/entradas/' . $nota_nombre;
				move_uploaded_file($_FILES['nota_entrega']['tmp_name'], $nota_ruta);
			}
			
			$respuesta = $o->entrada(
				$_POST['idp'],
				$_POST['cant'],
				$_POST['pcp'],
				$nota_ruta
			);
			echo json_encode($respuesta);
		}

		elseif($accion=='consultar2'){
			echo json_encode($o->consultar2());  
		}
		elseif($accion=='eliminar'){
			$o->set_codigo($_POST['codigo']); 
			echo json_encode($o->eliminar()); 
		}
		else{         
			if($accion=='incluir' || $accion=='modificar'){
				$o->set_codigo($_POST['codigo']);
				$o->set_nombre($_POST['nombre']);
				$o->set_marca($_POST['marca']);
				$o->set_modelo($_POST['modelo']);
				$o->set_cantidad($_POST['cantidad']);
				if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
					$imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; 
					$imagen_ruta = 'otros/img/equipos/' . $imagen_nombre; 
					move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); 
					$o->set_imagen($imagen_ruta); 
				} else {
					$o->set_imagen('otros/img/equipos/default.png'); 
				}
				if($accion == 'incluir'){
					echo json_encode($o->incluir()); 
				} elseif($accion == 'modificar'){
					echo json_encode($o->modificar()); 
				}
			}
		}
		if($accion=='obtenerNotificaciones'){
			echo json_encode($o->obtenerEquiposNotificacion());
		} 
		exit; 
	}
	require_once("vista/".$pagina.".php"); 
}
else{
	echo "pagina en construccion";
}
?>