<?php
require_once('modelo/datos.php');
class ingresos extends datos{
    // Propiedades de la clase	ingresos
	private $id;
	private $descripcion;
	private $monto;
	private $fecha;
	private $origen;

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
	// Método para incluir un nuevo registro de ingreso
	
	function incluir(){
		$r = array();
        // Verifica si el id ya existe		
		if(!$this->existe($this->id)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo registro en la base de datos				
				$co->query("INSERT INTO ingresos(descripcion, monto, fecha, origen)
              VALUES(
                  '$this->descripcion',
                  '$this->monto',
                  '$this->fecha', 
                  '$this->origen'
                  )
              ");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Ingreso Registrado!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
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
			try {
				// Actualiza los datos del ingreso
				$co->query("UPDATE ingresos SET 
					descripcion = '$this->descripcion',
					monto = '$this->monto',
					fecha = '$this->fecha',
					origen = '$this->origen'
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
				$r['mensaje'] =  '¡Registro eliminado con exito!';

			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este registro porque tiene transacciones asociadas';
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
    // Método para consultar productos		
	
function consultar(){
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    try{
        // Realiza la consulta para obtener los ingresos
        $resultado = $co->query("SELECT id, descripcion, monto, fecha, origen
                                FROM ingresos
                                ORDER BY fecha DESC");
        if ($resultado->rowCount() > 0) {
            $respuesta = "";
            $n = 1;
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                // Genera la respuesta en formato HTML
				$respuesta .= "<tr data-id='".$row['id']."'>";
                $respuesta .= "<td>$n</td>";
                $respuesta .= "<td>".$row['descripcion']."</td>";
                $respuesta .= "<td>".$row['monto']."</td>";
                $respuesta .= "<td>".$row['fecha']."</td>";
                $respuesta .= "<td>".$row['origen']."</td>";
				$respuesta .= "<td>";
					$respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar producto'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar producto'><i class='bi bi-trash'></i></button><br/>";
					$respuesta .= "</td>";
                $respuesta .= "</tr>";
                $n++;
            }
            $r['resultado'] = 'consultar'; // Cambié el nombre del resultado para diferenciar
            $r['mensaje'] = $respuesta;
        } else {
            $r['resultado'] = 'consultar';
            $r['mensaje'] = 'No se encontraron ingresos.';
        }
    } catch(Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
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



}
?>