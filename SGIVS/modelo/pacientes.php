<?php
require_once('modelo/datos.php');
class pacientes extends datos{
    // Propiedades del paciente	
	private $tipo_documento;
	private $cedula;
	private $nombre;
	private $apellido;
	private $fecha_nacimiento;
	private $genero;
    private $alergias;
    private $antecedentes;
	private $email;
	private $telefono;
	private $direccion;
	private $fecha_registro;

    // Métodos para establecer los valores de las propiedades
	function set_tipo_documento($valor){
		$this->tipo_documento = $valor;
	}
	function set_cedula($valor){
		$this->cedula = $valor;
	}	
	function set_nombre($valor){
		$this->nombre = $valor;
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
	function set_alergias($valor){
		$this->alergias = $valor;
	}	
	function set_antecedentes($valor){
		$this->antecedentes = $valor;
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
	function set_fecha_registro($valor){
		$this->fecha_registro = $valor;
	}
    // Métodos para obtener los valores de las propiedades		
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
	function get_alergias(){
		return $this->alergias;
	}
	function get_antecedentes(){
		return $this->antecedentes;
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
	function get_fecha_registro(){
		return $this->fecha_registro;
	}

    // Método para incluir un nuevo paciente en la base de datos
	function incluir(){
		$r = array();
        // Verifica si el número de documento ya existe		
		if(!$this->existe($this->cedula)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo paciente			
				$co->query("Insert into pacientes(tipo_documento,cedula,nombre,apellido,fecha_nacimiento,genero,alergias,antecedentes,email,telefono,direccion,fecha_registro)
					Values(
					'$this->tipo_documento',
					'$this->cedula',
					'$this->nombre',
					'$this->apellido',
					'$this->fecha_nacimiento',
					'$this->genero',
					'$this->alergias',
                    '$this->antecedentes',
					'$this->email',
					'$this->telefono',
					'$this->direccion',
                    '$this->fecha_registro'
                    )");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con exito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
			$co = null;
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el numero de documento';
		}
		return $r;
	}

    // Método para modificar un paciente existente	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->cedula)){
			try {
                // Actualiza los datos del paciente				
				$co->query("Update pacientes set
					tipo_documento = '$this->tipo_documento',
					nombre = '$this->nombre',
					apellido = '$this->apellido',
                    fecha_nacimiento = '$this->fecha_nacimiento',
                    genero = '$this->genero',
                    alergias = '$this->alergias',
                    antecedentes = '$this->antecedentes',
					email = '$this->email',
					telefono = '$this->telefono',
					direccion = '$this->direccion'
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
			$r['mensaje'] =  'numero de documento no registrado';
		}
		$co = null;
		return $r;
	}

    // Método para eliminar un paciente	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->cedula)){
			try {
				// Elimina el paciente de la base de datos
				$co->query("delete from pacientes where	cedula = '$this->cedula'");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este paciente porque tiene movimientos asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el numero de documento';
		}
		$co = null;
		return $r;
	}	

    // Método para consultar todos los paciente	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("Select * from pacientes ORDER BY id_paciente DESC");
			$pacientes = [];
			foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $row){
				// Calcular edad y clasificación
				$fecha_nacimiento = new DateTime($row['fecha_nacimiento']);
				$hoy = new DateTime();
				$edad = $hoy->diff($fecha_nacimiento)->y;
				if ($edad <= 12) {
					$clasificacion = "Niño";
				} elseif ($edad <= 17) {
					$clasificacion = "Adolescente";
				} else {
					$clasificacion = "Adulto";
				}
				$row['edad'] = $edad;
				$row['clasificacion'] = $clasificacion;
				$pacientes[] = $row;
			}
			$r['resultado'] = 'consultar';
			$r['mensaje'] = $pacientes;
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
			$resultado = $co->query("Select * from pacientes where cedula='$cedula'");
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