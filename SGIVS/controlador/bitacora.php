<?php  
if (!is_file("modelo/".$pagina.".php")){
    echo json_encode(array(
        'resultado' => 'error',
        'mensaje' => "Falta definir la clase ".$pagina
    )); 
    exit; 
}  

require_once("modelo/".$pagina.".php"); 

if(is_file("vista/".$pagina.".php")){ 
    if(!empty($_POST)){ 
        try {
            $o = new bitacora(); 
            $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
            
            if($accion == 'consultar'){
                $resultado = $o->consultar();
                echo json_encode($resultado);
            }
            elseif($accion == 'generar_reporte'){
                $resultado = $o->generar_reporte();
                echo json_encode($resultado);
            }
            else {
                echo json_encode(array(
                    'resultado' => 'error',
                    'mensaje' => 'Acción no válida'
                ));
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'resultado' => 'error',
                'mensaje' => 'Error: ' . $e->getMessage()
            ));
        }
        exit; 
    }      
    require_once("vista/".$pagina.".php"); 
}
else{
    echo json_encode(array(
        'resultado' => 'error',
        'mensaje' => 'ERROR 404: Vista no encontrada'
    )); 
}
?> 