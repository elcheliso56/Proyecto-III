<?php
// Aseguramos que el modelo exista
if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

// Incluimos el modelo
require_once("modelo/" . $pagina . ".php");

// Verificamos si la vista existe
if (is_file("vista/" . $pagina . ".php")) {
    // Si hay una petición POST, procesamos la lógica
    if (!empty($_POST)) {
        $o = new citas(); // Creamos una instancia del modelo de citas
        $accion = $_POST['accion']; // Obtenemos la acción a realizar

        switch ($accion) {
            case 'consultarCitasRegistradas':
                $criterio = isset($_POST['criterio_busqueda']) ? $_POST['criterio_busqueda'] : null;
                echo json_encode($o->consultarCitasRegistradas($criterio));
                break;

            case 'consultarSolicitudesCitasContacto':
                echo json_encode($o->consultarSolicitudesCitasContacto());
                break;

            case 'incluir':
                // Establecemos los datos de la cita desde el POST
                $o->set_cita_contacto_id(isset($_POST['cita_contacto_id']) && $_POST['cita_contacto_id'] !== '' ? $_POST['cita_contacto_id'] : null);
                $o->set_cedula_cliente($_POST['cedula_cliente']);
                $o->set_cedula_representante($_POST['cedula_representante']);
                $o->set_nombre_cliente($_POST['nombre_cliente']);
                $o->set_apellido_cliente($_POST['apellido_cliente']);
                $o->set_telefono_cliente($_POST['telefono_cliente']);
                $o->set_motivo_cita($_POST['motivo_cita']);
                $o->set_doctor_atendera($_POST['doctor_atendera']);
                $o->set_fecha_cita($_POST['fecha_cita']);
                $o->set_hora_cita($_POST['hora_cita']);
                // El estado se establecerá por defecto en el modelo

                try {
                    // ob_start() y ob_end_clean() son para evitar salida de datos inesperada antes del JSON
                    ob_start();
                    $respuesta = $o->incluir();
                    ob_end_clean();
                    echo json_encode($respuesta);
                } catch (Exception $e) {
                    echo json_encode(array(
                        'resultado' => 'error',
                        'mensaje' => 'Error al incluir la cita: ' . $e->getMessage()
                    ));
                }
                break;

            case 'modificar':
                // Establecemos el ID y los datos de la cita desde el POST
                $o->set_id($_POST['id']);
                $o->set_cedula_cliente($_POST['cedula_cliente']);
                $o->set_cedula_representante($_POST['cedula_representante']);
                $o->set_nombre_cliente($_POST['nombre_cliente']);
                $o->set_apellido_cliente($_POST['apellido_cliente']);
                $o->set_telefono_cliente($_POST['telefono_cliente']);
                $o->set_motivo_cita($_POST['motivo_cita']);
                $o->set_doctor_atendera($_POST['doctor_atendera']);
                $o->set_fecha_cita($_POST['fecha_cita']);
                $o->set_hora_cita($_POST['hora_cita']);

                try {
                    ob_start();
                    $respuesta = $o->modificar();
                    ob_end_clean();
                    echo json_encode($respuesta);
                } catch (Exception $e) {
                    echo json_encode(array(
                        'resultado' => 'error',
                        'mensaje' => 'Error al modificar la cita: ' . $e->getMessage()
                    ));
                }
                break;

            case 'cambiarEstado':
                // Establecemos el ID de la cita y el nuevo estado
                $o->set_id($_POST['id']);
                $o->set_estado_cita($_POST['estado_cita']);

                try {
                    ob_start();
                    $respuesta = $o->cambiarEstado();
                    ob_end_clean();
                    echo json_encode($respuesta);
                } catch (Exception $e) {
                    echo json_encode(array(
                        'resultado' => 'error',
                        'mensaje' => 'Error al cambiar el estado de la cita: ' . $e->getMessage()
                    ));
                }
                break;

            case 'eliminarSolicitudCitaContacto':
                // Establecemos el ID de la solicitud de contacto a eliminar lógicamente
                $o->set_id($_POST['id']);
                try {
                    ob_start();
                    $respuesta = $o->eliminarSolicitudCitaContacto();
                    ob_end_clean();
                    echo json_encode($respuesta);
                } catch (Exception $e) {
                    echo json_encode(array(
                        'resultado' => 'error',
                        'mensaje' => 'Error al eliminar la solicitud: ' . $e->getMessage()
                    ));
                }
                break;

            default:
                echo json_encode(array('resultado' => 'error', 'mensaje' => 'Acción no válida.'));
                break;
        }
        exit; // Es importante salir después de enviar la respuesta JSON
    }

    // Si no hay petición POST, o después de procesarla, se incluye la vista
    require_once("vista/" . $pagina . ".php");
} else {
    echo "ERROR 404. La vista " . $pagina . " no existe";
}
?>