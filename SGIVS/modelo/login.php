<?php
require_once(__DIR__ . '/usuario.php');

// Clase 'login' que extiende de 'usuarios'
class login extends usuarios{

	private $usuario; // Almacena el nombre de usuario
	private $id_rol; // Almacena el tipo de usuario
	private $id_permiso; // Almacena el tipo de usuario
	private $contrasena; // Almacena la contraseña del usuario

	// Método para establecer el nombre de usuario
	function set_usuario($valor){
		$this->usuario = $valor;
	}

	// Método para establecer el tipo de usuario
	function set_id_rol($valor){
		$this->id_rol = $valor;
	}

	// Método para establecer la contraseña
	function set_id_permiso($valor){
		$this->id_permiso = $valor;
	}	

	// Método para establecer la contraseña
	function set_contrasena($valor){
		$this->contrasena = $valor;
	}

	// Método para obtener el nombre de usuario
	function get_usuario(){
		return $this->usuario;
	}

	// Método para obtener el tipo de usuario
	function get_id_rol(){
		return $this->id_rol;
	}

	// Método para obtener el tipo de permiso
	function get_id_permiso(){
		return $this->id_permiso;
	}

	// Método para obtener la contraseña
	function get_contrasena(){
		return $this->contraseña;
	}

	// Método para autenticar al usuario
	function autenticar($usuario, $id_rol, $id_permiso, $contrasena){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		try {
			// Validar que los parámetros no estén vacíos
			if(empty($usuario) || empty($contrasena)) {
				throw new Exception("Usuario y contraseña son requeridos");
			}

			// Preparar la consulta para buscar el usuario y su rol
			$stmt = $co->prepare("SELECT u.*, ur.id_rol, r.nombre_rol, GROUP_CONCAT(rp.id_permiso) as permisos, u.nombre_apellido, u.imagen
                                  FROM usuario AS u 
                                  INNER JOIN usuario_rol as ur ON ur.usuario = u.usuario 
                                  INNER JOIN roles r ON ur.id_rol = r.id
                                  LEFT JOIN rol_permiso rp ON ur.id_rol = rp.id_rol
                                  WHERE u.usuario = :usuario
                                  GROUP BY u.usuario");
			$stmt->bindParam(':usuario', $usuario);
			$stmt->execute();
			$usuario_data = $stmt->fetch(PDO::FETCH_ASSOC);

			// Verificar si el usuario existe y si la contraseña es correcta
			if($usuario_data && password_verify($contrasena, $usuario_data['contrasena'])){
				// Almacenar información del usuario en la sesión
				$_SESSION['usuario'] = $usuario_data['usuario'];
				$_SESSION['id_rol'] = $usuario_data['id_rol'];
				$_SESSION['nombre_rol'] = $usuario_data['nombre_rol'];
				$_SESSION['permisos'] = !empty($usuario_data['permisos']) ? explode(',', $usuario_data['permisos']) : [];
				$_SESSION['nombre_apellido'] = $usuario_data['nombre_apellido'];
				$_SESSION['imagen'] = $usuario_data['imagen'];
				
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