<?php
require_once('modelo/datos.php');

class gestionarEquipos extends datos{
	
    // Propiedades de la clase	
	private $codigo; 
	private $nombre;
	private $marca;
	private $modelo;
	private $cantidad;
	private $imagen;
	private $errores = array();

    // Métodos para establecer valores de las propiedades	
	function set_codigo($valor){
		$this->codigo = $valor; 
	}
	function set_nombre($valor){
		$this->nombre = $valor;
	}
	function set_marca($valor){
		$this->marca = $valor;
	}	
	function set_modelo($valor){
		$this->modelo = $valor;
	}	
	function set_cantidad($valor){
		$this->cantidad = $valor;
	}
	function set_imagen($valor){
		$this->imagen = $valor;
	}
	function get_codigo(){
		return $this->codigo;
	}	
	function get_nombre(){
		return $this->nombre;
	}
	function get_marca(){
		return $this->marca;
	}
	function get_modelo(){
		return $this->modelo;
	}	
	function get_cantidad(){
		return $this->cantidad;
	}
	function get_imagen(){
		return $this->imagen;
	}	

    // Métodos de validación
    private function validarCodigo($codigo) {
        if (empty($codigo)) {
            $this->errores[] = "El código es obligatorio";
            return false;
        }
        if (strlen($codigo) > 20) {
            $this->errores[] = "El código no puede tener más de 20 caracteres";
            return false;
        }
        if (!preg_match('/^[^"\']{1,20}$/', $codigo)) {
            $this->errores[] = "El código contiene caracteres no permitidos";
            return false;
        }
        return true;
    }

    private function validarNombre($nombre) {
        if (empty($nombre)) {
            $this->errores[] = "El nombre es obligatorio";
            return false;
        }
        if (strlen($nombre) < 3 || strlen($nombre) > 50) {
            $this->errores[] = "El nombre debe tener entre 3 y 50 caracteres";
            return false;
        }
        return true;
    }

    private function validarMarca($marca) {
        if (!empty($marca)) {
            if (strlen($marca) > 50) {
                $this->errores[] = "La marca no puede tener más de 50 caracteres";
                return false;
            }
            if (!preg_match('/^[^"\']{1,50}$/', $marca)) {
                $this->errores[] = "La marca contiene caracteres no permitidos";
                return false;
            }
        }
        return true;
    }

    private function validarModelo($modelo) {
        if (!empty($modelo)) {
            if (strlen($modelo) > 50) {
                $this->errores[] = "El modelo no puede tener más de 50 caracteres";
                return false;
            }
            if (!preg_match('/^[^"\']{1,50}$/', $modelo)) {
                $this->errores[] = "El modelo contiene caracteres no permitidos";
                return false;
            }
        }
        return true;
    }

    private function validarCantidad($cantidad) {
        if (!is_numeric($cantidad)) {
            $this->errores[] = "La cantidad debe ser un número entero";
            return false;
        }
        if (!preg_match('/^[0-9]{1,10}$/', $cantidad)) {
            $this->errores[] = "La cantidad debe ser un número entero válido";
            return false;
        }
        if ($cantidad < 0) {
            $this->errores[] = "La cantidad no puede ser negativa";
            return false;
        }
        return true;
    }

    private function validarDatos() {
        $this->errores = array();
        
        $valido = true;
        $valido = $this->validarCodigo($this->codigo) && $valido;
        $valido = $this->validarNombre($this->nombre) && $valido;
        $valido = $this->validarMarca($this->marca) && $valido;
        $valido = $this->validarModelo($this->modelo) && $valido;
        $valido = $this->validarCantidad($this->cantidad) && $valido;

        return $valido;
    }

    // Método para incluir un nuevo equipo
	function incluir(){
		$r = array();
        
        if (!$this->validarDatos()) {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = implode(", ", $this->errores);
            return $r;
        }

        // Verifica si el código ya existe		
		if(!$this->existe($this->codigo)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo equipo en la base de datos				
				$co->query("INSERT INTO equipos(codigo, nombre, marca, modelo, cantidad, imagen)
					VALUES(
						'$this->codigo',
						'$this->nombre',
						'$this->marca',
						'$this->modelo',
						'$this->cantidad',
						'$this->imagen'
					)");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el código del equipo';
		}
		return $r;
	}

    // Método para modificar un equipo existente
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

        if (!$this->validarDatos()) {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = implode(", ", $this->errores);
            return $r;
        }

        // Verifica si el equipo existe
		if($this->existe($this->codigo)){
			try {
	            // Obtiene la imagen actual del equipo			
				$resultado = $co->query("SELECT imagen FROM equipos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];                
				// Actualiza los datos del equipo	
				$co->query("UPDATE equipos set 
					codigo = '$this->codigo',
					nombre = '$this->nombre',
					marca = '$this->marca',
					modelo = '$this->modelo',
					cantidad = '$this->cantidad',
					imagen = '$this->imagen'
					where
					codigo = '$this->codigo'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro actualizado con éxito!';                
				// Elimina la imagen anterior si es necesario
				if ($imagen_actual && $imagen_actual != 'otros/img/equipos/default.png') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Codigo no registrado';
		}
		return $r;
	}

    // Método para eliminar un equipo	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();        
		// Verifica si el equipo existe
		if($this->existe($this->codigo)){
			try {                
				// Obtiene la imagen del equipo a eliminar
				$resultado = $co->query("SELECT imagen FROM equipos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				// Elimina el equipo de la base de datos
				$co->query("DELETE from equipos where codigo = '$this->codigo' ");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
				// Elimina la imagen del equipo si existe
				if ($imagen && $imagen != 'otros/img/equipos/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este equipo porque tiene movimientos asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el código del equipo';
		}
		return $r;
	}

    // Método para consultar equipos		
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
            // Realiza la consulta para obtener equipos y sus detalles
			$resultado = $co->query("Select * from equipos ORDER BY id DESC");
			if ($resultado->rowCount() > 0) {
				$equipos = array();
				$productos_bajo_stock = array();
				$n = 1;
				while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
					$equipo = array(
						'numero' => $n,
						'codigo' => $row['codigo'],
						'nombre' => $row['nombre'],
						'marca' => $row['marca'],
						'modelo' => $row['modelo'],
						'cantidad' => $row['cantidad'],
						'imagen' => $row['imagen']
					);
					$equipos[] = $equipo;

					// Verifica si el stock es bajo
					if ($row['cantidad'] <= 0) {
						$productos_bajo_stock[] = array(
							'nombre' => $row['nombre'],
							'cantidad' => $row['cantidad']
						);
					}
					$n++;
				}
				$r['resultado'] = 'consultar';
				$r['datos'] = $equipos;
				$r['productos_bajo_stock'] = $productos_bajo_stock;
			} else {
				$r['resultado'] = 'consultar';
				$r['datos'] = array();
				$r['productos_bajo_stock'] = array();
			}
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		} finally {
			$co = null; // Cierra la conexión
		}
		return $r;
	}

    // Método privado para verificar si un equipo existe	
	private function existe($codigo){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$resultado = $co->query("Select * from equipos where codigo='$codigo'");	
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){
				return true;   
			}
			else{	
				return false;;
			}	
		}catch(Exception $e){
			return false;
		} finally {
			$co = null; // Cierra la conexión
		}
	}	
    // Método para obtener equipos que necesitan notificación		
	function obtenerEquiposNotificacion() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT codigo, nombre, cantidad FROM equipos WHERE cantidad = 0");
			$equipos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $equipos;
		} catch(Exception $e) {
			return array();
		} finally {
			$co = null; // Cierra la conexión
		}
	}

	function consultar2() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("
				SELECT ee.id, ee.fecha_entrada, ee.nota_entrega,
				e.codigo, e.nombre AS equipo_nombre, eed.cantidad, eed.precio, (eed.cantidad * eed.precio) AS subtotal,
				SUM(eed.cantidad * eed.precio) OVER (PARTITION BY ee.id) AS total
				FROM entradas_equipos ee
				JOIN entradas_equipos_detalles eed ON ee.id = eed.id_entrada_equipo
				JOIN equipos e ON eed.id_equipo = e.id
				ORDER BY ee.fecha_entrada DESC;
			");
			if ($resultado) {
				$entradas = array();
				$n = 1;
				$currentEntrada = null;
				$entradaActual = null;

				foreach ($resultado as $row) {
					if ($currentEntrada !== $row['id']) {
						if ($entradaActual !== null) {
							$entradas[] = $entradaActual;
						}
						
						$entradaActual = array(
							'numero' => $n,
							'id' => $row['id'],
							'fecha_entrada' => $row['fecha_entrada'],
							'nota_entrega' => $row['nota_entrega'],
							'total' => $row['total'],
							'detalles' => array()
						);
						
						$currentEntrada = $row['id'];
						$n++;
					}

					$entradaActual['detalles'][] = array(
						'codigo' => $row['codigo'],
						'nombre' => $row['equipo_nombre'],
						'cantidad' => $row['cantidad'],
						'precio' => $row['precio'],
						'subtotal' => $row['subtotal']
					);
				}

				// Agregar la última entrada
				if ($entradaActual !== null) {
					$entradas[] = $entradaActual;
				}

				$r['resultado'] = 'consultar2';
				$r['datos'] = $entradas;
			} else {
				$r['resultado'] = 'consultar2';
				$r['datos'] = array();
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		} finally {
			$co = null; // Cierra la conexión
		}
		return $r;
	}

	function entrada( $id_equipo, $cantidad, $precio, $imagen = null) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$respuesta_final = array();
		try {
			$co->beginTransaction();
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('Y-m-d H:i:s');
			
			$guarda = $co->prepare("INSERT INTO entradas_equipos(fecha_entrada,nota_entrega) VALUES (?, ?)");
			$guarda->execute([$fecha,$imagen]);
			
			$lid = $co->lastInsertId();
			$tamano = count($id_equipo);
			for($i = 0; $i < $tamano; $i++) {
				$gd = $co->prepare("INSERT INTO entradas_equipos_detalles (id_entrada_equipo, id_equipo, cantidad, precio) VALUES (?, ?, ?, ?)");
				$gd->execute([$lid, $id_equipo[$i], $cantidad[$i], $precio[$i]]);
				
				// Actualizar el stock y el precio de compra del equipo
				$actualizaEquipo = $co->prepare("UPDATE equipos SET cantidad = cantidad + ?, precio = ? WHERE id = ?");
				$actualizaEquipo->execute([$cantidad[$i], $precio[$i], $id_equipo[$i]]);
			}   
			$co->commit();
			$respuesta_final['resultado'] = 'entrada';
			$respuesta_final['mensaje'] = '¡Entrada registrada con éxito!';
		} catch(Exception $e) {
			$co->rollback();
			$respuesta_final['resultado'] = 'error';
			$respuesta_final['mensaje'] = $e->getMessage();
		} finally {
			$co = null; // Cierra la conexión
		}
		return $respuesta_final;
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

}
