<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');

class egresos extends datos {
    use validaciones;
    
    // Propiedades de la clase egresos
	private $id;
	private $descripcion;
	private $monto;
	private $fecha;
	private $origen;
	private $cuenta_id;

	//getters y setters para las propiedades
	function set_id($valor){
		$this->id = $valor;
	}
	function get_id(){
		return $this->id;
	}
	function set_descripcion($valor){
		$this->descripcion = $valor;
	}
	function get_descripcion(){
		return $this->descripcion;
	}
	function set_monto($valor){
		$this->monto = $valor;
	}
	function get_monto(){
		return $this->monto;
	}
	function set_fecha($valor){
		$this->fecha = $valor;
	}
	function get_fecha(){
		return $this->fecha;
	}
	function set_origen($valor){
		$this->origen = $valor;
	}
	function get_origen(){
		return $this->origen;
	}
	function set_cuenta_id($valor){
		$this->cuenta_id = $valor;
	}
	function get_cuenta_id(){
		return $this->cuenta_id;
	}
	
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

            // Verificar si la cuenta existe y está activa
            if (!$this->cuentaExiste($this->cuenta_id)) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'La cuenta seleccionada no existe o no está activa';
                return $r;
            }

			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo registro en la base de datos				
				$co->query("INSERT INTO egresos(descripcion, monto, fecha, origen, cuenta_id)
              VALUES(
                  '$this->descripcion',
                  '$this->monto',
                  '$this->fecha', 
                  '$this->origen',
                  '$this->cuenta_id'
                  )
              ");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Egreso Registrado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			} finally {
                $co = null; // Cierra la conexión después de la operación
            }
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Egreso ya registrado';
		}
		return $r;
	}

	function modificar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
	
		// Verifica si el egreso existe
		if ($this->existe($this->id)) {
            // Validar los datos antes de modificar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

            // Verificar si la cuenta existe y está activa
            if (!$this->cuentaExiste($this->cuenta_id)) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'La cuenta seleccionada no existe o no está activa';
                return $r;
            }

			try {
				// Actualiza los datos del egreso
				$co->query("UPDATE egresos SET 
					descripcion = '$this->descripcion',
					monto = '$this->monto',
					fecha = '$this->fecha',
					origen = '$this->origen',
					cuenta_id = '$this->cuenta_id'
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
			$r['mensaje'] = 'Egreso no modificado';
		}
	
		return $r;
	}
	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();        
		// Verifica si el registro existe
		if($this->existe($this->id)){
			try {                
				// Elimina el registro del egreso
				$co->query("DELETE from egresos 
					where
					id = '$this->id'
					");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con éxito!';

			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este registro porque tiene transacciones asociadas';
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
	
function consultar(){
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    try{
        // Realiza la consulta para obtener los egresos con información de la cuenta
        $resultado = $co->query("SELECT e.id, e.descripcion, e.monto, e.fecha, e.origen, e.cuenta_id, 
                                c.nombre as nombre_cuenta, c.moneda as moneda_cuenta
                                FROM egresos e
                                LEFT JOIN cuentas c ON e.cuenta_id = c.id
                                ORDER BY e.fecha DESC");
        if ($resultado->rowCount() > 0) {
            $respuesta = "";
            $n = 1;
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                // Genera la respuesta en formato HTML
				$respuesta .= "<tr data-id='".$row['id']."'>";
                $respuesta .= "<td>$n</td>";
                $respuesta .= "<td>".$row['descripcion']."</td>";
                $respuesta .= "<td>".number_format($row['monto'], 2)." ".$row['moneda_cuenta']."</td>";
                $respuesta .= "<td>".$row['fecha']."</td>";
                $respuesta .= "<td>".$row['origen']."</td>";
                $respuesta .= "<td data-cuenta-id='".$row['cuenta_id']."'>".$row['nombre_cuenta']."</td>";
				$respuesta .= "<td>";
                    // Botón de modificar comentado
                    // $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar egreso'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    // Botón de eliminar comentado
                    // $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar egreso'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                $respuesta .= "</tr>";
                $n++;
            }
            $r['resultado'] = 'consultar';
            $r['mensaje'] = $respuesta;
        } else {
            $r['resultado'] = 'consultar';
            $r['mensaje'] = 'No se encontraron egresos.';
        }
    } catch(Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    } finally {
        $co = null; // Cierra la conexión después de la operación
    }
    return $r;
}

	private function existe($id){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$resultado = $co->query("Select * from egresos where id='$id'");	
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

    // Método para obtener las cuentas activas
    function obtenerCuentas() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id, nombre, tipo, moneda 
                                    FROM cuentas 
                                    WHERE activa = 1 
                                    ORDER BY nombre");
            if ($resultado) {
                $cuentas = $resultado->fetchAll(PDO::FETCH_ASSOC);
                return $cuentas;
            }
            return array();
        } catch(Exception $e) {
            error_log("Error al obtener cuentas: " . $e->getMessage());
            return false;
        } finally {
            $co = null; // Cierra la conexión después de la operación
        }
    }

    // Método para verificar si la cuenta existe
    private function cuentaExiste($cuenta_id) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id FROM cuentas WHERE id = '$cuenta_id' AND activa = 1");
            return $resultado->rowCount() > 0;
        } catch(Exception $e) {
            return false;
        } finally {
            $co = null; // Cierra la conexión después de la operación
        }
    }
}
?>