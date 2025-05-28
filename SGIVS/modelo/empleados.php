<?php
require_once('modelo/datos.php');
class empleados extends datos{
    // Propiedades del empleados	
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
				$co->query("Insert into empleados(cedula,nombre,apellido,fecha_nacimiento,genero,email,telefono,direccion,fecha_contratacion,cargo,salario)
					Values(
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
		return $r;
	}	

    // Método para consultar todos los empleado	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{		
			$resultado = $co->query("Select * from empleados ORDER BY id_empleado DESC");
			if($resultado){	
				$respuesta = '';
				$n=1;
				// Recorre los resultados y genera la tabla HTML
				foreach($resultado as $r){
					// Calcular edad
					$fecha_nacimiento = new DateTime($r['fecha_nacimiento']);
					$hoy = new DateTime();
					$edad = $hoy->diff($fecha_nacimiento)->y;
					// Genera la fila de la tabla con los datos del empleado
					$respuesta .= "<tr class='text-center'>";
					$respuesta .= "<td class='align-middle'>$n</td>";
					$respuesta .= "<td class='align-middle'>".$r['cedula']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['nombre']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['apellido']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['fecha_nacimiento']."</td>";
					$respuesta .= "<td class='align-middle'>".$edad."</td>";
					$respuesta .= "<td class='align-middle'>".$r['genero']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['email']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['telefono']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['direccion']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['fecha_contratacion']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['cargo']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['salario']."</td>";
					$respuesta .= "<td class='align-middle' style='display:flex;'>";
					$respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar paciente' style='margin:.2rem'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button'class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar paciente' style='margin:.2rem'><i class='bi bi-trash-fill'></i></button><br/>";
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
			$resultado = $co->query("Select * from empleados where cedula='$cedula'");
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