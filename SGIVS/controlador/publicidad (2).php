<?php
// controlador/publicidad.php

// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

// Incluye el archivo del modelo
require_once("modelo/".$pagina.".php");

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){
    // Verifica si se han enviado datos por POST
    if(!empty($_POST)){
        $o = new publicidad();
        $accion = $_POST['accion'];

        if($accion == 'incluir'){
            $o->set_nombre($_POST['nombre']);
            $o->set_apellido($_POST['apellido']);
            $o->set_telefono($_POST['telefono']);
            $o->set_motivo($_POST['motivo']);
            
            $respuesta = $o->incluir();
            echo json_encode($respuesta);
        }
        exit;
    }
    
    // Incluye la vista
    require_once("vista/".$pagina.".php");
}
else{
    echo "ERROR 404";
}
?>