<?php
require_once('modelo/usuarios.php');

// Clase 'login' que extiende de 'usuarios'
class login extends usuarios{

	private $usuario; // Almacena el nombre de usuario
	private $id_rol; // Almacena el tipo de usuario
	private $contraseña; // Almacena la contraseña del usuario

	// Método para establecer el nombre de usuario
	function set_usuario($valor){
		$this->usuario = $valor;
	}

	// Método para establecer el tipo de usuario
	function set_id_rol($valor){
		$this->id_rol = $valor;
	}

	// Método para establecer la contraseña
	function set_contraseña($valor){
		$this->contraseña = $valor;
	}	

	// Método para obtener el nombre de usuario
	function get_usuario(){
		return $this->usuario;
	}

	// Método para obtener el tipo de usuario
	function get_id_rol(){
		return $this->id_rol;
	}

	// Método para obtener la contraseña
	function get_contraseña(){
		return $this->contraseña;
	}

	// Método para autenticar al usuario
	function autenticar($usuario, $contraseña){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		try {
			// Validar que los parámetros no estén vacíos
			if(empty($usuario) || empty($contraseña)) {
				throw new Exception("Usuario y contraseña son requeridos");
			}

			// Preparar la consulta para buscar el usuario y su rol
			$stmt = $co->prepare("
				SELECT u.*, ur.id_rol, r.nombre_rol 
				FROM usuario u
				INNER JOIN usuario_rol ur ON u.usuario = ur.usuario
				INNER JOIN roles r ON ur.id_rol = r.id
				WHERE u.usuario = :usuario AND ur.estado = 'ACTIVO'
			");
			error_log("Ejecutando consulta para usuario");
			$stmt->bindParam(':usuario', $usuario);
			$stmt->execute();
			$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

			// Verificar si el usuario existe y si la contraseña es correcta
			if($usuario && password_verify($contraseña, $usuario['contrasena'])){
				// Almacenar información del usuario en la sesión
				$_SESSION['usuario'] = $usuario['usuario'];
				$_SESSION['nombre_apellido'] = $usuario['nombre_apellido'];
				$_SESSION['imagen_usuario'] = $usuario['imagen'];
				$_SESSION['id_rol'] = $usuario['id_rol'];
				$_SESSION['rol_nombre'] = $usuario['nombre_rol'];
				
				// Registro de depuración
				error_log("Datos de sesión establecidos - Usuario: " . $_SESSION['usuario'] . ", Nombre y Apellido: " . $_SESSION['nombre_apellido']);
				
				// Registrar el inicio de sesión en la bitácora
				$this->registrarBitacora('Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente');
				
				$r['resultado'] = 'login_success';
				$r['mensaje'] = 'Inicio de sesión exitoso';
			} else {
				// Registrar el intento fallido en la bitácora
				$this->registrarBitacora('Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', "Usuario: $usuario");
				
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