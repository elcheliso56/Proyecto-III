<?php
// modelos/calendario.php
// Modelo para el calendario de citas

// Incluir el archivo de conexión a la base de datos
// Mantengo la ruta 'modelo/datos.php' ya que me confirmaste que es correcta para tu estructura de archivos.
require_once('modelo/datos.php');

class ModeloCalendario {

    private $conexion; 

    public function __construct() {
        // Instancia de tu clase de conexión 'datos'
        $this->conexion = new datos(); 
    }

    /**
     * Obtiene todas las citas con estado 'Confirmada' desde la base de datos,
     * formateadas para ser usadas por FullCalendar.
     * @return array Array de eventos formateados o un array de error en caso de fallo.
     */
    public function obtenerCitasConfirmadas() {
        try {
            $pdo = $this->conexion->conecta(); 
            if (!$pdo) {
                // Si la conexión falla, se loguea el error y se devuelve un array de error.
                error_log("Fallo la conexión a la base de datos en ModeloCalendario.");
                return ['resultado' => 'error', 'mensaje' => 'Error al conectar con la base de datos.'];
            }

            // Consulta SQL para obtener las citas confirmadas.
            // *** ESTA ES LA CONSULTA CORRECTA, SIN 'AND eliminado = 0' ***
            $sql = "SELECT 
                        id, 
                        cedula_cliente, 
                        cedula_representante, 
                        nombre_cliente, 
                        apellido_cliente, 
                        telefono_cliente, 
                        motivo_cita, 
                        doctor_atendera, 
                        fecha_cita, 
                        hora_cita, 
                        estado_cita 
                    FROM 
                        citas 
                    WHERE 
                        estado_cita = 'Confirmada'"; 

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $eventos = [];
            foreach ($citas as $cita) {
                $eventos[] = [
                    'id' => $cita['id'],
                    'title' => $cita['nombre_cliente'] . ' ' . $cita['apellido_cliente'] . ' - ' . $cita['motivo_cita'],
                    'start' => $cita['fecha_cita'] . 'T' . $cita['hora_cita'],
                    'extendedProps' => [
                        'cliente_completo' => $cita['nombre_cliente'] . ' ' . $cita['apellido_cliente'],
                        'cedula_cliente' => $cita['cedula_cliente'],
                        'cedula_representante' => $cita['cedula_representante'],
                        'telefono_cliente' => $cita['telefono_cliente'],
                        'motivo_cita' => $cita['motivo_cita'],
                        'doctor_atendera' => $cita['doctor_atendera'],
                        'estado_cita' => $cita['estado_cita']
                    ],
                    'backgroundColor' => '#28a745', // Verde para confirmadas
                    'borderColor' => '#28a745'
                ];
            }
            
            return $eventos; 

        } catch (PDOException $e) {
            // Si ocurre un error de PDO (conexión o consulta), se registra y se devuelve un array de error.
            error_log("Error de PDO en ModeloCalendario::obtenerCitasConfirmadas: " . $e->getMessage());
            return ['resultado' => 'error', 'mensaje' => 'Error de base de datos. Por favor, intente más tarde.'];
        } catch (Exception $e) {
            // Captura cualquier otra excepción inesperada, la registra y devuelve un array de error.
            error_log("Error inesperado en ModeloCalendario::obtenerCitasConfirmadas: " . $e->getMessage());
            return ['resultado' => 'error', 'mensaje' => 'Ha ocurrido un error inesperado.'];
        }
    }
}
?>