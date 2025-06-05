<?php
require_once __DIR__ . '/../dompdf/vendor/autoload.php';
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
            $this->registrarBitacora('Servicios', 'Incluir', 'Error al intentar incluir servicio', implode(", ", $this->errores));
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if (!$this->existe($this->nombre)) {
            try {
                $co->beginTransaction();
                
                // Inserta el nuevo servicio con estado activo (1)
                $stmt = $co->prepare("INSERT INTO servicios(nombre, descripcion, precio, estado) VALUES(?, ?, ?, 1)");
                $stmt->execute([$this->nombre, $this->descripcion, $this->precio]);
                $servicio_id = $co->lastInsertId();

                $detalles_insumos = array();
                $detalles_equipos = array();

                // Procesa los insumos si existen
                if (isset($_POST['id_insumo']) && is_array($_POST['id_insumo'])) {
                    for($i = 0; $i < count($_POST['id_insumo']); $i++) {
                        $insumo_id = $_POST['id_insumo'][$i];
                        $cantidad = $_POST['cantidad_insumo'][$i];
                        $precio = $_POST['precio_insumo'][$i];
                        
                        // Inserta la relación servicio-insumo
                        $stmt = $co->prepare("INSERT INTO servicios_insumos(id_servicio, id_insumo, cantidad, precio) VALUES(?, ?, ?, ?)");
                        $stmt->execute([$servicio_id, $insumo_id, $cantidad, $precio]);
                        
                        // Actualiza el precio del insumo
                        $stmt = $co->prepare("UPDATE insumos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $insumo_id]);

                        // Obtener detalles del insumo para la bitácora
                        $stmt = $co->prepare("SELECT codigo, nombre FROM insumos WHERE id = ?");
                        $stmt->execute([$insumo_id]);
                        $insumo = $stmt->fetch(PDO::FETCH_ASSOC);
                        $detalles_insumos[] = "Insumo: {$insumo['codigo']} - {$insumo['nombre']}, Cantidad: {$cantidad}, Precio: {$precio}";
                    }
                }

                // Procesa los equipos si existen
                if (isset($_POST['id_equipo']) && is_array($_POST['id_equipo'])) {
                    for($i = 0; $i < count($_POST['id_equipo']); $i++) {
                        $equipo_id = $_POST['id_equipo'][$i];
                        $cantidad = $_POST['cantidad_equipo'][$i];
                        $precio = $_POST['precio_equipo'][$i];
                        
                        // Inserta la relación servicio-equipo
                        $stmt = $co->prepare("INSERT INTO servicios_equipos(id_servicio, id_equipo, cantidad, precio) VALUES(?, ?, ?, ?)");
                        $stmt->execute([$servicio_id, $equipo_id, $cantidad, $precio]);

                        // Actualiza el precio del equipo
                        $stmt = $co->prepare("UPDATE equipos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $equipo_id]);

                        // Obtener detalles del equipo para la bitácora
                        $stmt = $co->prepare("SELECT codigo, nombre FROM equipos WHERE id = ?");
                        $stmt->execute([$equipo_id]);
                        $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
                        $detalles_equipos[] = "Equipo: {$equipo['codigo']} - {$equipo['nombre']}, Cantidad: {$cantidad}, Precio: {$precio}";
                    }
                }

                $co->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = '¡Servicio registrado con éxito!';

                // Registrar en bitácora
                $detalles = "Servicio: {$this->nombre}, Descripción: {$this->descripcion}, Precio: {$this->precio}";
                if (!empty($detalles_insumos)) {
                    $detalles .= "\nInsumos:\n" . implode("\n", $detalles_insumos);
                }
                if (!empty($detalles_equipos)) {
                    $detalles .= "\nEquipos:\n" . implode("\n", $detalles_equipos);
                }
                $this->registrarBitacora('Servicios', 'Incluir', 'Servicio incluido exitosamente', $detalles);

            } catch (Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
                $this->registrarBitacora('Servicios', 'Incluir', 'Error al incluir servicio', $e->getMessage());
            } finally {
                $co = null; // Cierra la conexión
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe un servicio con ese nombre';
            $this->registrarBitacora('Servicios', 'Incluir', 'Error: Nombre de servicio duplicado', "Nombre: {$this->nombre}");
        }
        return $r;
    }

    // Método para modificar un servicio existente
    function modificar() {
        $r = array();
        
        if (!$this->validarDatos()) {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = implode(", ", $this->errores);
            $this->registrarBitacora('Servicios', 'Modificar', 'Error al intentar modificar servicio', implode(", ", $this->errores));
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if ($this->existe($this->nombre)) {
            try {
                $co->beginTransaction();
                
                // 1. Obtener el ID del servicio y datos actuales
                $stmt = $co->prepare("SELECT id, descripcion, precio FROM servicios WHERE nombre = ?");
                $stmt->execute([$this->nombre]);
                $servicio_actual = $stmt->fetch(PDO::FETCH_ASSOC);
                $servicio_id = $servicio_actual['id'];
                
                // 2. Actualizar el servicio
                $stmt = $co->prepare("UPDATE servicios SET descripcion = ?, precio = ? WHERE nombre = ?");
                $stmt->execute([$this->descripcion, $this->precio, $this->nombre]);
                
                $detalles_cambios = array();
                if ($servicio_actual['descripcion'] !== $this->descripcion) {
                    $detalles_cambios[] = "Descripción: {$servicio_actual['descripcion']} -> {$this->descripcion}";
                }
                if ($servicio_actual['precio'] !== $this->precio) {
                    $detalles_cambios[] = "Precio: {$servicio_actual['precio']} -> {$this->precio}";
                }

                // 3. Obtener insumos y equipos actuales
                $stmt = $co->prepare("SELECT id_insumo FROM servicios_insumos WHERE id_servicio = ?");
                $stmt->execute([$servicio_id]);
                $insumos_actuales = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $stmt = $co->prepare("SELECT id_equipo FROM servicios_equipos WHERE id_servicio = ?");
                $stmt->execute([$servicio_id]);
                $equipos_actuales = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                // 4. Procesar insumos
                $insumos_nuevos = isset($_POST['id_insumo']) ? $_POST['id_insumo'] : array();
                $detalles_insumos = array();
                
                // Eliminar insumos que ya no están seleccionados
                $insumos_a_eliminar = array_diff($insumos_actuales, $insumos_nuevos);
                foreach ($insumos_a_eliminar as $id_insumo) {
                    $stmt = $co->prepare("DELETE FROM servicios_insumos WHERE id_servicio = ? AND id_insumo = ?");
                    $stmt->execute([$servicio_id, $id_insumo]);
                    
                    // Obtener detalles del insumo eliminado
                    $stmt = $co->prepare("SELECT codigo, nombre FROM insumos WHERE id = ?");
                    $stmt->execute([$id_insumo]);
                    $insumo = $stmt->fetch(PDO::FETCH_ASSOC);
                    $detalles_insumos[] = "Eliminado: Insumo {$insumo['codigo']} - {$insumo['nombre']}";
                }

                // Actualizar o agregar nuevos insumos
                if (isset($_POST['id_insumo']) && is_array($_POST['id_insumo'])) {
                    for($i = 0; $i < count($_POST['id_insumo']); $i++) {
                        $insumo_id = $_POST['id_insumo'][$i];
                        $cantidad = $_POST['cantidad_insumo'][$i];
                        $precio = $_POST['precio_insumo'][$i];
                        
                        // Verificar si el insumo ya existe
                        $stmt = $co->prepare("SELECT cantidad, precio FROM servicios_insumos WHERE id_servicio = ? AND id_insumo = ?");
                        $stmt->execute([$servicio_id, $insumo_id]);
                        $insumo_actual = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($insumo_actual) {
                            // Actualizar insumo existente
                            $stmt = $co->prepare("UPDATE servicios_insumos SET cantidad = ?, precio = ? WHERE id_servicio = ? AND id_insumo = ?");
                            $stmt->execute([$cantidad, $precio, $servicio_id, $insumo_id]);
                            
                            if ($insumo_actual['cantidad'] != $cantidad || $insumo_actual['precio'] != $precio) {
                                // Obtener detalles del insumo
                                $stmt = $co->prepare("SELECT codigo, nombre FROM insumos WHERE id = ?");
                                $stmt->execute([$insumo_id]);
                                $insumo = $stmt->fetch(PDO::FETCH_ASSOC);
                                $detalles_insumos[] = "Actualizado: Insumo {$insumo['codigo']} - {$insumo['nombre']}, " .
                                    "Cantidad: {$insumo_actual['cantidad']} -> {$cantidad}, " .
                                    "Precio: {$insumo_actual['precio']} -> {$precio}";
                            }
                        } else {
                            // Insertar nuevo insumo
                            $stmt = $co->prepare("INSERT INTO servicios_insumos(id_servicio, id_insumo, cantidad, precio) VALUES(?, ?, ?, ?)");
                            $stmt->execute([$servicio_id, $insumo_id, $cantidad, $precio]);
                            
                            // Obtener detalles del nuevo insumo
                            $stmt = $co->prepare("SELECT codigo, nombre FROM insumos WHERE id = ?");
                            $stmt->execute([$insumo_id]);
                            $insumo = $stmt->fetch(PDO::FETCH_ASSOC);
                            $detalles_insumos[] = "Agregado: Insumo {$insumo['codigo']} - {$insumo['nombre']}, Cantidad: {$cantidad}, Precio: {$precio}";
                        }
                        
                        // Actualizar el precio del insumo
                        $stmt = $co->prepare("UPDATE insumos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $insumo_id]);
                    }
                }

                // 5. Procesar equipos (similar a insumos)
                $equipos_nuevos = isset($_POST['id_equipo']) ? $_POST['id_equipo'] : array();
                $detalles_equipos = array();
                
                // Eliminar equipos que ya no están seleccionados
                $equipos_a_eliminar = array_diff($equipos_actuales, $equipos_nuevos);
                foreach ($equipos_a_eliminar as $id_equipo) {
                    $stmt = $co->prepare("DELETE FROM servicios_equipos WHERE id_servicio = ? AND id_equipo = ?");
                    $stmt->execute([$servicio_id, $id_equipo]);
                    
                    // Obtener detalles del equipo eliminado
                    $stmt = $co->prepare("SELECT codigo, nombre FROM equipos WHERE id = ?");
                    $stmt->execute([$id_equipo]);
                    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
                    $detalles_equipos[] = "Eliminado: Equipo {$equipo['codigo']} - {$equipo['nombre']}";
                }

                // Actualizar o agregar nuevos equipos
                if (isset($_POST['id_equipo']) && is_array($_POST['id_equipo'])) {
                    for($i = 0; $i < count($_POST['id_equipo']); $i++) {
                        $equipo_id = $_POST['id_equipo'][$i];
                        $cantidad = $_POST['cantidad_equipo'][$i];
                        $precio = $_POST['precio_equipo'][$i];
                        
                        // Verificar si el equipo ya existe
                        $stmt = $co->prepare("SELECT cantidad, precio FROM servicios_equipos WHERE id_servicio = ? AND id_equipo = ?");
                        $stmt->execute([$servicio_id, $equipo_id]);
                        $equipo_actual = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($equipo_actual) {
                            // Actualizar equipo existente
                            $stmt = $co->prepare("UPDATE servicios_equipos SET cantidad = ?, precio = ? WHERE id_servicio = ? AND id_equipo = ?");
                            $stmt->execute([$cantidad, $precio, $servicio_id, $equipo_id]);
                            
                            if ($equipo_actual['cantidad'] != $cantidad || $equipo_actual['precio'] != $precio) {
                                // Obtener detalles del equipo
                                $stmt = $co->prepare("SELECT codigo, nombre FROM equipos WHERE id = ?");
                                $stmt->execute([$equipo_id]);
                                $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
                                $detalles_equipos[] = "Actualizado: Equipo {$equipo['codigo']} - {$equipo['nombre']}, " .
                                    "Cantidad: {$equipo_actual['cantidad']} -> {$cantidad}, " .
                                    "Precio: {$equipo_actual['precio']} -> {$precio}";
                            }
                        } else {
                            // Insertar nuevo equipo
                            $stmt = $co->prepare("INSERT INTO servicios_equipos(id_servicio, id_equipo, cantidad, precio) VALUES(?, ?, ?, ?)");
                            $stmt->execute([$servicio_id, $equipo_id, $cantidad, $precio]);
                            
                            // Obtener detalles del nuevo equipo
                            $stmt = $co->prepare("SELECT codigo, nombre FROM equipos WHERE id = ?");
                            $stmt->execute([$equipo_id]);
                            $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
                            $detalles_equipos[] = "Agregado: Equipo {$equipo['codigo']} - {$equipo['nombre']}, Cantidad: {$cantidad}, Precio: {$precio}";
                        }
                        
                        // Actualizar el precio del equipo
                        $stmt = $co->prepare("UPDATE equipos SET precio = ? WHERE id = ?");
                        $stmt->execute([$precio, $equipo_id]);
                    }
                }

                $co->commit();
                $r['resultado'] = 'modificar';
                $r['mensaje'] = '¡Servicio actualizado con éxito!';

                // Registrar en bitácora
                $detalles = "Servicio: {$this->nombre}";
                if (!empty($detalles_cambios)) {
                    $detalles .= "\nCambios en servicio:\n" . implode("\n", $detalles_cambios);
                }
                if (!empty($detalles_insumos)) {
                    $detalles .= "\nCambios en insumos:\n" . implode("\n", $detalles_insumos);
                }
                if (!empty($detalles_equipos)) {
                    $detalles .= "\nCambios en equipos:\n" . implode("\n", $detalles_equipos);
                }
                $this->registrarBitacora('Servicios', 'Modificar', 'Servicio modificado exitosamente', $detalles);

            } catch (Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
                $this->registrarBitacora('Servicios', 'Modificar', 'Error al modificar servicio', $e->getMessage());
            } finally {
                $co = null; // Cierra la conexión
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'No existe un servicio con ese nombre';
            $this->registrarBitacora('Servicios', 'Modificar', 'Error: Servicio no encontrado', "Nombre: {$this->nombre}");
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
                // Obtener detalles del servicio antes de eliminarlo
                $stmt = $co->prepare("SELECT id, descripcion, precio FROM servicios WHERE nombre = ?");
                $stmt->execute([$this->nombre]);
                $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Obtener detalles de insumos asociados
                $stmt = $co->prepare("
                    SELECT i.codigo, i.nombre, si.cantidad, si.precio 
                    FROM servicios_insumos si 
                    JOIN insumos i ON si.id_insumo = i.id 
                    WHERE si.id_servicio = ?
                ");
                $stmt->execute([$servicio['id']]);
                $insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Obtener detalles de equipos asociados
                $stmt = $co->prepare("
                    SELECT e.codigo, e.nombre, se.cantidad, se.precio 
                    FROM servicios_equipos se 
                    JOIN equipos e ON se.id_equipo = e.id 
                    WHERE se.id_servicio = ?
                ");
                $stmt->execute([$servicio['id']]);
                $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Actualizar el estado a 0 (eliminado) en lugar de eliminar físicamente
                $stmt = $co->prepare("UPDATE servicios SET estado = 0 WHERE nombre = ?");
                $stmt->execute([$this->nombre]);
                
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = '¡Servicio eliminado con éxito!';

                // Registrar en bitácora
                $detalles = "Servicio: {$this->nombre}, Descripción: {$servicio['descripcion']}, Precio: {$servicio['precio']}";
                if (!empty($insumos)) {
                    $detalles .= "\nInsumos eliminados:\n";
                    foreach ($insumos as $insumo) {
                        $detalles .= "- {$insumo['codigo']} - {$insumo['nombre']}, Cantidad: {$insumo['cantidad']}, Precio: {$insumo['precio']}\n";
                    }
                }
                if (!empty($equipos)) {
                    $detalles .= "\nEquipos eliminados:\n";
                    foreach ($equipos as $equipo) {
                        $detalles .= "- {$equipo['codigo']} - {$equipo['nombre']}, Cantidad: {$equipo['cantidad']}, Precio: {$equipo['precio']}\n";
                    }
                }
                $this->registrarBitacora('Servicios', 'Eliminar', 'Servicio eliminado exitosamente', $detalles);

            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
                $this->registrarBitacora('Servicios', 'Eliminar', 'Error al eliminar servicio', $e->getMessage());
            } finally {
                $co = null; // Cierra la conexión
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'No existe un servicio con ese nombre';
            $this->registrarBitacora('Servicios', 'Eliminar', 'Error: Servicio no encontrado', "Nombre: {$this->nombre}");
        }
        return $r;
    }

    // Método para consultar todos los servicios con sus detalles
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Modificar la consulta para solo obtener servicios activos (estado = 1)
            $resultado = $co->query("
                SELECT s.*, 
                (SELECT SUM(si.cantidad * si.precio) FROM servicios_insumos si WHERE si.id_servicio = s.id) as total_insumos,
                (SELECT SUM(se.cantidad * se.precio) FROM servicios_equipos se WHERE se.id_servicio = s.id) as total_equipos
                FROM servicios s
                WHERE s.estado = 1
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




    function reporte_servicios(){    
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            date_default_timezone_set('America/Caracas');//zona horaria 
            $fecha = date('d-m-y H:i');
            
            // Consulta principal de servicios con sus totales
            $sql = "SELECT s.*, 
                (SELECT SUM(si.cantidad * si.precio) FROM servicios_insumos si WHERE si.id_servicio = s.id) as total_insumos,
                (SELECT SUM(se.cantidad * se.precio) FROM servicios_equipos se WHERE se.id_servicio = s.id) as total_equipos
                FROM servicios s 
                WHERE s.estado = 1";
            $params = array();
            
            if (!empty($this->nombre)) {
                $sql .= " AND s.nombre LIKE ?";
                $params[] = '%' . $this->nombre . '%';
            }
            if (!empty($this->descripcion)) {
                $sql .= " AND s.descripcion LIKE ?";
                $params[] = '%' . $this->descripcion . '%';
            }
            if (!empty($this->precio)) {
                $sql .= " AND s.precio = ?";
                $params[] = $this->precio;
            }
            
            $sql .= " ORDER BY s.id DESC";
            
            $stmt = $co->prepare($sql);
            $stmt->execute($params);
            $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generar el HTML del reporte
            $html = "<html><head></head><body>";
            $html = $html . "
            <div style='position: relative;'>
            <img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
            <h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>Centro Odontologico Vital Sonrisa, C.A<br>J-</h2>
            </div>";        
            $html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Direccion <br><strong>Telefono:</strong> 0414-<br><strong>Fecha:</strong> ".$fecha.".</p>";    

            $html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte de Servicios</h2></div>";
            $html = $html."<table style='width:100%; border: solid;' >";
            $html = $html."<thead style='width:100%;'>";
            $html = $html."<tr style='background-color: #f7dc6f ; '>";
            $html = $html."<th style='border: solid;'>#</th>";    
            $html = $html."<th style='border: solid;'>Nombre</th>";
            $html = $html."<th style='border: solid;'>Descripción</th>";
            $html = $html."<th style='border: solid;'>Precio Base</th>";
            $html = $html."<th style='border: solid;'>Total Insumos</th>";
            $html = $html."<th style='border: solid;'>Total Equipos</th>";
            $html = $html."<th style='border: solid;'>Total General</th>";
            $html = $html."</tr>";
            $html = $html."</thead>";
            $html = $html."<tbody>";
            
            if($servicios){
                $n = 1;
                foreach($servicios as $servicio){
                    $total_insumos = $servicio['total_insumos'] ?: 0;
                    $total_equipos = $servicio['total_equipos'] ?: 0;
                    $total_general = $servicio['precio'] + $total_insumos + $total_equipos;
                    
                    $html = $html."<tr>";
                    $html = $html."<td style='text-align:center; border: solid;'>".$n."</td>";
                    $html = $html."<td style='border: solid;'>".htmlspecialchars($servicio['nombre'])."</td>";
                    $html = $html."<td style='border: solid;'>".htmlspecialchars($servicio['descripcion'])."</td>";
                    $html = $html."<td style='border: solid;'>$".number_format($servicio['precio'], 2)."</td>";
                    $html = $html."<td style='border: solid;'>$".number_format($total_insumos, 2)."</td>";
                    $html = $html."<td style='border: solid;'>$".number_format($total_equipos, 2)."</td>";
                    $html = $html."<td style='border: solid;'>$".number_format($total_general, 2)."</td>";
                    $html = $html."</tr>";
                    $n++;
                }
            }
            
            $html = $html."</tbody>";
            $html = $html."</table>";
            $html = $html."</body></html>";    

            // Configurar opciones de DOMPDF
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            // Generar el PDF
            $dompdf = new Dompdf\Dompdf($options);
            $dompdf->set_paper("letter", "portrait");
            $dompdf->load_html(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $dompdf->render();
            
            // Generar nombre del archivo con fecha
            $fecha = date('d-m-y');
            $dompdf->stream("ReporteServicios_".$fecha.".pdf", array("Attachment" => false));
            
        } catch(Exception $e) {
            $html = '
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            </head>
            <body>
                <h1>Error al generar el reporte</h1>
                <p>'.htmlspecialchars($e->getMessage()).'</p>
            </body>
            </html>';
            
            $dompdf = new Dompdf\Dompdf();
            $dompdf->load_html($html);
            $dompdf->render();
            $dompdf->stream("Error_Reporte.pdf", array("Attachment" => false));
        } finally {
            $co = null; // Cerrar la conexión
        }
    }










    // Método privado para verificar si un servicio existe
    private function existe($nombre) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            // Modificar la consulta para verificar solo servicios activos
            $stmt = $co->prepare("SELECT * FROM servicios WHERE nombre = ? AND estado = 1");
            $stmt->execute([$nombre]);
            $fila = $stmt->fetchAll(PDO::FETCH_BOTH);
            return $fila ? true : false;
        } catch (Exception $e) {
            return false;
        } finally {
            $co = null;
        }
    }
}
?>