<?php
require_once('modelo/datos.php');
require_once('modelo/traits/validaciones.php');
require_once('modelo/traits/generador_ids.php');

class ingresos extends datos {
    use validaciones, generador_ids;
    
    // Propiedades de la clase	ingresos
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
	// Método para incluir un nuevo registro de ingreso
	
	function incluir(){
		$r = array();
        
        // Generar ID único antes de verificar si existe
        $this->id = $this->generarIdUnico('ingresos', 'ING');
        
        // Verifica si el id ya existe (aunque ya generamos uno único, es una verificación adicional)		
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
				$co->query("INSERT INTO ingresos(id, descripcion, monto, fecha, origen, cuenta_id)
              VALUES(
                  '$this->id',
                  '$this->descripcion',
                  '$this->monto',
                  '$this->fecha', 
                  '$this->origen',
                  '$this->cuenta_id'
                  )
              ");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Ingreso Registrado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			} finally {
                $co = null; // Cierra la conexión después de la operación
            }
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Entrada ya registrada';
		}
		return $r;
	}
    // Método para modificar un registro de ingreso existente
	function modificar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
	
		// Verifica si el ingreso existe
		if ($this->existe($this->id)) {
            // Validar los datos antes de modificar
            $validacion = $this->validarDatos();
            if (!$validacion['valido']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $validacion['mensaje'];
                return $r;
            }

			try {
				// Actualiza los datos del ingreso
				$co->query("UPDATE ingresos SET 
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
			$r['mensaje'] = 'Entrada no modificada';
		}
	
		return $r;
	}
	
    // Método para eliminar un producto	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();        
		// Verifica si el registro existe
		if($this->existe($this->id)){
			try {                
				// Elimina el registro del ingreso
				$co->query("DELETE from ingresos 
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
    // Método para consultar productos		
	
function consultar(){
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    try{
        // Realiza la consulta para obtener los ingresos con información de la cuenta
        $resultado = $co->query("SELECT i.id, i.descripcion, i.monto, i.fecha, i.origen, i.cuenta_id, 
                                c.nombre as nombre_cuenta, c.moneda as moneda_cuenta
                                FROM ingresos i
                                LEFT JOIN cuentas c ON i.cuenta_id = c.id
                                ORDER BY i.fecha DESC");
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
                    $respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar ingreso'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar ingreso'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta .= "</td>";
                $respuesta .= "</tr>";
                $n++;
            }
            $r['resultado'] = 'consultar';
            $r['mensaje'] = $respuesta;
        } else {
            $r['resultado'] = 'consultar';
            $r['mensaje'] = 'No se encontraron ingresos.';
        }
    } catch(Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    } finally {
        $co = null; // Cierra la conexión después de la operación
    }
    return $r;
}


    // Método privado para verificar si un producto existe	
	private function existe($id){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$resultado = $co->query("Select * from ingresos where id='$id'");	
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
	

    // Métodos para obtener categorías, ubicaciones y proveedores
	
	function obtenerCategorias() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT id, nombre FROM categorias");
			$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $categorias;
		} catch(Exception $e) {
			return array();
		}
	}
	function obtenerUbicaciones() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT id, nombre FROM ubicaciones");
			$ubicaciones = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $ubicaciones;
		} catch(Exception $e) {
			return array();
		}
	}

    // Agregar método para obtener las cuentas activas
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
            return array();
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
        }
    }

    // Método para validar los datos del ingreso
    private function validarDatos() {
        $r = array();
        $r['valido'] = true;
        $r['mensaje'] = '';

        // Validar descripción
        if (empty($this->descripcion) || strlen($this->descripcion) < 3 || strlen($this->descripcion) > 255) {
            $r['valido'] = false;
            $r['mensaje'] = 'La descripción debe tener entre 3 y 255 caracteres';
            return $r;
        }

        // Validar monto
        if (empty($this->monto) || !is_numeric($this->monto) || $this->monto <= 0) {
            $r['valido'] = false;
            $r['mensaje'] = 'El monto debe ser un número mayor a 0';
            return $r;
        }

        // Validar fecha
        if (empty($this->fecha)) {
            $r['valido'] = false;
            $r['mensaje'] = 'La fecha es obligatoria';
            return $r;
        }

        // Validar que la fecha sea válida
        $fecha_obj = DateTime::createFromFormat('Y-m-d', $this->fecha);
        if (!$fecha_obj || $fecha_obj->format('Y-m-d') !== $this->fecha) {
            $r['valido'] = false;
            $r['mensaje'] = 'La fecha no es válida';
            return $r;
        }

        // Validar origen
        if (empty($this->origen) || !in_array($this->origen, ['manual', 'consulta', 'servicio'])) {
            $r['valido'] = false;
            $r['mensaje'] = 'El origen no es válido';
            return $r;
        }

        // Validar cuenta_id
        if (empty($this->cuenta_id)) {
            $r['valido'] = false;
            $r['mensaje'] = 'La cuenta es obligatoria';
            return $r;
        }

        return $r;
    }

}
?>