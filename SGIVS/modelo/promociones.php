<?php
require_once('modelo/datos.php');

class publicidad extends datos {
    private $nombre; // Nombre de la promocion
    private $descripcion; // Descripción de la promocion
    private $fecha_inicio; // fecha de inicio de la promocion
    private $fecha_fin; // fecha de finalización de la promocion
    private $imagen; // Ruta de la imagen de la promocion

    // Método para establecer el nombre
    function set_nombre($valor) {
        $this->nombre = $valor;
    }
    // Método para establecer la descripción
    function set_descripcion($valor) {
        $this->descripcion = $valor;
    }
    // Método para establecer la fecha de inicio
    function set_fecha_inicio($valor) {
        $this->fecha_inicio = $valor;
    }
    // Método para establecer la fecha de finalización
    function set_fecha_fin($valor) {
        $this->fecha_fin = $valor;
    }
    // Método para establecer la imagen
    function set_imagen($valor) {
        $this->imagen = $valor;
    }

    // Método para obtener el nombre
    function get_nombre() {
        return $this->nombre;
    }
    // Método para obtener la descripción
    function get_descripcion() {
        return $this->descripcion;
    }
    // Método para obtener la fecha de inicio
    function get_fecha_inicio() {
        return $this->fecha_inicio;
    }
    // Método para obtener la fecha de finalización
    function get_fecha_fin() {
        return $this->fecha_fin;
    }
    // Método para obtener la imagen
    function get_imagen() {
        return $this->imagen;
    }

    // Método para incluir una nueva publicidad
    function incluir() {
        $r = array();
        if (!$this->existe($this->nombre)) { // Verifica si la publicidad ya existe
            $co = $this->conecta(); // Conecta a la base de datos
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // Inserta la nueva publicidad en la base de datos
                $co->query("Insert into publicidad(nombre, descripcion, fecha_inicio, fecha_fin, imagen)
                    Values(
                    '$this->nombre',
                    '$this->descripcion',
                    '$this->fecha_inicio',
                    '$this->fecha_fin',
                    '$this->imagen')");
                $r['resultado'] = 'incluir';
                $r['mensaje'] = '¡Registro guardado con exito!';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage(); // Captura errores
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe el nombre de documento'; // Mensaje si ya existe
        }
        return $r; // Retorna el resultado
    }

    // Método para modificar una publicidad existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->nombre)) { // Verifica si la publicidad existe
            try {
                $co->beginTransaction();
                date_default_timezone_set('America/Caracas');//zona horaria 
                $fecha = date('Y-m-d H:i:s');
                // Obtiene la imagen actual de la publicidad
                $resultado = $co->query("SELECT imagen FROM publicidad WHERE nombre = '$this->nombre'");
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                $imagen_actual = $fila['imagen'];
                // Actualiza la publicidad en la base de datos
                $co->query("UPDATE publicidad SET 
                    descripcion = '$this->descripcion',
                    fecha_inicio = '$this->fecha_inicio',
                    fecha_fin = '$this->fecha_fin',
                    imagen = '$this->imagen'
                    WHERE
                    nombre = '$this->nombre'");
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Registro actualizado con exito!';
                // Elimina la imagen anterior si no es la imagen por defecto
                if ($imagen_actual && $imagen_actual != 'otros/img/publicidad/default.png') {
                    if (file_exists($imagen_actual)) {
                        unlink($imagen_actual); // Elimina el archivo de imagen
                    }
                }
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage(); // Captura errores
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'nombre de documento no registrado'; // Mensaje si no existe
        }
        return $r; // Retorna el resultado
    }

    // Método para eliminar una publicidad
    function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->nombre)) { // Verifica si la publicidad existe
            try {
                // Obtiene la imagen de la publicidad a eliminar
                $resultado = $co->query("SELECT imagen FROM publicidad WHERE nombre = '$this->nombre'");
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                $imagen = $fila['imagen'];
                // Elimina la publicidad de la base de datos
                $co->query("DELETE FROM publicidad WHERE nombre = '$this->nombre'");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = '¡Registro eliminado con exito!';
                // Elimina la imagen si no es la imagen por defecto
                if ($imagen && $imagen != 'otros/img/publicidad/default.png') {
                    if (file_exists($imagen)) {
                        unlink($imagen); // Elimina el archivo de imagen
                    }
                }
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar esta publicidad porque tiene productos asociados';
                } else {
                    $r['mensaje'] = $e->getMessage();
                }
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'No existe el nombre de documento'; // Mensaje si no existe
        }
        return $r; // Retorna el resultado
    }

    // Método para consultar todas las publicidads
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("Select * from publicidad ORDER BY id DESC"); // Consulta todas las publicidads
            if ($resultado) {
                $respuesta = '';
                $n = 1; // Contador para enumerar las publicidads
                foreach ($resultado as $r) {
                    // Construye la tabla de resultados
                    $respuesta = $respuesta . "<tr class='text-center'>";
                    $respuesta = $respuesta . "<td class='align-middle'>$n</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['nombre'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['descripcion'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['fecha_inicio'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['fecha_fin'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'><a href='" .$r['imagen'] . "' target='_blank'><img src='" . $r['imagen'] . "' alt='Imagen de la publicidad' class='img'/></a></td>";           
                    $respuesta = $respuesta . "<td class='align-middle'>";
                    $respuesta = $respuesta .
                    "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar publicidad'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta = $respuesta . "<button type='button'
                    class='btn-sm btn-danger w-50 small-width mt-1' 
                    onclick='pone(this,1)'
                    title='Eliminar publicidad'
                    ><i class='bi bi-trash'></i></button><br/>";
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "</tr>";
                    $n++; // Incrementa el contador
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta; // Retorna la tabla de publicidads
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = ''; // Sin resultados
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage(); // Captura errores
        }
        return $r; // Retorna el resultado
    }

    // Método privado para verificar si una publicidad existe
    private function existe($nombre) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("Select * from publicidad where nombre='$nombre'"); // Consulta por nombre
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if ($fila) {
                return true; // La publicidad existe
            } else {
                return false; // La publicidad no existe
            }
        } catch (Exception $e) {
            return false; // Captura errores
        }
    }
}
?>