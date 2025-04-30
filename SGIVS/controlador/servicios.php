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
			echo  json_encode($o->consultar()); 
		}
		elseif($accion=='eliminar'){
			$o->set_nombre($_POST['nombre']); 
			echo  json_encode($o->eliminar()); 
		}
		else{		  
			if($accion=='incluir' || $accion=='modificar'){
				$o->set_nombre($_POST['nombre']); 
				$o->set_descripcion($_POST['descripcion']);
				if($accion == 'incluir'){
					echo json_encode($o->incluir()); 
				} 
				elseif($accion == 'modificar'){
					echo json_encode($o->modificar()); 
				}
			}
		}
		exit; 
	}	  
	require_once("vista/".$pagina.".php"); 
}
else{
	echo "ERROR 404"; 
}
?>