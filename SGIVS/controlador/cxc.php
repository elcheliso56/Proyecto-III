<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina; // Mensaje de error si no se encuentra el modelo
    exit;
}  
require_once("modelo/".$pagina.".php");  // Incluye el archivo del modelo

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){ 
    // Comprueba si hay datos enviados por POST
    
    if(!empty($_POST)){
        
        // Escribe los datos de $_POST en un archivo de registro
        file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);

        $o = new cxc();   // Crea una nueva instancia de la clase cxc
        $accion = $_POST['accion']; // Obtiene la acción a realizar

        // Acción para consultar cuentas por cobrar
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para cargar opciones (pacientes)
        elseif($accion=='cargarOpciones'){
            $pacientes = $o->cargarPacientes();
            $cuentas = $o->cargarCuentas();
            echo json_encode([
                'pacientes' => $pacientes,
                'cuentas' => $cuentas
            ]);
            exit;
        }
        // Acción para consultar cuotas de una cuenta
        elseif($accion=='consultarCuotas'){
            $o->set_id($_POST['id']);
            echo json_encode($o->consultarCuotas($_POST['id']));
        }
        // Acción para consultar cuotas pendientes de una cuenta
        elseif($accion=='consultarCuotasPendientes'){
            echo json_encode($o->consultarCuotasPendientes($_POST['id']));
        }
        // Acción para registrar un pago
        elseif($accion=='registrarPago'){
            echo json_encode($o->registrarPago(
                $_POST['cuota_id'],
                $_POST['monto'],
                $_POST['fecha_pago'],
                $_POST['metodo_pago'],
                $_POST['referencia']
            ));
        }
        // Acción para procesar un abono
        elseif($accion=='procesarAbono'){
            $r = $o->procesarAbono(
                $_POST['id_cuenta'],
                $_POST['monto'],
                $_POST['fecha_pago'],
                $_POST['referencia']
            );
            echo json_encode($r);
        }
        // Acción para eliminar una cuenta por cobrar
        elseif($accion=='eliminar'){
            $o->set_id($_POST['id']); // Establece el id del registro
            echo json_encode($o->eliminar()); // Elimina la cuenta por cobrar y devuelve el resultado
        }
        else{		  
            // Acciones para incluir o modificar una cuenta por cobrar
            if($accion=='incluir' || $accion=='modificar'){
                try {
                    // Establece los atributos de la cuenta por cobrar
                    $o->set_paciente_id($_POST['paciente_id']);
                    $o->set_fecha_emision($_POST['fecha_emision']);
                    $o->set_fecha_vencimiento($_POST['fecha_vencimiento']);
                    $o->set_monto_total($_POST['monto_total']);
                    $o->set_monto_pendiente($_POST['monto_total']); // Al crear, el monto pendiente es igual al total
                    $o->set_descripcion($_POST['descripcion']);
                    $o->set_referencia($_POST['referencia']);
                    $o->set_numero_cuotas($_POST['numero_cuotas']);
                    $o->set_frecuencia_pago($_POST['frecuencia_pago']);
                    $o->set_monto_cuota($_POST['monto_total'] / $_POST['numero_cuotas']);
                    $o->set_cuenta_id($_POST['cuenta_id']); // Asegurarse de que se establezca el cuenta_id

                    if($accion == 'modificar'){
                        $o->set_id($_POST['id']);
                        $o->set_monto_pendiente($_POST['monto_pendiente']);
                        $o->set_estado($_POST['estado']);
                    }

                    // Ejecuta la acción de incluir o modificar
                    if($accion == 'incluir'){
                        echo json_encode($o->incluir()); // Incluye la cuenta por cobrar y devuelve el resultado
                    } elseif($accion == 'modificar'){
                        echo json_encode($o->modificar()); // Modifica la cuenta por cobrar y devuelve el resultado
                    }
                } catch (Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error al procesar la solicitud: ' . $e->getMessage()
                    ]);
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