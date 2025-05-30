<?php
class publicidad {
    private $nombre;
    private $apellido;
    private $telefono;
    private $motivo;

    public function set_nombre($nombre) {
        $this->nombre = trim(htmlspecialchars($nombre));
    }

    public function set_apellido($apellido) {
        $this->apellido = trim(htmlspecialchars($apellido));
    }

    public function set_telefono($telefono) {
        $this->telefono = '+58' . trim(htmlspecialchars($telefono));
    }

    public function set_motivo($motivo) {
        $this->motivo = trim(htmlspecialchars($motivo));
    }

    public function validarDatos() {
        $errors = [];

        if (empty($this->nombre)) {
            $errors[] = "El campo Nombre es obligatorio.";
        }

        if (empty($this->apellido)) {
            $errors[] = "El campo Apellido es obligatorio.";
        } elseif (!preg_match("/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u", $this->apellido)) {
            $errors[] = "El Apellido solo debe contener letras y espacios.";
        } elseif (mb_strlen($this->apellido, 'UTF-8') > 16) {
            $errors[] = "El Apellido no debe exceder los 16 caracteres.";
        }

        $telefono_sin_prefijo = substr($this->telefono, 3);
        if (empty($telefono_sin_prefijo)) {
            $errors[] = "El campo Teléfono es obligatorio.";
        } elseif (!preg_match("/^[0-9]+$/", $telefono_sin_prefijo)) {
            $errors[] = "El número telefónico solo debe contener dígitos numéricos.";
        } elseif (mb_strlen($telefono_sin_prefijo, 'UTF-8') !== 10) {
            $errors[] = "El número telefónico debe tener exactamente 10 dígitos.";
        }

        if (empty($this->motivo)) {
            $errors[] = "El campo Motivo de Contacto es obligatorio.";
        }

        return $errors;
    }

    public function incluir() {
        $errors = $this->validarDatos();
        if (!empty($errors)) {
            return [
                'resultado' => 'error',
                'mensaje' => implode("<br>", $errors)
            ];
        }

        try {
            $conn = new mysqli("localhost", "root", "", "sgivs");
            
            if ($conn->connect_error) {
                throw new Exception("Error de conexión a la base de datos.");
            }

            $stmt = $conn->prepare("INSERT INTO citas_contacto (nombre, apellido, telefono, motivo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $this->nombre, $this->apellido, $this->telefono, $this->motivo);

            if ($stmt->execute()) {
                $respuesta = [
                    'resultado' => 'ok',
                    'mensaje' => "Su mensaje ha sido enviado correctamente. Nos pondremos en contacto pronto."
                ];
            } else {
                throw new Exception("Error al ejecutar la consulta.");
            }

            $stmt->close();
            $conn->close();
            return $respuesta;

        } catch (Exception $e) {
            return [
                'resultado' => 'error',
                'mensaje' => "Hubo un error al enviar su mensaje: " . $e->getMessage()
            ];
        }
    }
}
?> 