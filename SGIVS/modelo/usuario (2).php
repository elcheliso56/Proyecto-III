<?php
require_once('modelo/datos.php');
class usuarios extends datos{    
// Propiedades de la clase
	private $usuario;
	private $id_rol; 
	private $contraseña;
	private $imagen;
	private $estado;

	// Métodos para establecer los valores de las propiedades
	function set_usuario($valor){
		$this->usuario = $valor; 
	}
	function set_id_rol($valor){
		$this->id_rol = $valor;
	}
	function set_contraseña($valor){
		$this->contraseña = $valor;
	}
	function set_imagen($valor){
		$this->imagen = $valor;
	}
	function set_estado($valor){
		$this->estado = $valor;
	}

	function get_usuario(){
		return $this->usuario;
	}
	function get_id_rol(){
		return $this->id_rol;
	}
	function get_contraseña(){
		return $this->contraseña;
	}
	function get_imagen(){
		return $this->imagen;
	}
	function get_estado(){
		return $this->estado;
	}
	
	function incluir(){
		$r = array();
		if(!$this->existe($this->usuario)){
			$co = $this->conecta_usuarios();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$contraseña_hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
				$co->query("START TRANSACTION;

							INSERT INTO usuario (usuario, contrasena, imagen) 
							VALUES ('$this->usuario', '$contraseña_hash', '$this->imagen');

							INSERT INTO usuario_rol (usuario, id_rol, estado)
							VALUES ('$this->usuario', '$this->id_rol', '$this->estado');

							COMMIT;");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con exito!';
			} catch(Exception $e) {
				$co->query("ROLLBACK");
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

	function modificar(){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->usuario)){
			try {
				$resultado = $co->query("SELECT imagen FROM usuario WHERE usuario = '$this->usuario'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];

				$co->query("START TRANSACTION");

				// Si hay nueva contraseña, actualizarla
				if(!empty($this->contraseña)) {
					$contraseña_hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
					$co->query("UPDATE usuario SET 
								contrasena = '$contraseña_hash',
								imagen = '$this->imagen'
								WHERE usuario = '$this->usuario'");
				} else {
					$co->query("UPDATE usuario SET 
								imagen = '$this->imagen'
								WHERE usuario = '$this->usuario'");
				}

				$co->query("UPDATE usuario_rol SET 
							id_rol = '$this->id_rol',
							estado = '$this->estado'
							WHERE usuario = '$this->usuario'");

				$co->query("COMMIT");

				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro modificado con éxito!';

				// Eliminar imagen anterior si existe y es diferente a la default
				if ($imagen_actual && $imagen_actual != 'otros/img/usuarios/default.png' && $imagen_actual != $this->imagen) {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$co->query("ROLLBACK");
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Usuario no registrado';
		}
		return $r;
	}

	function eliminar(){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->usuario)){
			try {
				$resultado = $co->query("SELECT imagen FROM usuario WHERE usuario = '$this->usuario'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];

				$co->query("START TRANSACTION;
							
							DELETE FROM usuario_rol 
							WHERE usuario = '$this->usuario';
							
							DELETE FROM usuario 
							WHERE usuario = '$this->usuario';
							
							COMMIT;");

				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';

				if ($imagen && $imagen != 'otros/img/usuarios/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}
			} catch(Exception $e) {
				$co->query("ROLLBACK");
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el usuario';
		}
		return $r;
	}

	// Método para consultar todos los usuarios
	function consultar(){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("SELECT u.usuario, u.imagen, r.nombre_rol, ur.estado 
                                    FROM usuario_rol AS ur 
                                    INNER JOIN roles AS r ON ur.id_rol = r.id 
                                    INNER JOIN usuario AS u ON ur.usuario = u.usuario");
			$usuario = [];
			foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $row){
				$usuario[] = $row;
			}
			$r['resultado'] = 'consultar';
			$r['mensaje'] = $usuario;
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		$co = null;
		return $r;
	}
	private function existe($usuario){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("SELECT * FROM usuario WHERE usuario = '$usuario'");
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


	// Método para obtener datos de un usuario específico
	function obtenerDatosUsuario($id){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("SELECT * FROM usuarios WHERE id = '$id'");
			return $resultado->fetch(PDO::FETCH_ASSOC);
		} catch(Exception $e){
			return false;
		}
	}

	// Método para modificar el perfil de un usuario
	function modificarPerfil($id){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$query = "UPDATE usuarios SET 
			nombre = '$this->nombre',
			apellido = '$this->apellido',
			correo = '$this->correo',
			telefono = '$this->telefono',
			nombre_usuario = '$this->nombre_usuario'";
			
			if(!empty($this->contraseña)){
				$contraseña_hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
				$query .= ", contraseña = '$contraseña_hash'";
			}
			
			if(!empty($this->imagen)){
				$query .= ", imagen = '$this->imagen'";
			}
			
			$query .= " WHERE id = '$id'";
			
			$co->query($query);
			
			$r['resultado'] = 'modificar';
			$r['mensaje'] = '¡Perfil actualizado con éxito!';
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	// Método para listar empleados
	function listaEmpleados(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT cedula, nombre, apellido FROM empleados ORDER BY nombre ASC");
			if ($resultado) {
				$respuesta = '';
				foreach ($resultado as $r) {
					$respuesta .= "<tr style='cursor:pointer' onclick='colocaEmpleado(this);'>";
					$respuesta .= "<td class='text-center'>" . $r['cedula'] . "</td>";
					$respuesta .= "<td class='text-center'>" . $r['nombre'] . "</td>";
					$respuesta .= "<td class='text-center'>" . $r['apellido'] . "</td>";
					$respuesta .= "</tr>";
				}
				$r['resultado'] = 'listaEmpleados';
				$r['mensaje'] = $respuesta;
			} else {
				$r['resultado'] = 'listaEmpleados';
				$r['mensaje'] = '';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}
}
?>