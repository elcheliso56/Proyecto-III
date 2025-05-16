<?php
require_once('modelo/datos.php');
class movimientos extends datos{
    // Propiedades de la clase para ingresos
	private $id;
	private $descripcion;
	private $monto;
	private $fecha;
	private $origen;
	private $tipo; // Nuevo campo para distinguir entre ingreso y egreso

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
	function set_tipo($valor){
		$this->tipo = $valor;
	}
	function get_tipo(){
		return $this->tipo;
	}

	// Método para incluir un nuevo registro
	function incluir(){
		$r = array();
		if(!$this->existe($this->id)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$tabla = ($this->tipo == 'ingreso') ? 'ingresos' : 'egresos';
				$co->query("INSERT INTO $tabla(descripcion, monto, fecha, origen)
					VALUES(
						'$this->descripcion',
						'$this->monto',
						'$this->fecha', 
						'$this->origen'
					)
				");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro Guardado!';
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

	// Método para modificar un registro
	function modificar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
	
		if ($this->existe($this->id)) {
			try {
				$tabla = ($this->tipo == 'ingreso') ? 'ingresos' : 'egresos';
				$co->query("UPDATE $tabla SET 
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
	
	// Método para eliminar un registro
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();        
		if($this->existe($this->id)){
			try {                
				$tabla = ($this->tipo == 'ingreso') ? 'ingresos' : 'egresos';
				$co->query("DELETE from $tabla 
					where id = '$this->id'
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

	// Método para consultar todos los movimientos
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			// Consulta que combina ingresos y egresos
			$resultado = $co->query("SELECT 'ingreso' as tipo, id, descripcion, monto, fecha, origen
				FROM ingresos
				UNION ALL
				SELECT 'egreso' as tipo, id, descripcion, monto, fecha, origen
				FROM egresos
				ORDER BY fecha DESC
			");

			if ($resultado->rowCount() > 0) {
				$respuesta = "";
				$n = 1;
				while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){
					$tipo_clase = ($row['tipo'] == 'ingreso') ? 'text-success' : 'text-danger';
					$tipo_icono = ($row['tipo'] == 'ingreso') ? 'bi-arrow-up-circle' : 'bi-arrow-down-circle';
					
					$respuesta .= "<tr data-id='".$row['id']."' data-tipo='".$row['tipo']."'>";
					$respuesta .= "<td>$n</td>";
					$respuesta .= "<td><i class='bi $tipo_icono $tipo_clase'></i> ".$row['descripcion']."</td>";
					$respuesta .= "<td class='$tipo_clase'>".$row['monto']."</td>";
					$respuesta .= "<td>".$row['fecha']."</td>";
					$respuesta .= "<td>".$row['origen']."</td>";
					$respuesta .= "<td>";
					$respuesta .= "</td>";
					$respuesta .= "</tr>";
					$n++;
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] = $respuesta;
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] = 'No se encontraron movimientos.';
			}
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	// Método privado para verificar si un registro existe
	private function existe($id){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$tabla = ($this->tipo == 'ingreso') ? 'ingresos' : 'egresos';
			$resultado = $co->query("Select * from $tabla where id='$id'");	
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){
				return true;   
			}
			else{	
				return false;
			}	
		}catch(Exception $e){
			return false;
		}
	}	
}
?>