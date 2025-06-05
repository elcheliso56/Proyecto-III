<?php
// controladores/calendario.php
// Controlador para el calendario de citas

// Asumimos que $pagina está definida en el ámbito global (viene de index.php)
// y que su valor es 'calendario' cuando este archivo es incluido.

// Aseguramos que el modelo exista usando $pagina
if (!is_file("modelo/" . $pagina . ".php")) { 
    echo "Falta definir el modelo para el " . $pagina . ".";
    exit;
}

// Incluimos el modelo usando $pagina
require_once("modelo/" . $pagina . ".php"); 

// Verificamos si la vista existe usando $pagina
if (is_file("vista/" . $pagina . ".php")) { 
    // Si hay una petición POST, procesamos la lógica (para llamadas AJAX)
    if (!empty($_POST)) {
        // La clase del modelo debe llamarse "ModeloCalendario" (nombre fijo de la clase).
        // No se instancia dinámicamente como $o = new $pagina(); porque el nombre de la clase es 'ModeloCalendario'.
        $o = new ModeloCalendario(); // Creamos una instancia del modelo de Calendario
        $accion = $_POST['accion']; // Obtenemos la acción a realizar

        switch ($accion) {
            case 'obtenerCitasConfirmadas':
                // Esta es la solicitud AJAX de FullCalendar para obtener los eventos
                $citas = $o->obtenerCitasConfirmadas();
                header('Content-Type: application/json'); // Fundamental para que el navegador sepa que es JSON
                if (isset($citas['resultado']) && $citas['resultado'] === 'error') {
                    echo json_encode([]); // Devuelve un array vacío si hay error para no romper FullCalendar
                    error_log("Error al obtener citas para FullCalendar: " . $citas['mensaje']);
                } else {
                    echo json_encode($citas); // Imprime el array de eventos de FullCalendar
                }
                exit(); // CRUCIAL: Termina la ejecución para evitar que se renderice la vista HTML
                break;

            default:
                echo json_encode(array('resultado' => 'error', 'mensaje' => 'Acción no válida.'));
                exit(); // Salir si la acción no es válida en una petición POST
                break;
        }
    }

    // Si no hay petición POST, o después de procesarla (aunque con exit() no llegaría aquí),
    // se incluye la vista principal del calendario. Esto ocurre en la carga inicial de la página.
    require_once("vista/" . $pagina . ".php");
} else {
    echo "ERROR 404. La vista para el " . $pagina . " no existe";
}
?>