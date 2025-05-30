<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');

class cuentas extends datos {
    use validaciones;
    
    // Propiedades de la clase cuentas
    private $id;
    private $nombre;
    private $tipo;
    private $moneda;
    private $activa;
    private $entidad_bancaria;
    private $numero_cuenta;

    // Getters y setters para las propiedades
    function set_id($valor){
        $this->id = $valor;
    }
    function get_id(){
        return $this->id;
    }
    function set_nombre($valor){
        $this->nombre = $valor;
    }
    function get_nombre(){
        return $this->nombre;
    }
    function set_tipo($valor){
        $this->tipo = $valor;
    }
    function get_tipo(){
        return $this->tipo;
    }
    function set_moneda($valor){
        $this->moneda = $valor;
    }
    function get_moneda(){
        return $this->moneda;
    }
    function set_activa($valor){
        $this->activa = $valor;
    }
    function get_activa(){
        return $this->activa;
    }
    function set_entidad_bancaria($valor){
        $this->entidad_bancaria = $valor;
    }
    function get_entidad_bancaria(){
        return $this->entidad_bancaria;
    }
    function set_numero_cuenta($valor){
        $this->numero_cuenta = $valor;
    }
    function get_numero_cuenta(){
        return $this->numero_cuenta;
    }

    // Método para incluir una nueva cuenta
    function incluir(){
        $r = array();
        // Verifica si el id ya existe        
        if(!$this->existe($this->id)){
            // Validar los datos antes de insertar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // Inserta el nuevo registro en la base de datos                
                $co->query("INSERT INTO cuentas(nombre, tipo, moneda, activa, entidad_bancaria, numero_cuenta)
                    VALUES(
                        '$this->nombre',
                        '$this->tipo',
                        '$this->moneda',
                        '$this->activa',
                        '$this->entidad_bancaria',
                        '$this->numero_cuenta'
                    )
                ");
                $r['resultado'] = 'incluir';
                $r['mensaje'] =  '¡Cuenta Registrada con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            } finally {
                $this->cerrarConexion(); // Cierra la conexión después de la operación
            }
        }
        else{
            $r['resultado'] = 'incluir';
            $r['mensaje'] =  'Cuenta ya registrada';
        }
        return $r;
    }

    // Método para modificar una cuenta existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
    
        // Verifica si la cuenta existe
        if ($this->existe($this->id)) {
            // Validar los datos antes de modificar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

            try {
                // Actualiza los datos de la cuenta
                $co->query("UPDATE cuentas SET 
                    nombre = '$this->nombre',
                    tipo = '$this->tipo',
                    moneda = '$this->moneda',
                    activa = '$this->activa',
                    entidad_bancaria = '$this->entidad_bancaria',
                    numero_cuenta = '$this->numero_cuenta'
                    WHERE id = '$this->id'
                ");
    
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Registro actualizado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            } finally {
                $this->cerrarConexion(); // Cierra la conexión después de la operación
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Cuenta no modificada';
        }
    
        return $r;
    }
    
    // Método para eliminar una cuenta    
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();        
        // Verifica si el registro existe
        if($this->existe($this->id)){
            try {                
                // Elimina el registro de la cuenta
                $co->query("DELETE from cuentas 
                    where
                    id = '$this->id'
                    ");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] =  '¡Registro eliminado con éxito!';

            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar esta cuenta porque tiene transacciones asociadas';
                } else {
                    $r['mensaje'] = $e->getMessage();
                }
            } finally {
                $this->cerrarConexion(); // Cierra la conexión después de la operación
            }
        }
        else{
            $r['resultado'] = 'eliminar';
            $r['mensaje'] =  'No existe el registro';
        }
        return $r;
    }

    // Método para consultar cuentas        
    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            // Realiza la consulta para obtener las cuentas
            $resultado = $co->query("SELECT id, nombre, tipo, moneda, activa, entidad_bancaria, numero_cuenta 
                                    FROM cuentas 
                                    ORDER BY nombre");
            if ($resultado->rowCount() > 0) {
                $respuesta = "";
                $n = 1;
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                    // Genera la respuesta en formato HTML
                    $respuesta .= "<tr data-id='".$row['id']."'>";
                    $respuesta .= "<td>$n</td>";
                    $respuesta .= "<td>".$row['nombre']."</td>";
                    $respuesta .= "<td>".$row['tipo']."</td>";
                    $respuesta .= "<td>".$row['moneda']."</td>";
                    $respuesta .= "<td>".($row['activa'] == 1 ? "Activa" : "Inactiva")."</td>";
                    $respuesta .= "<td>".$row['entidad_bancaria']."</td>";
                    $respuesta .= "<td>".$row['numero_cuenta']."</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar cuenta'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar cuenta'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron cuentas.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrarConexion(); // Cierra la conexión después de la operación
        }
        return $r;
    }

    // Método privado para verificar si una cuenta existe    
    private function existe($id){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{        
            $resultado = $co->query("Select * from cuentas where id='$id'");    
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if($fila){
                return true;   
            }
            else{    
                return false;
            }    
        }catch(Exception $e){
            return false;
        } finally {
            $this->cerrarConexion(); // Cierra la conexión después de la operación
        }
    }    

    // Método para validar los datos de la cuenta
    private function validarDatos() {
        $r = array();
        $r['valido'] = true;
        $r['mensaje'] = '';

        // Validar nombre
        if (empty($this->nombre) || strlen($this->nombre) < 3 || strlen($this->nombre) > 50) {
            $r['valido'] = false;
            $r['mensaje'] = 'El nombre debe tener entre 3 y 50 caracteres';
            return $r;
        }

        // Validar tipo
        if (empty($this->tipo) || !in_array($this->tipo, ['bancaria', 'efectivo', 'otro'])) {
            $r['valido'] = false;
            $r['mensaje'] = 'El tipo de cuenta no es válido';
            return $r;
        }

        // Validar moneda
        if (empty($this->moneda) || !in_array($this->moneda, ['Bs', 'USD', 'EUR'])) {
            $r['valido'] = false;
            $r['mensaje'] = 'La moneda no es válida';
            return $r;
        }

        // Validar activa
        if (!in_array($this->activa, ['0', '1'])) {
            $r['valido'] = false;
            $r['mensaje'] = 'El estado no es válido';
            return $r;
        }

        // Validar entidad bancaria (solo si el tipo es bancaria)
        if ($this->tipo === 'bancaria') {
            if (empty($this->entidad_bancaria) || strlen($this->entidad_bancaria) < 3 || strlen($this->entidad_bancaria) > 50) {
                $r['valido'] = false;
                $r['mensaje'] = 'La entidad bancaria debe tener entre 3 y 50 caracteres';
                return $r;
            }
            if (empty($this->numero_cuenta) || !preg_match('/^[0-9]{10,20}$/', $this->numero_cuenta)) {
                $r['valido'] = false;
                $r['mensaje'] = 'El número de cuenta debe tener entre 10 y 20 dígitos';
                return $r;
            }
        }

        return $r;
    }
}
?> 