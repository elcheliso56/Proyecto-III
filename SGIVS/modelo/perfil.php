<?php
require_once('modelo/datos.php');
class perfil extends datos{
    // Propiedades privadas del perfil
    private $usuario;
    private $nombre_apellido;
    private $contraseña;
    private $imagen;

    // Métodos para establecer los valores de las propiedades
    function set_usuario($valor){
        $this->usuario = $valor;
    }
    function set_nombre_apellido($valor){
        $this->nombre_apellido = $valor;
    }
    function set_contraseña($valor){
        $this->contraseña = $valor;
    }
    function set_imagen($valor){
        $this->imagen = $valor;
    }

    // Método para cargar el perfil del usuario
    function cargarPerfil($usuario){
        $co = $this->conecta_usuarios(); // Conectar a la base de datos de usuarios
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            // Consultar los datos del usuario por ID
            $resultado = $co->query("SELECT * FROM usuario WHERE usuario = '$usuario'");
            $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
            unset($usuario['contraseña']); // Eliminar la contraseña del resultado
            return ['resultado' => 'ok', 'datos' => $usuario]; // Retornar datos del usuario
        } catch(Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()]; // Manejo de errores
        }
    }

    // Método para modificar el perfil del usuario
    function modificarPerfil($usuario){
        $co = $this->conecta_usuarios(); // Conectar a la base de datos de usuarios
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            // Obtener la imagen actual del usuario
            $resultado = $co->query("SELECT imagen FROM usuario WHERE usuario = '$usuario'");
            $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
            $imagen_actual = $usuario['imagen'];

            // Preparar la consulta de actualización
            $query = "UPDATE usuario SET 
                usuario = '$this->usuario',
                nombre_apellido = '$this->nombre_apellido'";

            // Si se proporciona una nueva contraseña, se encripta
            if(!empty($this->contraseña)) {
                $contraseña_hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
                $query .= ", contraseña = '$contraseña_hash'";
            }
            
            // Si se proporciona una nueva imagen
            if(!empty($this->imagen)) {
                $query .= ", imagen = '$this->imagen'";
                // Eliminar la imagen anterior si existe y no es la imagen por defecto
                if ($imagen_actual && $imagen_actual != 'otros/img/usuarios/default.png' && file_exists($imagen_actual)) {
                    unlink($imagen_actual); // Eliminar archivo de imagen
                }
                $nueva_imagen = $this->imagen; // Asignar nueva imagen
            } else {
                $nueva_imagen = $imagen_actual; // Mantener imagen actual
            }
            
                $query .= " WHERE usuario = '$usuario'"; // Condición para la actualización
            
            $co->query($query); // Ejecutar la consulta
            
            return ['resultado' => 'ok', 'mensaje' => '¡Perfil actualizado con éxito!', 'nueva_imagen' => $nueva_imagen]; // Retornar éxito
        } catch(Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()]; // Manejo de errores
        }
    }
}
?>
