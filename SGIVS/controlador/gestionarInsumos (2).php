<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina; 
    exit;
}  
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){ 
    $o = new gestionarInsumos();
 if(!empty($_POST)){

        $o = new gestionarInsumos();   
        $accion = $_POST['accion']; 
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        elseif($accion=='consultar2'){
            echo json_encode($o->consultar2());  
        }        
        elseif($accion=='eliminar'){
            $o->set_codigo($_POST['codigo']); 
            error_log("Intentando eliminar insumo con código: " . $_POST['codigo']);
            echo json_encode($o->eliminar()); 
        }
        else{		  
            if($accion=='incluir' || $accion=='modificar'){
                $o->set_codigo($_POST['codigo']);
                $o->set_nombre($_POST['nombre']);
                $o->set_marca($_POST['marca']);
                $o->set_stock_total($_POST['stock_total']);
                $o->set_stock_minimo($_POST['stock_minimo']);                
                $o->set_precio($_POST['precio']);
                $o->set_presentacion_id($_POST['presentacion_id']);
                if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
                    $imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; 
                    $imagen_ruta = 'otros/img/insumos/' . $imagen_nombre; 
                    move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); 
                    $o->set_imagen($imagen_ruta); 
                } else {
                    $o->set_imagen('otros/img/insumos/default.png'); 
                }
                if($accion == 'incluir'){
                    echo json_encode($o->incluir());
                } elseif($accion == 'modificar'){
                    echo json_encode($o->modificar()); 
                }
            }
        }
        if($accion=='obtenerNotificaciones'){
            echo json_encode($o->obtenerInsumosNotificacion());
        }        
        elseif($accion=='cargarOpciones'){
            $presentaciones = $o->obtenerPresentaciones();
            echo json_encode([
                'presentaciones' => $presentaciones
            ]);
        }
        else{         
            if($accion=='incluir2'){
                $o->set_descripcion($_POST['descripcion']); 
                if($accion == 'incluir2'){
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
        elseif($accion=='reporte_insumos'){
            $o->set_codigo($_POST['codigo']);
            $o->set_nombre($_POST['nombre']);
            $o->set_marca($_POST['marca']);
            $o->set_stock_total($_POST['stock_total']);
            $o->set_stock_minimo($_POST['stock_minimo']);
            $o->set_precio($_POST['precio']);
            $o->set_presentacion($_POST['presentacion']);
            $o->reporte_insumos();
        }

        exit;
    }	  
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "ERROR 404"; 
}
?>