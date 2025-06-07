<?php
require_once('modelo/usuarios.php');

// Clase 'login' que extiende de 'usuarios'
class login extends usuarios{

	private $nombre_usuario; // Almacena el nombre de usuario
	private $tipo_usuario; // Almacena el tipo de usuario
	private $contraseña; // Almacena la contraseña del usuario

	// Método para establecer el nombre de usuario
	function set_nombre_usuario($valor){
		$this->nombre_usuario = $valor;
	}

	// Método para establecer el tipo de usuario
	function set_tipo_usuario($valor){
		$this->tipo_usuario = $valor;
	}

	// Método para establecer la contraseña
	function set_contraseña($valor){
		$this->contraseña = $valor;
	}	

	// Método para obtener el nombre de usuario
	function get_nombre_usuario(){
		return $this->nombre_usuario;
	}

	// Método para obtener el tipo de usuario
	function get_tipo_usuario(){
		return $this->tipo_usuario;
	}

	// Método para obtener la contraseña
	function get_contraseña(){
		return $this->contraseña;
	}

	// Método para autenticar al usuario
	function autenticar($nombre_usuario, $contraseña){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		try {
			// Validar que los parámetros no estén vacíos
			if(empty($nombre_usuario) || empty($contraseña)) {
				throw new Exception("Usuario y contraseña son requeridos");
			}

			// Preparar la consulta para buscar el usuario y su rol
			$stmt = $co->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
			$stmt->bindParam(':nombre_usuario', $nombre_usuario);
			$stmt->execute();
			$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

			// Verificar si el usuario existe y si la contraseña es correcta
			if($usuario && password_verify($contraseña, $usuario['contraseña'])){
				// Almacenar información del usuario en la sesión
				$_SESSION['usuario_id'] = $usuario['id'];
				$_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
				$_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
				
				// Registrar el inicio de sesión en la bitácora
				$this->registrarBitacora('Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente');
				
				$r['resultado'] = 'login_success';
				$r['mensaje'] = 'Inicio de sesión exitoso';
			} else {
				// Registrar el intento fallido en la bitácora
				$this->registrarBitacora('Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', "Usuario: $nombre_usuario");
				
				$r['resultado'] = 'login_error';
				$r['mensaje'] = 'Nombre de usuario o contraseña incorrectos';
			}
		} catch(Exception $e){
			error_log("Error en autenticación: " . $e->getMessage());
			$r['resultado'] = 'error';
			$r['mensaje'] = 'Error en el servidor: ' . $e->getMessage();
		} finally {
			$co = null; // Cerrar la conexión
		}
		return $r;
	}
}
?>