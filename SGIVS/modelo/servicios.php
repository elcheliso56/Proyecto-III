<?php
require_once('modelo/datos.php');

class servicios extends datos {
    private $nombre; // Nombre de la servicio
    private $descripcion; // Descripción de la servicio
    private $precio;
    private $errores = array(); // Array para almacenar errores de validación

    // Método para establecer el nombre
    function set_nombre($valor) {
        $this->nombre = $valor;
    }

    // Método para establecer la descripción
    function set_descripcion($valor) {
        $this->descripcion = $valor;
    }

    function set_precio($valor){
        $this->precio = $valor;
    }

    // Método para obtener el nombre
    function get_nombre() {
        return $this->nombre;
    }

    // Método para obtener la descripción
    function get_descripcion() {
        return $this->descripcion;
    }

    function get_precio(){
        return $this->precio;
    }   

    // Métodos de validación
    private function validarNombre($nombre) {
        if (empty($nombre)) {
            $this->errores[] = "El nombre es obligatorio";
            return false;
        }
        if (strlen($nombre) < 3 || strlen($nombre) > 30) {
            $this->errores[] = "El nombre debe tener entre 3 y 30 caracteres";
            return false;
        }
        if (!preg_match('/^[^"\']{3,30}$/', $nombre)) {
            $this->errores[] = "El nombre contiene caracteres no permitidos";
            return false;
        }
        return true;
    }

    private function validarDescripcion($descripcion) {
        if (!empty($descripcion)) {
            if (strlen($descripcion) > 100) {
                $this->errores[] = "La descripción no puede tener más de 100 caracteres";
                return false;
            }
            if (!preg_match('/^[^"\']{0,100}$/', $descripcion)) {
                $this->errores[] = "La descripción contiene caracteres no permitidos";
                return false;
            }
        }
        return true;
    }



    private function validarDatos() {
        $this->errores = array();
        
        $valido = true;
        $valido = $this->validarNombre($this->nombre) && $valido;
        $valido = $this->validarDescripcion($this->descripcion) && $valido;


        return $valido;
    }

    // Método para incluir un servicio con sus insumos y equipos
    function incluir() {
        $r = array();
        
        if (!$this->validarDatos()) {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = implode(", ", $this->errores);
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if (!$this->existe($this->nombre)) {
            try {
                $co->beginTransaction();
                
                // Inserta el nuevo servicio
                $stmt = $co->prepare("INSERT INTO servicios(nombre, descripcion, precio) VALUES(?, ?, ?)");
                $stmt->execute([$this->nombre, $this->descripcion, $this->precio]);
                $servicio_id = $co->lastInsertId();

                // Procesa los insumos si existen
                if (isset($_POST['id_insumo']) && is_array($_POST['id_insumo'])) {
                    for($i = 0; $i < count($_POST['id_insumo']); $i++) {
                        $insumo_id = $_POST['id_insumo'][$i];
                        $cantidad = $_POST['cantidad'][$i];
                        $precio = $_POST['precio'][$i];
                        
                        // Inserta la relación servicio-insumo
                        $stmt = $co->prepare("INSERT INTO servicios_insumos(id_servicio, id_insumo, cantidad, precio) VALUES(?, ?, ?, ?)");
                        $stmt->execute([$servicio_id, $insumo_id, $cantidad, $precio]);
                        
                        // Actualiza el precio del insumo
                        $stmt = $co->prepare("UPDATE insumos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $insumo_id]);
                    }
                }

                // Procesa los equipos si existen
                if (isset($_POST['id_equipo']) && is_array($_POST['id_equipo'])) {
                    for($i = 0; $i < count($_POST['id_equipo']); $i++) {
                        $equipo_id = $_POST['id_equipo'][$i];
                        $cantidad = $_POST['cantidad'][$i];
                        $precio = $_POST['precio'][$i];
                        
                        // Inserta la relación servicio-equipo
                        $stmt = $co->prepare("INSERT INTO servicios_equipos(id_servicio, id_equipo, cantidad, precio) VALUES(?, ?, ?, ?)");
                        $stmt->execute([$servicio_id, $equipo_id, $cantidad, $precio]);

                        // Actualiza el precio del equipo
                        $stmt = $co->prepare("UPDATE equipos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $equipo_id]);
                    }
                }

                $co->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = '¡Servicio registrado con éxito!';
            } catch (Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            } finally {
                $co = null; // Cierra la conexión
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe un servicio con ese nombre';
        }
        return $r;
    }

    // Método para modificar un servicio existente
    function modificar() {
        $r = array();
        
        if (!$this->validarDatos()) {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = implode(", ", $this->errores);
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if ($this->existe($this->nombre)) {
            try {
                $co->beginTransaction();
                
                // 1. Obtener el ID del servicio
                $stmt = $co->prepare("SELECT id FROM servicios WHERE nombre = ?");
                $stmt->execute([$this->nombre]);
                $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
                $servicio_id = $servicio['id'];
                
                // 2. Actualizar el servicio
                $stmt = $co->prepare("UPDATE servicios SET descripcion = ?, precio = ? WHERE nombre = ?");
                $stmt->execute([$this->descripcion, $this->precio, $this->nombre]);
                
                // 3. Eliminar relaciones anteriores
                $stmt = $co->prepare("DELETE FROM servicios_insumos WHERE id_servicio = ?");
                $stmt->execute([$servicio_id]);
                
                $stmt = $co->prepare("DELETE FROM servicios_equipos WHERE id_servicio = ?");
                $stmt->execute([$servicio_id]);
                
                // 4. Procesar nuevos insumos si existen
                if (isset($_POST['id_insumo']) && is_array($_POST['id_insumo'])) {
                    for($i = 0; $i < count($_POST['id_insumo']); $i++) {
                        $insumo_id = $_POST['id_insumo'][$i];
                        $cantidad = $_POST['cantidad'][$i];
                        $precio = $_POST['precio'][$i];
                        
                        // Insertar nueva relación servicio-insumo
                        $stmt = $co->prepare("INSERT INTO servicios_insumos(id_servicio, id_insumo, cantidad, precio) VALUES(?, ?, ?, ?)");
                        $stmt->execute([$servicio_id, $insumo_id, $cantidad, $precio]);

                        // Actualizar el precio del insumo
                        $stmt = $co->prepare("UPDATE insumos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $insumo_id]);
                    }
                }
                
                // 5. Procesar nuevos equipos si existen
                if (isset($_POST['id_equipo']) && is_array($_POST['id_equipo'])) {
                    for($i = 0; $i < count($_POST['id_equipo']); $i++) {
                        $equipo_id = $_POST['id_equipo'][$i];
                        $cantidad = $_POST['cantidad'][$i];
                        $precio = $_POST['precio'][$i];
                        
                        // Insertar nueva relación servicio-equipo
                        $stmt = $co->prepare("INSERT INTO servicios_equipos(id_servicio, id_equipo, cantidad, precio) VALUES(?, ?, ?, ?)");
                        $stmt->execute([$servicio_id, $equipo_id, $cantidad, $precio]);

                        // Actualizar el precio del equipo
                        $stmt = $co->prepare("UPDATE equipos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $equipo_id]);
                    }
                }
                
                $co->commit();
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Registro actualizado con éxito!';
            } catch (Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Error al modificar: ' . $e->getMessage();
            } finally {
                $co = null; // Cierra la conexión
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'No existe un servicio con ese nombre';
        }
        return $r;
    }

    // Método para eliminar un servicio
    function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if ($this->existe($this->nombre)) { 
            try {
                $co->query("DELETE FROM servicios WHERE nombre = '$this->nombre'");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = '¡Registro eliminado con exito!';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                if ($e->getCode() == 23000) {
                    $r['mensaje'] = 'No se puede eliminar este servicio porque tiene consultas asociadas';
                } else {
                    $r['mensaje'] = $e->getMessage();
                }
            } finally {
                $co = null; // Cierra la conexión
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'No existe el nombre de documento'; // Mensaje si no existe
        }
        return $r; // Retorna el resultado
    }

    // Método para consultar todos los servicios con sus detalles
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Primero obtenemos los servicios
            $resultado = $co->query("
                SELECT s.*, 
                (SELECT SUM(si.cantidad * si.precio) FROM servicios_insumos si WHERE si.id_servicio = s.id) as total_insumos,
                (SELECT SUM(se.cantidad * se.precio) FROM servicios_equipos se WHERE se.id_servicio = s.id) as total_equipos
                FROM servicios s
                ORDER BY s.id DESC
            ");

            if ($resultado) {
                $servicios = array();
                $n = 1;
                foreach ($resultado as $row) {
                    // Obtenemos los detalles de insumos para este servicio
                    $stmt_insumos = $co->prepare("
                        SELECT i.codigo, i.nombre, si.cantidad, si.precio, (si.cantidad * si.precio) as subtotal
                        FROM servicios_insumos si
                        JOIN insumos i ON si.id_insumo = i.id
                        WHERE si.id_servicio = ?
                    ");
                    $stmt_insumos->execute([$row['id']]);
                    $insumos = $stmt_insumos->fetchAll(PDO::FETCH_ASSOC);

                    // Obtenemos los detalles de equipos para este servicio
                    $stmt_equipos = $co->prepare("
                        SELECT e.codigo, e.nombre, se.cantidad, se.precio, (se.cantidad * se.precio) as subtotal
                        FROM servicios_equipos se
                        JOIN equipos e ON se.id_equipo = e.id
                        WHERE se.id_servicio = ?
                    ");
                    $stmt_equipos->execute([$row['id']]);
                    $equipos = $stmt_equipos->fetchAll(PDO::FETCH_ASSOC);

                    $servicio = array(
                        'numero' => $n,
                        'nombre' => $row['nombre'],
                        'descripcion' => $row['descripcion'],
                        'precio' => $row['precio'],
                        'insumos' => $insumos,
                        'equipos' => $equipos,
                        'total_insumos' => $row['total_insumos'] ?: 0,
                        'total_equipos' => $row['total_equipos'] ?: 0,
                        'total_general' => ($row['total_insumos'] ?: 0) + ($row['total_equipos'] ?: 0)
                    );
                    $servicios[] = $servicio;
                    $n++;
                }
                $r['resultado'] = 'consultar';
                $r['datos'] = $servicios;
            } else {
                $r['resultado'] = 'consultar';
                $r['datos'] = array();
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null;
        }
        return $r;
    }

    function listadoInsumos(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $resultado = $co->query("Select * from insumos");
            if($resultado){
                $insumos = array();
                foreach($resultado as $row){
                    $insumo = array(
                        'id' => $row['id'],
                        'codigo' => $row['codigo'],
                        'nombre' => $row['nombre'],
                        'marca' => $row['marca'],
                        'cantidad' => $row['cantidad'],
                        'cantidad_minima' => $row['cantidad_minima'],
                        'precio' => $row['precio'],
                        'id_presentacion' => $row['id_presentacion'],
                        'imagen' => $row['imagen'],
                        'stock_bajo' => $row['cantidad'] <= $row['cantidad_minima'],
                        'sin_stock' => $row['cantidad'] == 0
                    );
                    $insumos[] = $insumo;
                }
                $r['resultado'] = 'listadoInsumos';
                $r['datos'] = $insumos;
            } else {
                $r['resultado'] = 'listadoInsumos';
                $r['datos'] = array();
            }
        } catch(Exception $e){
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null; // Cierra la conexión
        }
        return $r;
    }   

    function listadoEquipos(){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $resultado = $co->query("Select * from equipos");
            if($resultado){
                $equipos = array();
                foreach($resultado as $row){
                    $equipo = array(
                        'id' => $row['id'],
                        'codigo' => $row['codigo'],
                        'nombre' => $row['nombre'],
                        'marca' => $row['marca'],
                        'modelo' => $row['modelo'],
                        'cantidad' => $row['cantidad'],
                        'precio' => $row['precio'],
                        'imagen' => $row['imagen']
                    );
                    $equipos[] = $equipo;
                }
                $r['resultado'] = 'listadoEquipos';
                $r['datos'] = $equipos;
            } else {
                $r['resultado'] = 'listadoEquipos';
                $r['datos'] = array();
            }
        } catch(Exception $e){
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null; // Cierra la conexión
        }
        return $r;
    } 

    // Método privado para verificar si un servicio existe
    private function existe($nombre) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("Select * from servicios where nombre='$nombre'"); // Consulta por nombre
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);
            return $fila ? true : false; // El servicio existe
        } catch (Exception $e) {
            return false; // Captura errores
        } finally {
            $co = null; // Cierra la conexión
        }
    }
}
?>