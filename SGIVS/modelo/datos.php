<?php
class datos {
    // Propiedades privadas para la conexión a la base de datos
	private $ip = "localhost"; // Dirección IP del servidor de la base de datos
    private $bd = "inventario"; // Nombre de la base de datos
    private $usuario = "root"; // Usuario de la base de datos
    private $contrasena = ""; // Contraseña del usuario
    private $pdo = null; // Propiedad para almacenar la conexión PDO

    // Método para establecer la conexión a la base de datos
    function conecta() {
        try {
            // Si ya existe una conexión, la retornamos
            if ($this->pdo !== null) {
                return $this->pdo;
            }

            // Crear una nueva instancia de PDO para la conexión
            $this->pdo = new PDO(
                "mysql:host=".$this->ip.";dbname=".$this->bd."",
                $this->usuario,
                $this->contrasena,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );

            return $this->pdo;
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error al conectar con la base de datos");
        }
    }

    // Método para cerrar la conexión
    function cerrarConexion() {
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }

    // Destructor para asegurar que la conexión se cierre
    function __destruct() {
        $this->cerrarConexion();
    }
}
?>