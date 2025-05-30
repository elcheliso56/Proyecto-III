<?php
class datos {
    // Propiedades privadas para la conexión a la base de datos principal
    private $ip = "localhost";
    private $bd = "sgivs";
    private $usuario = "root";
    private $contrasena = "";

    // Propiedades privadas para la conexión a la base de datos de usuarios
    private $bd_usuarios = "bd_usuarios";

    // Método para establecer la conexión a la base de datos principal
    function conecta() {
        $pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd."",$this->usuario,$this->contrasena);
        $pdo->exec("set names utf8");
        return $pdo;
    }

    // Método para establecer la conexión a la base de datos de usuarios
    function conecta_usuarios() {
        $pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd_usuarios."",$this->usuario,$this->contrasena);
        $pdo->exec("set names utf8");
        return $pdo;
    }

    // Método para registrar acciones en la bitácora
    protected function registrarBitacora($modulo, $accion, $descripcion, $detalles = null) {
        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            date_default_timezone_set('America/Caracas');
            $fecha_hora = date('Y-m-d H:i:s');
            $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

            $stmt = $co->prepare("INSERT INTO bitacora (fecha_hora, modulo, accion, descripcion, detalles, usuario_id) 
                                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$fecha_hora, $modulo, $accion, $descripcion, $detalles, $usuario_id]);
        } catch(Exception $e) {
            error_log("Error al registrar en bitácora: " . $e->getMessage());
        } finally {
            $co = null;
        }
    }
}
?>