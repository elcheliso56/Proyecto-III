<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;	
	exit;
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){
	if(!empty($_POST)){
		$o = new entradasInsumos();
		$accion = $_POST['accion'];
		if($accion=='consultar'){
			echo  json_encode($o->consultar()); 
		}
		else{		  
			if($accion=='incluir'){
				$o->set_descripcion($_POST['descripcion']); 
				if($accion == 'incluir'){
					$o->set_nombre($_POST['nombre']); 
					echo json_encode($o->incluir()); 
				} 	
			}
		}
		if($accion=='listadoInsumos'){
			$respuesta = $o->listadoInsumos();
			echo json_encode($respuesta);
		}
		elseif($accion=='entrada'){
			$respuesta = $o->entrada(
				$_POST['idp'],
				$_POST['cant'],
				$_POST['pcp'],
				null
			);
			echo json_encode($respuesta);
		}
		exit; 
	}
	require_once("vista/".$pagina.".php"); 
}
else{
	echo "pagina en construccion";
}
?>