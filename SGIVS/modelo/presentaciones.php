<?php
require_once('modelo/datos.php');

class presentaciones extends datos {
    private $nombre; // Nombre de la presentacion
    private $descripcion; // Descripción de la presentacion

    // Método para establecer el nombre
    function set_nombre($valor) {
        $this->nombre = $valor;
    }

    // Método para establecer la descripción
    function set_descripcion($valor) {
        $this->descripcion = $valor;
    }

    // Método para obtener el nombre
    function get_nombre() {
        return $this->nombre;
    }

    // Método para obtener la descripción
    function get_descripcion() {
        return $this->descripcion;
    }

    // Método para incluir una nueva presentacion
    function incluir() {
        $r = array();
        if (!$this->existe($this->nombre)) { // Verifica si la presentacion ya existe
            $co = $this->conecta(); // Conecta a la base de datos
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                // Inserta la nueva presentacion en la base de datos
                $co->query("Insert into presentaciones(nombre, descripcion)
                    Values( '$this->nombre','$this->descripcion')");
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

    // Método para modificar una presentacion existente
    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->nombre)) { // Verifica si la presentacion existe
            try {
                // Actualiza la presentacion en la base de datos
                $co->query("UPDATE presentaciones SET 
                    descripcion = '$this->descripcion'
                    WHERE
                    nombre = '$this->nombre'");
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Registro actualizado con exito!';
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

    // Método para eliminar una presentacion
    function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->nombre)) { // Verifica si la presentacionexiste
            try {
                $co->query("DELETE FROM presentaciones WHERE nombre = '$this->nombre'");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = '¡Registro eliminado con exito!';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar esta presentacion porque tiene insumos asociados';
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

    // Método para consultar todas las presentaciones
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("Select * from presentaciones ORDER BY id DESC"); // Consulta todas las presentaciones
            if ($resultado) {
                $respuesta = '';
                $n = 1; // Contador para enumerar las presentaciones
                foreach ($resultado as $r) {
                    // Construye la tabla de resultados
                    $respuesta = $respuesta . "<tr class='text-center'>";
                    $respuesta = $respuesta . "<td class='align-middle'>$n</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['nombre'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['descripcion'] . "</td>";        
                    $respuesta = $respuesta . "<td class='align-middle'>";
                    $respuesta = $respuesta .
                    "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar presentacion'><i class='bi bi-arrow-repeat'></i></button><br/>";
                    $respuesta = $respuesta . "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar presentacion'><i class='bi bi-trash'></i></button><br/>";
                    $respuesta = $respuesta . "</td>";
                    $respuesta = $respuesta . "</tr>";
                    $n++; // Incrementa el contador
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta; // Retorna la tabla de presentaciones
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

    // Método privado para verificar si una presentaciones existe
    private function existe($nombre) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("Select * from presentaciones where nombre='$nombre'"); // Consulta por nombre
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            if ($fila) {
                return true; // La presentaciones existe
            } else {
                return false; // La presentaciones no existe
            }
        } catch (Exception $e) {
            return false; // Captura errores
        }
    }
}
?>