<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina; 
    exit;
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){ 
    if(!empty($_POST)){
        $o = new equipos();   
        $accion = $_POST['accion']; 
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
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
    echo "ERROR 404"; 
}
?>