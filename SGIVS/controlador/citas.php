<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  
require_once("modelo/".$pagina.".php");  

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){ 
    if(!empty($_POST)){
        $o = new citas();
        $accion = $_POST['accion'];

        // Acción para consultar citas
        if($accion=='consultar'){
            echo json_encode($o->consultar());
        }
        // Acción para cargar médicos
        elseif($accion=='cargarMedicos'){
            $medicos = $o->obtenerMedicos();
            echo json_encode(['medicos' => $medicos]);
            exit;
        }
        // Acción para eliminar una cita
        elseif($accion=='eliminar'){
            $o->set_id_cita($_POST['id_cita']);
            echo json_encode($o->eliminar());
        }
        else{		  
            // Acciones para incluir o modificar una cita
            if($accion=='incluir' || $accion=='modificar'){
                try {
                    // Establece los atributos de la cita
                    $o->set_nombre_paciente($_POST['nombre_paciente']);
                    $o->set_numero_contacto($_POST['numero_contacto']);
                    $o->set_id_medico($_POST['id_medico']);
                    $o->set_fecha_cita($_POST['fecha_cita']);
                    $o->set_hora_cita($_POST['hora_cita']);
                    $o->set_motivo_cita($_POST['motivo_cita']);
                    $o->set_observaciones($_POST['observaciones']);

                    if($accion == 'modificar'){
                        $o->set_id_cita($_POST['id_cita']);
                        $o->set_estado_cita($_POST['estado_cita']);
                    }

                    // Ejecuta la acción de incluir o modificar
                    if($accion == 'incluir'){
                        echo json_encode($o->incluir());
                    } elseif($accion == 'modificar'){
                        echo json_encode($o->modificar());
                    }
                } catch (Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error al procesar la solicitud: ' . $e->getMessage()
                    ]);
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