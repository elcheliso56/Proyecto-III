<?php
require_once('modelo/datos.php');
class pacientes extends datos{
    // Propiedades del cliente	
	private $cedula;
	private $nombre;
	private $apellido;
	private $fecha_nacimiento;
	private $genero;
	private $tipo_sangre;
    private $alergias;
    private $antecedentes;
	private $email;
	private $telefono;
	private $direccion;
	private $fecha_registro;

    // Métodos para establecer los valores de las propiedades
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
	function set_tipo_sangre($valor){
		$this->tipo_sangre = $valor;
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
	function get_tipo_sangre(){
		return $this->tipo_sangre;
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

    // Método para incluir un nuevo cliente en la base de datos
	function incluir(){
		$r = array();
        // Verifica si el número de documento ya existe		
		if(!$this->existe($this->cedula)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo cliente			
				$co->query("Insert into pacientes(cedula,nombre,apellido,fecha_nacimiento,genero,tipo_sangre,alergias,antecedentes,email,telefono,direccion,fecha_registro)
					Values(
					'$this->cedula',
					'$this->nombre',
					'$this->apellido',
					'$this->fecha_nacimiento',
					'$this->genero',
                    '$this->tipo_sangre',
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
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el numero de documento';
		}
		return $r;
	}

    // Método para modificar un cliente existente	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->cedula)){
			try {
                // Actualiza los datos del cliente				
				$co->query("Update pacientes set 
					nombre = '$this->nombre',
					apellido = '$this->apellido',
                    fecha_nacimiento = '$this->fecha_nacimiento',
                    genero = '$this->genero',
                    tipo_sangre = '$this->tipo_sangre',
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
		return $r;
	}

    // Método para eliminar un cliente	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->cedula)){
			try {
				// Elimina el cliente de la base de datos
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
		return $r;
	}	

    // Método para consultar todos los clientes	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{		
			$resultado = $co->query("Select * from pacientes  ORDER BY id DESC");
			if($resultado){	
				$respuesta = '';
				$n=1;
				// Recorre los resultados y genera la tabla HTML
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr class='text-center'>";
					$respuesta = $respuesta."<td class='align-middle'>$n</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['cedula']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['nombre']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['apellido']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['fecha_nacimiento']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['genero']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['tipo_sangre']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['alergias']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['antecedentes']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['email']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['telefono']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['direccion']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['fecha_registro']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>";
					$respuesta = $respuesta."<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar paciente'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta = $respuesta."<button type='button'class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar paciente'><i class='bi bi-trash'></i></button><br/>";
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."</tr>";
					$n++;
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  $respuesta;
			}
			else{
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  '';
			}	
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
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
				return false;;
			}	
		}catch(Exception $e){
			return false;
		}
	}	
}
?>