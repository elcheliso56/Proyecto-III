<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  
require_once("modelo/".$pagina.".php");  // Incluye el archivo del modelo

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){ 
    // Comprueba si hay datos enviados por POST
    if(!empty($_POST)){
        $o = new bancos();   // Crea una nueva instancia de la clase bancos
        $accion = $_POST['accion']; // Obtiene la acción a realizar
        
        // Acción para obtener bancos en formato JSON para selects
        if ($accion == 'obtenerBancosSelect') {
            $bancos = $o->obtenerBancosSelect();
            echo json_encode($bancos);
            exit;
        }
        // Acción para consultar bancos
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para eliminar un banco
        elseif($accion=='eliminar'){
            $o->set_id($_POST['id']); // Establece el id del registro
            echo json_encode($o->eliminar()); // Elimina el banco y devuelve el resultado
        }
        else{		  
            // Acciones para incluir o modificar un banco
            if($accion=='incluir' || $accion=='modificar'){
                // Establece los atributos del banco
                $o->set_nombre($_POST['nombre']);
                $o->set_codigo_swift($_POST['codigo_swift'] ?? '');
                $o->set_codigo_local($_POST['codigo_local'] ?? '');
                $o->set_activo($_POST['activo']);

                // Manejo del logo
                $logo_path = '';
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                    $upload_dir = 'otros/img/bancos/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $file_extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
                    
                    if (in_array($file_extension, $allowed_extensions)) {
                        $file_name = uniqid() . '.' . $file_extension;
                        $upload_path = $upload_dir . $file_name;
                        
                        if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                            $logo_path = $upload_path;
                        } else {
                            // Error al mover el archivo
                            error_log("Error al mover archivo: " . $_FILES['logo']['tmp_name'] . " a " . $upload_path);
                        }
                    } else {
                        // Extensión no permitida
                        error_log("Extensión no permitida: " . $file_extension);
                    }
                } else if (isset($_FILES['logo'])) {
                    // Error en la subida del archivo
                    error_log("Error en subida de archivo: " . $_FILES['logo']['error']);
                }
                $o->set_logo($logo_path);

                if($accion == 'modificar'){
                    $o->set_id($_POST['id']);
                }
                // Ejecuta la acción de incluir o modificar
                if($accion == 'incluir'){
                    echo json_encode($o->incluir()); // Incluye el banco y devuelve el resultado
                } elseif($accion == 'modificar'){
                    echo json_encode($o->modificar()); // Modifica el banco y devuelve el resultado
                }
            }
        }
        exit; // Termina la ejecución del script 
    }	
    require_once("vista/".$pagina.".php"); // Incluye el archivo de vista
}
else{
    echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?> 