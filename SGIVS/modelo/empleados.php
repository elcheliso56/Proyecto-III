<?php
require_once('modelo/datos.php');
class empleados extends datos{
    // Propiedades del empleados		
	private $tipo_rif;
	private $rif;
	private $tipo_documento;
	private $cedula;
	private $nombre;
	private $apellido;
	private $fecha_nacimiento;
	private $genero;
	private $email;
	private $telefono;
	private $direccion;
	private $fecha_contratacion;
    private $cargo;
    private $salario;

    // Métodos para establecer los valores de las     
	function set_tipo_rif($valor){
		$this->tipo_rif = $valor;
	}	    
	function set_rif($valor){
		$this->rif = $valor;
	}	    
	function set_tipo_documento($valor){
		$this->tipo_documento = $valor;
	}	
	function set_nombre($valor){
		$this->nombre = $valor;
	}	
	function set_cedula($valor){
		$this->cedula = $valor;
	}	
	function set_apellido($valor){
		$this->apellido = $valor;
	}	
	function set_fecha_nacimiento($valor){
		$this->fecha_nacimiento = $valor;
	}	
	function set_genero($valor){
		$this->genero = $valor;
	}	
	function set_email($valor){
		$this->email = $valor;
	}	
	function set_telefono($valor){
		$this->telefono = $valor;
	}	
	function set_direccion($valor){
		$this->direccion = $valor;
	}
	function set_fecha_contratacion($valor){
		$this->fecha_contratacion = $valor;
	}
	function set_cargo($valor){
		$this->cargo = $valor;
	}	
	function set_salario($valor){
		$this->salario = $valor;
	}	

    // Métodos para obtener los valores de las propiedades		
	function get_tipo_rif(){
		return $this->tipo_rif;
	}
	function get_rif(){
		return $this->rif;
	}
	function get_tipo_documento(){
		return $this->tipo_documento;
	}	
	function get_cedula(){
		return $this->cedula;
	}
	function get_nombre(){
		return $this->nombre;
	}
	function get_apellido(){
		return $this->apellido;
	}
	function get_fecha_nacimiento(){
		return $this->fecha_nacimiento;
	}	
	function get_genero(){
		return $this->genero;
	}	
	function get_email(){
		return $this->email;
	}	
	function get_telefono(){
		return $this->telefono;
	}	
	function get_direccion(){
		return $this->direccion;
	}
	function get_fecha_contratacion(){
		return $this->fecha_contratacion;
	}
	function get_cargo(){
		return $this->cargo;
	}
	function get_salario(){
		return $this->salario;
	}

    // Método para incluir un nuevo empleado en la base de datos
	function incluir(){
		$r = array();
        // Verifica si el número de cedula ya existe		
		if(!$this->existe($this->cedula)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo empleado en la base de datos			
				$co->query("Insert into empleados(tipo_rif, rif, tipo_documento, cedula,nombre,apellido,fecha_nacimiento,genero,email,telefono,direccion,fecha_contratacion,cargo,salario)
					Values(
					'$this->tipo_rif',
					'$this->rif',
					'$this->tipo_documento',
					'$this->cedula',
					'$this->nombre',
					'$this->apellido',
					'$this->fecha_nacimiento',
					'$this->genero',
					'$this->email',
					'$this->telefono',
					'$this->direccion',
                    '$this->fecha_contratacion',
					'$this->cargo',
					'$this->salario'
					)");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con exito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el número de cedula';
		}
		$co = null;
		return $r;
	}

    // Método para modificar un empleado existente	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de cedula existe		
		if($this->existe($this->cedula)){
			try {
                // Actualiza los datos del empleado en la base de datos				
				$co->query("Update empleados set 
					tipo_rif = '$this->tipo_rif',
					rif = '$this->rif',
                    tipo_documento = '$this->tipo_documento',
					nombre = '$this->nombre',
					apellido = '$this->apellido',
                    fecha_nacimiento = '$this->fecha_nacimiento',
                    genero = '$this->genero',
					email = '$this->email',
					telefono = '$this->telefono',
					direccion = '$this->direccion',
                    fecha_contratacion = '$this->fecha_contratacion',
                    cargo = '$this->cargo',
                    salario = '$this->salario'
					where
					cedula = '$this->cedula'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro modificado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'El número de cédula no registrado';
		}
		$co = null;
		return $r;
	}

    // Método para eliminar un empleado	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->cedula)){
			try {
				// Elimina el empleado de la base de datos
				$co->query("delete from empleados where	cedula = '$this->cedula'");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este empleado porque tiene movimientos asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el número de cédula';
		}
		$co = null;
		return $r;
	}	

    // Método para consultar todos los empleado	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("Select * from empleados ORDER BY id_empleado DESC");
			$empleados = [];
			foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $row){
				// Calcular edad y clasificación
				$fecha_nacimiento = new DateTime($row['fecha_nacimiento']);
				$hoy = new DateTime();
				$edad = $hoy->diff($fecha_nacimiento)->y;
				$row['edad'] = $edad;
				$empleados[] = $row;
			}
			$r['resultado'] = 'consultar';
			$r['mensaje'] = $empleados;
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		$co = null;
		return $r;
	}
	// Método privado para verificar si un número de documento ya existe
	private function existe($cedula){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("Select * from empleados where cedula='$cedula'");
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
		$co = null;
	}	
}
?>