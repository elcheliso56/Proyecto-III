<?php
require_once('modelo/datos.php');
class consultas extends datos{
    // Propiedades del cliente	
	private $cedula;
	private $nombre;
	private $apellido;
	private $telefono;
	private $tratamiento;
    private $fechaconsulta;

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
	}	
	function set_telefono($valor){
		$this->telefono = $valor;
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
	function get_correo(){
		return $this->correo;
	}	
	function get_telefono(){
		return $this->telefono;
	}	
	function get_direccion(){
		return $this->direccion;
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
				$co->query("Insert into clientes(tipo_documento, cedula,nombre,apellido,correo,telefono,direccion)
					Values(
					'$this->tipo_documento',
					'$this->cedula',
					'$this->nombre',
					'$this->apellido',
					'$this->correo',
					'$this->telefono',
					'$this->direccion')");
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
				$co->query("Update clientes set 
					tipo_documento = '$this->tipo_documento',
					cedula = '$this->cedula',
					nombre = '$this->nombre',
					apellido = '$this->apellido',
					correo = '$this->correo',
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
				$co->query("delete from clientes 
					where
					cedula = '$this->cedula'
					");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este cliente porque tiene movimientos asociados';
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
			$resultado = $co->query("Select * from clientes  ORDER BY id DESC");
			if($resultado){	
				$respuesta = '';
				$n=1;
				// Recorre los resultados y genera la tabla HTML
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr class='text-center'>";
					$respuesta = $respuesta."<td class='align-middle'>$n</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['tipo_documento'].":".$r['cedula']."</td>";					
					$respuesta = $respuesta."<td class='align-middle'>".$r['nombre']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['apellido']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['correo']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['telefono']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['direccion']."</td>";									
					$respuesta = $respuesta."<td class='align-middle'>";
					$respuesta = $respuesta.
					"<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar cliente'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta = $respuesta."<button type='button'
					class='btn-sm btn-danger w-50 small-width mt-1' 
					onclick='pone(this,1)'
					title='Eliminar cliente'
					><i class='bi bi-trash'></i></button><br/>";
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
			$resultado = $co->query("Select * from clientes where cedula='$cedula'");
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