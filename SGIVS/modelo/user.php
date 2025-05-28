<?php
require_once('modelo/datos.php');
class user extends datos{
    // Propiedades del paciente	
	private $user;
	private $name;
	private $password;
	private $id_rol_user;
	private $status;
    // Métodos para establecer los valores de las propiedades
	function set_user($valor){
		$this->user = $valor;
	}	
	function set_name($valor){
		$this->name = $valor;
	}	
	function set_password($valor){
		$this->password = $valor;
	}	
	function set_id_rol_user($valor){
		$this->id_rol_user = $valor;
	}	
	function set_status($valor){
		$this->status = $valor;
	}
    // Métodos para obtener los valores de las propiedades		
	function get_user(){
		return $this->user;
	}
	function get_name(){
		return $this->name;
	}
	function get_password(){
		return $this->password;
	}
	function get_id_rol_user(){
		return $this->id_rol_user;
	}	
	function get_status(){
		return $this->status;
	}
    // Método para incluir un nuevo paciente en la base de datos
	function incluir(){
		$r = array();
        // Verifica si el número de documento ya existe		
		if(!$this->existe($this->user)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo paciente
				$co->query("Insert into user(user,name,password,id_rol_user,status)
					Values(
					'$this->user',
					'$this->name',
					'$this->password',
					'$this->id_rol_user',
					'$this->status'
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
			$r['mensaje'] =  'Ya existe el usuario';
		}
		return $r;
	}

    // Método para modificar un paciente existente	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->user)){
			try {
                // Actualiza los datos del paciente				
				$co->query("Update user set 
					name = '$this->name',
					password = '$this->password',
                    id_rol_user = '$this->id_rol_user',
                    status = '$this->status'
					where
					user = '$this->user'
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
			$r['mensaje'] =  'el usuario no registrado';
		}
		return $r;
	}

    // Método para eliminar un paciente	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->user)){
			try {
				// Elimina el paciente de la base de datos
				$co->query("delete from user where	user = '$this->user'");
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
			$r['mensaje'] =  'No existe el usuario';
		}
		return $r;
	}	

    // Método para consultar todos los paciente	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{		
			$resultado = $co->query("Select * from user ORDER BY id_user DESC");
			if($resultado){	
				$respuesta = '';
				$n=1;
				// Recorre los resultados y genera la tabla HTML
				foreach($resultado as $r){
					// Generar fila de la tabla
					$respuesta .= "<tr class='text-center'>";
					$respuesta .= "<td class='align-middle'>$n</td>";
					$respuesta .= "<td class='align-middle'>".$r['user']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['name']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['id_rol_user']."</td>";
					$respuesta .= "<td class='align-middle'>".$r['status']."</td>";
					$respuesta .= "<td class='align-middle'>";
					$respuesta .= "<button type='button' class='btn-sm btn-secondary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar permisos' style='margin:.2rem'><i class='bi bi-key-fill'></i></button><br/>";
					$respuesta .= "<button type='button' class='btn-sm btn-info w-50 small-width mb-1' onclick='pone(this,1)' title='Modificar paciente' style='margin:.2rem'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button'class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,2)' title='Eliminar paciente' style='margin:.2rem'><i class='bi bi-trash-fill'></i></button><br/>";
					$respuesta .= "</td>";
					$respuesta .= "</tr>";
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
	private function existe($user){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("Select * from user where user='$user'");
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
	
	function mos(){
		$mo = $this->Busca("SELECT id_rol, name_rol FROM roles ORDER BY name_rol ASC");
		return $mo;
	}
}
?>