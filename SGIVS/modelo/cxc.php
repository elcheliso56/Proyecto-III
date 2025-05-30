<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');

class cxc extends datos {
    use validaciones;
    
    // Propiedades de la clase cxc
    private $id;
    private $paciente_id;
    private $fecha_emision;
    private $fecha_vencimiento;
    private $monto_total;
    private $monto_pendiente;
    private $estado;
    private $descripcion;
    private $referencia;
    private $numero_cuotas;
    private $monto_cuota;
    private $frecuencia_pago; // mensual, quincenal, semanal, etc.
    private $cuenta_id;
    private $mensaje;

    //getters y setters para las propiedades
    function set_id($valor){
        $this->id = $valor;
    }
    function get_id(){
        return $this->id;
    }
    function set_paciente_id($valor){
        $this->paciente_id = $valor;
    }
    function get_paciente_id(){
        return $this->paciente_id;
    }
    function set_fecha_emision($valor){
        $this->fecha_emision = $valor;
    }
    function get_fecha_emision(){
        return $this->fecha_emision;
    }
    function set_fecha_vencimiento($valor){
        $this->fecha_vencimiento = $valor;
    }
    function get_fecha_vencimiento(){
        return $this->fecha_vencimiento;
    }
    function set_monto_total($valor){
        $this->monto_total = $valor;
    }
    function get_monto_total(){
        return $this->monto_total;
    }
    function set_monto_pendiente($valor){
        $this->monto_pendiente = $valor;
    }
    function get_monto_pendiente(){
        return $this->monto_pendiente;
    }
    function set_estado($valor){
        $this->estado = $valor;
    }
    function get_estado(){
        return $this->estado;
    }
    function set_descripcion($valor){
        $this->descripcion = $valor;
    }
    function get_descripcion(){
        return $this->descripcion;
    }
    function set_referencia($valor){
        $this->referencia = $valor;
    }
    function get_referencia(){
        return $this->referencia;
    }
    function set_numero_cuotas($valor){
        $this->numero_cuotas = $valor;
    }
    function get_numero_cuotas(){
        return $this->numero_cuotas;
    }
    function set_monto_cuota($valor){
        $this->monto_cuota = $valor;
    }
    function get_monto_cuota(){
        return $this->monto_cuota;
    }
    function set_frecuencia_pago($valor){
        $this->frecuencia_pago = $valor;
    }
    function get_frecuencia_pago(){
        return $this->frecuencia_pago;
    }
    function set_cuenta_id($valor){
        $this->cuenta_id = $valor;
    }
    function get_cuenta_id(){
        return $this->cuenta_id;
    }

    // Método para validar los datos de la cuenta por cobrar
    private function validarDatos() {
        if(empty($this->paciente_id)) {
            $this->mensaje = "Debe seleccionar un paciente";
            return false;
        }
        if(empty($this->cuenta_id)) {
            $this->mensaje = "Debe seleccionar una cuenta";
            return false;
        }
        if(empty($this->fecha_emision)) {
            $this->mensaje = "Debe especificar la fecha de emisión";
            return false;
        }
        if(empty($this->fecha_vencimiento)) {
            $this->mensaje = "Debe especificar la fecha de vencimiento";
            return false;
        }
        if(empty($this->monto_total) || $this->monto_total <= 0) {
            $this->mensaje = "Debe especificar un monto total válido";
            return false;
        }
        if(empty($this->numero_cuotas) || $this->numero_cuotas <= 0) {
            $this->mensaje = "Debe especificar un número de cuotas válido";
            return false;
        }
        if(empty($this->frecuencia_pago)) {
            $this->mensaje = "Debe especificar la frecuencia de pago";
            return false;
        }
        return true;
    }

    // Método para incluir un nuevo registro de cuenta por cobrar
    public function incluir() {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if(!$this->validarDatos()) {
                return [
                    'resultado' => 'error',
                    'mensaje' => $this->mensaje
                ];
            }

            // Verificar si el paciente existe
            if (!$this->pacienteExiste($this->paciente_id)) {
                return [
                    'resultado' => 'error',
                    'mensaje' => 'El paciente seleccionado no existe'
                ];
            }

            // Iniciar transacción
            $co->beginTransaction();

            $sql = "INSERT INTO cuentas_por_cobrar (
                paciente_id, 
                cuenta_id,
                fecha_emision, 
                fecha_vencimiento, 
                monto_total, 
                monto_pendiente, 
                descripcion, 
                referencia, 
                numero_cuotas, 
                monto_cuota, 
                frecuencia_pago, 
                estado
            ) VALUES (
                :paciente_id,
                :cuenta_id,
                :fecha_emision,
                :fecha_vencimiento,
                :monto_total,
                :monto_pendiente,
                :descripcion,
                :referencia,
                :numero_cuotas,
                :monto_cuota,
                :frecuencia_pago,
                'pendiente'
            )";

            $stmt = $co->prepare($sql);
            $stmt->bindParam(':paciente_id', $this->paciente_id);
            $stmt->bindParam(':cuenta_id', $this->cuenta_id);
            $stmt->bindParam(':fecha_emision', $this->fecha_emision);
            $stmt->bindParam(':fecha_vencimiento', $this->fecha_vencimiento);
            $stmt->bindParam(':monto_total', $this->monto_total);
            $stmt->bindParam(':monto_pendiente', $this->monto_total); // Al crear, el monto pendiente es igual al total
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':referencia', $this->referencia);
            $stmt->bindParam(':numero_cuotas', $this->numero_cuotas);
            $stmt->bindParam(':monto_cuota', $this->monto_cuota);
            $stmt->bindParam(':frecuencia_pago', $this->frecuencia_pago);

            $stmt->execute();
            
            // Obtener el ID de la cuenta recién creada
            $cuenta_id = $co->lastInsertId();
            
            // Generar las cuotas
            $this->generarCuotas($co, $cuenta_id);

            // Confirmar transacción
            $co->commit();

            return [
                'resultado' => 'ok',
                'mensaje' => 'Cuenta por cobrar registrada exitosamente'
            ];
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            $co->rollBack();
            return [
                'resultado' => 'error',
                'mensaje' => 'Error al registrar la cuenta por cobrar: ' . $e->getMessage()
            ];
        }
    }

    // Método para generar las cuotas de pago
    private function generarCuotas($co, $cuenta_id) {
        $fecha_actual = new DateTime($this->fecha_emision);
        $monto_cuota = $this->monto_total / $this->numero_cuotas;

        for ($i = 1; $i <= $this->numero_cuotas; $i++) {
            // Calcula la fecha de vencimiento de la cuota según la frecuencia
            switch ($this->frecuencia_pago) {
                case 'semanal':
                    $fecha_actual->modify('+1 week');
                    break;
                case 'quincenal':
                    $fecha_actual->modify('+15 days');
                    break;
                case 'mensual':
                default:
                    $fecha_actual->modify('+1 month');
                    break;
            }

            $fecha_vencimiento = $fecha_actual->format('Y-m-d');
            
            // Inserta la cuota
            $co->query("INSERT INTO cuotas_pago (
                cuenta_por_cobrar_id, numero_cuota, monto, fecha_vencimiento,
                estado, fecha_creacion
            ) VALUES (
                '$cuenta_id',
                '$i',
                '$monto_cuota',
                '$fecha_vencimiento',
                'pendiente',
                NOW()
            )");
        }
    }

    // Método para consultar las cuotas de una cuenta
    function consultarCuotas($cuenta_id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT cp.*, 
                                    CASE 
                                        WHEN cp.fecha_vencimiento < CURDATE() AND cp.estado = 'pendiente' 
                                        THEN 'vencida'
                                        ELSE cp.estado 
                                    END as estado_actual
                                    FROM cuotas_pago cp
                                    WHERE cp.cuenta_por_cobrar_id = '$cuenta_id'
                                    ORDER BY cp.numero_cuota");
            
            if ($resultado->rowCount() > 0) {
                $respuesta = "";
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>".$row['numero_cuota']."</td>";
                    $respuesta .= "<td>".number_format($row['monto'], 2)." USD</td>";
                    $respuesta .= "<td>".$row['fecha_vencimiento']."</td>";
                    $respuesta .= "<td>".$row['estado_actual']."</td>";
                    if ($row['estado'] == 'pendiente') {
                        $respuesta .= "<td>
                            <button type='button' class='btn-sm btn-success' 
                                    onclick='registrarPago(".$row['id'].")' 
                                    title='Registrar pago'>
                                <i class='bi bi-cash'></i>
                            </button>
                        </td>";
                    } else {
                        $respuesta .= "<td>
                            <button type='button' class='btn-sm btn-info' 
                                    onclick='verDetallePago(".$row['id'].")' 
                                    title='Ver detalle de pago'>
                                <i class='bi bi-eye'></i>
                            </button>
                        </td>";
                    }
                    $respuesta .= "</tr>";
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron cuotas para esta cuenta.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Método para registrar un pago de cuota
    function registrarPago($cuota_id, $monto, $fecha_pago, $metodo_pago, $referencia) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Inicia una transacción
            $co->beginTransaction();

            // Registra el pago
            $co->query("INSERT INTO pagos_cuotas (
                cuota_id, monto, fecha_pago, metodo_pago, referencia
            ) VALUES (
                '$cuota_id',
                '$monto',
                '$fecha_pago',
                '$metodo_pago',
                '$referencia'
            )");

            // Actualiza el estado de la cuota
            $co->query("UPDATE cuotas_pago SET 
                estado = 'pagado',
                fecha_pago = '$fecha_pago'
                WHERE id = '$cuota_id'");

            // Actualiza el monto pendiente de la cuenta
            $co->query("UPDATE cuentas_por_cobrar cxc
                SET monto_pendiente = monto_pendiente - '$monto',
                    estado = CASE 
                        WHEN monto_pendiente - '$monto' <= 0 THEN 'pagado'
                        WHEN monto_pendiente - '$monto' < monto_total THEN 'parcial'
                        ELSE estado
                    END
                WHERE id = (SELECT cuenta_id FROM cuotas_pago WHERE id = '$cuota_id')");

            // Confirma la transacción
            $co->commit();

            $r['resultado'] = 'pago';
            $r['mensaje'] = '¡Pago registrado con éxito!';
        } catch(Exception $e) {
            // Si hay error, revierte la transacción
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Método para modificar un registro de cuenta por cobrar existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
    
        // Verifica si la cuenta por cobrar existe
        if ($this->existe($this->id)) {
            // Validar los datos antes de modificar
            if (!$this->validarDatos()) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $this->mensaje;
                return $r;
            }

            try {
                // Actualiza los datos de la cuenta por cobrar
                $co->query("UPDATE cuentas_por_cobrar SET 
                    paciente_id = '$this->paciente_id',
                    fecha_emision = '$this->fecha_emision',
                    fecha_vencimiento = '$this->fecha_vencimiento',
                    monto_total = '$this->monto_total',
                    monto_pendiente = '$this->monto_pendiente',
                    estado = '$this->estado',
                    descripcion = '$this->descripcion',
                    referencia = '$this->referencia'
                    WHERE id = '$this->id'
                ");
    
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Registro actualizado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Cuenta por cobrar no modificada';
        }
    
        return $r;
    }
    
    // Método para eliminar una cuenta por cobrar    
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();        
        // Verifica si el registro existe
        if($this->existe($this->id)){
            try {                
                // Elimina el registro de la cuenta por cobrar
                $co->query("DELETE from cuentas_por_cobrar 
                    where
                    id = '$this->id'
                    ");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] =  '¡Registro eliminado con éxito!';

            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar este registro porque tiene pagos asociados';
                } else {
                    $r['mensaje'] = $e->getMessage();
                }
            }
        }
        else{
            $r['resultado'] = 'eliminar';
            $r['mensaje'] =  'No existe el registro';
        }
        return $r;
    }

    // Método para consultar cuentas por cobrar        
    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            // Realiza la consulta para obtener las cuentas por cobrar con información del paciente
            $resultado = $co->query("SELECT cxc.id, cxc.paciente_id, cxc.fecha_emision, cxc.fecha_vencimiento, 
                                    cxc.monto_total, cxc.monto_pendiente, cxc.estado, cxc.descripcion, cxc.referencia,
                                    pacientes.nombre as nombre_paciente, pacientes.apellido as apellido_paciente
                                    FROM cuentas_por_cobrar cxc
                                    LEFT JOIN pacientes ON cxc.paciente_id = pacientes.id_paciente
                                    ORDER BY cxc.fecha_emision DESC");
            if ($resultado->rowCount() > 0) {
                $respuesta = "";
                $n = 1;
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                    // Genera la respuesta en formato HTML
                    $respuesta .= "<tr data-id='".$row['id']."'>";
                    $respuesta .= "<td>$n</td>";
                    $respuesta .= "<td data-paciente-id='".$row['paciente_id']."'>".$row['nombre_paciente']." ".$row['apellido_paciente']."</td>";
                    $respuesta .= "<td>".number_format($row['monto_total'], 2)." USD</td>";
                    $respuesta .= "<td>".number_format($row['monto_pendiente'], 2)." USD</td>";
                    $respuesta .= "<td>".$row['fecha_emision']."</td>";
                    $respuesta .= "<td>".$row['fecha_vencimiento']."</td>";
                    $respuesta .= "<td>".$row['estado']."</td>";
                    $respuesta .= "<td>".$row['referencia']."</td>";
                    $respuesta .= "<td>";
                        // Botón de modificar comentado
                        // $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar cuenta'><i class='bi bi-arrow-repeat'></i></button><br/>";
                        $respuesta .= "<button type='button' class='btn-sm btn-info w-50 small-width mb-1' onclick='pone(this,2)' title='Abonar cuenta'><i class='bi bi-cash'></i></button><br/>";
                        // Botón de eliminar comentado
                        // $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar cuenta'><i class='bi bi-trash'></i></button><br/>";
                        $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron cuentas por cobrar.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Método privado para verificar si una cuenta por cobrar existe    
    private function existe($id){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{        
            $resultado = $co->query("Select * from cuentas_por_cobrar where id='$id'");    
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

    // Método para obtener los pacientes activos
    function obtenerPacientes() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id_paciente as id, nombre, apellido 
                                    FROM pacientes 
                                    ORDER BY nombre, apellido");
            if ($resultado) {
                $pacientes = $resultado->fetchAll(PDO::FETCH_ASSOC);
                return $pacientes;
            }
            return array();
        } catch(Exception $e) {
            error_log("Error al obtener pacientes: " . $e->getMessage());
            return array();
        }
    }

    // Método para verificar si el paciente existe
    private function pacienteExiste($paciente_id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT id_paciente FROM pacientes WHERE id_paciente = ?");
            $stmt->execute([$paciente_id]);
            return $stmt->rowCount() > 0;
        } catch(Exception $e) {
            return false;
        }
    }

    public function cargarPacientes() {
        $co = $this->conecta();
        $sql = "SELECT id_paciente as id, nombre, apellido FROM pacientes ORDER BY nombre, apellido";
        $resultado = $co->query($sql);
        $pacientes = array();
        
        if ($resultado) {
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $pacientes[] = $fila;
            }
        }
        
        return $pacientes;
    }

    public function cargarCuentas() {
        $co = $this->conecta();
        $sql = "SELECT id, nombre, tipo, moneda FROM cuentas WHERE activa = 1 ORDER BY nombre";
        $resultado = $co->query($sql);
        $cuentas = array();
        
        if ($resultado) {
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $cuentas[] = $fila;
            }
        }
        
        return $cuentas;
    }

    // Función para consultar cuotas pendientes
    function consultarCuotasPendientes($cuenta_id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT cp.*, 
                                    CASE 
                                        WHEN cp.fecha_vencimiento < CURDATE() AND cp.estado = 'pendiente' 
                                        THEN 'vencida'
                                        ELSE cp.estado 
                                    END as estado_actual
                                    FROM cuotas_pago cp
                                    WHERE cp.cuenta_por_cobrar_id = '$cuenta_id'
                                    AND cp.estado = 'pendiente'
                                    ORDER BY cp.numero_cuota");
            
            if ($resultado->rowCount() > 0) {
                $respuesta = "";
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>".$row['numero_cuota']."</td>";
                    $respuesta .= "<td>".number_format($row['monto'], 2)." USD</td>";
                    $respuesta .= "<td>".date('d/m/Y', strtotime($row['fecha_vencimiento']))."</td>";
                    $respuesta .= "<td><span class='badge bg-".($row['estado_actual'] == 'vencida' ? 'danger' : 'warning')."'>".$row['estado_actual']."</span></td>";
                    $respuesta .= "</tr>";
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '<tr><td colspan="4" class="text-center">No se encontraron cuotas pendientes para esta cuenta.</td></tr>';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function procesarAbono($cuenta_id, $monto, $fecha_pago, $referencia) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Iniciar transacción
            $co->beginTransaction();

            // Obtener las cuotas pendientes ordenadas por fecha de vencimiento
            $stmt = $co->prepare("SELECT * FROM cuotas_pago 
                                WHERE cuenta_por_cobrar_id = ? 
                                AND estado = 'pendiente' 
                                ORDER BY fecha_vencimiento ASC");
            $stmt->execute([$cuenta_id]);
            $cuotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $monto_restante = $monto;
            $cuotas_actualizadas = 0;
            $cuota_id = null;

            // Procesar cada cuota pendiente
            foreach ($cuotas as $cuota) {
                if ($monto_restante <= 0) break;

                if ($monto_restante >= $cuota['monto']) {
                    // El abono cubre toda la cuota
                    $stmt = $co->prepare("UPDATE cuotas_pago 
                                        SET estado = 'pagado', 
                                            fecha_pago = ? 
                                        WHERE id = ?");
                    $stmt->execute([$fecha_pago, $cuota['id']]);
                    $monto_restante -= $cuota['monto'];
                    $cuotas_actualizadas++;
                    $cuota_id = $cuota['id'];
                } else {
                    // El abono es parcial
                    $stmt = $co->prepare("UPDATE cuotas_pago 
                                        SET monto = monto - ? 
                                        WHERE id = ?");
                    $stmt->execute([$monto_restante, $cuota['id']]);
                    $monto_restante = 0;
                    $cuotas_actualizadas++;
                    $cuota_id = $cuota['id'];
                }
            }

            // Obtener el cuenta_id de la cuenta por cobrar
            $stmt = $co->prepare("SELECT cuenta_id FROM cuentas_por_cobrar WHERE id = ?");
            $stmt->execute([$cuenta_id]);
            $cuenta_id_pago = $stmt->fetchColumn();

            // Registrar el pago (el trigger se encargará de actualizar el monto pendiente y el estado)
            $stmt = $co->prepare("INSERT INTO pagos_cuentas_por_cobrar 
                                (cuenta_por_cobrar_id, monto, fecha_pago, referencia_pago, cuenta_id, cuota_id) 
                                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$cuenta_id, $monto, $fecha_pago, $referencia, $cuenta_id_pago, $cuota_id]);

            // Confirmar transacción
            $co->commit();

            $r['resultado'] = 'ok';
            $r['mensaje'] = 'Pago procesado correctamente. Se actualizaron ' . $cuotas_actualizadas . ' cuotas.';
        } catch(Exception $e) {
            // Revertir transacción en caso de error
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>