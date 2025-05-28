<?php
require_once(BASE_PATH . "/config/google_calendar.php");
require_once(BASE_PATH . "/modelo/calendario.php");

if (!file_exists(BASE_PATH . "/modelo/calendario.php")) {
    die(json_encode(['error' => 'No se encontró el archivo del modelo']));
}

if (!file_exists(BASE_PATH . "/vista/calendario.php")) {
    die(json_encode(['error' => 'No se encontró el archivo de la vista']));
}

$modelo = new Calendario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['accion'])) {
        die(json_encode(['error' => 'No se especificó una acción']));
    }

    $accion = $_POST['accion'];
    $respuesta = [];

    switch ($accion) {
        case 'consultar':
            $respuesta = $modelo->consultar();
            break;

        case 'incluir':
            if (!isset($_POST['titulo']) || !isset($_POST['fecha_inicio']) || !isset($_POST['fecha_fin'])) {
                $respuesta = ['error' => 'Faltan datos requeridos'];
                break;
            }
            $respuesta = $modelo->incluir($_POST);
            break;

        case 'modificar':
            if (!isset($_POST['id']) || !isset($_POST['titulo']) || !isset($_POST['fecha_inicio']) || !isset($_POST['fecha_fin'])) {
                $respuesta = ['error' => 'Faltan datos requeridos'];
                break;
            }
            $respuesta = $modelo->modificar($_POST);
            break;

        case 'eliminar':
            if (!isset($_POST['id'])) {
                $respuesta = ['error' => 'No se especificó el ID del evento'];
                break;
            }
            $respuesta = $modelo->eliminar($_POST['id']);
            break;

        case 'sincronizar':
            if (!isGoogleAuthenticated()) {
                $respuesta = ['error' => 'No está autenticado con Google Calendar'];
                break;
            }
            
            try {
                $service = getCalendarService();
                $eventos = $modelo->consultar();
                
                foreach ($eventos as $evento) {
                    if (empty($evento['google_event_id'])) {
                        // Crear evento en Google Calendar
                        $googleEvent = new Google\Service\Calendar\Event([
                            'summary' => $evento['titulo'],
                            'description' => $evento['descripcion'],
                            'start' => ['dateTime' => $evento['fecha_inicio']],
                            'end' => ['dateTime' => $evento['fecha_fin']],
                            'colorId' => $evento['color']
                        ]);
                        
                        $createdEvent = $service->events->insert('primary', $googleEvent);
                        $modelo->actualizarGoogleEventId($evento['id'], $createdEvent->getId());
                    }
                }
                
                $respuesta = ['success' => true, 'message' => 'Sincronización completada'];
            } catch (Exception $e) {
                $respuesta = ['error' => 'Error al sincronizar con Google Calendar: ' . $e->getMessage()];
            }
            break;

        default:
            $respuesta = ['error' => 'Acción no válida'];
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit;
}

// Si no es POST, mostrar la vista
require_once(BASE_PATH . "/vista/calendario.php");
?> 