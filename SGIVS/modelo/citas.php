<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');

class citas extends datos {
    use validaciones;
    
    // Properties
    private $id_cita;
    private $nombre_paciente;
    private $numero_contacto;
    private $id_medico;
    private $motivo_cita;
    private $fecha_cita;
    private $hora_cita;
    private $estado_cita;
    private $fecha_registro;
    private $observaciones;
    private $id_solicitud;

    // Getters and setters
    function set_id_cita($valor) {
        $this->id_cita = $valor;
    }
    function get_id_cita() {
        return $this->id_cita;
    }
    function set_nombre_paciente($valor) {
        $this->nombre_paciente = $valor;
    }
    function get_nombre_paciente() {
        return $this->nombre_paciente;
    }
    function set_numero_contacto($valor) {
        $this->numero_contacto = $valor;
    }
    function get_numero_contacto() {
        return $this->numero_contacto;
    }
    function set_id_medico($valor) {
        $this->id_medico = $valor;
    }
    function get_id_medico() {
        return $this->id_medico;
    }
    function set_motivo_cita($valor) {
        $this->motivo_cita = $valor;
    }
    function get_motivo_cita() {
        return $this->motivo_cita;
    }
    function set_fecha_cita($valor) {
        $this->fecha_cita = $valor;
    }
    function get_fecha_cita() {
        return $this->fecha_cita;
    }
    function set_hora_cita($valor) {
        $this->hora_cita = $valor;
    }
    function get_hora_cita() {
        return $this->hora_cita;
    }
    function set_estado_cita($valor) {
        $this->estado_cita = $valor;
    }
    function get_estado_cita() {
        return $this->estado_cita;
    }
    function set_observaciones($valor) {
        $this->observaciones = $valor;
    }
    function get_observaciones() {
        return $this->observaciones;
    }
    function set_id_solicitud($valor) {
        $this->id_solicitud = $valor;
    }
    function get_id_solicitud() {
        return $this->id_solicitud;
    }

    // Method to include a new appointment
    function incluir() {
        $r = array();
        if(!$this->existe($this->id_cita)) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $co->query("INSERT INTO citas(nombre_paciente, numero_contacto, id_medico, motivo_cita, fecha_cita, hora_cita, estado_cita, observaciones)
                    VALUES(
                        '$this->nombre_paciente',
                        '$this->numero_contacto',
                        '$this->id_medico',
                        '$this->motivo_cita',
                        '$this->fecha_cita',
                        '$this->hora_cita',
                        'pendiente',
                        '$this->observaciones'
                    )");
                $r['resultado'] = 'incluir';
                $r['mensaje'] = '¡Cita solicitada con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Cita ya registrada';
        }
        return $r;
    }

    // Method to modify an appointment
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if($this->existe($this->id_cita)) {
            try {
                $co->query("UPDATE citas SET 
                    nombre_paciente = '$this->nombre_paciente',
                    numero_contacto = '$this->numero_contacto',
                    id_medico = '$this->id_medico',
                    motivo_cita = '$this->motivo_cita',
                    fecha_cita = '$this->fecha_cita',
                    hora_cita = '$this->hora_cita',
                    estado_cita = '$this->estado_cita',
                    observaciones = '$this->observaciones'
                    WHERE id_cita = '$this->id_cita'
                ");
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Cita actualizada con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Cita no encontrada';
        }
        return $r;
    }

    // Method to delete an appointment
    function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if($this->existe($this->id_cita)) {
            try {
                $co->query("DELETE from citas where id_cita = '$this->id_cita'");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = '¡Cita eliminada con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'No existe la cita';
        }
        return $r;
    }

    // Method to consult appointments
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT c.*, m.nombre_medico 
                                   FROM citas c 
                                   LEFT JOIN medicos m ON c.id_medico = m.id_medico 
                                   ORDER BY c.fecha_cita DESC, c.hora_cita DESC");
            if($resultado->rowCount() > 0) {
                $respuesta = "";
                $n = 1;
                while($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $estadoClass = '';
                    switch($row['estado_cita']) {
                        case 'pendiente':
                            $estadoClass = 'bg-warning';
                            break;
                        case 'confirmada':
                            $estadoClass = 'bg-success';
                            break;
                        case 'cancelada':
                            $estadoClass = 'bg-danger';
                            break;
                    }
                    
                    $respuesta .= "<tr data-id='".$row['id_cita']."'>";
                    $respuesta .= "<td>$n</td>";
                    $respuesta .= "<td>".$row['nombre_paciente']."</td>";
                    $respuesta .= "<td>".$row['numero_contacto']."</td>";
                    $respuesta .= "<td data-medico-id='".$row['id_medico']."'>".$row['nombre_medico']."</td>";
                    $respuesta .= "<td>".$row['fecha_cita']."</td>";
                    $respuesta .= "<td>".$row['hora_cita']."</td>";
                    $respuesta .= "<td>".$row['motivo_cita']."</td>";
                    $respuesta .= "<td><span class='badge $estadoClass'>".$row['estado_cita']."</span></td>";
                    $respuesta .= "<td>".$row['observaciones']."</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar cita'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar cita'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron citas.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Private method to check if appointment exists
    private function existe($id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT * FROM citas WHERE id_cita='$id'");
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if($fila) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
    }

    // Method to get doctors list
    function obtenerMedicos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id_medico, nombre_medico FROM medicos WHERE activo = 1 ORDER BY nombre_medico");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return array();
        }
    }
}
?> 