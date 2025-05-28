<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');
require_once(__DIR__ . '/../config/google_calendar.php');

class calendario extends datos {
    use validaciones;
    
    private $id;
    private $titulo;
    private $descripcion;
    private $fecha_inicio;
    private $fecha_fin;
    private $color;
    private $google_event_id;
    private $usuario_id;

    // Getters y Setters
    function set_id($valor) { $this->id = $valor; }
    function get_id() { return $this->id; }
    
    function set_titulo($valor) { $this->titulo = $valor; }
    function get_titulo() { return $this->titulo; }
    
    function set_descripcion($valor) { $this->descripcion = $valor; }
    function get_descripcion() { return $this->descripcion; }
    
    function set_fecha_inicio($valor) { $this->fecha_inicio = $valor; }
    function get_fecha_inicio() { return $this->fecha_inicio; }
    
    function set_fecha_fin($valor) { $this->fecha_fin = $valor; }
    function get_fecha_fin() { return $this->fecha_fin; }
    
    function set_color($valor) { $this->color = $valor; }
    function get_color() { return $this->color; }
    
    function set_google_event_id($valor) { $this->google_event_id = $valor; }
    function get_google_event_id() { return $this->google_event_id; }
    
    function set_usuario_id($valor) { $this->usuario_id = $valor; }
    function get_usuario_id() { return $this->usuario_id; }

    private function sincronizarConGoogle($accion) {
        try {
            $service = getCalendarService();
            $event = new Google\Service\Calendar\Event([
                'summary' => $this->titulo,
                'description' => $this->descripcion,
                'start' => ['dateTime' => $this->fecha_inicio],
                'end' => ['dateTime' => $this->fecha_fin],
                'colorId' => $this->getGoogleColorId($this->color)
            ]);

            if ($accion == 'incluir') {
                $event = $service->events->insert('primary', $event);
                return $event->getId();
            } else if ($accion == 'modificar' && $this->google_event_id) {
                $event = $service->events->update('primary', $this->google_event_id, $event);
                return $event->getId();
            } else if ($accion == 'eliminar' && $this->google_event_id) {
                $service->events->delete('primary', $this->google_event_id);
                return true;
            }
        } catch (Exception $e) {
            error_log("Error en sincronización con Google Calendar: " . $e->getMessage());
            return false;
        }
    }

    private function getGoogleColorId($color) {
        $colorMap = [
            '#3788d8' => '1', // Azul
            '#28a745' => '2', // Verde
            '#dc3545' => '3', // Rojo
            '#ffc107' => '4', // Amarillo
            '#17a2b8' => '5', // Cyan
            '#6f42c1' => '6', // Púrpura
            '#fd7e14' => '7', // Naranja
            '#20c997' => '8', // Verde agua
            '#e83e8c' => '9', // Rosa
            '#6c757d' => '10' // Gris
        ];
        return $colorMap[$color] ?? '1';
    }

    function incluir() {
        $r = array();
        if(!$this->existe($this->id)) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // Sincronizar con Google Calendar
                $google_event_id = $this->sincronizarConGoogle('incluir');
                
                $co->query("INSERT INTO eventos_calendario(titulo, descripcion, fecha_inicio, fecha_fin, color, google_event_id, usuario_id)
                    VALUES(
                        '$this->titulo',
                        '$this->descripcion',
                        '$this->fecha_inicio',
                        '$this->fecha_fin',
                        '$this->color',
                        '$google_event_id',
                        '$this->usuario_id'
                    )");
                $r['resultado'] = 'incluir';
                $r['mensaje'] = '¡Evento registrado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Evento ya registrado';
        }
        return $r;
    }

    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if($this->existe($this->id)) {
            try {
                // Sincronizar con Google Calendar
                $google_event_id = $this->sincronizarConGoogle('modificar');
                
                $co->query("UPDATE eventos_calendario SET 
                    titulo = '$this->titulo',
                    descripcion = '$this->descripcion',
                    fecha_inicio = '$this->fecha_inicio',
                    fecha_fin = '$this->fecha_fin',
                    color = '$this->color',
                    google_event_id = '$google_event_id'
                    WHERE id = '$this->id'");
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Evento actualizado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Evento no encontrado';
        }
        return $r;
    }

    function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if($this->existe($this->id)) {
            try {
                // Sincronizar con Google Calendar
                $this->sincronizarConGoogle('eliminar');
                
                $co->query("DELETE FROM eventos_calendario WHERE id = '$this->id'");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = '¡Evento eliminado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Evento no encontrado';
        }
        return $r;
    }

    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT * FROM eventos_calendario WHERE usuario_id = '$this->usuario_id' ORDER BY fecha_inicio DESC");
            
            if($resultado->rowCount() > 0) {
                $respuesta = "";
                $n = 1;
                while($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $respuesta .= "<tr data-id='".$row['id']."'>";
                    $respuesta .= "<td>$n</td>";
                    $respuesta .= "<td>".$row['titulo']."</td>";
                    $respuesta .= "<td>".$row['descripcion']."</td>";
                    $respuesta .= "<td>".$row['fecha_inicio']."</td>";
                    $respuesta .= "<td>".$row['fecha_fin']."</td>";
                    $respuesta .= "<td style='background-color: ".$row['color'].";'></td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar evento'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar evento'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron eventos.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    private function existe($id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT * FROM eventos_calendario WHERE id='$id'");
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            return $fila ? true : false;
        } catch(Exception $e) {
            return false;
        }
    }

    // Método para sincronizar con Google Calendar
    function sincronizarGoogleCalendar() {
        $r = array();
        try {
            $client = getGoogleClient();
            $service = getCalendarService($client);

            // Obtener eventos de la base de datos
            $co = $this->conecta();
            $resultado = $co->query("SELECT * FROM eventos_calendario WHERE google_event_id IS NULL");
            
            while ($evento = $resultado->fetch(PDO::FETCH_ASSOC)) {
                // Crear evento en Google Calendar
                $googleEvent = new Google\Service\Calendar\Event(array(
                    'summary' => $evento['titulo'],
                    'description' => $evento['descripcion'],
                    'start' => array(
                        'dateTime' => $evento['fecha_inicio'],
                        'timeZone' => 'America/Caracas',
                    ),
                    'end' => array(
                        'dateTime' => $evento['fecha_fin'],
                        'timeZone' => 'America/Caracas',
                    ),
                    'colorId' => $this->getColorId($evento['color'])
                ));

                $calendarId = 'primary';
                $event = $service->events->insert($calendarId, $googleEvent);

                // Actualizar el ID de Google Calendar en la base de datos
                $co->query("UPDATE eventos_calendario SET google_event_id = '".$event->getId()."' WHERE id = '".$evento['id']."'");
            }

            $r['resultado'] = 'sincronizar';
            $r['mensaje'] = 'Sincronización completada con éxito';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Método auxiliar para convertir colores a IDs de Google Calendar
    private function getColorId($color) {
        $colorMap = array(
            '#3788d8' => '1', // Azul
            '#28a745' => '2', // Verde
            '#dc3545' => '3', // Rojo
            '#ffc107' => '4', // Amarillo
            '#17a2b8' => '5', // Cyan
            '#6f42c1' => '6', // Púrpura
            '#fd7e14' => '7', // Naranja
            '#20c997' => '8', // Verde agua
            '#e83e8c' => '9', // Rosa
            '#6c757d' => '10' // Gris
        );

        return isset($colorMap[$color]) ? $colorMap[$color] : '1';
    }

    public function actualizarGoogleEventId($id, $google_event_id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("UPDATE eventos_calendario SET google_event_id = ? WHERE id = ?");
            $stmt->execute([$google_event_id, $id]);
            return true;
        } catch (Exception $e) {
            error_log("Error al actualizar google_event_id: " . $e->getMessage());
            return false;
        }
    }
}
?> 