<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}
else{
	require_once('modelo/reportes.php');
// Verifica si el usuario es un administrador
	if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
	header("Location: ?pagina=principal"); // Redirige si no es administrador
	exit;
}
}
if(is_file("vista/".$pagina.".php")){
	if(isset($_POST['reporte_usuarios'])){
		$o = new reportes();
		$o->set_cedula($_POST['cedula']);
		$o->set_nombre($_POST['nombre']);
		$o->set_apellido($_POST['apellido']);
		$o->set_correo($_POST['correo']);
		$o->set_telefono($_POST['telefono']);
		$o->set_nombre_usuario($_POST['nombre_usuario']);
		$o->set_tipo_usuario($_POST['tipo_usuario']);		  		  		 		   		  		  		 	  		  
		$o->reporte_usuarios();
	}	  	 	  	
	else if(isset($_POST['reporte_insumos'])){
		$o = new reportes();
		$o->set_codigo($_POST['codigo']);
		$o->set_nombre($_POST['nombre']);
		$o->set_marca($_POST['marca']);
		$o->set_stock_total($_POST['stock_total']);
		$o->set_stock_minimo($_POST['stock_minimo']);
		$o->set_precio($_POST['precio']);
		$o->set_presentacion($_POST['presentacion']);
		$o->reporte_insumos();
	}
	else if(isset($_POST['reporte_insumos'])){
		$o = new reportes();
		$o->set_codigo($_POST['codigo']);
		$o->set_nombre($_POST['nombre']);
		$o->set_marca($_POST['marca']);
		$o->set_modelo($_POST['modelo']);
		$o->set_cantidad($_POST['cantidad']);
		$o->set_precio($_POST['precio']);
		$o->reporte_equipos();
	}	
	else if(isset($_POST['reporte_entradas'])){
		$o = new reportes();
		if(!empty($_POST['fecha_inicio'])) {
			$o->set_fecha_inicio($_POST['fecha_inicio']);
		}
		if(!empty($_POST['fecha_fin'])) {
			$o->set_fecha_fin($_POST['fecha_fin']);
		}
		if(!empty($_POST['proveedor'])) {
			$o->set_proveedor($_POST['proveedor']);
		}
		$o->reporte_entradas();
	}		
	require_once("vista/".$pagina.".php"); 
}
else{
	echo "pagina en construccion";
}
?>