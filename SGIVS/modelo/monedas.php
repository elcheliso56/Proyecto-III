<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');
require_once('modelo/traits/generador_ids.php');

class monedas extends datos {
    use validaciones, generador_ids;
    
    // Propiedades de la clase monedas
    private $id;
    private $codigo;
    private $nombre;
    private $simbolo;
    private $activa;
    private $es_principal;

    // Getters y setters para las propiedades
    function set_id($valor){
        $this->id = $valor;
    }
    function get_id(){
        return $this->id;
    }
    function set_codigo($valor){
        $this->codigo = $valor;
    }
    function get_codigo(){
        return $this->codigo;
    }
    function set_nombre($valor){
        $this->nombre = $valor;
    }
    function get_nombre(){
        return $this->nombre;
    }
    function set_simbolo($valor){
        $this->simbolo = $valor;
    }
    function get_simbolo(){
        return $this->simbolo;
    }
    function set_activa($valor){
        $this->activa = $valor;
    }
    function get_activa(){
        return $this->activa;
    }
    function set_es_principal($valor){
        $this->es_principal = $valor;
    }
    function get_es_principal(){
        return $this->es_principal;
    }

    // Método para incluir una nueva moneda
    function incluir(){
        $r = array();
        
        // Generar ID único antes de verificar si existe
        $this->id = $this->generarIdUnico('monedas', 'MON');
        
        // Verifica si el id ya existe (aunque ya generamos uno único, es una verificación adicional)        
        if(!$this->existe($this->id)){
            // Validar los datos antes de insertar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

            // Verificar que no haya otra moneda principal si esta es principal
            if ($this->es_principal == 1 && $this->existeMonedaPrincipal()) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe una moneda principal. Solo puede haber una moneda principal.';
                return $r;
            }

            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // Inserta el nuevo registro en la base de datos                
                $co->query("INSERT INTO monedas(id, codigo, nombre, simbolo, activa, es_principal)
                    VALUES(
                        '$this->id',
                        '$this->codigo',
                        '$this->nombre',
                        '$this->simbolo',
                        '$this->activa',
                        '$this->es_principal'
                    )
                ");
                $r['resultado'] = 'incluir';
                $r['mensaje'] =  '¡Moneda Registrada con éxito!';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            } finally {
                $co = null; // Cierra la conexión después de la operación
            }
        }
        else{
            $r['resultado'] = 'incluir';
            $r['mensaje'] =  'Moneda ya registrada';
        }
        return $r;
    }

    // Método para modificar una moneda existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
    
        // Verifica si la moneda existe
        if ($this->existe($this->id)) {
            // Validar los datos antes de modificar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

            // Verificar que no haya otra moneda principal si esta es principal
            if ($this->es_principal == 1 && $this->existeMonedaPrincipal($this->id)) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe una moneda principal. Solo puede haber una moneda principal.';
                return $r;
            }

            try {
                // Actualiza los datos de la moneda
                $co->query("UPDATE monedas SET 
                    codigo = '$this->codigo',
                    nombre = '$this->nombre',
                    simbolo = '$this->simbolo',
                    activa = '$this->activa',
                    es_principal = '$this->es_principal'
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
            $r['mensaje'] = 'Moneda no modificada';
        }
    
        return $r;
    }
    
    // Método para eliminar una moneda    
    function eliminar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();        
        // Verifica si el registro existe
        if($this->existe($this->id)){
            try {                
                // Verificar si es la moneda principal
                if ($this->esMonedaPrincipal($this->id)) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'No se puede eliminar la moneda principal';
                    return $r;
                }

                // Verificar si tiene tipos de cambio asociados
                if ($this->tieneTiposCambio($this->id)) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'No se puede eliminar esta moneda porque tiene tipos de cambio asociados';
                    return $r;
                }

                // Elimina el registro de la moneda
                $co->query("DELETE from monedas 
                    where
                    id = '$this->id'
                    ");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] =  '¡Registro eliminado con éxito!';

            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar esta moneda porque tiene transacciones asociadas';
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

    // Método para consultar una moneda específica por ID
    function consultarPorId($id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id, codigo, nombre, simbolo, activa, es_principal 
                                    FROM monedas 
                                    WHERE id = '$id'");
            if ($resultado && $resultado->rowCount() > 0) {
                return $resultado->fetch(PDO::FETCH_ASSOC);
            }
            return null;
        } catch(Exception $e) {
            error_log("Error al consultar moneda por ID: " . $e->getMessage());
            return null;
        }
    }

    // Método para consultar monedas        
    function consultar(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            // Realiza la consulta para obtener las monedas
            $resultado = $co->query("SELECT id, codigo, nombre, simbolo, activa, es_principal 
                                    FROM monedas 
                                    ORDER BY es_principal DESC, nombre");
            if ($resultado->rowCount() > 0) {
                $respuesta = "";
                $n = 1;
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                    // Genera la respuesta en formato HTML
                    $respuesta .= "<tr data-id='".$row['id']."'>";
                    $respuesta .= "<td>$n</td>";
                    $respuesta .= "<td>".$row['codigo']."</td>";
                    $respuesta .= "<td>".$row['nombre']."</td>";
                    $respuesta .= "<td>".$row['simbolo']."</td>";
                    $respuesta .= "<td class='text-center'>";
                    if ($row['activa'] == 1) {
                        $respuesta .= "<span class='badge bg-success'>Activa</span>";
                    } else {
                        $respuesta .= "<span class='badge bg-danger'>Inactiva</span>";
                    }
                    $respuesta .= "</td>";
                    $respuesta .= "<td class='text-center'>";
                    if ($row['es_principal'] == 1) {
                        $respuesta .= "<span class='badge bg-primary'>Principal</span>";
                    } else {
                        $respuesta .= "<span class='badge bg-secondary'>Secundaria</span>";
                    }
                    $respuesta .= "</td>";
                    $respuesta .= "<td class='text-center'>";
                    $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar moneda'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar moneda'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No se encontraron monedas.';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null; // Cierra la conexión después de la operación
        }
        return $r;
    }

    // Método para obtener monedas activas (para selectores)
    function obtenerMonedasActivas() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id, codigo, nombre, simbolo, es_principal 
                                    FROM monedas 
                                    WHERE activa = 1 
                                    ORDER BY es_principal DESC, nombre");
            if ($resultado) {
                $monedas = $resultado->fetchAll(PDO::FETCH_ASSOC);
                return $monedas;
            }
            return array();
        } catch(Exception $e) {
            error_log("Error al obtener monedas: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener la moneda principal
    function obtenerMonedaPrincipal() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id, codigo, nombre, simbolo 
                                    FROM monedas 
                                    WHERE es_principal = 1 AND activa = 1 
                                    LIMIT 1");
            if ($resultado && $resultado->rowCount() > 0) {
                return $resultado->fetch(PDO::FETCH_ASSOC);
            }
            return null;
        } catch(Exception $e) {
            error_log("Error al obtener moneda principal: " . $e->getMessage());
            return null;
        }
    }

    // Método privado para verificar si una moneda existe    
    private function existe($id){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{        
            $resultado = $co->query("Select * from monedas where id='$id'");    
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

    // Método para verificar si existe una moneda principal
    private function existeMonedaPrincipal($excluir_id = null) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $sql = "SELECT COUNT(*) as total FROM monedas WHERE es_principal = 1";
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

    // Método para verificar si una moneda es principal
    private function esMonedaPrincipal($id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT es_principal FROM monedas WHERE id = '$id'");
            if ($resultado && $resultado->rowCount() > 0) {
                $row = $resultado->fetch(PDO::FETCH_ASSOC);
                return $row['es_principal'] == 1;
            }
            return false;
        } catch(Exception $e) {
            return false;
        }
    }

    // Método para verificar si una moneda tiene tipos de cambio
    private function tieneTiposCambio($id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT COUNT(*) as total FROM tipos_cambio 
                                    WHERE moneda_origen = '$id' OR moneda_destino = '$id'");
            $row = $resultado->fetch(PDO::FETCH_ASSOC);
            return $row['total'] > 0;
        } catch(Exception $e) {
            return false;
        }
    }

    // Método para validar los datos de la moneda
    private function validarDatos() {
        $r = array();
        $r['valido'] = true;
        $r['mensaje'] = '';

        // Validar código
        if (empty($this->codigo) || strlen($this->codigo) != 3) {
            $r['valido'] = false;
            $r['mensaje'] = 'El código debe tener exactamente 3 caracteres';
            return $r;
        }

        // Validar que el código sea alfanumérico
        if (!preg_match('/^[A-Z]{3}$/', $this->codigo)) {
            $r['valido'] = false;
            $r['mensaje'] = 'El código debe contener solo letras mayúsculas';
            return $r;
        }

        // Validar nombre
        if (empty($this->nombre) || strlen($this->nombre) < 3 || strlen($this->nombre) > 50) {
            $r['valido'] = false;
            $r['mensaje'] = 'El nombre debe tener entre 3 y 50 caracteres';
            return $r;
        }

        // Validar símbolo
        if (strlen($this->simbolo) > 5) {
            $r['valido'] = false;
            $r['mensaje'] = 'El símbolo no puede tener más de 5 caracteres';
            return $r;
        }

        // Validar estado activa
        if (!in_array($this->activa, [0, 1])) {
            $r['valido'] = false;
            $r['mensaje'] = 'El estado no es válido';
            return $r;
        }

        // Validar es_principal
        if (!in_array($this->es_principal, [0, 1])) {
            $r['valido'] = false;
            $r['mensaje'] = 'El campo principal no es válido';
            return $r;
        }

        return $r;
    }
}
?> 