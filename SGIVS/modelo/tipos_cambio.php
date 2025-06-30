<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');
require_once('modelo/traits/generador_ids.php');

class tipos_cambio extends datos {
    use validaciones, generador_ids;
    
    // Propiedades de la clase tipos_cambio
    private $id;
    private $moneda_origen;
    private $moneda_destino;
    private $tipo_cambio;
    private $fecha;
    private $usuario_id;

    // Getters y setters para las propiedades
    function set_id($valor){
        $this->id = $valor;
    }
    function get_id(){
        return $this->id;
    }
    function set_moneda_origen($valor){
        $this->moneda_origen = $valor;
    }
    function get_moneda_origen(){
        return $this->moneda_origen;
    }
    function set_moneda_destino($valor){
        $this->moneda_destino = $valor;
    }
    function get_moneda_destino(){
        return $this->moneda_destino;
    }
    function set_tipo_cambio($valor){
        $this->tipo_cambio = $valor;
    }
    function get_tipo_cambio(){
        return $this->tipo_cambio;
    }
    function set_fecha($valor){
        $this->fecha = $valor;
    }
    function get_fecha(){
        return $this->fecha;
    }
    function set_usuario_id($valor){
        $this->usuario_id = $valor;
    }
    function get_usuario_id(){
        return $this->usuario_id;
    }

    // Método para incluir un nuevo tipo de cambio
    function incluir(){
        $r = array();
        // Generar ID único antes de verificar si existe
        $this->id = $this->generarIdUnico('tipos_cambio', 'TC');
        // Verifica si el id ya existe (aunque ya generamos uno único, es una verificación adicional)        
        if(!$this->existe($this->id)){
            // Validar los datos antes de insertar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }
            // Verificar que no exista ya un tipo de cambio para la misma fecha y monedas
            if ($this->existeTipoCambioFecha()) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe un tipo de cambio para estas monedas en esta fecha';
                return $r;
            }
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // Inserta el nuevo registro en la base de datos                
                $co->query("INSERT INTO tipos_cambio(id, moneda_origen, moneda_destino, tipo_cambio, fecha, usuario_id)
                    VALUES(
                        '$this->id',
                        '$this->moneda_origen',
                        '$this->moneda_destino',
                        '$this->tipo_cambio',
                        '$this->fecha',
                        '$this->usuario_id'
                    )
                ");
                // Insertar el tipo de cambio inverso si no existe
                $stmt = $co->prepare("SELECT COUNT(*) FROM tipos_cambio WHERE moneda_origen = ? AND moneda_destino = ? AND fecha = ?");
                $stmt->execute([$this->moneda_destino, $this->moneda_origen, $this->fecha]);
                if ($stmt->fetchColumn() == 0) {
                    $id_inverso = $this->generarIdUnico('tipos_cambio', 'TC');
                    $tipo_cambio_inverso = 1 / floatval($this->tipo_cambio);
                    $co->prepare("INSERT INTO tipos_cambio (id, moneda_origen, moneda_destino, tipo_cambio, fecha, usuario_id) VALUES (?, ?, ?, ?, ?, ?)")
                        ->execute([$id_inverso, $this->moneda_destino, $this->moneda_origen, $tipo_cambio_inverso, $this->fecha, $this->usuario_id]);
                }
                $r['resultado'] = 'incluir';
                $r['mensaje'] =  '¡Tipo de cambio registrado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            } finally {
                $co = null; // Cierra la conexión después de la operación
            }
        }
        else{
            $r['resultado'] = 'incluir';
            $r['mensaje'] =  'Tipo de cambio ya registrado';
        }
        return $r;
    }

    // Método para modificar un tipo de cambio existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
    
        // Verifica si el tipo de cambio existe
        if ($this->existe($this->id)) {
            // Validar los datos antes de modificar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

            // Verificar que no exista ya un tipo de cambio para la misma fecha y monedas (excluyendo el actual)
            if ($this->existeTipoCambioFecha($this->id)) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe un tipo de cambio para estas monedas en esta fecha';
                return $r;
            }

            try {
                // Actualiza los datos del tipo de cambio
                $co->query("UPDATE tipos_cambio SET 
                    moneda_origen = '$this->moneda_origen',
                    moneda_destino = '$this->moneda_destino',
                    tipo_cambio = '$this->tipo_cambio',
                    fecha = '$this->fecha',
                    usuario_id = '$this->usuario_id'
                    WHERE id = '$this->id'
                ");
    
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Registro actualizado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            } finally {
                $co = null; // Cierra la conexión después de la operación
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Tipo de cambio no modificado';
        }
    
        return $r;
    }
    
    // Método para eliminar un tipo de cambio    
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();        
        // Verifica si el registro existe
        if($this->existe($this->id)){
            try {                
                // Elimina el registro del tipo de cambio
                $co->query("DELETE from tipos_cambio 
                    where
                    id = '$this->id'
                    ");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] =  '¡Registro eliminado con éxito!';

            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar este tipo de cambio porque tiene transacciones asociadas';
                } else {
                    $r['mensaje'] = $e->getMessage();
                }
            } finally {
                $co = null; // Cierra la conexión después de la operación
            }
        }
        else{
            $r['resultado'] = 'eliminar';
            $r['mensaje'] =  'No existe el registro';
        }
        return $r;
    }

    // Método para consultar un tipo de cambio específico por ID
    function consultarPorId($id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id, moneda_origen, moneda_destino, tipo_cambio, fecha, usuario_id
                                    FROM tipos_cambio 
                                    WHERE id = '$id'");
            if ($resultado && $resultado->rowCount() > 0) {
                return $resultado->fetch(PDO::FETCH_ASSOC);
            }
            return null;
        } catch(Exception $e) {
            error_log("Error al consultar tipo de cambio por ID: " . $e->getMessage());
            return null;
        }
    }

    // Método para consultar tipos de cambio        
    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            // Realiza la consulta para obtener los tipos de cambio con información de monedas
            $resultado = $co->query("SELECT tc.id, tc.tipo_cambio, tc.fecha, tc.usuario_id,
                                    mo.codigo as moneda_origen_codigo, mo.nombre as moneda_origen_nombre, mo.simbolo as moneda_origen_simbolo,
                                    md.codigo as moneda_destino_codigo, md.nombre as moneda_destino_nombre, md.simbolo as moneda_destino_simbolo
                                    FROM tipos_cambio tc
                                    INNER JOIN monedas mo ON tc.moneda_origen = mo.id
                                    INNER JOIN monedas md ON tc.moneda_destino = md.id
                                    ORDER BY tc.fecha DESC, mo.codigo, md.codigo");
            if ($resultado->rowCount() > 0) {
                $respuesta = "";
                $n = 1;
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                    // Genera la respuesta en formato HTML
                    $respuesta .= "<tr data-id='".$row['id']."'>";
                    $respuesta .= "<td>$n</td>";
                    $respuesta .= "<td>".$row['moneda_origen_codigo']." (".$row['moneda_origen_simbolo'].")</td>";
                    $respuesta .= "<td>".$row['moneda_destino_codigo']." (".$row['moneda_destino_simbolo'].")</td>";
                    $respuesta .= "<td class='text-end'>".number_format($row['tipo_cambio'], 4)."</td>";
                    $respuesta .= "<td>".$row['fecha']."</td>";
                    $respuesta .= "<td class='text-center'>";
                    $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar tipo de cambio'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar tipo de cambio'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron tipos de cambio.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null; // Cierra la conexión después de la operación
        }
        return $r;
    }

    // Método para obtener el tipo de cambio actual entre dos monedas
    function obtenerTipoCambioActual($moneda_origen_id, $moneda_destino_id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT tipo_cambio, fecha 
                                    FROM tipos_cambio 
                                    WHERE moneda_origen = '$moneda_origen_id' 
                                    AND moneda_destino = '$moneda_destino_id'
                                    ORDER BY fecha DESC 
                                    LIMIT 1");
            if ($resultado && $resultado->rowCount() > 0) {
                return $resultado->fetch(PDO::FETCH_ASSOC);
            }
            return null;
        } catch(Exception $e) {
            error_log("Error al obtener tipo de cambio: " . $e->getMessage());
            return null;
        }
    }

    // Método para convertir un monto entre monedas
    function convertirMonto($monto, $moneda_origen_id, $moneda_destino_id, $fecha = null) {
        // Si es la misma moneda, retornar el mismo valor
        if ($moneda_origen_id == $moneda_destino_id) {
            return $monto;
        }

        // Si no se especifica fecha, usar la fecha actual
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT tipo_cambio 
                                    FROM tipos_cambio 
                                    WHERE moneda_origen = '$moneda_origen_id' 
                                    AND moneda_destino = '$moneda_destino_id'
                                    AND fecha = '$fecha'
                                    LIMIT 1");
            if ($resultado && $resultado->rowCount() > 0) {
                $row = $resultado->fetch(PDO::FETCH_ASSOC);
                return $monto * $row['tipo_cambio'];
            }
            return null; // No hay tipo de cambio disponible
        } catch(Exception $e) {
            error_log("Error al convertir monto: " . $e->getMessage());
            return null;
        }
    }

    // Método para obtener tipos de cambio por fecha
    function obtenerTiposCambioPorFecha($fecha) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT tc.id, tc.tipo_cambio,
                                    mo.codigo as moneda_origen_codigo, mo.nombre as moneda_origen_nombre,
                                    md.codigo as moneda_destino_codigo, md.nombre as moneda_destino_nombre
                                    FROM tipos_cambio tc
                                    INNER JOIN monedas mo ON tc.moneda_origen = mo.id
                                    INNER JOIN monedas md ON tc.moneda_destino = md.id
                                    WHERE tc.fecha = '$fecha'
                                    ORDER BY mo.codigo, md.codigo");
            if ($resultado) {
                return $resultado->fetchAll(PDO::FETCH_ASSOC);
            }
            return array();
        } catch(Exception $e) {
            error_log("Error al obtener tipos de cambio por fecha: " . $e->getMessage());
            return array();
        }
    }

    // Método privado para verificar si un tipo de cambio existe    
    private function existe($id){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{        
            $resultado = $co->query("Select * from tipos_cambio where id='$id'");    
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if($fila){
                return true;   
            }
            else{    
                return false;;
            }    
        }catch(Exception $e){
            return false;
        }
    }

    // Método para verificar si existe un tipo de cambio para la misma fecha y monedas
    private function existeTipoCambioFecha($excluir_id = null) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $sql = "SELECT COUNT(*) as total FROM tipos_cambio 
                    WHERE moneda_origen = '$this->moneda_origen' 
                    AND moneda_destino = '$this->moneda_destino' 
                    AND fecha = '$this->fecha'";
            if ($excluir_id) {
                $sql .= " AND id != '$excluir_id'";
            }
            $resultado = $co->query($sql);
            $row = $resultado->fetch(PDO::FETCH_ASSOC);
            return $row['total'] > 0;
        } catch(Exception $e) {
            return false;
        }
    }

    // Método para validar los datos del tipo de cambio
    private function validarDatos() {
        $r = array();
        $r['valido'] = true;
        $r['mensaje'] = '';

        // Validar moneda origen (siempre será la moneda principal)
        if (empty($this->moneda_origen)) {
            $r['valido'] = false;
            $r['mensaje'] = 'La moneda origen es obligatoria';
            return $r;
        }

        // Validar moneda destino
        if (empty($this->moneda_destino)) {
            $r['valido'] = false;
            $r['mensaje'] = 'La moneda destino es obligatoria';
            return $r;
        }

        // Validar tipo de cambio
        if (empty($this->tipo_cambio) || !is_numeric($this->tipo_cambio) || $this->tipo_cambio <= 0) {
            $r['valido'] = false;
            $r['mensaje'] = 'El tipo de cambio debe ser un número mayor a 0';
            return $r;
        }

        // Validar fecha
        $hoy = date('Y-m-d');
        if (empty($this->fecha) || $this->fecha !== $hoy) {
            $r['valido'] = false;
            $r['mensaje'] = 'Solo se puede registrar la tasa de cambio del día actual (' . $hoy . ')';
            return $r;
        }

        // Validar formato de fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->fecha)) {
            $r['valido'] = false;
            $r['mensaje'] = 'El formato de fecha no es válido';
            return $r;
        }

        return $r;
    }
}
?> 