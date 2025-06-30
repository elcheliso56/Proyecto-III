<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');
require_once('modelo/traits/generador_ids.php');

class bancos extends datos {
    use validaciones, generador_ids;
    
    // Propiedades de la clase bancos
    private $id;
    private $nombre;
    private $codigo_swift;
    private $codigo_local;
    private $logo;
    private $activo;
    private $fecha_registro;

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
    function set_codigo_swift($valor){
        $this->codigo_swift = $valor;
    }
    function get_codigo_swift(){
        return $this->codigo_swift;
    }
    function set_codigo_local($valor){
        $this->codigo_local = $valor;
    }
    function get_codigo_local(){
        return $this->codigo_local;
    }
    function set_logo($valor){
        $this->logo = $valor;
    }
    function get_logo(){
        return $this->logo;
    }
    function set_activo($valor){
        $this->activo = $valor;
    }
    function get_activo(){
        return $this->activo;
    }
    function set_fecha_registro($valor){
        $this->fecha_registro = $valor;
    }
    function get_fecha_registro(){
        return $this->fecha_registro;
    }

    // Método para incluir un nuevo banco
    function incluir(){
        $r = array();
        
        // Generar ID único antes de verificar si existe
        $this->id = $this->generarIdUnico('bancos', 'BAN');
        
        // Verifica si el id ya existe (aunque ya generamos uno único, es una verificación adicional)        
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
                $co->query("INSERT INTO bancos(id, nombre, codigo_swift, codigo_local, logo, activo)
                    VALUES(
                        '$this->id',
                        '$this->nombre',
                        '$this->codigo_swift',
                        '$this->codigo_local',
                        '$this->logo',
                        '$this->activo'
                    )
                ");
                $r['resultado'] = 'incluir';
                $r['mensaje'] =  '¡Banco Registrado con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            } finally {
                $co = null; // Cierra la conexión después de la operación
            }
        }
        else{
            $r['resultado'] = 'incluir';
            $r['mensaje'] =  'Banco ya registrado';
        }
        return $r;
    }

    // Método para modificar un banco existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
    
        // Verifica si el banco existe
        if ($this->existe($this->id)) {
            // Validar los datos antes de modificar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

            try {
                // Actualiza los datos del banco
                $co->query("UPDATE bancos SET 
                    nombre = '$this->nombre',
                    codigo_swift = '$this->codigo_swift',
                    codigo_local = '$this->codigo_local',
                    logo = '$this->logo',
                    activo = '$this->activo'
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
            $r['mensaje'] = 'Banco no modificado';
        }
    
        return $r;
    }
    
    // Método para eliminar un banco    
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();        
        // Verifica si el registro existe
        if($this->existe($this->id)){
            try {                
                // Elimina el registro del banco
                $co->query("DELETE from bancos 
                    where
                    id = '$this->id'
                    ");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] =  '¡Registro eliminado con éxito!';

            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar este banco porque tiene cuentas asociadas';
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

    // Método para consultar bancos        
    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            // Realiza la consulta para obtener los bancos
            $resultado = $co->query("SELECT id, nombre, codigo_swift, codigo_local, logo, activo, fecha_registro 
                                    FROM bancos 
                                    ORDER BY nombre");
            if ($resultado->rowCount() > 0) {
                $respuesta = "";
                $n = 1;
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                    // Genera la respuesta en formato HTML
                    $respuesta .= "<tr data-id='".$row['id']."'>";
                    $respuesta .= "<td>$n</td>";
                    $respuesta .= "<td>";
                    if (!empty($row['logo'])) {
                        $respuesta .= "<img src='".$row['logo']."' alt='Logo' class='img-thumbnail me-2' style='width: 40px; height: 40px; object-fit: contain;'>";
                    }
                    $respuesta .= $row['nombre']."</td>";
                    $respuesta .= "<td>".$row['codigo_swift']."</td>";
                    $respuesta .= "<td>".$row['codigo_local']."</td>";
                    $respuesta .= "<td>".($row['activo'] == 1 ? "Activo" : "Inactivo")."</td>";
                    $respuesta .= "<td>".date('d/m/Y H:i', strtotime($row['fecha_registro']))."</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar banco'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar banco'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron bancos.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null; // Cierra la conexión después de la operación
        }
        return $r;
    }

    // Método privado para verificar si un banco existe    
    private function existe($id){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{        
            $resultado = $co->query("Select * from bancos where id='$id'");    
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
            $co = null; // Cierra la conexión después de la operación
        }
    }    

    // Método para validar los datos del banco
    private function validarDatos() {
        $r = array();
        $r['valido'] = true;
        $r['mensaje'] = '';

        // Validar nombre
        if (empty($this->nombre) || strlen($this->nombre) < 3 || strlen($this->nombre) > 100) {
            $r['valido'] = false;
            $r['mensaje'] = 'El nombre debe tener entre 3 y 100 caracteres';
            return $r;
        }

        // Validar código SWIFT (opcional pero si se proporciona debe tener formato válido)
        if (!empty($this->codigo_swift)) {
            if (strlen($this->codigo_swift) < 8 || strlen($this->codigo_swift) > 11) {
                $r['valido'] = false;
                $r['mensaje'] = 'El código SWIFT debe tener entre 8 y 11 caracteres';
                return $r;
            }
            if (!preg_match('/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/', $this->codigo_swift)) {
                $r['valido'] = false;
                $r['mensaje'] = 'El código SWIFT debe tener formato válido (ej: BANCPEPL)';
                return $r;
            }
        }

        // Validar código local (opcional pero si se proporciona debe tener formato válido)
        if (!empty($this->codigo_local)) {
            if (strlen($this->codigo_local) < 2 || strlen($this->codigo_local) > 20) {
                $r['valido'] = false;
                $r['mensaje'] = 'El código local debe tener entre 2 y 20 caracteres';
                return $r;
            }
            if (!preg_match('/^[A-Z0-9]+$/', $this->codigo_local)) {
                $r['valido'] = false;
                $r['mensaje'] = 'El código local solo puede contener letras mayúsculas y números';
                return $r;
            }
        }

        // Validar activo
        if (!in_array($this->activo, ['0', '1'])) {
            $r['valido'] = false;
            $r['mensaje'] = 'El estado no es válido';
            return $r;
        }

        return $r;
    }

    public function obtenerBancosSelect() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bancos = [];
        $resultado = $co->query("SELECT id, nombre, codigo_local FROM bancos WHERE activo = 1 ORDER BY nombre");
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            $bancos[] = $row;
        }
        return $bancos;
    }
}
?> 